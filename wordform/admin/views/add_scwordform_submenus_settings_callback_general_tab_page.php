<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$general_tab_options					= SC_Wordform::sc_wordform_get_settings_general_tab_info();
 $send_email_on_form_submission			= ( isset( $general_tab_options[ 'send_email_on_form_submission'] ) && $general_tab_options[ 'send_email_on_form_submission'] == 'yes' )? ' checked ' : ''; 
$submit_button_background_color			= isset( $general_tab_options[ 'submit_button_background_color'] )?  sanitize_text_field( $general_tab_options[ 'submit_button_background_color'] ) : '#2271b1';
$submit_button_background_hover_color	= isset( $general_tab_options[ 'submit_button_background_hover_color'] )? sanitize_text_field( $general_tab_options[ 'submit_button_background_hover_color'] ) : '#006ba1';
$submit_button_font_color				= isset( $general_tab_options[ 'submit_button_font_color'] )? sanitize_text_field( $general_tab_options[ 'submit_button_font_color'] ) : '#ffffff';
$submit_button_font_size				= isset( $general_tab_options[ 'submit_button_font_size'] )? sanitize_text_field( $general_tab_options[ 'submit_button_font_size'] ) : 16;
$submit_button_font_weight				= isset( $general_tab_options[ 'submit_button_font_weight'] )? sanitize_text_field( $general_tab_options[ 'submit_button_font_weight'] ) : 'normal';
$submit_button_padding_top_bottom		= isset( $general_tab_options[ 'submit_button_padding_top_bottom'] )? sanitize_text_field( $general_tab_options[ 'submit_button_padding_top_bottom'] ) : 8;
$submit_button_padding_left_right		= isset( $general_tab_options[ 'submit_button_padding_left_right'] )? sanitize_text_field( $general_tab_options[ 'submit_button_padding_left_right'] ) : 25;

?>
<div class="sc-wordform-setting-page-general-tab-table-wrapper">
	<form enctype="multipart/form-data" method="post" id="scWordformSettingsGeneralTabForm">
		<table style="border-spacing: 25px;">
			<tbody>

			    <!-- On Form Submission : Send Email -->
				<tr>
					<td>
						<h4><?php _e('On Form Submission', 'wordform-drag-drop-forms-builder');?></h4>   					
					</td>
					<td>   	
					    <input type="checkbox" id="scWordformFormSubmissionSendEmail" class="sc-wordform-form-submission-send-email" name="sc-wordform-form-submission-send-email" <?php esc_html_e( $send_email_on_form_submission );?> >
						<label for="#scWordformFormSubmissionSendEmail"><?php _e('Send Email', 'wordform-drag-drop-forms-builder');?></label><br/>&nbsp;&nbsp;&nbsp;&nbsp;
						<small><?php esc_html_e('If checked then on each Form submission by users E-mail will be sent to your email ', 'wordform-drag-drop-forms-builder') . esc_html( get_option('admin_email' ) ) .'. '; ?></small>
						<small><?php esc_html_e('Your Mail setting should work properly to receive email on each form submission.', 'wordform-drag-drop-forms-builder' );?></small>
					</td>   				
				</tr>
				
				<!-- Submit Button : Background Color -->
				<tr>
					<td>
						<h4><?php _e('Form Submit Button Color', 'wordform-drag-drop-forms-builder');?></h4>   					
					</td>
					<td>   	
					    <input type="text" value="<?php echo esc_html( $submit_button_background_color );?>" class="sc-wordform-form-submit-button-background-color" name="sc-wordform-form-submit-button-background-color" >						
					</td>   				
				</tr>
				
				<!-- Submit Button : Background Color (Hover) -->
				<tr>
					<td>
						<h4><?php _e('Form Submit Button Color(Hover)', 'wordform-drag-drop-forms-builder');?></h4>   					
					</td>
					<td>
						<input type="text" value="<?php echo esc_html($submit_button_background_hover_color);?>" class="sc-wordform-form-submit-button-background-hover-color" name="sc-wordform-form-submit-button-background-hover-color">	
					</td>
						
				</tr>
				
				<!-- Submit Button : Font-Color -->
				<tr>
					<td>
						<h4><?php _e('Submit Button Font Color', 'wordform-drag-drop-forms-builder');?></h4>   					
					</td>
					<td>
						<input type="text" value="<?php echo esc_html($submit_button_font_color);?>" class="sc-wordform-form-submit-button-font-color" name="sc-wordform-form-submit-button-font-color">	
					</td>
						
				</tr>
				
				<!-- Submit Button : Font-Size -->
				<tr>
					<td>
						<h4><?php _e('Submit Button Text Size', 'wordform-drag-drop-forms-builder');?></h4>   					
					</td>
					<td>
						<select class="sc-wordform-form-submit-button-text-size" name="sc-wordform-form-submit-button-text-size">
						    <?php
							for( $i=12; $i<=40; $i +=2 ) {									
								if ( $i == $submit_button_font_size ) {
									echo '<option value="' . esc_html($i) . '" selected>' . esc_html($i) . 'px</option>';								
								}
								else {
									echo '<option value="' . esc_html($i) . '">' . esc_html($i) . 'px</option>';								
								}
							}
							?>							
						</select>						
					</td>
						
				</tr>
				
				<!-- Submit Button : Padding(Top-Bottom) -->
				<tr>
					<td>
						<h4><?php _e('Submit Button Padding(Top-Bottom)', 'wordform-drag-drop-forms-builder');?></h4>   					
					</td>
					<td>
						<select class="sc-wordform-form-submit-button-padding-top-bottom" name="sc-wordform-form-submit-button-padding-top-bottom">
						    <?php
							for( $i=2; $i<=30; $i++ ) {									
								if ( $i == $submit_button_padding_top_bottom ) {
									echo '<option value="' . esc_html($i) . '" selected>' . esc_html($i) . 'px</option>';								
								}
								else {
									echo '<option value="' . esc_html($i) . '">' . esc_html($i) . 'px</option>';								
								}
							}
							?>							
						</select>						
					</td>
						
				</tr>
				
				<!-- Submit Button : Padding(Left-Right) -->
				<tr>
					<td>
						<h4><?php _e('Submit Button Padding(Left-Right)', 'wordform-drag-drop-forms-builder');?></h4>   					
					</td>
					<td>
						<select class="sc-wordform-form-submit-button-padding-left-right" name="sc-wordform-form-submit-button-padding-left-right">
						    <?php
							for( $i=2; $i<=30; $i++ ) {									
								if ( $i == $submit_button_padding_left_right ) {
									echo '<option value="' . esc_html($i) . '" selected>' . esc_html($i) . 'px</option>';								
								}
								else {
									echo '<option value="' . esc_html($i) . '">' . esc_html($i) . 'px</option>';								
								}
							}
							?>							
						</select>						
					</td>
						
				</tr>
				
				<!-- Submit Button : Font-Weight -->
				<tr>
					<td>
						<h4><?php _e('Submit Button Font Weight', 'wordform-drag-drop-forms-builder');?></h4>   					
					</td>
					<td>
				        <?php						
						$font_weight_normal	= '';
						$font_weight_bold	= '';
						if ( $submit_button_font_weight == 'normal' ) {
								$font_weight_normal = ' checked';
						}
						else if ( $submit_button_font_weight == 'bold' ) {
							$font_weight_bold		= ' checked';
						}
						?>
					    <input type="radio" name="sc-wordform-form-submit-button-font-weight" value="normal" <?php echo esc_html($font_weight_normal);?> /> <?php _e('Normal', 'wordform-drag-drop-forms-builder');?>
					    &nbsp;&nbsp;&nbsp;
					    <input type="radio" name="sc-wordform-form-submit-button-font-weight" value="bold" <?php echo esc_html($font_weight_bold);?>/> <?php _e('Bold', 'wordform-drag-drop-forms-builder');?>
					</td>
						
				</tr>
				
				

				<tr>
					<td>
						<button type="submit" class="button button-primary button-large"><?php _e('Save', 'wordform-drag-drop-forms-builder');?></button>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<button type="button" class="button button-primary button-large sc-wordform-settings-general-tab-reset"><?php _e('Reset', 'wordform-drag-drop-forms-builder');?></button>
					</td>
					<td class="sc-wordform-general-tab-update-info"></td>
				</tr>


			</tbody>
		</table>  
	</form>
</div>


<script type="text/javascript">
	jQuery(document).ready( function($) {		
		
		var myOptions = {
			// you can declare a default color here,
			// or in the data-default-color attribute on the input
			defaultColor: false,
			// a callback to fire whenever the color changes to a valid color
			change: function(event, ui){},
			// a callback to fire when the input is emptied or an invalid color
			clear: function() {},
			// hide the color picker controls on load
			hide: true,
			// show a group of common colors beneath the square
			// or, supply an array of colors to customize further
			palettes: true
		};		
		
		$('.sc-wordform-form-submit-button-background-color, .sc-wordform-form-submit-button-background-hover-color, .sc-wordform-form-submit-button-font-color').wpColorPicker( {
			change: function(event, ui ) {
				//console.log(ui);
				//console.log(event);
			}
		});
	});
</script>