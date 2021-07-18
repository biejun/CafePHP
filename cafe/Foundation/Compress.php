<?php namespace Cafe\Foundation;

/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @link     https://github.com/biejun/CafePHP
 * @copyright Copyright (c) 2021 Jun Bie
 * @license This content is released under the MIT License.
 */

use Cafe\Foundation\App;
use MatthiasMullie\Minify;

class Compress
{
    protected $files = [];
    
    protected $app;
    
    protected $inputPath;
    
    protected $outputPath;
    /*
     * 将静态资源打包
     
     * @params $inputPath 输入路径
     * @params $outputPath 输出路径
    */
    public function __construct(App $app, $inputPath = '', $outputPath = '')
    {
       $this->app = $app;
       if( empty($inputPath) ) {
           // 如果没有设置输入路径，默认路径在 pulbic/@src
           $this->inputPath = $app->publicPath('@src');
       }else{
           $this->inputPath = $inputPath;
       }
       if( empty($outputPath) ) {
           // 如果没有设置输出路径，默认路径在 pulbic/@dist
           $this->outputPath = $app->publicPath('@dist');
       }else{
           $this->outputPath = $outputPath;
       }
    }
    /* 添加需要压缩的文件 */
    public function add($file)
    {
        $this->files[] = $this->inputPath . $file;
        return $this;
    }
    /* 压缩js */
    public function js($outputFile, $version = '1.0.0')
    {
        $outputFile = ltrim($outputFile, '/');
        if (IS_DEVELOPMENT) {
            $minifier = new Minify\JS($this->files);
            $minifier->minify($this->pathJoin($this->outputPath, $outputFile));
        }
        $file = $this->getFilePath($outputFile, $version);
        echo "<script type=\"text/javascript\" src=\"$file\"></script>\n";
    }
    /* 压缩css */
    public function css($outputFile, $version = '1.0.0')
    {
        $outputFile = ltrim($outputFile, '/');
        if (IS_DEVELOPMENT) {
            $minifier = new Minify\CSS($this->files);
            $minifier->minify($this->pathJoin($this->outputPath, $outputFile));
        }
        $file = $this->getFilePath($outputFile, $version);
        echo "<link rel=\"stylesheet\" href=\"$file\">\n";
    }
    /* 获取文件基于页面的路径（非物理路径） */    
    public function getFilePath($outputFile, $version)
    {
        $basePath = str_replace($this->app->publicPath(), $this->path, $this->outputPath);   
        return $this->fileJoinVersion($this->pathJoin($basePath, $outputFile), $version);
    }
    
    /* 给文件资源加上版本号 */
    public function fileJoinVersion($filePath, $suffixVersion = null)
    {
        return $filePath . (is_null($suffixVersion) ? '' : '?v='.$suffixVersion);
    }
    
    /* 将多个参数组合成一个路径 */
    public function pathJoin()
    {
        $path = array();
        $args = func_get_args();
        if (count($args) > 0) {
            foreach ($args as $key => $value) {
                $path[] = $value;
            }
        }
        return join('/', $path);
    }
}