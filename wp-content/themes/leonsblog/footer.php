<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
</div><!-- #page -->
<footer id="colophon" role="contentinfo">
	<div class="site-info">
		<p>
			powered by <a href="http://wordpress.org/" title="采用wordpress" rel="nofollow">wordpress</a> |
		icons by <a href="http://glyphicons.com/" title="Glyphiconss" rel="nofollow">Glyphicons</a> |
		designed by <a href="http://www.xiaoweilee.com/" title="leon’s blog">leon</a>
		</p>
		<p>
			<span>Copyright © 2011-2017 xiaoweilee.com</span>
		</p>
	</div><!-- .site-info -->
</footer><!-- #colophon -->
<?php wp_footer(); ?>
<div id="roll" class="scroll-window scroll-window-hide">
	<a id="toTop" href="javascript:;">返回顶部</a>
</div>
<script>
//返回顶部以及固定边栏
(function(){
var footer = function(page) {
	var getOffset = function(obj,type){
		var l = type === 'top' ? obj.offsetTop : obj.offsetLeft;
		while(obj.offsetParent != null){ 
			obj = obj.offsetParent;
			type === 'top' ? l += obj.offsetTop : l += obj.offsetLeft;
		}  
		return l;
	};
	var sideBarContainer = document.getElementById('secondary');
	var parimary = document.getElementById('primary');
	var fixedSidebarFlag = sideBarContainer.offsetHeight < parimary.offsetHeight ? true : false;
	var windowHeight = window.innerHeight || document.documentElement.clientHeight;
	var _this = this;
	this.roll = document.getElementById('roll')
	this.y 		= 0,
	this.oldY 	= 0,
	this.requestId = 0,
	this.getsBarMsg = function() {
		sideBarContainer.style.cssText  = '';
		sideBarContainer.className = 'widget-area';
		var innerHeight = windowHeight;
		this.sBarMsgOffsetTop 	= getOffset(sideBarContainer,'top'),
		this.sBarMsgOffsetLeft	= getOffset(sideBarContainer,'left'),
		this.sBarMsgWidth 		= sideBarContainer.offsetWidth,
		this.sBarMsgHeight 		= sideBarContainer.offsetHeight,
		this.sBarMsgmaxScroll   = this.sBarMsgHeight - innerHeight > 0 ? this.sBarMsgHeight - innerHeight : 0,
		this.sBarMsgmaxScrollPos= this.sBarMsgmaxScroll + this.sBarMsgOffsetTop,
		this.sBarMsgpositionTop = this.sBarMsgmaxScroll > 0 ? -this.sBarMsgmaxScroll : 0;
	},
	this.sBarMsgbackLength  = 0;
	//orz
	this.fixedSidebar = function(){
		
		console.log(_this.sBarMsgbackLength)
		if(!fixedSidebarFlag){
			return;
		}
		var y = _this.y;
		var value = _this.oldY - y;
		if(value <= 0 && y > _this.sBarMsgmaxScrollPos){
			_this.sBarMsgbackLength += value;
			_this.sBarMsgbackLength >= 0 ? _this.sBarMsgbackLength = _this.sBarMsgbackLength : _this.sBarMsgbackLength = 0;
			if(sideBarContainer.className !== 'widget-area widget-transition'){
				sideBarContainer.className = 'widget-area widget-transition';
			}
		} else if(value <= 0){
			_this.sBarMsgbackLength = this.sBarMsgOffsetTop - y -_this.sBarMsgpositionTop;
		} else if( value > 0 && (value + _this.sBarMsgbackLength) >= _this.sBarMsgmaxScroll){
			_this.sBarMsgbackLength = _this.sBarMsgmaxScroll;
			if(y <= _this.sBarMsgOffsetTop){
				sideBarContainer.className = 'widget-area';
				_this.sBarMsgbackLength +=  _this.sBarMsgOffsetTop - y;
			};
		} else if(value > 0){
			_this.sBarMsgbackLength += value;
		}
		var cssText = 'width:'+ _this.sBarMsgWidth +
		 	  'px;top:'+ (_this.sBarMsgpositionTop + _this.sBarMsgbackLength) +
		 	  'px;' +
		 	  'px;position:fixed';
 	  	sideBarContainer.style.cssText = cssText;
		_this.oldY = y;
	},
	this.init = function(){
		document.getElementById('toTop').onclick = function() {
			_this.backToTop(300, function() {

			});
		};

		// if(fixedSidebarFlag){
		// 	_this.getsBarMsg();
		// }

		// addEvent(window, 'scroll', this.listenScroll);
		if(typeof(addEventListener) !== 'undefined') {
			window.addEventListener('load',function() {
				_this.showLoadTime();
				if(typeof(drawBackground) !== 'undefined') {
					_this.visibilitychange();
				}
			},false);
		}
		this.showRatioTips();
	},
	this.backToTop = function(time, callback, t){
		if(_this.y === 0){
			cancelAnimationFrame(this.requestId);
			callback();
		}
		else{
			t = t || _this.y/(time * 0.06);//按每秒60帧来算
			_this.y -= t;
			window.scrollTo(0, _this.y);
			_this.requestId = requestAnimFrame(function(){
				_this.backToTop(time, callback, t)
			});
		}
	},
	//listen
	this.listenScroll = function(){
		_this.y = document.documentElement && document.documentElement.scrollTop ||
				  document.body && document.body.scrollTop;
		if(_this.y > 140){
			_this.roll.className = 'scroll-window scroll-window-show';
		}
		else{
			_this.roll.className = 'scroll-window scroll-window-hide';
		}
		_this.fixedSidebar();
	},
	//show load time
	this.showLoadTime = function(){
		var time = window.performance.timing;
		console.log('用户感知延迟: '       + (time.domComplete - time.navigationStart) + 'ms\n'+ 
					'DNS查找时间: '        + (time.domainLookupEnd - time.domainLookupStart) + 'ms\n'+ 
					'服务器连接时间: '     + (time.connectEnd - time.connectStart) + 'ms\n'+
					'服务器执行脚本耗时: ' + (time.responseStart - time.connectEnd) + 'ms\n'+
					'服务器传输耗时: ' 	   + (time.responseEnd - time.responseStart) + 'ms\n'+
					'页面渲染耗时: '	   + (time.domComplete - time.responseEnd) + 'ms' );
	},
	//visibilitychange
	this.visibilitychange = function() {
		document.addEventListener("webkitvisibilitychange", function() {
			document.webkitHidden ? drawBackground.stopAnimate() : drawBackground.startAnimate();
		});
	},
	this.showRatioTips = function() {
		var ratio = window.devicePixelRatio || screen.deviceXDPI/screen.logicalXDPI, 
			ratioTip = document.getElementById('ratioTip');
		if(ratio !== 1 && ratioTip === null) {
			var div = document.createElement('div');
			div.id = "ratioTip";
			div.className = "alert";
			div.innerHTML = '<button type="button" class="close" onclick="document.body.removeChild(this.parentNode)">×</button><strong>提示：</strong>您的浏览器正处于缩放状态，缩放比例为<span id="ratioTipNum">' + Math.round(ratio*100) + '</span>%';
			var firstChild = document.body.children[0];
			document.body.insertBefore(div, firstChild);
		} else if(ratio !== 1) {
			document.getElementById('ratioTipNum').innerHTML = Math.round(ratio*100);
		} else if(ratioTip) {
			ratioTip.parentNode.removeChild(ratioTip);
		}
	}
	
}
var footer = new footer('toTop', 'page');

// var rt = 0;
// window.onresize = function(){
// 	clearTimeout(rt);
// 	rt = setTimeout(function(){
// 		footer.init();
// 		footer.fixedSidebar();
// 	}, 20);
// }
footer.init();
})<?php if(!userAgent()) echo '()';?>;
</script>
<script type="text/javascript">
/* <![CDATA[ */
var code_prettify_settings = {"base_url":"/wp-content\/plugins\/code-prettify\/prettify"};
/* ]]> */
</script>
<script src="/wp-content/plugins/code-prettify/prettify/run_prettify.js?ver=1.3.4"></script>
<!--统计开始 -->
<div style="display:none">
	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_4989857'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s21.cnzz.com/stat.php%3Fid%3D4989857' type='text/javascript'%3E%3C/script%3E"));</script>
</div>
<!--统计结束 -->
</body>
</html>
