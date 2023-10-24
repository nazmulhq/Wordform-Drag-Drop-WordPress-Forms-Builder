<?php
class SC_Wordform_Shortcode {
	
	public static $initiated = false;
	
	public function __construct() {
		if ( ! self::$initiated ) {
			self::initiate_hooks();
		}								
	}
	
	/**
	 * Init hook
	 */
	public static function initiate_hooks() {	
	    add_action( 'init', [ __CLASS__, 'sc_wordform_add_shortcode'] );	
		
		self::$initiated = true;
	}
	
	/**
	 * Add shortcode
	 */
	public static function sc_wordform_add_shortcode() {
		add_shortcode('scwordform', [ __CLASS__, 'sc_wordform_shortcode_built_form_callback' ] );		
	}
	
	/**
	 *  Create form
	 *  [scwordform sc-wordform-id="wordform-13"]
	 *  return html form data
	 */
	public static function sc_wordform_shortcode_built_form_callback( $atts, $content, $tag ) {		
		$form_id								= isset( $atts['sc-wordform-id'] ) && ! empty( $atts['sc-wordform-id'])? sanitize_text_field( $atts['sc-wordform-id'] ) : '';				
		$built_form_html						= '';
		if ( $form_id && ! empty( $form_id ) ) {
			$results							= SC_Wordform::sc_wordform_db_query( $form_id );	
			if ( $results && array_filter( $results ) && isset( $results[0]['form'] ) ) {
				SC_BuildForm::$form_elements	= json_decode( $results[0]['form'], true );
				$created_form					= SC_BuildForm::sc_wordform_build_form_from_elements( $form_id );									
				foreach( $created_form as $form_element ) {
					$built_form_html			.= $form_element;
				}
			}
		}
        		
		return $built_form_html;
	}
	
} // class
