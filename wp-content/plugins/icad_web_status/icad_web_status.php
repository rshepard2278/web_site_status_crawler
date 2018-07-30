<?php
/**
*  Plugin Name: ICAD Web Status 
*  Plugin URI:
*  Description: Monitors the status of your site and all (mostly all) of the pages. Sends daily alerts if a page goes down and weekly reports of site status
*  Version:     2018.07.28.1
*  Author:      Insight Computer & Design
*  Author URI:	www.insightcad.com
*  License:     GPL2
*  License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
$root =  plugin_dir_path( __FILE__ );
include($root . "options/init_options.php");

function crawl_from_front( $atts ){
	if($atts["type"] == "front"){
		include "crawl_from_front.php";
		return "Complete";
	} else if($atts["type"] == "back") {
		include "crawl_from_back.php";
		return "Complete";
	}
	
}
add_shortcode( 'icad_crawl_front', 'crawl_from_front' );

// function crawl_from_backend( $atts ){
// 	include "crawl_from_back.php";
// 	return "Complete";
// }
// add_shortcode( 'icad_crawl_back', 'crawl_from_backend' );

