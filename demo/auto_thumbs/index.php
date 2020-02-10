<?php
	error_reporting(0);
	if(isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], 'xiaoweilee.com') === false){
		exit;
	} elseif(!isset($_SERVER["HTTP_REFERER"]) || $_SERVER["HTTP_REFERER"] === NULL){
		exit;
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>等高格子堆砌</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<?php
	function curl_get_contents($url) {
	    $ch = curl_init();   
	    curl_setopt($ch, CURLOPT_URL, $url);            //设置访问的url地址   
	    //curl_setopt($ch,CURLOPT_HEADER,1);            //是否显示头部信息   
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5);           //设置超时   
	    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   //用户访问代理 User-Agent   
	    curl_setopt($ch, CURLOPT_REFERER, 'http://www.aaa.com/');        //设置 referer   
	    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);      //跟踪301   
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果   
	    $r = curl_exec($ch);   
	    curl_close($ch);
	    return $r;   
	}
	$albumID = !empty($_GET['aid']) ? $_GET['aid'] : '67172624';
	$width = !empty($_GET['w']) ? $_GET['w'] : 700;
	$height = !empty($_GET['h']) ? $_GET['h'] : 200;
	$albumData = curl_get_contents("https://api.douban.com/v2/album/{$albumID}/photos?count=10");
	$albumData = json_decode($albumData);
	//var_dump($albumData->photos);
?>
<script type="text/javascript">
(function(window){
	var photo = function(elm,opt) {
		var me = this;
		me.container = typeof elm == 'object' ? elm : document.getElementById(elm);
		me.childrens = me.container.children;
		me.opt = {
			defaultHeight : 150,
			margin : parseInt(getComputedStyle(me.childrens[0])['margin-left']) + parseInt(getComputedStyle(me.childrens[0])['margin-right'])
		}
		for (i in opt) me.opt[i] = opt[i];
		me.refleshLayout();
	}
	photo.prototype = {
		_getEveryLineNums : function () {
			var me 	     = this,
				l 		 = me.childrens.length,
				curWidth = 0,//计数
				start    = 0,//计数
				tmp      = {},
				r        = [];//返回值
			for (var i = 0 ; i < l; i++) {
				var o = me._imgMsg(me.childrens[i]);
				curWidth += me.opt.defaultHeight * o.w/o.h + me.opt.margin;
				if(curWidth >= me.maxWidth) {
					tmp = {
						start : start,
						end   : i,
						height: Math.round(me.maxWidth/curWidth * me.opt.defaultHeight)
					};
					r.push(tmp);
					start = i + 1;
					curWidth = 0;
				}
			}

			return r;
		},
		_imgMsg : function(obj){
			var	o = {
					w : obj.getAttribute('data-width'),
			  		h : obj.getAttribute('data-height')
				}
			return o;
		},
		_sort : function(data) {
			var me = this, max = me.childrens.length - 1;
			if(data.length > 0){
				for(var i in data) {
					me._setAttr(data[i].start, data[i].end, data[i].height, false);
				}
				if(data[i].end < max){
					me._setAttr(data[i].end + 1, max, me.opt.defaultHeight, true);
				}
			} else{
				me._setAttr(0, max, me.opt.defaultHeight, true);
			}
		},
		_setAttr : function (start, end, h, last) {
			var me = this, item = me.childrens;
			var neW = me._getNeWidth(start, end, h, last, item);
			for(var i in neW) {
				me.__setAttrBox(neW[i], h, item[i]);
				me.__setAttrImg(neW[i], h, item[i]);
			}
		},
		__setAttrBox : function(w, h, box) {
			box.style.width   = w + 'px';
			box.style.height  = h + 'px';
		},
		__setAttrImg : function(w, h, box){
			var me = this;
			var img = box.getElementsByTagName('img')[0];
			baseSize = me._imgMsg(box);//保持图片原有比例
			if(w*h/baseSize.w > baseSize.h) {
				h = w*baseSize.h/baseSize.w;
			} else {
				w = baseSize.w*h/baseSize.h;
			}
			img.src = img.getAttribute('_src') + '&w=' + w + '&h=' + h;
			img.width   = w;
			img.height  = h;
		},
		_getNeWidth : function(s, e, h, last, item){
			var me = this, o = {}, r = [], tmpW = 0, tmpS,
				dValue = e > s ? e - s + 1 : 1;
			//先算误差
			for(var i = s; i <= e; i++) {
				o = me._imgMsg(item[i]);
				r[i] = h/o.h * o.w;
				tmpW += r[i];
			}
			var gep = !last ? Math.round( (me.maxWidth - tmpW - me.opt.margin * dValue)/dValue ): 0;
			tmpW = 0;
			for(var i in r) {
				tmpS = Math.floor(r[i] + gep);//把误差分配给前n-1个，最后一个另算
				if(tmpW + tmpS + me.opt.margin > me.maxWidth || (Number(i) === e && tmpW + tmpS + me.opt.margin <= me.maxWidth && !last)) {
					tmpS = me.maxWidth - tmpW - me.opt.margin;
				}
				r[i] = tmpS;
				tmpW += tmpS + me.opt.margin;
			}
			return r;
		},
		refleshLayout : function () {
			var me = this;
			me.maxWidth = me.container.offsetWidth;
			me._sort(me._getEveryLineNums());
		}
	};
	window.ePhoto = photo;
})(window);
</script>
<style type="text/css">
	body{margin: 0;padding: 0}
	.contener{width:<?php echo $width;?>px;overflow: hidden;margin: 0 0 0 -1em}
	.contener .item{float: left;margin: 0.5em 0 0.5em 1em;overflow: hidden;position: relative;background: #ccc}
	.contener .item img{}
	.contener .item p{margin: 0;bottom: 0;opacity: 0.7;background: #000;width: 100%;display: block;font-size: 12px;position: absolute;}
	.contener .item p a{text-decoration: none;display: block;line-height: 12px;height: 12px;overflow: hidden;margin: 5px 0;color: #FFF;text-indent: 0.5em}
	.args{font-size: 12px;}
	.args input[type='number']{width: 50px;border: 1px solid #CCC;}
	.args input[type='text']{width: 70px;border: 1px solid #CCC;}
</style>
</head>
<body>
	<div class="args">
		<form action="index.php" method="get">
			<label>豆瓣相册ID <input name="aid" type="text" value="<?php echo $albumID;?>"/></label>
			<label>容器宽度 <input name="w" type="number" min="1" value="<?php echo $width;?>"/></label>
			<label>基础高度 <input name="h" type="number" min="1" value="<?php echo $height;?>"/></label>
			<input type="submit" value="提交"/>
		</form>
	</div>
	<div class="contener" id="photo">
		<?php
			foreach ($albumData->photos as $key => $value) {
				//var_dump($value->alt);
				?>
					<div class="item" data-width="<?php echo $value->sizes->large[0];?>" data-height="<?php echo $value->sizes->large[1];?>">
						<a href="javascript:" target="_blank">
							<img _src="img.php?url=<?php echo $value->large;?>" />
						</a>
						<p>
							<?php
								if($value->desc !== '') {
									?>
									<a href="<?php echo $value->alt;?>" target="_blank"><?php echo $value->desc;?></a>
									<?php 
								}
							?>
						</p>
					</div>
				<?php
			}
		?>
	</div>
	<script type="text/javascript">
		var _ePhoto = new ePhoto('photo', {defaultHeight : <?php echo $height;?>});
	</script>
</body>
</html>