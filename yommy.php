<?php
/**
 * @package Yommy
 * @author Abdelilah Sawab, johnnny
 * @version 1.0
 */
/*
Plugin Name: Yommy
Description: Yommy widget integration into posts and pages. 
Author: Abdelilah Sawab
Version: 1.0
*/



// Hook admin menu | options page
add_action('admin_menu', 'yommy_menu');

function yommy_menu() {
  add_options_page('Yommy Options', 'Yommy', 'administrator', 'yommy-admin', 'yommy_options');
}

function yommy_options() {
  require dirname(__FILE__).'/page_options.php';
}


add_filter("the_posts", "yommy_content_filter" );


function yommy_content_filter($content)
{
	$options = get_option('atokens_config');
	
	$posts = array();
	foreach($content as $item)
	{
		if(
			($item->post_type == 'post' && $options['posts'] == '1') || 
			($item->post_type == 'page' && $options['pages'] == '1')
		)
		{
			$style = "";
			if($options['hpos'] == 'right')
				$style .= "float: right;";
			
			$code = '<div class="atokens" style="'.$style.'"><script>var atokens_settings = {"username": "'.$options['username'].'", "style": "'.$options['style'].'", "url": "'.$item->guid.'"} 
</script><script src="https://yommy.com/js/widget/widget.js?v=0" type="text/javascript"></script><br style="clear: both" /></div>';
			
			if($options['vpos'] == 'top')
				$item->post_content = $code . $item->post_content;
			else
				$item->post_content .= $code;
		}
		
		$posts[] = $item;
	}
	
	return $posts;
}



