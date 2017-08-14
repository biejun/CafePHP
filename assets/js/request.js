/**
 * Request.js 请求地址解析
 *
 * 根据页面地址取得参数
 */
(function(w){
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

	function Request(url){

		this.url = url || w.location.toString();

		var params = Request_parse(this.url);

		for(var key in params){
			this[key] = params[key];
		}
	}

	Request.prototype = {
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

	this.Request = Request;

})(window);