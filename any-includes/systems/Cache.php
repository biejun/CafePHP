<?php
if(!defined('ABSPATH'))exit('Access denied!');
/**
 *  程序数据缓存类，数据查询结果缓存
 *
 *  @author   biejun
 *  @since   2014.10.25
 */

class Cache {
	private $dir;
	public function  __construct($dir){
		$this->dir=$dir;
        make_dir($dir);
	}
    public function read($key,$minutes=0){
    	$filename=$this->filename($key);
        $datas=array();
        if(file_exists($filename) && is_readable($filename)){
            $datas = unserialize(file_get_contents($filename));
            if($minutes==0 || APP_TIME - $datas['time'] < $minutes*60 )
                return $datas['data'];
        }
    	return $datas;
    }
    public function write($key,$data){
    	$filename=$this->filename($key);
    	if($handle = fopen($filename,'w+')){
    		$datas = array('data'=>$data,'time'=>APP_TIME);
    		flock($handle,LOCK_EX);
    		$rs = fputs($handle,serialize($datas));
    		flock($handle,LOCK_UN);
    		fclose($handle);
    		if($rs!==false)return true;
    	}
    	return false;
    }
    public function delete_cache($key){
    	$filename=$this->filename($key);
    	if(file_exists($filename))@unlink($filename);
    }
    private function filename($key){
    	return $this->dir.md5($key.VALIDATE);
    }
    public function get_cache_files(){
        return glob($this->dir.'*');
    }
    public function clear_caches(){
        $file = $this->get_cache_files();
        $result = array_map("unlink",$file);
        if($result !== false){
            return true;
        }else{
            return false;
        }
    }
}