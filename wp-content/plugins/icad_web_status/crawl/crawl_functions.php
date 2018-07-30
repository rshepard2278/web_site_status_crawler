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
		$crawled_urls = array();
		$found_urls = array();
		$uen = urlencode($u);
		$url_status_array = array();

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
				$bad_count++;
			}
		}
		echo $html;
		send_mail("Web Crawl Status", $html, $email);
	}

	function get_status_report($url_status_array) {
		$html = '';
		$good_count = 0;
		$down_count = 0;
		$pages_down = array();
		foreach ($url_status_array as $url) {
			if ($url['status'] == 200) {
				$good_count++;
			} else {
				$down_count++;
				array_push($pages_down, $url);
			}
		}
		var_dump($pages_down);
		$total_checked = $down_count + $good_count;
		$up_percetage = round((($good_count / $total_checked) * 100),2);
		$up_percetage = $up_percetage . "%";
		$report = array(
			"up_percetage" => $up_percetage,
			"pages_checked" => $total_checked,
			"pages_down"    => $pages_down
		);
		return $report;
	}

	function check_site_status($url) {
		$spider = new Spider($url);
		$status  = $spider->startSpider();
		if($status == 200) {
			return true;
		} else {
			error_log("Site root url status !200");
			return false;
		}
	}

	// add the following to the functions.php file in WP
			// function wpdocs_set_html_mail_content_type() {
			// 	return 'text/html';
			// }
			// add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
	function send_mail($subject, $message, $email ) {
		echo "Sending mail to: " . $email;
		$from = 'Web Crawler';
		$from = 'webCrawler@testing.local';
		$headers = 'From: '. $from . "\r\n" . 'Reply-To: ' . $from . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		// $sent = mail($to, $subject, $message, $headers);
		// if($sent) {
		// 	error_log("***************  Email Sent  ********************");
		// } else {
		// 	error_log(error_get_last()['message']);
		// }
		if(wp_mail($email, $subject, $message, $headers)) {
			echo "Mail Sent";
		} else {
			echo "Email not sent";
		}
	}