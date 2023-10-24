<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$nonce	= wp_create_nonce( 'scwordform_wpnonce' );
?>
<div id="wordformUsersSubmissionWrapperTable" style="display: none;">
	<h3><i class="dashicons dashicons-editor-table" style="color: green;"></i>&nbsp;<?php _e('Users Form Submission Data', 'wordform-drag-drop-forms-builder');?></h3>
	<table id="wordformUsersSubmissionDataTable" class="display hover stripe">
		<thead>
			<tr>
				<th><?php _e('Form Name', 'wordform-drag-drop-forms-builder');?></th>				
				<th><?php _e('Submission Data', 'wordform-drag-drop-forms-builder');?></th>
				<th><?php _e('Date', 'wordform-drag-drop-forms-builder');?></th>				
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>						
</div><!-- #wordformUsersSubmissionDataTable -->



<script type="text/javascript">
	jQuery(document).ready(function($){				
		//$('#wordformAllFormsTable').DataTable();								
		//$('#wordformUsersSubmissionDataTable').DataTable();
		
		$('#wordformUsersSubmissionDataTable').DataTable({
  			//"processing": true,    										
			
			"ajax": { 
				'type': 'POST',
				'url': "<?php echo admin_url('admin-ajax.php');?>",
				'data': { action: 'sc_wordform_users_submission_data_load', security: '<?php echo esc_js( $nonce );?>' } 
			},
			
			columns: [
					{ data: 'formName' },
					{ data: 'submissionData' },
					{ data: 'date' }
				],
			order: [[ 2, 'desc' ]]
			
		});
		
		
		
		$('#wordformUsersSubmissionWrapperTable').fadeIn(500);
		// Fix Show Entries select box UI
		$('body').removeClass('wp-core-ui');
	});
</script>