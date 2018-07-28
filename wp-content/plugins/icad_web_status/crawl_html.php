<?php
	$root =  plugin_dir_path( __FILE__ );
	include($root . "crawl/url_input_form.php");
	include($root . "crawl/initial_crawl_page.php");
	include($root . "crawl/spider.class.php");

	$crawled_urls = array();
	$found_urls = array();

	include($root . "crawl/crawl_functions.php");

	if(isset($_POST['submit'])){
		$url = $_POST['url'];
		if ($url == ''){
			echo "<h2>A valid URL please.</h2>";
		} else {
			echo "<h2>Result - URL's Found</h2>";
			$url_status_array = crawl_site($url);
			display_results($url_status_array);
		}
	}
?>