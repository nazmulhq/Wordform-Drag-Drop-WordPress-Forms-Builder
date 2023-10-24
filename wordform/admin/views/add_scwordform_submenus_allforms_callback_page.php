<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap" id="wordformAllFormsWrapperTable" style="display: none;">
	<h3><i class="dashicons dashicons-editor-table" style="color: green;"></i>&nbsp;<?php _e('All Created Forms', 'wordform-drag-drop-forms-builder');?></h3>
	<table id="wordformAllFormsTable" class="display hover stripe">
		<thead>
			<tr>
				<th><?php _e('FormName', 'wordform-drag-drop-forms-builder');?></th>				
				<th><?php _e('Shortcode', 'wordform-drag-drop-forms-builder');?></th>
				<th><?php _e('Created', 'wordform-drag-drop-forms-builder');?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		   <?php 
			if ( isset( $query_results ) && array_filter( $query_results ) ) {
				foreach ( $query_results as $data ) {
					$form_id	= sanitize_text_field( $data['form_id'] );
					$edit_link	= admin_url('admin.php?page=sc-wordform-create-forms&wordform-edit-id=' . $form_id );
			?>
			<tr>
				<td><?php echo esc_html( $data['form_name'] );?></td>				
				<td class="sc-wordform-shortcode-display-td">
				    <?php 
					$shortcode			=  '[scwordform sc-wordform-id="'. $form_id .'"]'; 
					$copt_clipboard_id  =  esc_attr( 'sc-wordform-copy-shortcode-'. $form_id );
					?>
					<span id="<?php echo $copt_clipboard_id;?>">
						<?php echo $shortcode;?>
					</span>
					<span class="sc-wordform-shortcode-copy-text" style="display: none;" data-clipboard-text='<?php echo $shortcode;?>' >Copy</span>					
				</td>
				<td><?php echo esc_html( $data['created_at'] );?></td>
				<td>
					<a href="<?php echo esc_url( $edit_link );?>">Edit</a>
					&nbsp;|&nbsp;
					<a href="<?php echo esc_url( site_url() . '?sc-wordform-id=' . $form_id );?>" target="_blank">View</a>
					&nbsp;|&nbsp;
					<a data-sc-wordform-name="<?php echo esc_attr( $data['form_name'] );?>" data-sc-wordform-id="<?php echo esc_attr( $form_id );?>" class="sc-wordform-delete-btn" href="javascript:void(0)">
						<span class="dashicons dashicons-trash" title="<?php __('Delete Form', 'wordform-drag-drop-forms-builder'); ?>"></span>
					</a>
					
				</td>
			</tr>
			<?php 
				}
			}
			?>
		</tbody>
	</table>						
</div><!-- #wordformAllFormsTable -->


<script type="text/javascript">
	jQuery(document).ready(function($){				
		//$('#wordformAllFormsTable').DataTable();								
		$('#wordformAllFormsTable').DataTable({
			order: [[ 2, 'desc' ]]
		});
		$('#wordformAllFormsWrapperTable').fadeIn(500);
		// Fix Show Entries select box UI
		$('body').removeClass('wp-core-ui');
		
		// Copy Shortcode
		new ClipboardJS('.sc-wordform-shortcode-copy-text');
		
		$('body').on('mouseenter', '#wordformAllFormsTable tr', function() {
			$(this).find('.sc-wordform-shortcode-copy-text').show()
		});
		$('body').on('mouseleave', '#wordformAllFormsTable tr', function() {
			$(this).find('.sc-wordform-shortcode-copy-text').hide();
		});
		
		$('body').on('click', '.sc-wordform-shortcode-copy-text', function() {	
			let copyBtnTextEle	= $(this);
			copyBtnTextEle.text('Copied');
			setTimeout(function() { copyBtnTextEle.text('Copy');}, 3000);
		});

	});
</script>