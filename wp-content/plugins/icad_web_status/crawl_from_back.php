<?php
	
	// TODO 
	// 	Get Array of URLS to crawl
	// 		Create an options screen that allows for the addition/removal of URLs
	//		Show last run statuses on options page
	// 	Validate format of said URLS
	// 	Create cron job to start the crawl
	// 	Start Crawl
	// 		Only crawl URLs that on the root dir (don't crawl external)
	// 		OPTIONAL: Store crawl data to compare to on next crawl
	// 	Create well formated report
	// 		List parent URLs for all crawled
	// 			Amount of pages crawled per URL
	// 			List of pages that have status other than 200
	// 			Percentage of site that is UP (pages_crawled - other_status_pages) / pages_crawled
	// 			OPTIONAL: Compared to stored crawl data and give stats (i.e. pages added, deleted, new pages, down last time and up this time or vice versa, any other cool stats that would be cool)
	// 	Email report to an array of email address
	// 		Create another options screen or use same that allows for the addition/removal of email addresses
	// 		Send email on end of crawl
	include($root . "crawl/initial_crawl_page.php");
	include($root . "crawl/spider.class.php");

	$crawled_urls = array();
	$found_urls = array();

	include($root . "crawl/crawl_functions.php");

	$url_status_array = crawl_site($url);
	display_results($url_status_array);

?>