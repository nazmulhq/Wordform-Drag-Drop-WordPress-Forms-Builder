<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap sc-wordform-setting-page-validation-tab-table-wrapper">

<form id="scWordformSettingsValidationTabForm" method="post">
	<table style="border-spacing: 15px;">
		<tbody>		    		    
				<tr>
					<td class="sc-wordform-settings-validation-tab-label-td">
						<h4><?php _e('Select Form', 'wordform-drag-drop-forms-builder');?></h4>   											
					</td>
					<td>
						<select id="scWordformSelectValidationMsgsFor" name="sc-wordform-select-validation-message-form-id">
							<option value="all_form"><?php _e('All Froms', 'wordform-drag-drop-forms-builder');?></option>
							<?php 
								if ( isset( $all_created_forms ) && array_filter( $all_created_forms ) ) { 
									foreach ( $all_created_forms as $data ) {							
										echo '<option value="'. esc_html( $data['form_id'] ) . '">'. esc_html( $data['form_name'] ) .'</option>'; 							
									}
								}
							?>
						</select>
						<br/><small><?php _e('You can set different validation messages for each form.', 'wordform-drag-drop-forms-builder');?></small>
					</td>
				</tr>
		       
                <tr>
                	<table class="sc-wordform-validation-messages-display-table">
						<tbody>
							<!-- Include template - table rows -->
							<?php 								
							include_once SCWORDFORM_PLUGIN_DIR . 'admin/views/add_scwordform_submenus_settings_callback_validation_tab_page_template.php'; 
							?>
						</tbody>
					</table>
				</tr>

				<tr>
					<td colspan="2">
					    <input type="hidden" class="sc-wordform-validation-tab-selected-form-name" name="sc-wordform-validation-tab-selected-form-name" value="All Forms" />
					    <input type="hidden" class="sc-wordform-validation-tab-selected-form-id" name="sc-wordform-validation-tab-selected-form-id" value="all_form" />
						<button type="submit" class="button button-primary button-large"><?php _e('Save', 'wordform-drag-drop-forms-builder');?></button>
						<span class="sc-wordform-validation-message-settings-info"></span>
					</td>
				</tr>
			          
		</tbody>
	</table>
</form>  

</div>
