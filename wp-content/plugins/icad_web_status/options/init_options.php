<?php
add_action( 'admin_menu', 'icad__add_admin_menu' );
add_action( 'admin_init', 'icad__settings_init' );


function icad__add_admin_menu(  ) { 

	add_menu_page( 'Web Status', 'Web Status', 'manage_options', 'web_status', 'icad__options_page' );

}


function icad__settings_init(  ) { 

	register_setting( 'pluginPage', 'icad__settings', array('sanitize_callback' => 'icad_sanitize_inputs') );

	add_settings_section(
		'icad__pluginPage_site_settings_section', 
		__( 'Site Settings', 'icad_text_domain' ), 
		'icad__site_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'icad_site_list', 
		__( 'Site to add:', 'icad_text_domain' ), 
		'icad_site_section', 
		'pluginPage', 
		'icad__pluginPage_site_settings_section' 
	);

	add_settings_section(
		'icad__pluginPage_report_settings_section', 
		__( 'Report Settings', 'icad_text_domain' ), 
		'icad__report_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'icad_email_list', 
		__( 'Email to add:', 'icad_text_domain' ), 
		'icad_report_section', 
		'pluginPage', 
		'icad__pluginPage_report_settings_section' 
	);
}



function icad_site_section() { 

	$options = get_option( 'icad__settings' );
	?>
	<input type='url' id='icad_site_list' name='icad_site_list' value=''>
	<?php

}


function icad_report_section() { 

	$options = get_option( 'icad__settings' );
	?>
	<input type='email'  id='icad_email_list' name='icad_email_list' value=''>
	<?php

}


function icad__site_settings_section_callback(  ) { 
	$options = get_option( 'icad__settings' );
	echo __( 'Sites to check:', 'icad_text_domain' );
	
	if(isset($options['icad_site_list']) && is_array($options['icad_site_list'])) {
		$html = '<ul class="list site-list">';
		foreach($options['icad_site_list'] as $site) {
			$html .= '<li class="item site-item">' . $site . '</li>';
		}
		$html .= '</ul>';
		echo $html;
	}
}

function icad__report_settings_section_callback(  ) { 
	$options = get_option( 'icad__settings' );
	//var_dump($options);
	echo __( 'Sending reports to following addresses:', 'icad_text_domain' );
	if(isset($options['icad_email_list']) && is_array($options['icad_email_list'])) {
		$html = '<ul class="list email-list">';
		foreach($options['icad_email_list'] as $email) {
			$html .= '<li class="item email-item">' . $email . '</li>';
		}
		$html .= '</ul>';
		echo $html;
	}
}


function icad__options_page(  ) { 
	?>
	<form action='options.php' method='post'>

		<h2>Web Status Settings</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php
}

function icad_sanitize_inputs($input) {
	$options = get_option( 'icad__settings' );
	$site_list = array();
	if(isset($options['icad_site_list']) && is_array($options['icad_site_list'])) {
		$site_list = $options['icad_site_list'];
	} else {
		$options['icad_site_list'] = array();
	}
	
	if(isset($_POST['icad_site_list']) && $_POST['icad_site_list'] != '') {
		$url = esc_url($_POST['icad_site_list'], array('http', 'https'));
		if(!in_array($url, $site_list)) {
			array_push($site_list, $url);
			$options['icad_site_list'] = $site_list;
		} else {
			error_log("Sanitize inputs: URL already in list");
		}
	}


	$email_list = array();
	if(isset($options['icad_email_list']) && is_array($options['icad_email_list'])) {
		$email_li = $options['icad_email_list'];
	} else {
		$options['icad_email_list'] = array();
	}

	if(isset($_POST['icad_email_list']) && $_POST['icad_email_list'] != '') {
		$email = sanitize_email($_POST['icad_email_list']);
		if(!in_array($email, $email_list)) {
			array_push($email_list, $email);
			$options['icad_email_list'] = $email_list;
		} else {
			error_log("Sanitize inputs: Email already in list");
		}
	}
	//var_error_log($options);
	return $options;
}

function var_error_log( $object=null ){
    ob_start();                    // start buffer capture
    var_dump( $object );           // dump the values
    $contents = ob_get_contents(); // put the buffer into a variable
    ob_end_clean();                // end capture
    error_log( $contents );        // log contents of the result of var_dump( $object )
}

?>