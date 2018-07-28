<?php
// Please specify your Mail Server - Example: mail.example.com.
ini_set("SMTP","smtp.gmail.com");

// Please specify an SMTP Number 25 and 8889 are valid SMTP Ports.
ini_set("smtp_port","587");

	function rel2abs($rel, $base){
		if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
		if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;
		extract(parse_url($base));
		$path = preg_replace('#/[^/]*$#', '', $path);
		if ($rel[0] == '/') $path = '';
		$abs = "$host$path/$rel";
		$re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
		for($n=1; $n>0;$abs=preg_replace($re,'/', $abs,-1,$n)){}
			$abs=str_replace("../","",$abs);
		return $scheme.'://'.$abs;
	}

	function perfect_url($u,$b){
		$bp=parse_url($b);
		if(($bp['path']!="/" && $bp['path']!="") || $bp['path']==''){
			if($bp['scheme']==""){
				$scheme="http";
			} else {
				$scheme = $bp['scheme'];
			}
			$b = $scheme . "://" .$bp['host'] . "/";
		}
		if(substr($u,0,2)=="//"){
			$u="http:".$u;
		}
		if(substr($u,0,4)!="http"){
			$u=rel2abs($u,$b);
		}
		return $u;
	}

	function crawl_site($u){
		global $crawled_urls;
		$uen = urlencode($u);
		$url_status_array = array();
		//ob_start();
		if((array_key_exists($uen,$crawled_urls) == 0 || $crawled_urls[$uen] < date("YmdHis",strtotime('-25 seconds', time())))){
			$html = file_get_html($u);
			$crawled_urls[$uen] = date("YmdHis");
			foreach($html->find("a") as $li){
				$url = perfect_url($li->href,$u);
				$enurl = urlencode($url);
				if($url != '' && substr($url,0,4) != "mail" && substr($url,0,4) != "java" && array_key_exists($enurl,$found_urls) == 0){
					$found_urls[$enurl] = 1;
					$spider = new Spider($url);
					$url_status_array[$url]['status']  = $spider->startSpider();
				 	$url_status_array[$url]['link_html'] = "<a target='_blank' href='" . $url . "'>" . $url . "</a>";

				}
			}
		}
		return $url_status_array;
		//ob_end_clean();
	}

	function display_results($url_status_array) {
		$html = '';
		$good_count = 0;
		$bad_count = array();
		foreach ($url_status_array as $url) {
			if ($url['status'] == 200) {
				$html .=  $url['link_html'] . "-------> Status: <span style='color:green; font-weight: bold;'>Working</span><br>";
				$good_count++;
			} else {
				$html .=  $url['link_html'] . "-------> Status: <span style='color:red; font-weight: bold;'>Error</span> " . $url['status'] . "<br>";
				
			}
		}
		echo $html;
		send_mail("Web Crawl Status", $html);
	}

	// add the following to the functions.php file in WP
			// function wpdocs_set_html_mail_content_type() {
			// 	return 'text/html';
			// }
			// add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
	function send_mail($subject, $message ) {
		$from = 'Web Crawler';
		$email = 'webCrawler@testing.local';
		$headers = 'From: '. $from . "\r\n" . 'Reply-To: ' . $email . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$to = 'rick@insightcad.com';
		// $sent = mail($to, $subject, $message, $headers);
		// if($sent) {
		// 	error_log("***************  Email Sent  ********************");
		// } else {
		// 	error_log(error_get_last()['message']);
		// }
		wp_mail( $to, $subject, $message, $headers);
	}