var webpack = require('webpack')
var path = require('path')
var extractText = require("extract-text-webpack-plugin")

var config = {
	entry : {
		app : './src/main.js'
	},
	output : {
		path : path.join(__dirname, 'build'),
		filename : 'main.js'
	},
	resolve : {
		root: path.resolve('./')
	},
	module : {
		loaders: [
			{ test: /\.vue$/, loader: 'vue' },
			{ test: /\.js$/, loader: 'babel', exclude: /node_modules/ },
			{ test: /\.(jpe?g|png|jpg|gif|ico)$/,loader: "url?limit=10000&name=images/[name]_[hash:8].[ext]"},
			{ test: /\.((woff2?|svg|ttf|eot)(\?\d+))|(woff2?|svg|ttf|eot)$/, loader: 'file?name=font/[name].[ext]' },
			{ test: /\.css$/, loader: (process.env.NODE_ENV === 'production') ? extractText.extract("style-loader", "css-loader") :"style-loader!css-loader"}
		]
	},
	vue : {
		loaders: {
			js: 'babel'
		}
	},
	babel : {
		presets: ['es2015', 'stage-2'],
		plugins: ['transform-runtime'],
		comments: false
	},
	plugins : [
		new webpack.ProvidePlugin({
			"$": 'jquery',
			"jQuery": 'jquery',
		})
	]
};

if (process.env.NODE_ENV === 'production'){
	var fs = require('fs')
	var html = require('html-webpack-plugin')

	function clear(_path, _fs) {
		if (_fs.existsSync(_path)) {
			_fs.readdirSync(_path).forEach(function(_file, _index) {
				var _curPath = _path + "/" + _file;
				if (_fs.statSync(_curPath).isDirectory()) {
					clear(_curPath, _fs);
				} else {
					_fs.unlinkSync(_curPath);
				}
			});
			_fs.rmdirSync(_path);
			return true;
		}
		return false;
	}
	// 清空已编译
	clear("./build/", fs)

	config.entry.vendor = ['vue','vue-router','jquery'] // 公用js文件
	config.output.filename = 'js/app.[hash:4].js'
	config.vue.loaders.css = extractText.extract("css-loader") // 提取vue文件中的样式到css中

	config.plugins.push(
		new webpack.DefinePlugin({
			'process.env': {
				NODE_ENV: '"production"'
			}
		})
	)
	config.plugins.push(
        new html({
            filename: 'index.html',
            template: './src/index.html'
        })
	)
	config.plugins.push(
        new extractText('[name].[hash:4].css',{ allChunks: true, }) // 打包全部样式到css文件
	)
	config.plugins.push(
		new webpack.optimize.CommonsChunkPlugin("vendor","js/vendor.[hash:4].js") // 打包公用js文件
	)
	config.plugins.push(new webpack.optimize.OccurenceOrderPlugin())
	config.plugins.push(
		new webpack.optimize.UglifyJsPlugin({
			sourceMap: false,
			mangle: true,
			compress: {
				warnings: false
			}
		})
	)

}else{
	// config.devServer = {
	// 	proxy: {
	// 		'/BusinessRuleEngine/BusinessRuleStudioService.svc': {
	// 			target: 'https://poolcutwcf:443',
	// 			secure: false,
	// 			changeOrigin: true
	// 		}
	// 	}
	// }
	config.devtool = '#source-map'
}

module.exports = config;