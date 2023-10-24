<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap" style="margin: 1%;">
	<div id="tabs" class="sc-wordform-tabs-wrapper-div" style="display: none;">
		  <ul>			
			<li><a href="#scWordFormGeneral" style="font-weight: bold;"><i class="dashicons dashicons-admin-settings"></i>&nbsp;<?php _e('General', 'wordform-drag-drop-forms-builder');?></a></li>			
			<li><a href="#scWordFormValidation" style="font-weight: bold;"><i class="dashicons dashicons-yes-alt"></i>&nbsp;<?php _e('Validation', 'wordform-drag-drop-forms-builder');?></a></li>
		  </ul>
		  		  
		  <div id="scWordFormGeneral">
				<h3><i class="dashicons dashicons-admin-settings"></i>&nbsp;<?php _e('General Settings', 'wordform-drag-drop-forms-builder');?></h3>
				<?php include_once SCWORDFORM_PLUGIN_DIR . 'admin/views/add_scwordform_submenus_settings_callback_general_tab_page.php'; ?>
		  </div><!-- #scWordFormGeneral -->
		  
		  <div id="scWordFormValidation">
				<h3><i class="dashicons dashicons-yes-alt"></i>&nbsp;<?php _e('Validation Message Settings', 'wordform-drag-drop-forms-builder');?></h3>
				<?php include_once SCWORDFORM_PLUGIN_DIR . 'admin/views/add_scwordform_submenus_settings_callback_validation_tab_page.php'; ?>
		  </div><!-- #scWordFormValidation -->
		  
		  
	</div> <!-- /.tabs -->
</div><!-- /.wrap -->


<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#tabs').tabs().promise().done(function() {
			$('.sc-wordform-tabs-wrapper-div').fadeIn(500);
		});		
	});
</script>