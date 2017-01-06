<?php
if( !defined('IS_ANY') ) exit('Access denied!');
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
    	
        $filename=$this->fileName($key);
        
        $datas=array();
        
        if(file_exists($filename) && is_readable($filename)){
        
            $datas = unserialize(secure_core(file_get_contents($filename),'DECODE'));
        
            if($minutes==0 || time() - $datas['time'] < $minutes*60 )
        
                return $datas['data'];
        }
    	return $datas;
    }

    public function write($key,$data){
    	
        $filename=$this->fileName($key);
    	
        if($handle = fopen($filename,'w+')){
    	
        	$datas = array('data'=>$data,'time'=>time());
    	
        	flock($handle,LOCK_EX);
    	
        	$rs = fputs($handle,secure_core(serialize($datas),'ENCODE'));
    	
        	flock($handle,LOCK_UN);
    	
        	fclose($handle);
    	
        	if($rs!==false)return true;
    	}
    	return false;
    }

    public function deleteCache($key){
    	
        $filename=$this->fileName($key);
    	
        if(file_exists($filename)) unlink($filename);
    }

    private function fileName($key){

    	return $this->dir.md5($key.VALIDATE);
    }

    public function getCacheFiles(){
        return glob($this->dir.'*');
    }
    
    public function clearCaches(){
        
        $file = $this->getCacheFiles();
        
        $result = array_map("unlink",$file);
        
        if($result !== false){
        
            return true;
        }else{
        
            return false;
        }
    }
}