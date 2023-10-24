<?php
class SC_Wordform_FormSubmission {
	public static $sc_wordform_submission_readable_data			=	[];
	public static $sc_wordform_submission_array_data			=   [];
	
	
	/**
	 * Process users form submission data
	 * @since 1.0.0
	 */
	public static function sc_wordform_process_submission_data() {
		self::$sc_wordform_submission_readable_data				= [];
		
		foreach( self::$sc_wordform_submission_array_data as $input_type => $input_data ) {
			switch($input_type) {
				case 'text':
					self::process_text( $input_data );
					break;
				case 'number':
					self::process_number( $input_data );
					break;
				case 'textarea':
					self::process_textarea( $input_data );
					break;
				case 'radio':
					self::process_radio( $input_data );
					break;
				case 'checkbox':
					self::process_checkbox( $input_data );
					break;
				case 'select':
					self::process_select( $input_data );
					break;
					
			} // switch
		} // foreach
		
		return implode( "<br/>", self::$sc_wordform_submission_readable_data );
	}
	
	/**
	 * Process Text data
	 * @since 1.0.0
	 */
	public static function process_text( $input_data ) {
		foreach ( $input_data as $data ) {
			$temp										 	 = '';
			$temp											.= isset( $data['label'] )? '<strong>' . sanitize_text_field( $data['label'] ) . ' : </strong>' : '';
			$temp											.= isset( $data['values'] ) && array_filter($data['values'])? sanitize_text_field( implode(",", $data['values'] ) ) : '';												
			self::$sc_wordform_submission_readable_data[] 	 = $temp;
		}				
	}
	
	/**
	 * Process Number data
	 * @since 1.0.0
	 */
	public static function process_number( $input_data ) {
		foreach ( $input_data as $data ) {
			$temp										 	 = '';
			$temp											.= isset( $data['label'] )? '<strong>' . sanitize_text_field( $data['label'] ) . ' : </strong>' : '';
			$temp											.= isset( $data['values'] ) && array_filter($data['values'])? sanitize_text_field( implode(",", $data['values'] ) ) : '';
			
			self::$sc_wordform_submission_readable_data[] 	 = $temp;
		}				
	}
	
	/**
	 * Process Textarea data
	 * @since 1.0.0
	 */
	public static function process_textarea( $input_data ) {
		foreach ( $input_data as $data ) {
			$temp										 	 = '';
			$temp											.= isset( $data['label'] )? '<strong>' . sanitize_text_field( $data['label'] ) . ' : </strong>' : '';
			$temp											.= isset( $data['values'] ) && array_filter($data['values'])? nl2br( sanitize_textarea_field( implode(",", $data['values'] ) ) ) : '';			
			self::$sc_wordform_submission_readable_data[] 	 = $temp;
		}				
	}
	
	/**
	 * Process Radio data
	 * @since 1.0.0
	 */
	public static function process_radio( $input_data ) {
		foreach ( $input_data as $data ) {
			$temp										 	 = '';
			$temp											.= isset( $data['label'] )? '<strong>' . sanitize_text_field( $data['label'] ) . ' : </strong>' : '';
			$temp											.= isset( $data['values'] ) && array_filter($data['values'])? sanitize_text_field( implode(",", $data['values'] ) ) : '';
			
			self::$sc_wordform_submission_readable_data[] 	 = $temp;
		}				
	}
	
	/**
	 * Process Checkbox data
	 * @since 1.0.0
	 */
	public static function process_checkbox( $input_data ) {
		foreach ( $input_data as $data ) {
			$temp										 	 = '';
			$temp											.= isset( $data['label'] )? '<strong>' . sanitize_text_field( $data['label'] ) . ' : </strong>' : '';
			$temp											.= isset( $data['values'] ) && array_filter($data['values'])? sanitize_text_field( implode(",", $data['values'] ) ) : '';
			
			self::$sc_wordform_submission_readable_data[] 	 = $temp;
		}				
	}
	
	/**
	 * Process Select data
	 * @since 1.0.0
	 */
	public static function process_select( $input_data ) {
		foreach ( $input_data as $data ) {
			$temp										 	 = '';
			$temp											.= isset( $data['label'] )? '<strong>' . sanitize_text_field( $data['label'] ) . ' : </strong>' : '';
			$temp											.= isset( $data['values'] ) && array_filter($data['values'])? sanitize_text_field( implode(",", $data['values'] ) ) : '';
			
			self::$sc_wordform_submission_readable_data[] 	 = $temp;
		}				
	}
	
	
} // class
?>