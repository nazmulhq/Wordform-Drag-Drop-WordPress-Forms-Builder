<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( isset( $wordform_id ) && ! empty( $wordform_id ) ) {
	// Edit Created Forms
	//print_r($query_results );
	include_once SCWORDFORM_PLUGIN_DIR . 'admin/views/add_scwordform_submenus_create_forms_edit_page.php';		
}
else {
	// Create New Forms
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

					<a type="button" class="button button-primary button-large wordform-preview-btn disabled" style="margin-left: 15px;" href="#" target="_blank">
						<span class="dashicons dashicons-welcome-view-site" style="vertical-align: middle;"></span> <?php _e('Preview & Test', 'wordform-drag-drop-forms-builder');?>
				   </a>  				   				           
			   </div>


			<!-- Start Form Builder Zone -->
			<div class="wordform-builder-div-wrapper">
				
				<!-- Element: FORM Name -->
				<div id="wordformElement-1" class="wordform-input-element-div-wrapper wordform-input-element-div-wrapper-form-name wordform-create-form-name-label-title" data-input-element-type="form-name" data-element-index="1" >
					<span><?php _e('New Form', 'wordform-drag-drop-forms-builder');?></span>
				</div>
								
				<!-- Elements DropZone -->
				<span class="sc-wordform-dropzone-watermark-text"><?php _e('DROPZONE', 'wordform-drag-drop-forms-builder');?></span>
				<div id="wordform-sortable" class="wordform-dropzone-div-wrapper"></div>

				<!--Element: Submit Button  -->
				<div id="wordformElement-2" class="wordform-input-element-div-wrapper wordform-input-element-div-wrapper-submit-button" data-input-element-type="submit-button" data-element-index="2">
					<button class="button button-primary button-large wordform-admin-create-form-submit-button"><?php _e('Submit', 'wordform-drag-drop-forms-builder');?></button>
				</div>

		
				<input type="hidden" class="wordform-form-builder-element-number" value="2" />
				<input type="hidden" class="wordform-form-saved" value="false" />
				<input type="hidden" class="wordform-created-form-id" value="" />    
		
			</div><!-- /.wordform-builder-div-wrapper -->
			<!-- End Form Builder Zone -->

			<!-- Element Field Options - Right Sidebar -->
			<div class="wordform-builder-form-options-div-wrapper">
				<?php include_once(  SCWORDFORM_PLUGIN_INC . 'elements/form-name.php');?>
				<?php include_once(  SCWORDFORM_PLUGIN_INC . 'elements/submit-button.php');?>
			</div><!-- /.wordform-builder-form-options-div-wrapper -->


		</div><!-- ./wordform-div-wrapper -->
<?php
	}
?>


<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		$(window).on('beforeunload', function(e) {			 
			 let scWordFormElementDropped  = $('.wordform-dropzone-div-wrapper').children().length;
			 let scWordFormID	= $('.wordform-created-form-id').val();
			 console.log(scWordFormID.length );
			 // Changes but not saved
			 if ( $.trim( scWordFormID ).length == 0 && scWordFormElementDropped > 0 ) {
				 return 'Please click Save Form button to save your form.';	 
			 }
			 else if ( $.trim( scWordFormID ).length > 0 ) {				
				return '';	 
			 }            
			
			return '';
        });
	});
</script>