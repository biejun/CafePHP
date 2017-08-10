/**
 * Request.js 请求地址解析
 *
 * @author biejun
 * @description 获取页面请求地址及参数
 */
(function(){

	function _decode(str) {
		return decodeURIComponent(str.replace(/\+/g, ' '));
	}

	function _fetch(arg,str){

		var flag = arg.charAt(0), // 识别符 ?代表query #代表hash
			split = str.split('&'), // 参数地址解析成数组
			field = [], // 存储字段与值
			params = {}, // 返回值
			key = arg.substring(1).toLowerCase();

		for( var i = 0; i < split.length; i++ ){

			field = split[i].match(/(.*?)=(.*)/);

			if( !field ){

				field = [split[i], split[i], ''];
			}

			field[1] = field[1].toLowerCase();

			if( field[1].replace(/\s/g, '') !== '' ){

				field[2] = _decode(field[2] || '');

				if( key === field[1] ) { return field[2]; }

				params[field[1]] = field[2];
			}
		}

		if (flag === arg) { return params; }

		return params[key];
	}

	window.Request = function(url){

		this.url = url || window.location.toString();
	}

	Request.prototype = {

		parse: function(arg){

			var url = this.url;

			var params = {} , tmp , arg = arg.toString();

			if( tmp = url.match(/^mailto:([^\/].+)/) ){

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
				// fetch hash
				if( params.hash && arg.match(/^#/) ){

					return _fetch(arg, params.hash);
				}
				// query
				if( tmp = url.match(/(.*?)\?(.*)/) ){

					params.query = tmp[2];
					url = tmp[1];
				}
				// fetch query
				if( params.query && arg.match(/^\?/) ){

					return _fetch(arg, params.query);
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

				if(arg in params){ return params[arg]; }

				if(arg === 'all'){ return params; }
			}

			return undefined;
		},

		// 获取网站地址 如 http://www.baidu.com
		getHost: function(){
			var params = this.params,
				protocol = params.protocol,
				hostname = params.hostname;
			return protocol+'://'+hostname;
		},

		getPort: function(){

			return this.params.port;
		},

		// 获取请求地址参数
		getQueryString: function(){
			return this.parse('query');
		},

		// 获取请求地址中某个参数的值 如 ?name=biejun&uid=1  new Request().query('name') => biejun
		getQuery: function(key){
			if( key ){
				return this.parse('?'+key);
			}else{
				return this.parse('?');
			}
		},

		// 获取HASH值
		getHashString: function(){
			return this.parse('hash');
		},

		// 获取HASH中某个参数的值
		getHash: function(key){
			if(key){
				return this.parse('#'+key);
			}else{
				return this.parse('#');
			}
		},
		
		getAll: function(){
			return this.parse('all');
		}
	};

})();