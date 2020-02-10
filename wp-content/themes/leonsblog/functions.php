<?php
add_theme_support( 'post-thumbnails' );
/*
if(get_option('upload_path')=='wp-content/uploads' || get_option('upload_path')==null) {
	update_option('upload_path',WP_CONTENT_DIR.'/uploads');
}*/
if ( ! function_exists( 'twentytwelve_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentytwelve_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 *
 * @return void
 */
function twentytwelve_entry_meta($postID) {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '<i class="icon-tag"></i> ', __( ' | ' ) );

	$date = sprintf( '<i class="icon-time"></i> <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date('Y-m-d') )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( '<i class="icon-list"></i> %1$s %2$s %3$s <i class="icon-eye-open"></i> %4$s' );
	} elseif ( $categories_list ) {
		$utility_text = __( '<i class="icon-list"></i> %1$s %3$s <i class="icon-eye-open"></i> %4$s' );
	} else {
		$utility_text = __( 'This entry was posted %3$s <i class="icon-eye-open"></i> %4$s');
	}

	$views = getPostViews($postID);
	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$views
	);
}
endif;
if ( ! function_exists( 'twentytwelve_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;
//custom widget tag cloud
add_filter( 'widget_tag_cloud_args', 'theme_tag_cloud_args' );
function theme_tag_cloud_args( $args ){
	$newargs = array(
		'number'      => 25,     //显示个数
		'format'      => 'flat',//列表格式，可以是flat、list或array
		'separator'   => "\n",   //分隔每一项的分隔符
		'orderby'     => 'count',//排序字段，可以是name或count
		'order'       => 'DESC', //升序或降序，ASC或DESC
		'exclude'     => null,   //结果中排除某些标签
		'include'     => null,  //结果中只包含这些标签
		'link'        => 'view',
        'taxonomy'    => array('post_tag','category'),
        'echo'        => true,
	);
	$return = array_merge( $args, $newargs);
	return $return;
}
/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentytwelve_page_menu_args' );

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentytwelve' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentytwelve_widgets_init' );
/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function twentytwelve_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentytwelve_wp_title', 10, 2 );
if ( ! function_exists( 'twentytwelve_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentytwelve_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Twelve 1.0
 *
 * @return void
 */
function twentytwelve_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'twentytwelve' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link"><i class="icon-edit"></i> ', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<?php echo get_avatar( $comment, 48 ); ?>
			<header class="comment-meta comment-author vcard">
				<?php
					printf( '<cite><i class="icon-user"></i> %1$s <i class="icon-time"></i> <a href="%2$s"><time datetime="%3$s">%4$s</time></a></cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', '' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<section class="comment-content comment">
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( '评论正在等待审核.', 'twentytwelve' ); ?></p>
				<?php endif; ?>
				<?php comment_text(); ?>
			</section><!-- .comment-content -->

			<footer>
				<?php edit_comment_link( __( '编辑'), '<i class="icon-edit"></i> ', '' ); ?>
	
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( '回复', 'twentytwelve' ), 'before' => '<span><i class="icon-share-alt"></i></span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	
			</footer>
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;
//highlight
// add_action('wp_syntax_init_geshi', 'my_custom_geshi_styles');
// function my_custom_geshi_styles($geshi)
// {
// 	$geshi->set_methods_style(1, 'color: #d3c570;'); //第一组方法名称
//     $geshi->set_comments_style(1, 'color: #999;'); //第一组注释
//     $geshi->set_comments_style(2, 'color: #33CC66;'); //第二组注释
//     $geshi->set_comments_style('MULTI', 'color: #999;'); //多行注释
//     $geshi->set_strings_style('color: #d3c570;'); //字符串直接量
//     $geshi->set_strings_style('color: #d3c570;', false, 'HARD'); //字符串直接量(*)
// }
// if ( has_action( 'wp_print_styles', 'wp_syntax_style' ) ) {  
// 	remove_action( 'wp_print_styles', 'wp_syntax_style' );  
// };  
//分页
function pagenavi($p = 4,$before = '', $after = '') {   
	if ( is_singular() ) return;   
	global $wp_query, $paged;   
	$max_page = $wp_query->max_num_pages;   
	if ( $max_page == 1 ) return;   
	if ( empty( $paged ) ) $paged = 1;   
	echo $before.'<div class="pagination pagination-centered"><ul>'."\n";   
	if ( $paged > 1 ) p_link( $paged - 1, '上一页', '«' );   
	if ( $paged > $p + 1 ) p_link( 1, '首页', '首页' );   

	for( $i = $paged - $p; $i <= $paged + $p; $i++ ) {   
		if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<li class='active'><a href='javasctipt:'>{$i}</a></li>" : p_link( $i );   
	}

	if ( $paged < $max_page ) p_link( $paged + 1,'下一页', '»' ); 
	if ( $paged < $max_page - $p ) p_link( $max_page, '末页', '末页' );
	echo '</ul></div>'.$after."\n"; 
}

function p_link( $i, $title = '', $linktype = '' ) {   
	if ( $title == '' ) {
		$title = "第{$i}页";   
	}
	if ( $linktype == '' ) { 
			$linktext = $i; 
		} 
	else { 
		$linktext = $linktype; 
	}   
	echo "<li><a href='", esc_html( get_pagenum_link( $i ) ), "' title='{$title}'>{$linktext}</a></li>";   
}
//先找h2
function article_index($content) {
    $matches = array();
    $li = '';
    $h2 = "/<h2>([^<]+)<\/h2>/im";
    if(preg_match_all($h2, $content, $matches)) {
        foreach($matches[1] as $num => $title) {
            $content = str_replace($matches[0][$num], '<h2 id="'.$title.'">'.$title.'</h2>', $content);        
            $tmp = find_h3($content, 'title-'.$num, ($num + 1));
            $h3 = $tmp['ol'];
            $content = $tmp['content'];
            unset($tmp);
            $li .= "<li><a href='#{$title}' title='{$title}'>{$title}</a>{$h3}</li>\n";
        }
        $content = "\n<div id=\"article-index\">
        				<h2>文章目录</h2>
		                <ol id=\"index-ul\">\n" . $li . "</ol>
		        	</div><div id='content-main'>\n" . $content . "</div>";
    }
    return $content;
}
//再找h3
function find_h3($c, $start, $nth){
	$start_pos = stripos($c, $start);
	$bef = substr($c, 0, $start_pos);
	$aft = '';
	$c = substr($c, $start_pos);
	$end_pos = stripos($c, "<h2>");
	if(!empty($end_pos)){
		$aft = substr($c, $end_pos);
		$c = substr($c, 0, $end_pos);
	}

	$h3 = "/<h3>([^<]+)<\/h3>/im";
	$ul = '<ol>';
	$matches = array();
	if(preg_match_all($h3, $c, $matches)) {
        foreach($matches[1] as $num => $title) {
        	$id = $start.'-'.$num;
            $c = str_replace($matches[0][$num], '<h3 id="'.$title.'">'.$title.'</h3>', $c);
            $ul .= '<li><a href="#'.$title.'" title="'.$title.'">'.$nth.'.'.($num + 1).'&nbsp;'.$title."</a></li>\n";
        }
	}
	$ul .= '</ol>';
	$c = $bef . $c . $aft;
	return array('ol' => $ul, 'content' => $c);
}
//列表时过滤掉目录
function index_article_content($c){
	$c = mb_strimwidth(strip_tags($c), 0, 400,"...");
	return $c;
}


//控制摘要长度
function excerpt_read_more_link($output) {
	global $post;
	$output = mb_substr($output,0, 55); // 这里修改想要截取的长度
	return $output . '<a href="'. get_permalink($post->ID) . '"> 阅读全文...</a>';
}

//文章阅读次数
function getPostViews($postID){
    $count_key = 'views';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        $rand = 1;
        add_post_meta($postID, $count_key, $rand);
        return $rand;
    }
    return $count;
}
 
function setPostViews($postID) {
    $count_key = 'views';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, 1);
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
function userAgent() {
	$arr = array("iPhone","iPad","webOS","BlackBerry","Android");
	foreach($arr as $value)
	if(stripos($_SERVER['HTTP_USER_AGENT'], $value)){
		return TRUE;
	}
	return FALSE;
}
function unblock_gravatar( $avatar ) {
    $avatar = str_replace( array( 'http://www.gravatar.com', 'http://0.gravatar.com', 'http://1.gravatar.com', 'http://2.gravatar.com' ), 'https://secure.gravatar.com', $avatar );
    return $avatar;
}
function disable_srcset( $sources ) {
	return false;
}

add_filter( 'the_content', 'article_index' );
add_filter( 'get_avatar', 'unblock_gravatar' );
remove_action( 'wp_head', 'wp_generator' ); 
remove_action( 'wp_head', 'rsd_link' );   
remove_action( 'wp_head', 'wlwmanifest_link' ); 
add_filter( 'wp_calculate_image_srcset', 'disable_srcset' );
add_filter('the_excerpt', 'excerpt_read_more_link');