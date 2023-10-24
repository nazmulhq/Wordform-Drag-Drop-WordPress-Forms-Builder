<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SC_Wordform_Autoloader {
	
	public function __construct() {		
		new SC_Wordform();
		new SC_Wordform_Ajaxhandler();
		new SC_BuildForm();
		new SC_Wordform_Shortcode();
		new SC_Wordform_FormValidation();
		new SC_Wordform_FormSubmission();
		
	}
}
?>