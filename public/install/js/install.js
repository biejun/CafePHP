function randomHash(len){
    len = len || 32;
    var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|',
        maxLen = chars.length;
    var hash = '';
    for (var i = len - 1; i >= 0; i--) {
        hash += chars.charAt(Math.floor(Math.random() * maxLen));
    };
    return hash;
}

(function guide() {

    var req = new UrlRequest,
        fetch = new Ajax,
        app = document.getElementById('App');

    var TOKEN = app.getAttribute('data-token');

    function KO() {
        this.step = ko.observable(function(){
            var step = 1;
            if(step){
                return (step > 3) ? 3 : step;
            }else{
                return 1;
            }
        }());

        this.welcome = '开始安装';

        this.checkVersion = ko.observable(Boolean(app.getAttribute('data-allow-install')));

        this.errors = ko.observableArray([]);
        this.success = ko.observable('');

        this.dbname = ko.observable('cafe');
        this.dbuser = ko.observable('');
        this.dbpassword = ko.observable('');
        this.dbport = ko.observable(3306);
        this.dbhost = ko.observable('localhost');
        this.charset = ko.observable('utf8mb4');
        this.codeOptions = ['utf8', 'utf8mb4'];


        this.username = ko.observable('');
        this.password = ko.observable('');
        this.passwordonce = ko.observable('');
        this.safetycode = ko.observable('');
        this.hash = ko.observable(randomHash());

        this.setupOne = function(){
            this.errors([]);

            var data = {
                name : this.dbname()
                ,user : this.dbuser()
                ,password : this.dbpassword()
                ,port : this.dbport()
                ,host : this.dbhost()
                ,charset : this.charset()
            };

            if(data.name === ''){
                this.errors.push('数据库名称不能为空！');
            }
            if(data.user === ''){
                this.errors.push('数据库用户名不能为空！');
            }
            if(data.password === ''){
                this.errors.push('数据库密码不能为空！');
            }

            if(this.errors().length > 0) return false;

            fetch.http(req.path + 'welcome/setup-one')
                .header('TOKEN',TOKEN)
                .data(data)
                .post(function(res){
                    if(res.success){
                        this.step(3);
                        this.success(res.data);
                    }else{
                        this.errors.push(res.data);
                    }
                }.bind(this))
        }

        this.setupTwo = function() {
            this.errors([]);
            var data = {
                username:this.username(),
                password:md5(this.password()),
                passwordonce:md5(this.passwordonce()),
                safetycode:md5(this.safetycode()),
                hash:this.hash()
            }

            if(data.username === ''){
                this.errors.push('用户名不能为空!');
            }
            if(data.password === ''){
                this.errors.push('密码不能为空!');
            }

            if(data.password !== data.passwordonce){
                this.errors.push('两次输入的密码不一致!');
            }

            if(this.errors().length > 0) return false; 
            fetch.http(req.path+'welcome/setup-two')
                .header('TOKEN',TOKEN)
                .data(data)
                .post(function(res){
                    if(res.success){
                        window.location.href = req.path + 'login';
                    }else{
                        this.errors.push(res.data);
                    }
                }.bind(this)
            );
        }
    }

    ko.applyBindings(new KO,app);
})();