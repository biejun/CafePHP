/*
 *	编译Vue文件
*/

var webpack = require('webpack')
var text = require("extract-text-webpack-plugin")
var HtmlWebpackPlugin = require('html-webpack-plugin')
var fs = require('fs')

// 配置主题项目
var theme = 'single'

const clear = _path => {
	if (fs.existsSync(_path)) {
		fs.readdirSync(_path).forEach(function(_file, _index) {
			var _curPath = _path + "/" + _file
			if (fs.statSync(_curPath).isDirectory()) {
				clear(_curPath, fs)
			} else {
				fs.unlinkSync(_curPath)
			}
		});
		fs.rmdirSync(_path)
		return true
	}
	return false
}

// 清理编译文件夹
clear("./any-themes/"+theme+"/static/")

module.exports = {
	entry : {
		app: "./any-themes/"+theme+"/app.js",
		common: [
			'vue',
			'vue-router',
			'vue-resource',
		]
	},
	output : {
		path: "./any-themes/"+theme+"/static/",
		publicPath: "/any-themes/"+theme+"/static/",
		filename: "app.[hash:5].js"
	},
	module : {
		loaders: [{
			test: /\.vue$/,
			loader: 'vue'
		}, {
			test: /\.js$/,
			loader: 'babel',
			exclude: /node_modules/
		}, {
			test: /\.(png|jpg|gif|ttf|eot|svg|woff|mp4)$/,
			loader: "file"
		}]
	},
	vue : {
		loaders: {
			css: text.extract("css"),
			js: 'babel'
		}
	},
	plugins : [
		new webpack.DefinePlugin({
			'process.env': {
				NODE_ENV: '"production"'
			}
		}),
		new text('app.[hash:5].css'),
		new HtmlWebpackPlugin({
			filename: '../index.php',
			template: "./any-themes/"+theme+"/build.html",
			inject: true,
			minify: {
				removeComments: true,
				collapseWhitespace: true,
				removeAttributeQuotes: true
				// more options:
				// https://github.com/kangax/html-minifier#options-quick-reference
			}
		}),
		new webpack.optimize.CommonsChunkPlugin({
			name: "common",
			filename: "[name].[hash:5].js"
		}),
		new webpack.optimize.OccurenceOrderPlugin(),
		new webpack.optimize.UglifyJsPlugin({
			sourceMap: false,
			mangle: true,
			compress: {
				warnings: false
			}
		})
	]
}
