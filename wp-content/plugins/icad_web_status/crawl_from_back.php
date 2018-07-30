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

	$dir =  plugin_dir_path( __FILE__ );
	$crawled_urls = array();
	$found_urls = array();
	include($dir . "crawl/initial_crawl_page.php");
		//die("initial_crawl_page included");
	include($dir . "crawl/spider.class.php");
		//die("spider.class.php included");
	include($dir . "crawl/crawl_functions.php");
		//die("crawl_funcitons.php included");
	
	const EMAIL = 'email';
	const SITES	= 'sites';



	$urls_to_crawl = get_icad_options(SITES);
	echo "URLs to Crawl<br><pre>";
	var_dump($urls_to_crawl);
	echo "</pre>";
	$emails_to_send = get_icad_options(EMAIL);

	echo "Emails to Send<br><pre>";
	var_dump($emails_to_send);
	echo "</pre>";
	//die("Loaded emails and urls");
	foreach($urls_to_crawl as $url) {
		echo "<br>Checking: " . $url;
		if(check_site_status($url)) {
			$message = "<br>" . $url . " is up";
			//die ($message);
			$url_status_array = crawl_site($url);
			foreach($emails_to_send as $email) {
				send_email_report($url_status_array, $email);
			}
		} else {
			echo "<br>" . $url . " is down";
		}
	}
	
	

	function get_icad_options($type) {
		$options = get_option( 'icad__settings' );
		if($type == SITES) {
			return $options['icad_site_list'];
		} else if ($type == EMAIL) {
			return $options['icad_email_list'];
		} else {
			error_log("Not correct type");
		}
	}
?>