/*
 *	生产环境
 *
 *	用于在线调试页面，演示地址 http://localhost:3366/build.html
*/

var webpack = require('webpack')

// 配置主题项目
var theme = 'single'

module.exports = {
	entry : {
		app: "./any-themes/"+theme+"/app.js"
	},
	output : {
		path: "./build/static",
		publicPath: "/static/",
		filename: "app.js"
	},
	module : {
		loaders: [{
			test: /\.vue$/,
			loader: 'vue'
		}, {
			test: /\.js$/,
			loader: 'babel',
			exclude: /node_modules/
		},{
			test: /\.(png|jpg|gif|ttf|eot|svg|woff)$/,
			loader: "file"
		}]
	},
    devServer: {
        proxy: {
            '/': {
                target: 'https://localhost:8888',
                secure: false,
                changeOrigin: true
            }
        }
    },
	vue : {
		loaders: {
			js: 'babel'
		}
	}	
}
