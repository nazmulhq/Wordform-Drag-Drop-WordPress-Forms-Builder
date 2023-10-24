<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( isset( $validation_messages[0]['validation_messages'] ) ) {
	$validation_messages_data		= json_decode( $validation_messages[0]['validation_messages'], true );
		
	
	// Text Validation Message
	$validation_text_message		= isset( $validation_messages_data['sc-wordform-validation-text-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-text-msg'] ) : 'This field value required.';
	// Number Validation Message
	$validation_number_message		= isset( $validation_messages_data['sc-wordform-validation-number-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-number-msg'] ) : 'This field value required.';
	// Textarea Validation Message
	$validation_textarea_message		= isset( $validation_messages_data['sc-wordform-validation-textarea-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-textarea-msg'] ) : 'This field value required.';
	// Radio Validation Message
	$validation_radio_message		= isset( $validation_messages_data['sc-wordform-validation-radio-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-radio-msg'] ) : 'This field value required.';
	// Checkbox Validation Message
	$validation_checkbox_message		= isset( $validation_messages_data['sc-wordform-validation-checkbox-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-checkbox-msg'] ) : 'This field value required.';
	// Select Validation Message
	$validation_select_message		= isset( $validation_messages_data['sc-wordform-validation-select-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-validation-select-msg'] ) : 'This field value required.';

	// Form Submission Validation Success Message
	$validation_form_submission_success_message		= isset( $validation_messages_data['sc-wordform-form-submission-success-msg'] )? sanitize_text_field( $validation_messages_data['sc-wordform-form-submission-success-msg'] ) : __('We have received your message.', 'wordform-drag-drop-forms-builder' );
		
}

?>
<!-- Input field validation messages  -->
<tr>
	<td colspan="2">
		<h4><span class="dashicons dashicons-marker"></span> <?php _e('Input Field Validation Messages', 'wordform-drag-drop-forms-builder');?></h4>   					
	</td>
</tr>

<tr>
	<td class="sc-wordform-settings-validation-tab-label-td">
		<h4>Text</h4>   					
	</td>
	<td>   	
		<input type="text" value="<?php esc_html_e( $validation_text_message);?>" name="sc-wordform-validation-text-msg" class="sc-wordform-validation-text-msg" />				
	</td>   				
</tr>

<tr>
	<td class="sc-wordform-settings-validation-tab-label-td">
		<h4>Number</h4>   					
	</td>
	<td>   	
		<input type="text" value="<?php esc_html_e( $validation_number_message);?>" name="sc-wordform-validation-number-msg" class="sc-wordform-validation-number-msg" />				
	</td>   				
</tr>

<tr>
	<td class="sc-wordform-settings-validation-tab-label-td">
		<h4>Textarea</h4>   					
	</td>
	<td>   	
		<input type="text" value="<?php esc_html_e( $validation_textarea_message);?>" name="sc-wordform-validation-textarea-msg" class="sc-wordform-validation-textarea-msg" />				
	</td>   				
</tr>

<tr>
	<td class="sc-wordform-settings-validation-tab-label-td">
		<h4>Radio</h4>   					
	</td>
	<td>   	
		<input type="text" value="<?php esc_html_e( $validation_radio_message);?>" name="sc-wordform-validation-radio-msg" class="sc-wordform-validation-radio-msg" />				
	</td>   				
</tr>

<tr>
	<td class="sc-wordform-settings-validation-tab-label-td">
		<h4>Checkbox</h4>   					
	</td>
	<td>   	
		<input type="text" value="<?php esc_html_e( $validation_checkbox_message);?>" name="sc-wordform-validation-checkbox-msg" class="sc-wordform-validation-checkbox-msg" />				
	</td>   				
</tr>

<tr>
	<td class="sc-wordform-settings-validation-tab-label-td">
		<h4>Select</h4>   					
	</td>
	<td>   	
		<input type="text" value="<?php esc_html_e( $validation_select_message);?>" name="sc-wordform-validation-select-msg" class="sc-wordform-validation-select-msg" />				
	</td>   				
</tr>



<tr>
	<td colspan="2">
		<hr/>
	</td>
</tr>


<!-- Form sibmission Messages -->
<tr>
	<td colspan="2">
		<h4><span class="dashicons dashicons-marker"></span> <?php _e('Form submission Messages', 'wordform-drag-drop-forms-builder');?></h4>   					
	</td>
</tr>

<tr>
	<td class="sc-wordform-settings-validation-tab-label-td">
		<h4><?php _e('Form Submission Success', 'wordform-drag-drop-forms-builder');?></h4>   					
	</td>
	<td>   	
		<input type="text" value="<?php esc_html_e( $validation_form_submission_success_message);?>" name="sc-wordform-form-submission-success-msg" class="sc-wordform-form-submission-success-msg" />				
	</td>   				
</tr>
