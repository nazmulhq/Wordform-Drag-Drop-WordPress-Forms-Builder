<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SC_Wordform_FormValidation {	
	public static $wordform_id					= null;
	public static $sc_wordform_validation_data	= [];
	
	// Fallback default validation messages
	public static $validation_text_message						= 'This field value required.';
	public static $validation_number_message					= 'This field value required.';
	public static $validation_textarea_message					= 'This field value required.';
	public static $validation_radio_message						= 'This field value required.';
	public static $validation_checkbox_message					= 'This field value required.';
	public static $validation_select_message					= 'This field value required.';
	public static $validation_form_submission_success_message 	= 'We have received your message.';
	
	
	/**
	 * Fetch & set validation messages 
	 */
	public static function sc_wordform_set_validation_messages() {
				
		// check if set validation messages
		if ( isset( self::$wordform_id ) && self::$wordform_id ) {						
			$validation_messages    						= SC_Wordform::sc_wordform_get_validation_messages_data_by_formid( self::$wordform_id );
			if ( isset( $validation_messages[0]['validation_messages'] ) ) {
				$validation_messages_data					= json_decode( $validation_messages[0]['validation_messages'], true );

				// Text Validation Message
				self::$validation_text_message				= isset( $validation_messages_data['sc-wordform-validation-text-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-text-msg'] ) : 'This field value required.';
				// Number Validation Message
				self::$validation_number_message			= isset( $validation_messages_data['sc-wordform-validation-number-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-number-msg'] ) : 'This field value required.';
				// Textarea Validation Message
				self::$validation_textarea_message			= isset( $validation_messages_data['sc-wordform-validation-textarea-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-textarea-msg'] ) : 'This field value required.';
				// Radio Validation Message
				self::$validation_radio_message				= isset( $validation_messages_data['sc-wordform-validation-radio-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-radio-msg'] ) : 'This field value required.';
				// Checkbox Validation Message
				self::$validation_checkbox_message			= isset( $validation_messages_data['sc-wordform-validation-checkbox-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-checkbox-msg'] ) : 'This field value required.';
				// Select Validation Message
				self::$validation_select_message			= isset( $validation_messages_data['sc-wordform-validation-select-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-select-msg'] ) : 'This field value required.';

				// Form Submission Validation Success Message
				self::$validation_form_submission_success_message		= isset( $validation_messages_data['sc-wordform-form-submission-success-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-form-submission-success-msg'] ) : 'We have received your message.';			
			}			
		}		
			
		
	}
	/**
	 * Text validation
	 */
	public static function text_validation( $data = [] ) {		
		if ( array_filter( $data ) ) {			
			foreach ( $data as $key => $input_data ) {
				$temp										= [];
				$temp['inputType']							= 'Text';
				
				$temp['validationMsgClass']					= 'validation-msg-' . $key;
				$temp['label']								= $input_data['label'];
				if ( $input_data['required'] == 'true'  ) {
					$temp['required']						= true;
					$temp['validationMsg']					= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? self::$validation_text_message : '';
					$temp['validationStatus']				= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? 'error' : 'success';
				}
				else {
					$temp['inputType']						= 'Text';
					$temp['required']						= false;
					$temp['validationMsg']					= '';
					$temp['validationStatus']				= 'success';
				}
				
				self::$sc_wordform_validation_data[]  		= $temp;
			} // foreach
		}	
		else {
			    $temp['inputType']							= 'Text';
				$temp['validationMsgClass']					= 'validation-msg-' . $key;								
				$temp['validationMsg']						= 'Input element data missing';
				$temp['validationStatus']					= 'error';
			 
			   self::$sc_wordform_validation_data[]  	= $temp;
		}				
	}
	
    /**
	 * Number validation
	 */
	public static function number_validation( $data = [] ) {		
		if ( array_filter( $data ) ) {			
			foreach ( $data as $key => $input_data ) {
				$temp										= [];
				$temp['inputType']							= 'Number';
				
				$temp['validationMsgClass']					= 'validation-msg-' . $key;
				$temp['label']								= $input_data['label'];
				if ( $input_data['required'] == 'true' ) {
					$temp['required']						= true;
					$temp['validationMsg']					= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? self::$validation_number_message : '';
					$temp['validationStatus']				= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? 'error' : 'success';
				}
				else {
					$temp['inputType']						= 'Number';
					$temp['required']						= false;
					$temp['validationMsg']					= '';
					$temp['validationStatus']				= 'success';
				}
				
				self::$sc_wordform_validation_data[]  		= $temp;
			} // foreach
		}	
		else {
			    $temp['inputType']							= 'Number';
				$temp['validationMsgClass']					= 'validation-msg-' . $key;								
				$temp['validationMsg']						= 'Input element data missing';
				$temp['validationStatus']					= 'error';
			 
			   self::$sc_wordform_validation_data[]  		= $temp;
		}				
	}
	
    /**
	 * Textarea validation
	 */
	public static function textarea_validation( $data = [] ) {		
		if ( array_filter( $data ) ) {			
			foreach ( $data as $key => $input_data ) {
				$temp										= [];
				$temp['inputType']							= 'Textarea';
				
				$temp['validationMsgClass']					= 'validation-msg-' . $key;
				$temp['label']								= $input_data['label'];
				if ( $input_data['required'] == 'true' ) {
					$temp['required']						= true;
					$temp['validationMsg']					= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? self::$validation_textarea_message : '';
					$temp['validationStatus']				= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? 'error' : 'success';
				}
				else {
					$temp['inputType']						= 'Textarea';
					$temp['required']						= false;
					$temp['validationMsg']					= '';
					$temp['validationStatus']				= 'success';
				}
				
				self::$sc_wordform_validation_data[]  		= $temp;
			} // foreach
		}	
		else {
			    $temp['inputType']							= 'Textarea';
				$temp['validationMsgClass']					= 'validation-msg-' . $key;								
				$temp['validationMsg']						= 'Input element data missing';
				$temp['validationStatus']					= 'error';
			 
			   self::$sc_wordform_validation_data[]  		= $temp;
		}				
	}
	
    /**
	 * Radio validation
	 */
	public static function radio_validation( $data = [] ) {		
		if ( array_filter( $data ) ) {			
			foreach ( $data as $key => $input_data ) {
				$temp										= [];
				$temp['inputType']							= 'Radio';
				
				$temp['validationMsgClass']					= 'validation-msg-' . $key;
				$temp['label']								= $input_data['label'];
				if ( $input_data['required'] == 'true' ) {
					$temp['required']						= true;
					$temp['validationMsg']					= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? self::$validation_radio_message : '';
					$temp['validationStatus']				= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? 'error' : 'success';
				}
				else {
					$temp['inputType']						= 'Radio';
					$temp['required']						= false;
					$temp['validationMsg']					= '';
					$temp['validationStatus']				= 'success';
				}
				
				self::$sc_wordform_validation_data[]  		= $temp;
			} // foreach
		}	
		else {
			    $temp['inputType']							= 'Radio';
				$temp['validationMsgClass']					= 'validation-msg-' . $key;								
				$temp['validationMsg']						= 'Input element data missing';
				$temp['validationStatus']					= 'error';
			 
			   self::$sc_wordform_validation_data[]  		= $temp;
		}				
	}
	
    /**
	 * Checkbox validation
	 */
	public static function checkbox_validation( $data = [] ) {		
		if ( array_filter( $data ) ) {			
			foreach ( $data as $key => $input_data ) {
				$temp										= [];
				$temp['inputType']							= 'Checkbox';
				
				$temp['validationMsgClass']					= 'validation-msg-' . $key;
				$temp['label']								= $input_data['label'];
				if ( $input_data['required'] == 'true' ) {
					$temp['required']						= true;
					$temp['validationMsg']					= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? self::$validation_checkbox_message : '';
					$temp['validationStatus']				= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? 'error' : 'success';
				}
				else {
					$temp['inputType']						= 'Checkbox';
					$temp['required']						= false;
					$temp['validationMsg']					= '';
					$temp['validationStatus']				= 'success';
				}
				
				self::$sc_wordform_validation_data[]  		= $temp;
			} // foreach
		}	
		else {
			    $temp['inputType']							= 'Checkbox';
				$temp['validationMsgClass']					= 'validation-msg-' . $key;								
				$temp['validationMsg']						= 'Input element data missing';
				$temp['validationStatus']					= 'error';
			 
			   self::$sc_wordform_validation_data[]  		= $temp;
		}				
	}
	
    /**
	 * Select validation
	 */
	public static function select_validation( $data = [] ) {		
		if ( array_filter( $data ) ) {			
			foreach ( $data as $key => $input_data ) {
				$temp										= [];
				$temp['inputType']							= 'Select';
				
				$temp['validationMsgClass']					= 'validation-msg-' . $key;
				$temp['label']								= $input_data['label'];
				if ( $input_data['required'] == 'true' ) {
					$temp['required']						= true;
					$temp['validationMsg']					= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? self::$validation_select_message : '';
					$temp['validationStatus']				= ! isset( $input_data['values'] ) || ! array_filter( $input_data['values'] )? 'error' : 'success';
				}
				else {
					$temp['inputType']						= 'Select';
					$temp['required']						= false;
					$temp['validationMsg']					= '';
					$temp['validationStatus']				= 'success';
				}
				
				self::$sc_wordform_validation_data[]  		= $temp;
			} // foreach
		}	
		else {
			    $temp['inputType']							= 'Select';
				$temp['validationMsgClass']					= 'validation-msg-' . $key;								
				$temp['validationMsg']						= 'Input element data missing';
				$temp['validationStatus']					= 'error';
			 
			   self::$sc_wordform_validation_data[]  		= $temp;
		}				
	}
	
	
} // class