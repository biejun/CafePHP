/**
 * 封装一个原生的ajax请求类
 */
;(function(global){

    function parseData(data){
        var rvalidchars = /^[\],:{}\s]*$/,
            rvalidescape = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,
            rvalidtokens = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
            rvalidbraces = /(?:^|:|,)(?:\s*\[)+/g;

        if(typeof data!== "string" || !data) return null;

        data = data.trim();

        if(global.JSON && global.JSON.parse){
            return global.JSON.parse( data );
        }
        if(rvalidchars.test(data.replace(rvalidescape,"@").replace(rvalidtokens,"]").replace(rvalidbraces,""))){
            return (new Function("return " + data))();
        }
    }

    global.Ajax = function(){
        var xhr;
        if( typeof XMLHttpRequest !== 'undefined'){
            xhr = new XMLHttpRequest();
        }else{
            var versions = [
                "MSXML2.XmlHttp.6.0",
                "MSXML2.XmlHttp.5.0",
                "MSXML2.XmlHttp.4.0",
                "MSXML2.XmlHttp.3.0",
                "MSXML2.XmlHttp.2.0",
                "Microsoft.XmlHttp"
            ];
            for (var i = 0; i < versions.length; i++) {
                try {
                    xhr = new ActiveXObject(versions[i]);
                    break;
                } catch (e) {
                }
            }
        }
        this.xhr = xhr;
        this.async = true;
        this.headers = {};
    }

    Ajax.prototype.http = function(url) {
        this.url = url;
        return this;
    }

    Ajax.prototype.header = function(name, value) {
        this.headers[name] = value;
        return this;
    }

    Ajax.prototype.async = function(async) {
        this.async = async;
        return this;
    }

    Ajax.prototype.data = function(data) {
        var query = [];
        for (var key in data) {
            query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
        }
        this.query = query;
        return this;
    }

    Ajax.prototype.get = function(success, fail){
        var url = this.url, query = this.query,
            xhr = this.xhr , async = this.async;
        if(query.length) {
            query = '?'+query.join('&');
            this.query = '';
        }else{
            query = '';
        }
        url = url + query;
        xhr.open('GET', url, async);
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4) {
                var status = xhr.status;
                if (status >= 200 && status < 300) {
                    success && success(parseData(xhr.responseText),xhr.responseXML,xhr);
                } else {
                    fail && fail(status);
                }
            }
        }
        xhr.send(null);
    }

    Ajax.prototype.post = function(success, fail){
        var url = this.url, query = this.query,
            xhr = this.xhr , async = this.async;
        xhr.open('POST', url, async);
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4) {
                var status = xhr.status;
                if (status >= 200 && status < 300) {
                    success && success(parseData(xhr.responseText),xhr.responseXML,xhr);
                } else {
                    fail && fail(status);
                }
            }
        }
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        for(var i in this.headers) {
            xhr.setRequestHeader(i, this.headers[i]);
        }
        if(query) {
            query = query.join('&');
            this.query = '';
        }else{
            query = null;
        }
        xhr.send(query);
    }
})(this)
/**
 * 封装Cookie操作
 */
;(function(root){
    root.cookie = function(name, value, options) {
        if (typeof value != 'undefined') {
            options = options || {};
            if (value === null) {
                value = '';
                options.expires = -1;
            }
            var expires = '';
            if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                var date;
                if (typeof options.expires == 'number') {
                    date = new Date();
                    date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                } else {
                    date = options.expires;
                }
                expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
            }
            var path = options.path ? '; path=' + (options.path) : '';
            var domain = options.domain ? '; domain=' + (options.domain) : '';
            var secure = options.secure ? '; secure' : '';
            document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
        } else {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = cookies[i].trim();
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }
    }
})(this)
/**
 * UrlRequest.js 请求地址解析
 *
 * 根据页面地址取得参数
 */
;(function(global){
    /**
     * 解析请求地址
     * @param string url  页面链接地址
     * @return object 页面地址解析结果
     */
    function Request_parse(url){
        var params = {} , tmp;

        if(tmp = url.match(/^mailto:([^\/].+)/)){

            params.protocol = 'mailto';
            params.email = temp[1];
        }else{
            // 去掉 #!
            if( tmp = url.match(/(.*?)\/#\!(.*)/) ){

                url = tmp[1] + tmp[2];
            }
            // hash
            if( tmp = url.match(/(.*?)#(.*)/) ){

                params.hash = tmp[2];
                url = tmp[1];
            }
            // query
            if( tmp = url.match(/(.*?)\?(.*)/) ){

                params.query = tmp[2];
                url = tmp[1];
            }
            // protocol eg: http/https
            if( tmp = url.match(/(.*?)\:?\/\/(.*)/) ){

                params.protocol = tmp[1].toLowerCase();
                url = tmp[2];
            }
            // path eg: /src/login.html
            if( tmp = url.match(/(.*?)(\/.*)/) ){

                params.path = (tmp[2] || '').replace(/^([^\/])/, '/$1');
                url = tmp[1];
            }
            // port eg: 8080
            if( tmp = url.match(/(.*)\:([0-9]+)$/) ){

                params.port = tmp[2];
                url = tmp[1];
            }
            // auth
            if( tmp = url.match(/(.*?)@(.*)/) ){

                params.auth = tmp[1];
                url = tmp[2];
            }

            if( params.auth ){
                tmp = params.auth.match(/(.*)\:(.*)/);

                params.username = tmp ? tmp[1] : params.auth;
                params.password = tmp ? tmp[2] : undefined;
            }

            params.hostname = url.toLowerCase();

            if( params.hostname.indexOf('.')>=0 ){

                var hosts = params.hostname.split('.');
                params.sub = hosts[0];
                params.domain = (params.hostname.replace(hosts[0],'')).substring(1);
            }

            params.port = params.port || (params.protocol === 'https' ? '443' : '80');

            params.protocol = params.protocol || (params.port === '443' ? 'https' : 'http');
        }

        return params;
    }
    /**
     * 根据请求参数取得特定的值
     *
     * @param string str 请求参数
     * @param string key 要取得参数的键名
     */
    function Request_fetch(str,key){

        var __decode = function(str){
            return decodeURIComponent(str.replace(/\+/g, ' '));
        };

        var split = str.split('&'), // 参数地址解析成数组
            field = [], // 存储字段与值
            params = {}; // 返回值

        for( var i = 0; i < split.length; i++ ){

            field = split[i].match(/(.*?)=(.*)/);

            if( !field ){

                field = [split[i], split[i], ''];
            }

            field[1] = field[1].toLowerCase();

            if( field[1].replace(/\s/g, '') !== '' ){

                field[2] = __decode(field[2] || '');

                if( key === field[1] ) { return field[2]; }

                params[field[1]] = field[2];
            }
        }

        return (key) ? params[key] : params;
    }

    function UrlRequest(){

        var params = Request_parse(window.location.toString());

        for(var key in params){
            this[key] = params[key];
        }
    }

    UrlRequest.prototype = {
        // 获取网站地址 如 http://www.baidu.com
        getHost: function(){
            return this.protocol+'://'+this.hostname;
        },
        getPort: function(){
            return this.port;
        },
        // 获取请求地址参数
        getQueryString: function(){
            return this.query;
        },
        // 获取请求地址中某个参数的值
        getQuery: function(key){
            return Request_fetch(this.query,key);
        },
        // 获取HASH值
        getHashString: function(){
            return this.hash;
        },
        // 获取HASH中某个参数的值
        getHash: function(key){
            return Request_fetch(this.hash,key);
        }
    };

    global.UrlRequest = UrlRequest;

})(this);