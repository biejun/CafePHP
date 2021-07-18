<?php $this->layout('common::layout') ?>

<?php $this->start('styles') ?>
<style>
    html,body{
    	height: 100%;
    	background-color: #c7dcf1;
    }
    .body{
    	display: table;
    	width: 100%;
    	max-width: 400px;
    	margin: 0 auto;
        height: 100%;
    }
    .wrap{
    	display: table-cell;
    	width: 100%;
    	padding: 20px 0;
    	vertical-align: middle;
    }
    .box{
    	position: relative;
    	width: 400px;
    	margin: auto;
    	background-color: #fff;
    	box-shadow: 1px 4px 4px #9ab8d7;
    	border-radius: 2px;
    	padding: 30px;
    	box-sizing: border-box;
    	display: none;
    }
    
    .welcome{
    	height: 35px;
    	line-height: 35px;
    	font-weight: normal;
    	font-size: 35px;
    	margin: 30px auto;
    	text-align: center;
    	color: #6494c4;
    }
    
    .step-one,.step-two,.step-three{
    	display: block;
    }
    
    .step-one{
    	text-align: center;
    }
    
    .tip{
    	color: #b4c3d3;
    	font-size: 14px;
    	height: 30px;
    }
    
    .button[large]{
    	background-color: #7398bc;
    	height: 35px;
    	padding: 0;
    	line-height: 35px;
    	border-radius: 2px;
    	width: 100%;
    	text-align: center;
    	color: #fff;
    	font-size: 14px;
    	border: 1px solid #5885b1;
    	cursor: pointer;
    }
    
    .button[simple] {
    	background: transparent;
    	border: none;
    	color: #4f8dca;
    	font-size: 16px;
    	cursor: pointer;
    	height: 30px;
    }
    
    .copyright{
    	position: absolute;
    	bottom: -40px;
    	left: 0;
    	right: 0;
    	text-align: center;
    	color: #7398bc;
    	font-size: 12px;
    }
    
    .title{
    	font-size: 20px;
    	font-weight: normal;
    	height: 20px;
    	line-height: 20px;
    	margin: 0;
    	text-align: center;
    }
    
    .post-form{
    	margin-top: 20px;
    	padding: 20px 0;
    	border-top: 1px solid #ededed;
    	font-size: 12px;
    }
    .post-form label{
    	display: block;
    	font-size: 12px;
    	font-weight: bold;
    	margin-top: 5px;
    	margin-bottom: 5px;
    }
    
    .post-form em{
    	margin-top: 5px;
    	margin-bottom: 8px;
    	color:#888;
    	display: block;
    }
    .hash .hash-input{
    	width: 80%;
    	float: left;
    }
    .hash .hash-btn{
    	width: 20%;
    	float: right;
    }
    
    .post-errors{
    	font-size: 12px;
    	line-height: 1.777;
    	margin-bottom: 10px;
    	color: red;
    	font-weight: bold;
    }
    
    .post-success{
        margin-top: 10px;
    	padding: 8px 12px;
        background-color: #4cc64c;
        color: #e2f6e2;
    	font-size: 12px;
    	font-weight: bold;
    }
</style>
<?php $this->stop() ?>

<div class="body">
    <div id="App" class="wrap" data-allow-install="<?=$checkVersion?>" data-token="<?=$token?>">
        <div data-bind="attr:{class:'box '+step().replace('#', '')}" class="box">
            <!-- ko if:step() === '#step-one' -->
            <div class="welcome" data-bind="text:welcome,attr:{'aria-label':welcome}"></div>
            <p class="tip" data-bind="text:checkVersion?'使用之前还需要进行一些必须的配置！':'您的PHP版本过低，建议您先升级PHP版本至5.5.38以上来完成安装。'">
            </p>
            <svg t="1522823315462" class="icon" style="margin-bottom:20px" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1055" xmlns:xlink="http://www.w3.org/1999/xlink" width="120" height="100"><defs><style type="text/css"></style></defs><path d="M470.9 954.6c-1.2 0-2.3-0.3-3.4-0.9l-240-132.2c-2.2-1.2-3.6-3.6-3.6-6.1V270.6c0-2.5 1.3-4.7 3.4-6L546.9 70.4c1.1-0.7 2.4-1 3.6-1 1.2 0 2.4 0.3 3.4 0.9l242.6 136.9c2.2 1.2 3.6 3.6 3.6 6.1v552.8c0 2.5-1.4 4.9-3.6 6.1L474.4 953.7c-1.1 0.6-2.3 0.9-3.5 0.9z" fill="#bfdaf6" p-id="1056"></path><path d="M550.5 76.4l242.6 136.9v552.8L470.9 947.6l-240-132.2V270.6L550.5 76.4m0-14c-2.5 0-5 0.7-7.3 2L223.6 258.6c-4.2 2.5-6.7 7.1-6.7 12v544.8c0 5.1 2.8 9.8 7.3 12.3l240 132.2c2.1 1.2 4.4 1.7 6.8 1.7 2.4 0 4.7-0.6 6.9-1.8L800 778.3c4.4-2.5 7.1-7.2 7.1-12.2V213.3c0-5.1-2.7-9.7-7.1-12.2L557.4 64.2c-2.1-1.2-4.5-1.8-6.9-1.8z" fill="#6494c4" p-id="1057"></path><path d="M550.5 76.4L230.9 270.6l240 130.5 322.2-187.8z" fill="#FFFFFF" p-id="1058"></path><path d="M470.9 916V401.1" fill="#bfdaf6" p-id="1059"></path><path d="M470.9 923.1c-3.9 0-7-3.1-7-7v-515c0-3.9 3.1-7 7-7s7 3.1 7 7V916c0 3.9-3.1 7.1-7 7.1z" fill="#6494c4" p-id="1060"></path><path d="M754.5 235.8L470.9 401.1" fill="#bfdaf6" p-id="1061"></path><path d="M470.9 408.1c-2.4 0-4.8-1.2-6.1-3.5-1.9-3.3-0.8-7.6 2.5-9.6L751 229.7c3.4-2 7.6-0.8 9.6 2.5 1.9 3.3 0.8 7.6-2.5 9.6L474.5 407.2c-1.1 0.6-2.4 0.9-3.6 0.9z" fill="#6494c4" p-id="1062"></path><path d="M261.5 287.2l209.4 113.9" fill="#bfdaf6" p-id="1063"></path><path d="M470.9 408.1c-1.1 0-2.3-0.3-3.3-0.8L258.1 293.4c-3.4-1.8-4.7-6.1-2.8-9.5 1.9-3.4 6.1-4.6 9.5-2.8L474.3 395c3.4 1.8 4.7 6.1 2.8 9.5-1.3 2.3-3.7 3.6-6.2 3.6z" fill="#6494c4" p-id="1064"></path><path d="M258.2 744.4l177.2 99.3" fill="#bfdaf6" p-id="1065"></path><path d="M435.4 850.7c-1.2 0-2.3-0.3-3.4-0.9l-177.1-99.3c-3.4-1.9-4.6-6.2-2.7-9.5 1.9-3.4 6.1-4.6 9.5-2.7l177.1 99.3c3.4 1.9 4.6 6.2 2.7 9.5-1.3 2.3-3.7 3.6-6.1 3.6z" fill="#6494c4" p-id="1066"></path><path d="M258.2 694.8l177.2 99.3" fill="#bfdaf6" p-id="1067"></path><path d="M435.4 801.1c-1.2 0-2.3-0.3-3.4-0.9l-177.1-99.3c-3.4-1.9-4.6-6.2-2.7-9.5 1.9-3.4 6.1-4.6 9.5-2.7L438.8 788c3.4 1.9 4.6 6.2 2.7 9.5-1.3 2.3-3.7 3.6-6.1 3.6z" fill="#6494c4" p-id="1068"></path><path d="M258.2 645.1l177.2 99.3" fill="#bfdaf6" p-id="1069"></path><path d="M435.4 751.4c-1.2 0-2.3-0.3-3.4-0.9l-177.1-99.3c-3.4-1.9-4.6-6.2-2.7-9.5 1.9-3.4 6.1-4.6 9.5-2.7l177.1 99.3c3.4 1.9 4.6 6.2 2.7 9.5-1.3 2.3-3.7 3.6-6.1 3.6z" fill="#6494c4" p-id="1070"></path><path d="M222.7 538.9l248.2 135.5" fill="#bfdaf6" p-id="1071"></path><path d="M219.335 545.028l6.708-12.289 248.241 135.49-6.707 12.289z" fill="#6494c4" p-id="1072"></path><path d="M393 430.7l40.3 20.1" fill="#bfdaf6" p-id="1073"></path><path d="M433.3 457.8c-1.1 0-2.1-0.2-3.1-0.7L389.9 437c-3.5-1.7-4.9-5.9-3.1-9.4 1.7-3.5 6-4.9 9.4-3.1l40.3 20.1c3.5 1.7 4.9 5.9 3.1 9.4-1.3 2.4-3.8 3.8-6.3 3.8z" fill="#6494c4" p-id="1074"></path></svg>
            <div>
                <button type="button" simple class="button" aria-label="下一步"
                    data-bind="visible:checkVersion,click:function(){nextStep('#step-two')}">下一步</button>
            </div>
            <!-- /ko -->
            <!-- ko if:step() === '#step-two' -->
            <h3 class="title" data-bind="text:'数据库配置'"></h3>
            <div class="post-form">
                <label>数据库名</label>
                <div class="ui fluid input">
                  <input type="text" data-bind="value:dbname"/>
                </div>
                <em>将系统安装到哪个数据库？</em>
                <label>数据库用户名</label>
                <div class="ui fluid input">
                  <input type="text" data-bind="value:dbuser" placeholder="用户名" />
                </div>
                <em>您的数据库用户名。</em>
                <label>数据库密码</label>
                <div class="ui fluid input">
                  <input type="password" data-bind="value:dbpassword" placeholder="密码" />
                </div>
                <em>您的数据库密码。</em>
                <label>数据库主机</label>
                <div class="ui fluid input">
                  <input type="text" data-bind="value:dbhost">
                </div>
                <em>如果localhost不能用，您通常可以从网站服务提供商处得到正确的信息。</em>
                <label>端口</label>
                <div class="ui fluid input">
                  <input type="text" data-bind="value:dbport"/>
                </div>
                <em>系统默认设置，一般不用修改。</em>
            </div>
            <div data-bind="foreach:errors()" class="post-errors">
                <div data-bind="text:$data"></div>
            </div>
            <button type="button" large class="button" data-bind="click:setupOne">下一步</button>
            <!-- /ko -->
            <!-- ko if:step() === '#step-three' -->
            <h3 class="title" data-bind="text:'注册账户'"></h3>
            <div class="post-form">
                <label>用户名</label>
                <div class="ui fluid input">
                  <input type="text" data-bind="value:username"/>
                </div>
                <em>用户名只能含有字母、数字及下划线。</em>
                <label>邮箱</label>
                <div class="ui fluid input">
                  <input type="text" data-bind="value:email"/>
                </div>
                <em>用于找回密码</em>
                <label>密码</label>
                <div class="ui fluid input">
                  <input type="password" data-bind="value:password"/>
                </div>
                <em>密码必须包含字母和数字，并且是不能少于六位的复杂组合。</em>
                <label>确认密码</label>
                <div class="ui fluid input">
                  <input type="password" data-bind="value:passwordonce"/>
                </div>
                <em>再输一次。</em>
                <label>安全码</label>
                <div class="ui fluid input">
                  <input type="password" data-bind="value:safetycode" />
                </div>
                <em>用于后台关键操作的安全验证。</em>
                <label>数据加密密钥</label>
                <div class="hash cf">
                    <div class="hash-input">
                        <div class="ui fluid input">
                          <input type="text" data-bind="value:hash"/>
                        </div>
                    </div>
                    <div class="hash-btn">
                        <button type="button" class="button" simple data-bind="click:function(){hash(randomHash())}">换一个</button>
                    </div>
                </div>
            </div>
            <div data-bind="foreach:errors()" class="post-errors">
                <div data-bind="text:$data"></div>
            </div>
            <button type="button" large class="button" data-bind="click:setupTwo">注册</button>
            <!-- /ko -->
            <div class="post-success" data-bind="text:success,visible:success()!==''"></div>
            <div class="copyright">© <?=date('Y')?> Cafe.</div>
        </div>
    </div>
</div>

<?php $this->start('scripts') ?>
	<script>
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
                
            var path = '<?=$this->u()?>'
		
		    var TOKEN = app.getAttribute('data-token');
		
		    function KO() {
		        this.step = ko.observable(location.hash ? location.hash : '#step-one');
		
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
		
		        this.username = ko.observable('miaoji');
		        this.email = ko.observable('biejun@qq.com');
		        this.password = ko.observable('');
		        this.passwordonce = ko.observable('');
		        this.safetycode = ko.observable('');
		        this.hash = ko.observable(randomHash());
                
                this.nextStep = function(step) {
                    window.location.hash = step.replace('#', '');
                    this.step(step)
                }
		
		        this.setupOne = function(){
		            this.errors([]);
		
		            var data = {
		                database : this.dbname()
		                ,username : this.dbuser()
		                ,password : this.dbpassword()
		                ,port : this.dbport()
		                ,host : this.dbhost()
		                ,charset : this.charset()
		            };
		
		            if(data.database === ''){
		                this.errors.push('数据库名称不能为空！');
		            }
		            if(data.username === ''){
		                this.errors.push('数据库用户名不能为空！');
		            }
		            if(data.password === ''){
		                this.errors.push('数据库密码不能为空！');
		            }
		
		            if(this.errors().length > 0) return false;
		
		            fetch.http(path + 'install/setup-one')
		                .header('TOKEN',TOKEN)
		                .data(data)
		                .post(function(res){
		                    if(res.success){
                                this.nextStep('#step-three')
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
                        email: this.email(),
		                password:md5(this.password()),
		                passwordonce:md5(this.passwordonce()),
		                safetycode:md5(this.safetycode()),
		                hash:this.hash()
		            }
		
		            if(data.username === ''){
		                this.errors.push('用户名不能为空!');
		            }
                    if(data.email === ''){
                        this.errors.push('邮箱不能为空!');
                    }
		            if(data.password === ''){
		                this.errors.push('密码不能为空!');
		            }
		
		            if(data.password !== data.passwordonce){
		                this.errors.push('两次输入的密码不一致!');
		            }
		
		            if(this.errors().length > 0) return false; 
		            fetch.http(path +'install/setup-two')
		                .header('TOKEN',TOKEN)
		                .data(data)
		                .post(function(res){
		                    if(res.success){
		                        window.location.href = path + 'login';
		                    }else{
		                        this.errors.push(res.data);
		                    }
		                }.bind(this)
		            );
		        }
		    }
            
            var vm = new KO;
		    ko.applyBindings(vm, app);
           
		})();
	</script>
<?php $this->stop() ?>
