<?php namespace Cafe\Image;

class Upload
{
    public $maxSize = 20;
    public $allowTypes = ['image/png', 'image/jpeg', 'image/gif'];
    public $allowExts = ['jpg', 'jpeg', 'png', 'gif'];
    
    public function __construct($maxSize = 20, $allowExts = [])
    {
        // 文件大小限制 （单位 MB）
        $this->maxSize = $maxSize;
        
        // 允许的后缀
        if (!empty($allowExts)) {
            $this->allowExts  = $allowExts;
            // 根据后缀取conten-type
            foreach($allowExts as $ext) {
                $this->allowTypes[] = MimeTypes::getMimetype($ext);
            }
        }
    }
    // 检查文件
    public function check($file)
    {
        if ($file['error'] !== 0) {
            return $this->error($file['error']);
        }
        
        // 检查文件Mime类型
        if (!$this->checkType($file['type'])) {
            return '上传文件MIME类型不允许！';
        }
        
        if (!$this->checkExt($this->getExt($file['name']))) {
            return '上传文件类型不允许！';
        }
        
        if (!$this->checkUpload($file['tmp_name'])) {
            return '上传文件不合法！';
        }
        
        if($file['size'] > $this->maxSize * 1024 * 1024) {
            return "上传文件不能超过{$this->maxSize}MB！";
        }
        return '';
    }
    
    public function save($file, $saveFolder = '', $saveName = '')
    {
        $savePath = app()->publicPath('upload/'. $saveFolder);
        // 检查上传目录
        if (!is_dir($savePath)) {
            // 尝试创建目录
            if (!mkdir($savePath, 0777, true)) {
                return "上传目录$savePath不存在";
            }
        } else {
            if (!is_writable($savePath)) {
                return "上传目录$savePath不可写";
            }
        }
        
        $from = $file['tmp_name'];
        $to   = $savePath;
        if(empty($saveName)) {
            $saveName = uniqid().time();
        }
        $ext      = $this->getExt($file['name']);
        $filename = $saveName . "." . $ext;
        
        if($this->move($from, $to.$filename)) {
            return u('upload'. $saveFolder .$filename);
        }else{
            return false;
        }
    }
    
    /**
     * 移动上传文件
     *
     * @param   string  $from   文件来源
     * @param   string  $target 移动目标地
     * @return  boolean
     */
    public function move($from, $target= '')
    {
        if (function_exists("move_uploaded_file")) {
            if (move_uploaded_file($from, $target)) {
                @chmod($target, 0755);
                return true;
            }
        } elseif (copy($from, $target)) {
            @chmod($target, 0755);
            return true;
        }
        return false;
    }
    
    // 获取文件后缀
    public function getExt($filename)
    {
        $pathinfo = pathinfo($filename);
        return $pathinfo['extension'];
    }
    
    // 检查文件类型
    private function checkType($type)
    {
        if (!empty($this->allowTypes)) {
            return in_array(strtolower($type), $this->allowTypes);
        }
        return true;
    }
    
    // 检查文件后缀
    private function checkExt($ext)
    {
        if (in_array($ext, array('php', 'php3', 'exe', 'sh', 'html', 'asp', 'aspx', 'js', 'go', 'py'))) {
            return false;
        }
    
        if (!empty($this->allowExts)) {
            return in_array(strtolower($ext), $this->allowExts, true);
        }
        return true;
    }
    // 检查是否经过POST提交
    private function checkUpload($filename)
    {
        return is_uploaded_file($filename);
    }
    
    // 将错误友好的输出中文显示
    private function error($errorCode)
    {
        $error = '';
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                $error = "上传的文件超过了 php.ini 中 upload_max_filesize选项限制的值！";
                break;
            case UPLOAD_ERR_PARTIAL:
                $error = "文件只有部分上传！";
                break;
            case UPLOAD_ERR_NO_FILE:
                $error = "没有文件上传！";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $error = "找不到临时文件夹！";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $error = "文件写入失败！";
                break;
        }
        return $error;
    }
}