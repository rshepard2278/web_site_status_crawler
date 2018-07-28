<?php
add_action( 'admin_menu', 'icad__add_admin_menu' );
add_action( 'admin_init', 'icad__settings_init' );


function icad__add_admin_menu(  ) { 

	add_menu_page( 'Web Status', 'Web Status', 'manage_options', 'web_status', 'icad__options_page' );

}


function icad__settings_init(  ) { 

	register_setting( 'pluginPage', 'icad__settings' );

	add_settings_section(
		'icad__pluginPage_site_settings_section', 
		__( 'Site Settings', 'icad_text_domain' ), 
		'icad__site_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'icad__text_field_0', 
		__( 'Site to add:', 'icad_text_domain' ), 
		'icad__text_field_0_render', 
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
		'icad__text_field_1', 
		__( 'Email to add:', 'icad_text_domain' ), 
		'icad__text_field_1_render', 
		'pluginPage', 
		'icad__pluginPage_report_settings_section' 
	);


}


function icad__text_field_0_render(  ) { 

	$options = get_option( 'icad__settings' );
	?>
	<input type='text' name='icad__settings[icad__text_field_0]' value='<?php echo $options['icad__text_field_0']; ?>'>
	<?php

}


function icad__text_field_1_render(  ) { 

	$options = get_option( 'icad__settings' );
	?>
	<input type='text' name='icad__settings[icad__text_field_1]' value='<?php echo $options['icad__text_field_1']; ?>'>
	<?php

}


function icad__site_settings_section_callback(  ) { 

	echo __( 'Sites to check:', 'icad_text_domain' );
	//Todo get list of sites

}

function icad__report_settings_section_callback(  ) { 

	echo __( 'Sending reports to following addresses:', 'icad_text_domain' );
	//Todo get list of emails
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

?>