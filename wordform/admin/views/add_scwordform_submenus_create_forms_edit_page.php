<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data					=	json_decode( $query_results[0]['builtform_html_data'], true );
$builtform				=	isset( $data['builtform_data'] )? wp_kses( $data['builtform_data'], SC_Wordform_Ajaxhandler::sc_wordform_allowed_html_tags() ) : '';
$builtform_options		=	isset( $data['builtform_options_data'] )? wp_kses( $data['builtform_options_data'], SC_Wordform_Ajaxhandler::sc_wordform_allowed_html_tags() ): '';
?>

<div class="wordform-div-wrapper" style="padding: 15px;">	

	  <!-- Drag Input Elements -->      
	  <div class="wordform-draggable-elements-div-wrapper" style="padding: 15px;">
		   <button class="draggable-element wordform-type-singletext button button-primary button-large wordform-drag-elements-button" type="button"><?php _e('Text', 'wordform-drag-drop-forms-builder');?></button>
		   <button class="draggable-element wordform-type-singlenumber button button-primary button-large wordform-drag-elements-button" type="button"><?php _e('Number', 'wordform-drag-drop-forms-builder');?></button>
		   <button class="draggable-element wordform-type-multitext button button-primary button-large wordform-drag-elements-button" type="button"><?php _e('Textarea', 'wordform-drag-drop-forms-builder');?></button>
		   <button class="draggable-element wordform-type-select button button-primary button-large wordform-drag-elements-button" type="button"><?php _e('Select', 'wordform-drag-drop-forms-builder');?></button>
		   <button class="draggable-element wordform-type-checkbox button button-primary button-large wordform-drag-elements-button" type="button"><?php _e('Checkbox', 'wordform-drag-drop-forms-builder');?></button>
		   <button class="draggable-element wordform-type-radio button button-primary button-large wordform-drag-elements-button" type="button"><?php _e('Radio', 'wordform-drag-drop-forms-builder');?></button>
	   </div>
	   <small class="wordform-drag-elements-hints"><?php _e('Drag & drop the element into the DropZone. Rearrange by dragging elements inside DropZone.Click the dropped element to add / update element label / options inside DropZone.', 'wordform-drag-drop-forms-builder');?></small>
	   <hr/>

	   <!-- Actions Update Info -->
	   <p class="wordform-message-info"></p>
   
   
	   <!-- Action Buttons -->
	   <div class="wordform-form-action-buttons-wrapper">
			<button type="button" class="button button-primary button-large wordform-save-btn">
				<span class="dashicons dashicons-saved" style="vertical-align: middle;"></span> <?php _e('Save Form', 'wordform-drag-drop-forms-builder');?>
		   </button>

			<a type="button" class="button button-primary button-large wordform-preview-btn" style="margin-left: 15px;" href="<?php echo esc_url( site_url() . '?sc-wordform-id=' . $wordform_id );?>" target="_blank">
				<span class="dashicons dashicons-welcome-view-site" style="vertical-align: middle;"></span>  <?php _e('Preview & Test', 'wordform-drag-drop-forms-builder');?>
		   </a>      
	   </div>


	<!-- Start Form Builder Zone -->
	<div class="wordform-builder-div-wrapper">
		<?php echo stripslashes($builtform); ?>	
	</div><!-- /.wordform-builder-div-wrapper -->
	<!-- End Form Builder Zone -->

	<!-- Element Field Options - Right -->
	<div class="wordform-builder-form-options-div-wrapper">
		<?php echo stripslashes($builtform_options); ?>
	</div><!-- /.wordform-builder-form-options-div-wrapper -->


</div><!-- ./wordform-div-wrapper -->
