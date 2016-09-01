<?php
# ================================================================
# 图片处理类
# @core     anyjs.org
# @author   biejun
# @update   2015.09.20
# @notice   您只能在不用于商业目的的前提下对程序代码进行修改和使用
# ================================================================
class image {

    /**
     * 取得图像信息
     * 
     * @param string $image 图像文件名
     * @return mixed
     */
	public static function getImageInfo($img) {
		$imageInfo = getimagesize($img);
		if( $imageInfo!== false) {
			$imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
			$imageSize = filesize($img);
			$info = array(
				"width"=>$imageInfo[0],
				"height"=>$imageInfo[1],
				"type"=>$imageType,
				"size"=>$imageSize,
				"mime"=>$imageInfo['mime']
			);
		    return $info;
		}else {
		    return false;
		}
	}
	
	/**
	 * 给图片添加水印 支持JPG,GIF,PNG
	 * 
	 * @param string $source 原图片 (包含路径 例子: ROOT.'images/pic.jpg')
	 * @param string $water  水印图片  (包含路径 例子: ROOT.'images/water.png')
	 * @param string $water  保存图片名  (包含路径 例子: ROOT.'images/save.jpg')
	 * @param int $position  水印位置 1顶部居左, 2顶部居中, 3顶部居右, 4居中, 5底部居左, 6底部居中, 7底部居右, 默认为随机, 这里设置为 7底部居右
	 * @param int $alpha 水印的透明度
	 * @return int
	 */
	public static function water($source, $water, $savename = '', $position = 7, $alpha = 80) {
		# 检查原图或水印图是否存在
		if(!is_file($source)||!is_file($water)) {
			return 0; # 要添加水印图或原图片路径错误或者不存在
		}
		# 图片信息
		$source_info = getimagesize($source);
		$water_info = getimagesize($water);
		
		if($source_info[2] > 3) return -2; # 仅支持gif,jpg,png格式的文件
		
		# 如果图片小于水印图片，不生成水印图片
		if($source_info[0] < $water_info[0] || $source_info[1] < $water_info[1]) {
			# 如果没有给出图片保存名,直接不生成
			if(empty($savename)) {
				return 1;
			}
			# 如果给出文件名,复制原图
			if(copy($source, $savename)) {
				return 1;
			} else {
				return -3; # 复制图片生成水印图出错
			}
		} else {
			switch($source_info[2]) {
				case 1:
					$source_image = imagecreatefromgif($source);
					break;
				case 2:
					$source_image = imagecreatefromjpeg($source);
					break;
				case 3:
					$source_image = imagecreatefrompng($source);
					break;
			}
			switch($water_info[2]) {
				case 1:
					$water_image = imagecreatefromgif($water);
					break;
				case 2:
					$water_image = imagecreatefromjpeg($water);
					break;
				case 3:
					$water_image = imagecreatefrompng($water);
					break;
			}
			# 设定图像的混色模式
			imagealphablending($water_image, TRUE);
			# 水印图像位置,默认为随机
			switch($position) {
				case 1:
					$position_x = 10;
					$position_y = 10;
					break;
				case 2:
					$position_x = ($source_info[0] - $water_info[0]) / 2;
					$position_y = 10;
					break;
				case 3:
					$position_x = $source_info[0] - $water_info[0] - 10;
					$position_y = 10;
					break;
				case 4:
					$position_x = ($source_info[0] - $water_info[0]) / 2;
					$position_y = ($source_info[1] - $water_info[1]) / 2;
					break;
				case 5:
					$position_x = 10;
					$position_y = $source_info[1] - $water_info[1] - 10;
					break;
				case 6:
					$position_x = ($source_info[0] - $water_info[0]) / 2;
					$position_y = $source_info[1] - $water_info[1] - 10;
					break;
				case 7:
					$position_x = $source_info[0] - $water_info[0] - 10;
					$position_y = $source_info[1] - $water_info[1] - 10;
					break;
				default:
					$position_x = mt_rand(0, $source_info[0] - $water_info[0]);
					$position_y = mt_rand(0, $source_info[1] - $water_info[1]);
			}
			# 透明处理
			imagesavealpha($source_image, TRUE);
			imagesavealpha($water_image, TRUE);
			# 生成混合图像
			imagecopymerge($source_image, $water_image, $position_x, $position_y, 0, 0, $water_info[0], $water_info[1], $alpha);
			# 输出图像函数
			$image_fun = '';
			switch($source_info[2]) {
				case 1:
					$image_fun = 'imagegif';
					break;
				case 2:
					$image_fun = 'imagejpeg';
					break;
				case 3:
					$image_fun = 'imagepng';
					break;
			}
			# 如果没有给出保存文件名，默认为原图像名
			if(empty($savename)) {
				$savename = $source;
			}
			# 保存图像
			if($image_fun($source_image, $savename)) {
				imagedestroy($source_image);
				imagedestroy($water_image);
				return 1;
			} else {
				return -4; // 图片添加水印失败
			}
		}
	}
	
	/**
	 * 生成缩略图 支持JPG,GIF,PNG
	 * 
	 * @param string $image  原图 (包含路径 例子: ROOT.'images/pic.jpg')
	 * @param string $thumbname 缩略图文件名 (包含路径 例子: ROOT.'images/thumb_pic.jpg')
	 * @param string $max_width  最大宽度
	 * @param string $max_height 最大高度
	 * @param boolean $interlace 启用隔行扫描
	 * @return boolean
	 */
	public static function thumb($image,$thumbname,$maxWidth=200,$maxHeight='auto',$interlace=true) {
		# 检查图片是否存在
		if (!is_file($image))return false;

		$info  = Self::getImageInfo($image);
		if($info !== false){
			$srcWidth  = $info['width'];
			$srcHeight = $info['height'];
			$type = $info['type'];
			$interlace  =  $interlace? 1:0;
			unset($info);
			# 计算缩放比例
			$scale = ($maxHeight=='auto')?$maxWidth/$srcWidth:min($maxWidth/$srcWidth, $maxHeight/$srcHeight);

			if($scale>=1) {
				# 超过原图大小不再缩略
				$width   =  $srcWidth;
				$height  =  $srcHeight;
			}else{
				# 缩略图尺寸
				$width  = (int)($srcWidth*$scale);
				$height = (int)($srcHeight*$scale);
			}

			# 载入原图
			$createFun = 'ImageCreateFrom'.($type=='jpg'?'jpeg':$type);
			$srcImg     = $createFun($image);

			# 创建缩略图
			if($type!='gif' && function_exists('imagecreatetruecolor')){
			    $thumbImg = @imagecreatetruecolor($width, $height);
			}else{
			    $thumbImg = @imagecreate($width, $height);
			}

			# 新建PNG缩略图通道透明处理
			if('png'==$type) {
			    @imagealphablending($thumbImg, false);# 取消默认的混色模式
			    @imagesavealpha($thumbImg,true);# 设定保存完整的 alpha 通道信息
			}elseif('gif'==$type) {
				# 新建GIF缩略图预处理，保证透明效果不失效
			    $background_color  =  @imagecolorallocate($thumbImg,0,255,0); # 指派一个绿色
			    @imagecolortransparent($thumbImg,$background_color); # 设置为透明色，若注释掉该行则输出绿色的图
			}
			if(function_exists("ImageCopyResampled")){
			    @imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth,$srcHeight);
			}else{
			    @imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height,  $srcWidth,$srcHeight);
			}
			# 对jpeg图形设置隔行扫描
			if('jpg'==$type || 'jpeg'==$type)@imageinterlace($thumbImg,$interlace);
			$imageFun = 'image'.($type=='jpg'?'jpeg':$type);
			@$imageFun($thumbImg,$thumbname);
			@imagedestroy($thumbImg);
			@imagedestroy($srcImg);
			return $thumbname;
		}
		return false;
	}
    /**
     * 裁剪图片 支持 JPG,GIF,PNG
     * 
     * @param string $image  原图 (包含路径 ROOT)
     * @param string $filename 裁剪后的图片
     * @param int $mode 图片模式 1 裁剪 2 缩放 填充白色背景
     * @param int $cutWidth
     * @param int $cutHeight
     * @return void
     */
    public static function cut($image,$filename,$maxWidth=200,$maxHeight=200,$mode=1,$interlace=true){

		# 获取原图信息
		$info  = Self::getImageInfo($image);
		if($info !== false) {
			$srcWidth  = $info['width'];
			$srcHeight = $info['height'];
			$pathinfo = pathinfo($image);
			$type =  $pathinfo['extension'];
			$type = $info['type'];
			$interlace  =  $interlace? 1:0;
			unset($info);
			$createFun = 'ImageCreateFrom'.(($type=='jpg' || $type=='ile')?'jpeg':$type);
			$srcImg = $createFun($image);
			if($type!='gif' && function_exists('imagecreatetruecolor')){
			    $thumbImg = imagecreatetruecolor($maxWidth, $maxHeight);
			}else{
			    $thumbImg = imagecreate($maxWidth, $maxHeight);
			}
			if('png'==$type) {
			    imagealphablending($thumbImg, false);
			    imagesavealpha($thumbImg,true);
			}elseif('gif'==$type) {
			    $background_color  =  imagecolorallocate($thumbImg,0,255,0);
			    imagecolortransparent($thumbImg,$background_color);
			}
			if($mode==1){
				# 计算缩放比例
				if(($maxWidth/$maxHeight)>=($srcWidth/$srcHeight)){
				    # 宽不变,截高，从中间截取 y=
				    $width  =   $srcWidth;
				    $height =   $srcWidth*($maxHeight/$maxWidth);
				    $x      =   0;
				    $y      =   ($srcHeight-$height)*0.5;
				}else{
				    # 高不变,截宽，从中间截取，x=
				    $width  =   $srcHeight*($maxWidth/$maxHeight);
				    $height =   $srcHeight;
				    $x      =   ($srcWidth-$width)*0.5;
				    $y      =   0;
				}
				if(function_exists("ImageCopyResampled")){
				    ImageCopyResampled($thumbImg, $srcImg, 0, 0, $x, $y, $maxWidth, $maxHeight, $width,$height);
				}else{
				    ImageCopyResized($thumbImg, $srcImg, 0, 0, $x, $y, $maxWidth, $maxHeight,  $width,$height);
				}
			}else{
				$x=0;$y=0;
				if(($srcWidth/$maxWidth)>($srcHeight/$maxHeight)){
					$width=$maxWidth;
					$height=round($srcHeight/($srcWidth/$maxWidth));
				}else{
					$width=round($srcWidth/($srcHeight/$maxHeight));
					$height=$maxHeight;
				}
				if($width<$maxWidth) $x = ($maxWidth-$width)/2;
				if($height<$maxHeight) $y = ($maxHeight-$height)/2;
				//$rgb  =  imagecolorat($img,0,15);
				//$r  = ( $rgb  >>  16 ) &  0xFF ;
				//$g  = ( $rgb  >>  8 ) &  0xFF ;
				//$b  =  $rgb  &  0xFF ;
				//$bg = imagecolorallocate($thumbImg,$r, $g, $b);
				$bg = imagecolorallocate($thumbImg,255, 255, 255);
				imagefill($thumbImg,0,0,$bg);
				imagecopyresampled($thumbImg,$srcImg,$x,$y,0,0,$width,$height,$srcWidth,$srcHeight);
			}
			if('jpg'==$type || 'jpeg'==$type) imageinterlace($thumbImg,$interlace);
			$imageFun = 'image'.($type=='jpg'?'jpeg':$type);
			$imageFun($thumbImg,$filename);
			imagedestroy($thumbImg);
			imagedestroy($srcImg);
			return $filename;
		}
		return false;
	}
}