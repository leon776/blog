<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( ' - ', true, 'right' ); ?></title>
<link rel="apple-touch-icon" href="" />
<link rel="stylesheet" href="/wp-content/themes/leonsblog/style.css" type="text/css" media="all">
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<?php
if (is_archive() && ($paged > 1) && ($paged < $wp_query->max_num_pages)) { ?>   
	<link rel="prefetch" href="<?php echo get_next_posts_page_link(); ?>" />   
	<link rel="prerender" href="<?php echo get_next_posts_page_link(); ?>" />   
<?php } ?>
<!--[if lt IE 9]>
<script src="/wp-content/themes/leonsblog/js/html5.js" type="text/javascript"></script>
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" href="/wp-content/themes/leonsblog/css/ie.css" type="text/css" media="all">
<![endif]-->
<?php wp_head(); ?>
<link rel="stylesheet" type="text/css" href="/wp-content/plugins/code-prettify/prettify/prettify.css">
<script>
	window.cancelRequestAnimFrame = ( function() {
	    return window.cancelAnimationFrame          ||
	        window.webkitCancelRequestAnimationFrame||
	        window.mozCancelRequestAnimationFrame   ||
	        window.oCancelRequestAnimationFrame     ||
	        window.msCancelRequestAnimationFrame    ||
	        clearTimeout
	} )();
	window.requestAnimFrame = (function(){
	    return  window.requestAnimationFrame   || 
	        window.webkitRequestAnimationFrame || 
	        window.mozRequestAnimationFrame    || 
	        window.oRequestAnimationFrame      || 
	        window.msRequestAnimationFrame     || 
	        function(/* function */ callback, /* DOMElement */ element){
	            return window.setTimeout(callback, 1000 / 60);
	        };
	})();
	function addEvent(obj,type,fn){
	    if(obj.attachEvent){
	        obj.attachEvent('on'+type,function(){
	            fn.call(obj);
	        })
	    }else{
	        obj.addEventListener(type,fn,false);
	    }
	}
	function removeEvent(element, type, handler) { 
	  if (element.removeEventListener) { 
	    element.removeEventListener(type, handler, false); 
	  } else { 
	    // delete the event handler from the hash table 
	    if (element.events && element.events[type]) { 
	      delete element.events[type][handler.$$guid];
	    } 
	  } 
	}; 
	var header = {
		baseUrl : '/wp-content/themes/leonsblog/',
		webgl : ( function () { try { return !! window.WebGLRenderingContext && !! document.createElement( 'canvas' ).getContext( 'experimental-webgl' ); } catch( e ) { return false; } } )(),
		_addScript : function(src, callback) {
			var head= document.getElementsByTagName('head')[0];  
			var script= document.createElement('script');
			script.onload = script.onreadystatechange = function() {  
		    if (!this.readyState || this.readyState === "loaded" || this.readyState === "complete" ) {
		        // Handle memory leak in IE 
		        script.onload = script.onreadystatechange = null;
		        callback();
		        head.removeChild(script);
		    }}; 
		    script.src = this.baseUrl + src;  
			head.appendChild(script);
		},
		drawHeadBackground : function(id) {
			var _this = this;
			if(this.webgl && Math.random() > 0.5) {
				this._addScript('/js/three.min.js', function(){
					_this._addScript('/js/background.js', function(){
						drawBackground.init(id);
					});
				});
			}
			else {
				document.getElementById(id).style.backgroundImage = 'url('+ this.baseUrl +'/images/hd_bg-'+
				Math.round(Math.random()*2) +
				'.jpg)';
			}
		}
	}
</script>
</head>

<body <?php body_class(); ?> onload="PR.prettyPrint()">
<!--[if lt IE 8]>
	<script>window.location.href = 'kill_ie7.html';</script>
	<noscript>
		<style type="text/css">
			.site,#colophon,#roll,.site-info{display:none}
		</style>
	</noscript>
	<div style="text-align:center;padding:20% 0;">系统检测到您正在使用低于IE7且禁用js脚本的的浏览器，本站内容可能不太适合您=。=</div>
<![endif]-->
<div id="pageBackground" class="site-background"></div>
<script>
	header.drawHeadBackground('pageBackground');
</script>
<div id="page" class="hfeed site">
	<div class="shadow-border"></div>
	<div class="shadow-border shadow-border-right"></div>
	<header id="masthead" class="site-header" role="banner">
		<hgroup>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</hgroup>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ,'container' => 'div', 'container_class' => 'nav-menu-container') ); ?>
			<?php get_search_form($echo = true);?>

		</nav><!-- #site-navigation -->

 		
	</header><!-- #masthead -->

	<div id="main" class="wrapper">