<?php
	include("/crawl/url_input_form.php");
	include("/crawl/initial_crawl_page.php");
	include("/crawl/spider.class.php");

	$crawled_urls = array();
	$found_urls = array();

	include("/crawl/crawl_functions.php");

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