<?php
error_reporting(E_ALL);
if(isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], 'xiaoweilee.com') === false){
    //exit;
} elseif(!isset($_SERVER["HTTP_REFERER"]) || $_SERVER["HTTP_REFERER"] === NULL){
    //exit;
}

//png2jpg  
function png2jpg($srcPathName, $delOri=true)  
{  
    $srcFile=$srcPathName;  
    $srcFileExt=strtolower(trim(substr(strrchr($srcFile,'.'),1)));  
    if($srcFileExt=='png')  
    {  
        $dstFile = str_replace('.png', '.jpg', $srcPathName);  
        $photoSize = GetImageSize($srcFile);  
        $pw = $photoSize[0];  
        $ph = $photoSize[1];  
        $dstImage = ImageCreateTrueColor($pw, $ph);  
        imagecolorallocate($dstImage, 255, 255, 255);  
        //读取图片  
        $srcImage = ImageCreateFromPNG($srcFile);  
        //合拼图片  
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $pw, $ph, $pw, $ph);  
        imagejpeg($dstImage, $dstFile, 90);  
        if ($delOri)  
        {  
            unlink($srcFile);  
        }  
        imagedestroy($srcImage);  
    }  
}  

function curl_get_contents($url, $ip) {
    $header = array( 
                    "CLIENT-IP:{$ip}", 
                    "X-FORWARDED-FOR:{$ip}", 
                    ); 
    $ch = curl_init();   
    curl_setopt($ch, CURLOPT_URL, $url);            //设置访问的url地址   
    //curl_setopt($ch,CURLOPT_HEADER,1);            //是否显示头部信息   
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  //伪造Ip
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);           //设置超时   
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   //用户访问代理 User-Agent   
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.douban.com/');        //设置 referer   
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);      //跟踪301   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果   
    $r = curl_exec($ch);   
    curl_close($ch);   
    return $r;
}
//调整图片大小
/**
 *图片按比例调整大小的原理：
 *1、比较原图大小是否小于等于目标大小，如果是则直接采用原图宽高
 *2、如果原图大小超过目标大小，则对比原图宽高大小
 *3、如：宽>高，则宽=目标宽, 高=目标宽的比例 * 原高
 *4、如：高>宽，则高=目标高，宽=目标高的比例 * 原宽   
 **/
function imgResize($image, $max_width, $max_height){

    $size = getimagesize('data://image/jpeg;base64,'. base64_encode($image));//得到图像的大小
    
    $width = $size[0];             
    $height = $size[1];

    $x_ratio = $max_width / $width;
    $y_ratio = $max_height / $height;

    if (($width <= $max_width) && ($height <= $max_height))
    {
        $tn_width = $width;
        $tn_height = $height;
    }
    elseif (($y_ratio * $width) < $max_width)
    {
        $tn_height = $max_height;
        $tn_width = ceil($y_ratio * $width);
    }
    else
    {
        $tn_width = $max_width;
        $tn_height = ceil($x_ratio * $height);
    }

    $src = imagecreatefromjpeg('data://image/jpeg;base64,'. base64_encode($image));
    if(empty($src)) {
        $src = ImageCreateFromPNG('data://image/jpeg;base64,'. base64_encode($image));  
    }
    
    $dst = imagecreatetruecolor($tn_width, $tn_height); //新建一个真彩色图像
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);        //重采样拷贝部分图像并调整大小

    header('Content-Type: image/jpeg');
    imagejpeg($dst,null,100);
    imagedestroy($src);
    imagedestroy($dst);
    unset($dst);
    unset($src);
}
function main(){
    $url = $_GET['url'];
    $url = str_replace('https:', 'http:', $url);
    $max_width = $_GET['w'];
    $max_height = $_GET['h'];
    
    if(!is_numeric($max_width) || !is_numeric($max_height) || empty($url) || !preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)) {
        exit;
    }
    $user_IP = isset($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
    $user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
    $image = curl_get_contents($url, $user_IP);
    imgResize($image, $max_width, $max_height);
}
main();
?>