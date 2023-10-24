<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SC_BuildForm {	
	public static $wordform_form_id					= '';
	public static $form_elements					= [];
	public static $built_html_form_elements			= [];
	public static $disable_submit_button			= false;
	public static $sc_wordform_size					= '';      // small | medium | large
	public static $sc_wordform_submit_button_align	= 'left';  // left | center | right
	
		
	public static function sc_wordform_build_form_from_elements( $form_id	=	'') {		
		self::$wordform_form_id				= $form_id;
		// Reset each time before build the each form 		
		self::$built_html_form_elements		= [];
		
		foreach ( self::$form_elements as $key => $element ) {
			switch( $element['type'] ) {
				case 'text':
					self::build_text( $key, $element );
					break;
				case 'number':
					self::build_number( $key, $element );
					break;
				case 'textarea':
					self::build_textarea( $key, $element );
					break;
				case 'radio':
					self::build_radio( $key, $element );
					break;
				case 'checkbox':
					self::build_checkbox( $key, $element );
					break;
				case 'select':
					self::build_select( $key, $element );
					break;
			} // switch
		} // foreach
		
		
		// Include form name - submit button with form tag
		self::include_form_name_with_submit_info();
				
		return self::$built_html_form_elements;
	}
	
	/**
	 * Build Text Field
	 * $param - $element - array
	 */
	public static function build_text( $key = 0, $element = [] ) {
		$name								= 'wordform['.self::$wordform_form_id.'][text]['.$key.'][values][]';
		$label_hidden_name					= 'wordform['.self::$wordform_form_id.'][text]['.$key.'][label]';
		$value_required_status				= 'wordform['.self::$wordform_form_id.'][text]['.$key.'][required]';
		
		if ( isset( $element['label'] ) ) {
			$labelname						=	sanitize_text_field( $element['label'] );
		} 
		else {
			$labelname						=	'';
		}
		
		if ( isset( $element['required'] ) && $element['required'] == 'true' ) {
			$required	 		  			=	' required ';
			$labelname_required   			= $labelname . '<span class="sc-wordform-required-field">*</span> <small class="sc-wordform-error-msg validation-msg-' . esc_attr($key) . '"></small>';
		} 
		else {
			$required	 		 			=	'';
			$labelname_required	 			= $labelname;
		}
				
		$label								= '<div class="sc-wordform-built-text-element-label-wrapper"><label class="sc-wordform-built-text-element-label" >'. $labelname_required .'</label></div>';
		$input								= '<input type="text" class="sc-wordform-built-text-element" name="' . esc_attr($name) . '" '. esc_attr($required) . '/>';		
		$input							   .= '<input type="hidden" name="' . esc_attr($label_hidden_name) . '" value="' . esc_html($labelname) . '" />';
		$input							   .= '<input type="hidden" name="' . esc_attr($value_required_status) . '" value="' . esc_html( $element['required'] ) . '" />';
		$wrapper_with_element				= '<div class="sc-wordform-built-text-wrapper">'. $label . $input . '</div>';
		self::$built_html_form_elements[]	= $wrapper_with_element;
	}
	
	/**
	 * Build Number Field
	 * $param - $element - array
	 */
	public static function build_number( $key = 0, $element = [] ) {
		$name								= 'wordform['.self::$wordform_form_id.'][number]['.$key.'][values][]';
		$label_hidden_name					= 'wordform['.self::$wordform_form_id.'][number]['.$key.'][label]';
		$value_required_status				= 'wordform['.self::$wordform_form_id.'][number]['.$key.'][required]';
		
		if ( isset( $element['label'] ) ) {
			$labelname						= sanitize_text_field( $element['label'] );
		} 
		else {
			$labelname						= '';
		}
		
		if ( isset( $element['required'] ) && $element['required'] == 'true' ) {
			$required						= ' required ';
			$labelname_required   			= $labelname . '<span class="sc-wordform-required-field">*</span> <small class="sc-wordform-error-msg validation-msg-' . esc_attr($key) . '"></small>';
		} 
		else {
			$required						= '';
			$labelname_required	 			= $labelname;
		}
				
		$label								= '<div class="sc-wordform-built-number-element-label-wrapper"><label class="sc-wordform-built-number-element-label" >'. $labelname_required .'</label></div>';
		$input								= '<input type="number" class="sc-wordform-built-number-element" name="' . esc_attr($name) . '" '. esc_attr($required) . '/>';
		$input							   .= '<input type="hidden" name="' . esc_attr($label_hidden_name) . '" value="' . esc_html($labelname) . '" />';
		$input							   .= '<input type="hidden" name="' . esc_attr($value_required_status) . '" value="' . esc_html($element['required']) . '" />';
		$wrapper_with_element				= '<div class="sc-wordform-built-number-wrapper">'. $label . $input . '</div>';
		self::$built_html_form_elements[]	= $wrapper_with_element;
	}
	
	/**
	 * Build Textarea Field
	 * $param - $element - array
	 */
	public static function build_textarea( $key = 0, $element = [] ) {
		$name								= 'wordform['.self::$wordform_form_id.'][textarea]['.$key.'][values][]';
		$label_hidden_name					= 'wordform['.self::$wordform_form_id.'][textarea]['.$key.'][label]';
		$value_required_status				= 'wordform['.self::$wordform_form_id.'][textarea]['.$key.'][required]';
		
		if ( isset( $element['label'] ) ) {
			$labelname						=	sanitize_text_field( $element['label'] );
		} 
		else {
			$labelname						=	'';
		}
		
		if ( isset( $element['required'] ) && $element['required'] == 'true' ) {
			$required						=	' required ';
			$labelname_required   			= $labelname . '<span class="sc-wordform-required-field">*</span> <small class="sc-wordform-error-msg validation-msg-' . esc_attr($key) . '"></small>';
		} 
		else {
			$required						=	'';
			$labelname_required	 			= $labelname;
		}
				
		$label								= '<div class="sc-wordform-built-textarea-element-label-wrapper"><label class="sc-wordform-built-textarea-element-label" >' . $labelname_required .'</label></div>';
		$input								= '<textarea class="sc-wordform-built-textarea-element" name="' . esc_attr($name) . '" ' . esc_attr($required) . '></textarea>';
		$input							   .= '<input type="hidden" name="' . esc_attr($label_hidden_name) . '" value="' . esc_html($labelname) . '" />';
		$input							   .= '<input type="hidden" name="' . esc_attr($value_required_status) . '" value="' . esc_html($element['required']) . '" />';
		$wrapper_with_element				= '<div class="sc-wordform-built-textarea-wrapper">'. $label . $input . '</div>';
		self::$built_html_form_elements[]	= $wrapper_with_element;
	}
	
	/**
	 * Build Radio Field
	 * $param - $element - array
	 */
	public static function build_radio( $key = 0, $element = [] ) {
		$name									= 'wordform['.self::$wordform_form_id.'][radio]['.$key.'][values][]';
		$label_hidden_name						= 'wordform['.self::$wordform_form_id.'][radio]['.$key.'][label]';
		$value_required_status					= 'wordform['.self::$wordform_form_id.'][radio]['.$key.'][required]';
		
		if ( isset( $element['label'] ) ) {
			$labelname							=	sanitize_text_field( $element['label'] );
		} 
		else {
			$labelname							=	'';
		}
		
		if ( isset( $element['required'] ) && $element['required'] == 'true' ) {
			$required						=	' required ';
			$labelname_required   			=   $labelname . '<span class="sc-wordform-required-field">*</span> <small class="sc-wordform-error-msg validation-msg-' . esc_attr($key) . '"></small>';
		} 
		else {
			$required						=	'';
			$labelname_required	 			=  $labelname;
		}
		
		if ( isset( $element['multiOption'] ) && is_array( $element['multiOption'] ) && array_filter( $element['multiOption'] ) ) {			
			$multi_options					 = '';
			foreach ( $element['multiOption'] as $option ) {
				$options					 = '';
				$checked					 = $option['checkStatus'] == 'true'? ' checked ' : '';	
				
				$options					.= '<div class="sc-wordform-built-radio-element-option-wrapper">';	
				$options					.= '<input type="radio" class="sc-wordform-built-radio-element" name="' . esc_attr($name) . '" value="' . sanitize_text_field( $option['optionText'] ) . '"' . esc_attr($checked) . '/>';
				$options					.= '<label class="sc-wordform-built-radio-element-option-text-label">' . sanitize_text_field( $option['optionText'] ) . '</label>';
				$options					.= '</div>';	
				
				$multi_options				.= $options;
			} // foreach
		}
				
		$label								= '<div class="sc-wordform-built-radio-element-label-wrapper"><label class="sc-wordform-built-radio-element-label" >'. $labelname_required .'</label></div>';
		$input								= $multi_options;
		$input							   .= '<input type="hidden" name="' . esc_attr($label_hidden_name) . '" value="' . esc_html($labelname) . '" />';
		$input							   .= '<input type="hidden" name="' . esc_attr($value_required_status) . '" value="' . esc_html($element['required']) . '" />';
		$wrapper_with_element				= '<div class="sc-wordform-built-radio-wrapper">'. $label . $input . '</div>';
		self::$built_html_form_elements[]	= $wrapper_with_element;
	}
	
	/**
	 * Build Checkbox Field
	 * $param - $element - array
	 */
	public static function build_checkbox( $key = 0, $element = [] ) {
		$name									= 'wordform['.self::$wordform_form_id.'][checkbox]['.$key.'][values][]';
		$label_hidden_name						= 'wordform['.self::$wordform_form_id.'][checkbox]['.$key.'][label]';
		$value_required_status					= 'wordform['.self::$wordform_form_id.'][checkbox]['.$key.'][required]';
		
		if ( isset( $element['label'] ) ) {
			$labelname							=	sanitize_text_field( $element['label'] );
		} 
		else {
			$labelname							=	'';
		}
		
		if ( isset( $element['required'] ) && $element['required'] == 'true' ) {
			$required						=	' required ';
			$labelname_required   			=   $labelname . '<span class="sc-wordform-required-field">*</span> <small class="sc-wordform-error-msg validation-msg-' . esc_attr($key) . '"></small>';
		} 
		else {
			$required						=	'';
			$labelname_required	 			=  $labelname;
		}
		
		if ( isset( $element['multiOption'] ) && is_array( $element['multiOption'] ) && array_filter( $element['multiOption'] ) ) {			
			$multi_options					 = '';
			foreach ( $element['multiOption'] as $option ) {				
				$options					 = '';				
				$checked					 = $option['checkStatus'] == 'true'? ' checked ' : '';	
				
				$options					.= '<div class="sc-wordform-built-checkbox-element-option-wrapper">';	
				$options					.= '<input type="checkbox" class="sc-wordform-built-checkbox-element" name="'. esc_attr($name) . '" value="' . sanitize_text_field( $option['optionText'] ) . '"' . esc_attr($checked) . '/>';
				$options					.= '<label class="sc-wordform-built-checkbox-element-option-text-label">' . sanitize_text_field( $option['optionText'] ) . '</label>';
				$options					.= '</div>';	
				
				$multi_options				.= $options;
			}
		}
				
		$label								= '<div class="sc-wordform-built-checkbox-element-label-wrapper"><label class="sc-wordform-built-checkbox-element-label" >'. $labelname_required .'</label></div>';
		$input								= $multi_options;
		$input							   .= '<input type="hidden" name="' . esc_attr($label_hidden_name) . '" value="' . esc_html($labelname) . '" />';
		$input							   .= '<input type="hidden" name="' . esc_attr($value_required_status) . '" value="' . esc_html($element['required']) . '" />';
		$wrapper_with_element				= '<div class="sc-wordform-built-checkbox-wrapper">'. $label . $input . '</div>';
		self::$built_html_form_elements[]	= $wrapper_with_element;
	}

	/**
	 * Build Select Field
	 * $param - $element - array
	 */
	public static function build_select( $key = 0, $element = [] ) {
		$name								= 'wordform['.self::$wordform_form_id.'][select]['.$key.'][values][]';
		$label_hidden_name					= 'wordform['.self::$wordform_form_id.'][select]['.$key.'][label]';
		$value_required_status				= 'wordform['.self::$wordform_form_id.'][select]['.$key.'][required]';
		
		if ( isset( $element['label'] ) ) {
			$labelname						=	sanitize_text_field( $element['label'] );
		} 
		else {
			$labelname						=	'';
		}
		
		if ( isset( $element['required'] ) && $element['required'] == 'true' ) {
			$required						=	' required ';
			$labelname_required   			=   $labelname . '<span class="sc-wordform-required-field">*</span> <small class="sc-wordform-error-msg validation-msg-' . esc_attr($key) . '"></small>';
		} 
		else {
			$required						=	'';
			$labelname_required	 			=  $labelname;
		}
		
		if ( isset( $element['multiOption'] ) && is_array( $element['multiOption'] ) && array_filter( $element['multiOption'] ) ) {			
			$multi_options					 = '';
			foreach ( $element['multiOption'] as $option ) {				
				$options					 = '';				
				$checked					 = $option['checkStatus'] == 'true'? ' selected ' : '';	
												
				$options					.= '<option value="' . sanitize_text_field( $option['optionText'] ) . '" ' . esc_attr($checked) . '>' . sanitize_text_field( $option['optionText'] ) . '</option>';				
				
				$multi_options				.= $options;
			}
		}
				
		$label								= '<div class="sc-wordform-built-select-element-label-wrapper"><label class="sc-wordform-built-select-element-label" >'. $labelname_required .'</label></div>';
		$input								= '';
		$input							   .= '<div class="sc-wordform-built-select-element-wrapper">';
		$input							   .= '<select class="sc-wordform-built-select-element" name="'. esc_attr($name) . '" ' . esc_attr($required) . '>';
		$input							   .= $multi_options;
		$input							   .= '</select>';	
		$input							   .= '<input type="hidden" name="' . esc_attr($label_hidden_name) . '" value="' . esc_html($labelname) . '" />';
		$input							   .= '<input type="hidden" name="' . esc_attr($value_required_status) . '" value="' . esc_html($element['required']) . '" />';
		$input							   .= '</div>';		
		
		$wrapper_with_element				= '<div class="sc-wordform-built-select-wrapper">'. $label . $input . '</div>';
		self::$built_html_form_elements[]	= $wrapper_with_element;
	}
	
	
	/**
	 * Include form name
	 * Include Submit button
	 */
	public static function include_form_name_with_submit_info() {
		foreach ( self::$form_elements as $key => $element ) {
			
			// Include form name
			if ( $element['type'] == 'form-name' ) {
				$sc_wordform_form_name					= sanitize_text_field( $element['label'] );
				if ( $element['hide'] == "false" ) {
					$wrapper_with_element				= '<div class="sc-wordform-built-form-formname-label-wrapper"><h4>'. esc_html($element['label']) .'</h4></div>';
					array_unshift( self::$built_html_form_elements, $wrapper_with_element );					
				}
			}
			
			// Include submit button
			if ( $element['type'] == 'submit-button' ) {			
				    // Get submit button settings : General tab info
				    $styles								= self::sc_wordform_submit_button_customize();	
				
				    if ( self::$disable_submit_button ) {
						$submit_button					= '<button style="'. esc_attr( $styles['styles'] ) . '" onMouseOver="' . esc_attr( $styles['mouseover'] ) .'" onMouseOut="' . esc_attr( $styles['mouseout'] ) . '" class="button button-primary">' . esc_html( $element['label'] ) . '</button>';	
					}
					else {
						$submit_button					= '<button style="'. esc_attr( $styles['styles'] ) . '" onMouseOver="' . esc_attr( $styles['mouseover'] ) . '"  onMouseOut="' . esc_attr( $styles['mouseout'] ) . '" type="submit" class="button button-primary">' . esc_html( $element['label'] ) . '</button>';	
					}
				    
					$wrapper_with_element				= '<div class="sc-wordform-built-form-submit-button-wrapper ' . esc_attr( self::$sc_wordform_submit_button_align ) . '">' . $submit_button . '</div>';
					self::$built_html_form_elements[]	= $wrapper_with_element;
			}
									
		} // foreach
		
		// Include <form> tag
		$formtag_start									= '<div class="sc-wordform-built-form-frontend-outermost-div-wrapper"><form enctype="multipart/form-data" method="post" class="sc-wordform-built-created-form-frontend-display-form ' . esc_attr( self::$sc_wordform_size ) . '" id="'. esc_attr( self::$wordform_form_id ) . '" data-scwordform-form-name="'. esc_attr(  $sc_wordform_form_name ) .'" >';
		$formtag_end									= '</form></div>';
		// Insert at first
		array_unshift( self::$built_html_form_elements, $formtag_start );
		// Insert at end
		self::$built_html_form_elements[]				= $formtag_end;		
	}
	
	/**
	 * Setting : General Tab submit button customized settings info
	 * Check & Process data
	 * Build style attributes
	 */
	public static function sc_wordform_submit_button_customize() {
		$styleinfo							= [];
		$general_tab_options				= SC_Wordform::sc_wordform_get_settings_general_tab_info();
		$style_attributes					= [];
		//if ( isset( $general_tab_options['submit_button_background_color'] ) ) {
		$style_attributes[]				    = 'background: '   . sanitize_text_field( $general_tab_options['submit_button_background_color'] );
		$style_attributes[]				    = 'border-color: ' . sanitize_text_field( $general_tab_options['submit_button_background_color'] );
		$style_attributes[]				    = 'font-size: '    . sanitize_text_field( $general_tab_options['submit_button_font_size'] ) . 'px';
		$style_attributes[]				    = 'font-weight: '  . sanitize_text_field( $general_tab_options['submit_button_font_weight'] );
		$style_attributes[]				    = 'color: '        . sanitize_text_field( $general_tab_options['submit_button_font_color'] );
		$style_attributes[]				    = 'padding: '      . sanitize_text_field( $general_tab_options['submit_button_padding_top_bottom'] ) . 'px' . ' ' . sanitize_text_field( $general_tab_options['submit_button_padding_left_right'] ) . 'px';
		//}
		
		// Style attributes
		if ( array_filter( $style_attributes ) ) {
			$styleinfo['styles'] 			= implode(';', $style_attributes );
		}
		else {
			$styleinfo['styles'] 			= '';
		}
		
		// Hover - On Mouse Over				
		$mouseover							= [];
		$mouseover[]						= 'background: '   . sanitize_text_field( $general_tab_options['submit_button_background_hover_color'] );
		$mouseover[]						= 'border-color: ' . sanitize_text_field( $general_tab_options['submit_button_background_hover_color'] );
		$mouseover[]						= 'font-size: '    . sanitize_text_field( $general_tab_options['submit_button_font_size'] ) . 'px';
		$mouseover[]						= 'font-weight: '  . sanitize_text_field( $general_tab_options['submit_button_font_weight'] );
		$mouseover[]						= 'color: '        . sanitize_text_field( $general_tab_options['submit_button_font_color'] );
		$mouseover[]						= 'color: '        . sanitize_text_field( $general_tab_options['submit_button_font_color'] );
		$mouseover[]                        = 'padding: '      . sanitize_text_field( $general_tab_options['submit_button_padding_top_bottom'] ) . 'px' . ' ' . sanitize_text_field( $general_tab_options['submit_button_padding_left_right'] ) . 'px';
		
		// On Mouse Over
		$styleinfo['mouseover']				= "this.style.cssText='" . implode(';', $mouseover ) . "'";
				
		// Reset to default - On Mouse Out
		$styleinfo['mouseout']				= "this.style.cssText='" . implode(';', $style_attributes ) . "'";				
		
		return $styleinfo;
	}
		
} // class
?>