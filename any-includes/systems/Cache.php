<?php
if(!defined('ABSPATH'))exit('Access denied!');
/**
 * 数据缓存类
 *
 * @package Cache
 * @version 1.0.0
 * @since   2014.10.25
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
            $datas = unserialize(secure_core(file_get_contents($filename),'DECODE'));
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
    		$rs = fputs($handle,secure_core(serialize($datas),'ENCODE'));
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