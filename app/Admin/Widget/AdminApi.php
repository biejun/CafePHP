<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminApi extends Widget
	{
		public function getSiteConfig()
		{
			$data = $this->cache->get('site:configs');
			if(!$data){
				$data = $this->db->rows('configs','*','`group` = 1');
				$this->cache->add('site:configs',$data);
			}
			return $data;
		}

		private function checkBackupFolder()
		{
			$dir = 'cache/backup';
			if (! file_exists ($dir)) {
				if (! is_writeable (dirname ($dir))) {
					die ('Backup folder must be writeable to continue.');
				}
				mkdir ($dir);
				chmod ($dir, 0777);
			}
			return $dir;
		}

		// 获取数据库备份文件
		public function getBackupFiles()
		{
			$backupFolder = $this->checkBackupFolder();
			// 读取文件夹
			$array = array();
			if($handle=opendir($backupFolder)){
				$no=1;
				while(false!==($file=readdir($handle))){
					if (strpos($file,'.sql')!==false){
						$array[$no]['no']=$no;
						$array[$no]['file']=$file;
						$array[$no]['created']=date('Y-m-d H:i:s',filemtime($backupFolder.'/'.$file));
						$no++;
					}
				}
				closedir($handle);
			}
			return $array;
		}

		// 导出指定数据库备份文件
		public function exportSQL()
		{
			$dir = $this->checkBackupFolder();

			$backup = $this->db->export();

			$filename = date('YmdHis') . mt_rand(0,99999) . '.sql';

			return file_put_contents($dir .'/'. $filename, $backup);
		}

		// 还原指定数据库备份文件
		public function restoreSQL($file)
		{
			if(empty($file)) return false;

			$dir = $this->checkBackupFolder();
			$filename = $dir .'/'. $filename;

			if(file_exists($filename)){

				$backup = explode(";\n", file_get_contents($filename));

				$this->db->query($backup);

				return true;
			}
			return false
		}

		// 删除指定数据库备份文件
		public function deleteSQL($file)
		{
			if(empty($file)) return false;

			$dir = $this->checkBackupFolder();
			$filename = $dir .'/'. $filename;

			if(file_exists($filename)){

				unlink($filename);

				return true;
			}
			return false;
		}
	}
}