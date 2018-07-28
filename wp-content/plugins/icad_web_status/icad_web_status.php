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



function build_crawling_form_page( $atts ){
	include "crawl_html.php";
	return "Complete";
}
add_shortcode( 'icad_crawl', 'build_crawling_form_page' );