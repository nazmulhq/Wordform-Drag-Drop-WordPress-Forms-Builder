<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SC_Wordform_Ajaxhandler {
	
	private static $initiated             	  = false;		
	public static $output					  = [];	
				
	public function __construct() {
		if ( ! self::$initiated ) {
			self::initiate_hooks();
		}								
	}
	
	private static function initiate_hooks() {		
		  // Enqueue scripts 
		  add_action( 'admin_enqueue_scripts', array( __CLASS__, 'sc_wordform_admin_required_scripts') );			
		  add_action( 'wp_enqueue_scripts', array( __CLASS__, 'sc_wordform_frontend_required_scripts') );
		  add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'sc_wordform_block_editor_required_scripts'] );		  		 
		  add_filter( 'safe_style_css', [ __CLASS__, 'sc_wordform_style_filter_hook' ], 10, 1 );		  
		  
		  // Ajax : Elements
		  add_action( 'wp_ajax_sc_wordform_render_element_options_type_text',  [ __CLASS__, 'sc_wordform_render_element_options_type_text'] );
		  add_action( 'wp_ajax_sc_wordform_render_element_options_type_number',  [ __CLASS__, 'sc_wordform_render_element_options_type_number'] );
		  add_action( 'wp_ajax_sc_wordform_render_element_options_type_textarea',  [ __CLASS__, 'sc_wordform_render_element_options_type_textarea'] );
		  add_action( 'wp_ajax_sc_wordform_render_element_options_type_radio', [ __CLASS__, 'sc_wordform_render_element_options_type_radio'] );
		  add_action( 'wp_ajax_sc_wordform_render_element_options_type_checkbox', [ __CLASS__, 'sc_wordform_render_element_options_type_checkbox'] );
		  add_action( 'wp_ajax_sc_wordform_render_element_options_type_select', [ __CLASS__, 'sc_wordform_render_element_options_type_select'] );
		  
		
		  // Ajax : Save created - edited wordform data - Delete
		  add_action( 'wp_ajax_sc_wordform_save', [ __CLASS__, 'sc_wordform_save'] );	
		  add_action( 'wp_ajax_sc_wordform_built_form_data_save', [ __CLASS__, 'sc_wordform_built_form_data_save'] );	
		  add_action( 'wp_ajax_sc_wordform_all_forms_page_delete_form', [ __CLASS__, 'sc_wordform_all_forms_page_delete_form'] );
		
		
		  // Ajax : Settings : Validation tab 
		  add_action( 'wp_ajax_sc_wordform_settings_menu_validation_tab_data_save', [ __CLASS__, 'sc_wordform_settings_menu_validation_tab_data_save'] );	
		  add_action( 'wp_ajax_sc_wordform_settings_menu_validation_tab_selected_form_data_save', [ __CLASS__, 'sc_wordform_settings_menu_validation_tab_selected_form_data_save'] );	
		  // Ajax: Settings : General Tab
		  add_action( 'wp_ajax_sc_wordform_settings_general_tab_form', [ __CLASS__, 'sc_wordform_settings_general_tab_form'] );	
		 		 		 		
		 
		  // Ajax : Front-end Users submission form data display through datatable 
		  add_action( 'wp_ajax_sc_wordform_users_submission_data_load', [ __CLASS__, 'sc_wordform_users_submission_data_load'] );				  
		
		  // Ajax : Front-end users submission form
		  add_action( 'wp_ajax_sc_wordform_created_form_submission', [ __CLASS__, 'sc_wordform_created_form_submission'] );		
		  add_action( 'wp_ajax_nopriv_sc_wordform_created_form_submission', [ __CLASS__, 'sc_wordform_created_form_submission'] );	
		
		
		  // REST API : End Points : Wordform Block Editor 
		  add_action( 'rest_api_init', function () {
														 register_rest_route( 'wordform/v1', '/all-form-list', array(
														 'methods' 	=> 'POST',
														 'callback' => [ __CLASS__, 'sc_wordform_block_editor_get_forms_ajax_callback' ],
														 'permission_callback' => function() { return true;}		 
    													  ));
		  											});		
				  
		
		  add_action( 'rest_api_init', function () {
														 register_rest_route( 'wordform/v1', '/render-selected-form', array(
														 'methods' 	=> 'POST',
														 'callback' => [ __CLASS__, 'sc_wordform_block_editor_get_selected_form_ajax_callback' ],
														 'permission_callback' => function() { return true;}		 
    													  ));
		  											});		
		
		
		 		 		  
		  self::$initiated = true;
	}	
	

    /**
	 * Rest API Callback
	 * Get form lists for block editor to choose from the select dropdown menu
	 * return - json
	 */
	public static function sc_wordform_block_editor_get_forms_ajax_callback( $data ) {
		self::$output	=	[];
		$result 							= $data->get_body();
		if ( $result ) {
			$result_arr						= json_decode( $result, true );
			if ( isset( $result_arr['postID'] ) && ! empty( $result_arr['postID'] ) ) {
				$postID						= sanitize_text_field( $result_arr['postID'] );
				$created_forms				=  SC_Wordform::sc_wordform_get_all_forms_for_block_dynamic_callback();				
				self::$output['status']		= 'success';
				self::$output['formList']	= $created_forms;
				self::$output['postID']		= $postID;
				self::$output['formID']     = SC_Wordform::sc_wordform_check_if_wordform_is_attached_with_post( $postID );
			}
			else {
				self::$output['status']			= 'fail';
				self::$output['comment']		= 'postID not received.';
			}
		}
		else {
			self::$output['status']				= 'fail';
			self::$output['comment']			= 'No post Result data found.';			
		}
		return wp_json_encode( self::$output );		
	}
	
	/**
	* Rest API Callback
	* Rendering the created form based on selected or chosen form on block editor
	* Embed the selected form with the post
	* Also save the POST ID attached with the form
	* return json
	*/
	public static function sc_wordform_block_editor_get_selected_form_ajax_callback( $data ) {
		self::$output							=	[];
		
		$result 								= $data->get_body();
		if ( $result ) {
			$result_arr							= json_decode( $result, true );
			if ( isset( $result_arr['WordFormID'] ) && ! empty( $result_arr['WordFormID'] ) ) {
				$form_id						= sanitize_text_field( $result_arr['WordFormID'] );
				$previous_form_id			    = isset( $result_arr['PreviousWordFormID'] ) && ! empty($result_arr['PreviousWordFormID'])? sanitize_text_field( $result_arr['PreviousWordFormID'] ) : '';
				$attached_post_id				= sanitize_text_field( $result_arr['postID'] );
				
				$results						= SC_Wordform::sc_wordform_db_query( $form_id );						
				SC_BuildForm::$form_elements	= json_decode( $results[0]['form'], true );
				$created_form					= SC_BuildForm::sc_wordform_build_form_from_elements( $form_id );				
				$formdata						= '';
				foreach ( $created_form as $form ) {
					$formdata				   .= $form;
				}
				
				// Store the form attached POST IDs against form id
				global $wpdb;
			    $table							= $wpdb->prefix . SC_Wordform::$sc_wordform_tbl;
				$query_results					= $wpdb->get_results( $wpdb->prepare('SELECT form_id, attach_post_ids FROM %i WHERE form_id = %s LIMIT 1', [ $table, $form_id ] ), ARRAY_A );
				if ( array_filter($query_results ) && $query_results ) {
					// Yet any WordForm not attached with Post - first time
					if ( empty( $query_results[0]['attach_post_ids'] ) ) {
						$post_ids[]				= $attached_post_id;
					}
					// When WordForm already attached with Post
					else {
						// Post IDs stored as Json object - retrieve old ones first
						$post_ids				= json_decode( $query_results[0]['attach_post_ids'], true ); 
						$post_ids               = array_map( function( $post_id ) { return sanitize_text_field( $post_id ); }, $post_ids );						
						// Push the new one
						$post_ids[]				= $attached_post_id; 						
					}
					
					// Update					
					$post_ids               			= array_unique( $post_ids );
					$update_data['attach_post_ids']		= wp_json_encode( $post_ids );
					$where_data['form_id']				= $form_id;
					// Update current form ID
					$updated_status						= $wpdb->update( $table, $update_data, $where_data );
					// Also update previous form id data - remove attached post Id from previous form ID
					if ( $updated_status && $previous_form_id ) {
						$previous_attached_post_ids[]   = $attached_post_id;
						self::$output['attachedPostIdsRemoved'] = SC_Wordform::sc_wordform_remove_attached_post_ids_of_previous_form_id( $previous_form_id, $previous_attached_post_ids );				;
					}
					self::$output['postIDUpdateStatus']	= $updated_status;
				}
				
								
				self::$output['formdata']		= $formdata;
				self::$output['status']			= 'success';
				self::$output['reason']			= 'Form data ready to display.';
			}
		}
		else {
			self::$output['status']				= 'fail';
			self::$output['reason']				= 'no form ID received.';
		}
		
		return json_encode(self::$output);
	}
	
	
	/**
	 * Enqueue admin scripts
	 */			
	public static function sc_wordform_admin_required_scripts() {
		// get current admin screen
		global $pagenow;		
		$screen 		= get_current_screen();		
		$admin_page		= isset( $_GET['page'] )? sanitize_text_field( $_GET['page'] ) : '';
		$wordform_pages	= [ 'word-form-topmenu', 'sc-wordform-create-forms', 'sc-wordform-user-submission-data', 'sc-wordform-settings' ];
		
		if ( in_array( $admin_page, $wordform_pages ) ) {			
			
			// Add the color picker css file 
			wp_enqueue_style( 'wp-color-picker' );
			
			wp_enqueue_style('sc-wordform-datatable-style', 'https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css', array(), SCWORDFORM_VERSION, 'all' );
			wp_enqueue_style('sc-wordform-admin-style', plugins_url( '../admin/css/sc-wordform-admin-misc-styles.css', __FILE__ ) , array(), SCWORDFORM_VERSION, 'all' );			wp_enqueue_style('sc-wordform-jquery-ui-style', '//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' , array(), SCWORDFORM_VERSION, 'all' );
			
			wp_enqueue_script('sc-wordform-datatable-script', 'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js' , array('jquery'), SCWORDFORM_VERSION, 'all');
			wp_enqueue_script('sc-wordform-admin-copy-to-clipboard-script', plugins_url( '../admin/js/clipboard.min.js', __FILE__ ) , array('jquery'), SCWORDFORM_VERSION, true);

			
			wp_enqueue_script('sc-wordform-admin-misc-script', plugins_url( '../admin/js/sc-wordform-admin-misc-script.js', __FILE__ ) , array('jquery', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'jquery-ui-tabs', 'wp-color-picker' ), SCWORDFORM_VERSION, true);
						
			$nonce = wp_create_nonce( 'scwordform_wpnonce' );
			
			// localize script
			wp_localize_script(
				'sc-wordform-admin-misc-script',
				'sc_wordform_metabox_script_obj',
				array(
					'adminajax_url'                  => admin_url('admin-ajax.php'),
					'nonce'                          => $nonce, 
					'current_screenid'               => $screen->id,
					'current_posttype'               => $screen->post_type,
					'current_pagenow'                => $pagenow,
				
				    'noElementDroppedMsg'			 => '<span class="dashicons dashicons-info"></span> Drag & Drop Element first into the DropZone to create Form!' 	
				)
			);
		}
	}
	
	/**
	 * Front-end required scripts
	 * If plugin active then add scripts
	 */
	public static function sc_wordform_frontend_required_scripts() {
		if ( is_plugin_active( 'wordform/wordform.php' ) ) {
			
			global $pagenow;		
			wp_enqueue_style('sc-wordform-frontend-misc-style', plugins_url( '../assets/css/sc-wordform-frontend-misc-styles.css', __FILE__ ) , array(), SCWORDFORM_VERSION, 'all' );
			wp_enqueue_script('sc-wordform-frontend-misc-script',  plugins_url( '../assets/js/sc-wordform-frontend-misc-script.js', __FILE__ ), array('jquery' ), SCWORDFORM_VERSION, true);
			$nonce 			= wp_create_nonce( 'scwordform_wpnonce' );
			
			// localize script
			wp_localize_script(
				'sc-wordform-frontend-misc-script',
				'sc_wordform_frontend_misc_script_obj',
				array(
					'ajax_url'                  	 => admin_url('admin-ajax.php'),
					'nonce'                          => $nonce, 
					'current_pagenow'                => $pagenow,			
				)
			);			
		} 
	}
	
	/**
	 * Block editor scripts enqeue
	 */
	public static function sc_wordform_block_editor_required_scripts() {
		if ( is_plugin_active( 'wordform/wordform.php' ) ) {
			
			global $pagenow;		
			$screen 		= get_current_screen();		
			
			wp_enqueue_style('sc-wordform-frontend-block-editor-misc-style', plugins_url( '../assets/css/sc-wordform-block-editor-misc-styles.css', __FILE__ ) , array(), SCWORDFORM_VERSION, 'all' );
			wp_enqueue_script('sc-wordform-block-editor-misc-script',  plugins_url( '../assets/js/sc-wordform-block-editor-script.js', __FILE__ ), array('jquery' ), SCWORDFORM_VERSION, true);
			
			$nonce 			= wp_create_nonce( 'scwordform_wpnonce' );
			
			// localize script
			wp_localize_script(
				'sc-wordform-block-editor-misc-script',
				'sc_wordform_block_editor_misc_script_obj',
				array(
					'ajax_url'                  	 => admin_url('admin-ajax.php'),
					'nonce'                          => $nonce, 
					'current_screenid'               => $screen->id,
					'current_posttype'               => $screen->post_type,
					'current_pagenow'                => $pagenow,			
				)
			);			
		} 		
	}
	
	/**
	 * Add custom style attributes
	 * To allow with wp_kses
	 */
	public static function sc_wordform_style_filter_hook( $styles ) {
			$styles[] 	= 'display';
			$styles[] 	= 'float';
			return $styles;		
	}
	
	// Load Text Options template 
	public static function sc_wordform_render_element_options_type_text() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		$element_wrapper_id					    =  isset( $_POST['elementWrapperID'] )? sanitize_text_field( $_POST['elementWrapperID'] ) : '';
		self::$output							=  [];		
		self::$output['elementType']			=  'Text';
		self::$output['elementWrapperID']		=  $element_wrapper_id;
		$element_index							= explode('-', $element_wrapper_id)[1];
		ob_start();
		include_once( SCWORDFORM_PLUGIN_INC . 'elements/text.php' );
		self::$output['fieldEditOptionsHtml']	=  ob_get_clean();
		if  ( ! isset( $element_wrapper_id) || empty( $element_wrapper_id ) ) {
			self::$output['status']				= 'fail';	
			self::$output['comment']			= 'Element wrapper ID missing.';	
		}
		else {
			self::$output['status']				= 'success';	
		}		
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();		
	}

	// Load Number Options template 
	public static function sc_wordform_render_element_options_type_number() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		$element_wrapper_id					    =  isset( $_POST['elementWrapperID'] )? sanitize_text_field( $_POST['elementWrapperID'] ) : '';
		self::$output							=  [];		
		self::$output['elementType']			=  'Number';
		self::$output['elementWrapperID']		=  $element_wrapper_id;
		$element_index							= explode('-', $element_wrapper_id)[1];
		ob_start();
		include_once( SCWORDFORM_PLUGIN_INC . 'elements/number.php' );
		self::$output['fieldEditOptionsHtml']	=  ob_get_clean();
		if  ( ! isset( $element_wrapper_id) || empty( $element_wrapper_id ) ) {
			self::$output['status']				= 'fail';	
			self::$output['comment']			= 'Element wrapper ID missing.';	
		}
		else {
			self::$output['status']				= 'success';	
		}		
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();		
	}
	
	
	
	// Load Textarea Options template 
	public static function sc_wordform_render_element_options_type_textarea() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		$element_wrapper_id					    =  isset( $_POST['elementWrapperID'] )? sanitize_text_field( $_POST['elementWrapperID'] ) : '';
		self::$output							=  [];		
		self::$output['elementType']			=  'Textarea';
		self::$output['elementWrapperID']		= $element_wrapper_id;
		$element_index							= explode('-', $element_wrapper_id)[1];
		ob_start();
		include_once( SCWORDFORM_PLUGIN_INC . 'elements/textarea.php' );
		self::$output['fieldEditOptionsHtml']	=  ob_get_clean();
		if  ( ! isset( $element_wrapper_id) || empty( $element_wrapper_id ) ) {
			self::$output['status']				= 'fail';	
			self::$output['comment']			= 'Element wrapper ID missing.';	
		}
		else {
			self::$output['status']				= 'success';	
		}		
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();		
	}
	
	
	// Load Radio Options template 
	public static function sc_wordform_render_element_options_type_radio() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		$element_wrapper_id					    =  isset( $_POST['elementWrapperID'] )? sanitize_text_field( $_POST['elementWrapperID'] ) : '';
		self::$output							=  [];		
		self::$output['elementType']			=  'Radio';
		self::$output['elementWrapperID']		= $element_wrapper_id;
		$element_index							= explode('-', $element_wrapper_id)[1];
		ob_start();
		include_once( SCWORDFORM_PLUGIN_INC . 'elements/radio.php' );
		self::$output['fieldEditOptionsHtml']	=  ob_get_clean();
		if  ( ! isset( $element_wrapper_id) || empty( $element_wrapper_id ) ) {
			self::$output['status']				= 'fail';	
			self::$output['comment']			= 'Element wrapper ID missing.';	
		}
		else {
			self::$output['status']				= 'success';	
		}		
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();		
	}		
	
	// Load Select Dropdown template 
	public static function sc_wordform_render_element_options_type_select() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		$element_wrapper_id					    =  isset( $_POST['elementWrapperID'] )? sanitize_text_field( $_POST['elementWrapperID'] ) : '';
		self::$output							=  [];		
		self::$output['elementType']			=  'Select';
		self::$output['elementWrapperID']		= $element_wrapper_id;
		$element_index							= explode('-', $element_wrapper_id)[1];
		ob_start();
		include_once( SCWORDFORM_PLUGIN_INC . 'elements/select.php' );
		self::$output['fieldEditOptionsHtml']	=  ob_get_clean();
		if  ( ! isset( $element_wrapper_id) || empty( $element_wrapper_id ) ) {
			self::$output['status']				= 'fail';	
			self::$output['comment']			= 'Element wrapper ID missing.';	
		}
		else {
			self::$output['status']				= 'success';	
		}		
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();		
	}		
		
	// Load Checkbox template 
	public static function sc_wordform_render_element_options_type_checkbox() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		$element_wrapper_id					    =  isset( $_POST['elementWrapperID'] )? sanitize_text_field( $_POST['elementWrapperID'] ) : '';
		self::$output							=  [];		
		self::$output['elementType']			=  'Checkbox';
		self::$output['elementWrapperID']		= $element_wrapper_id;
		$element_index							= explode('-', $element_wrapper_id)[1];
		ob_start();
		include_once( SCWORDFORM_PLUGIN_INC . 'elements/checkbox.php' );
		self::$output['fieldEditOptionsHtml']	=  ob_get_clean();
		if  ( ! isset( $element_wrapper_id) || empty( $element_wrapper_id ) ) {
			self::$output['status']				= 'fail';	
			self::$output['comment']			= 'Element wrapper ID missing.';	
		}
		else {
			self::$output['status']				= 'success';	
		}
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();		
	}		
	
	
	/**
	 * Created form data
	 * Save: Created wordform 
	 * @since 1.0.0
	 */
	public static function sc_wordform_save() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		global $wpdb;
		self::$output						=	[];		
		//var_dump( $_POST );
		//print_r( $_POST['params'] );		
		//exit();
		$form_saved							= isset( $_POST[ 'saveMeta' ]['formSaved'] )? sanitize_text_field( $_POST[ 'saveMeta' ]['formSaved'] )  : false;
		$saved_form_id			        	= isset( $_POST[ 'saveMeta' ]['createdFormID'] )? sanitize_text_field( $_POST[ 'saveMeta' ]['createdFormID'] ) : ''; 
		$form_name			        		= isset( $_POST[ 'saveMeta' ]['formName'] ) && !empty( $_POST[ 'saveMeta' ]['formName'] )? sanitize_text_field( $_POST[ 'saveMeta' ]['formName'] ) : 'New Form'; 
				
		if ( isset( $_POST['params'] ) && is_array( $_POST['params'] ) ) {	
			$sanitized_form_data			= self::sc_wordform_sanitize_created_form_data( $_POST['params'] );									
			$form_data						= json_encode( $sanitized_form_data, JSON_HEX_APOS );
			// Already Saved once - Update
			if ( $form_saved && ! empty( $saved_form_id ) ) {
				$preview_url				= site_url() . '?sc-wordform-id=' . $saved_form_id;
				$update_data['form']		= $form_data;
				$update_data['form_name']	= $form_name;
				$where_data['form_id']		= trim( $saved_form_id );
				$updated_status				= $wpdb->update( $wpdb->prefix . SC_Wordform::$sc_wordform_tbl, $update_data, $where_data );
				self::$output['status']		= 'success';
				//self::$output['reason']		= 'Form saved successfully. <a href="'.$preview_url.'" target="_blank">Preview & Test</a>';
				self::$output['reason']		= '<span class="dashicons dashicons-saved"></span> ' . __('Form updated successfully.');
				self::$output['formSaved']	= true;
				self::$output['savedFormID']= $saved_form_id;
				self::$output['previewURL']	= $preview_url;
			}
			// New form data - insert
			else {
				$insert_data				= [];							
				$insert_data['form_name']	= $form_name;
				$insert_data['form']		= $form_data;			
				$insert_record				= $wpdb->insert( $wpdb->prefix . SC_Wordform::$sc_wordform_tbl, $insert_data );
				if ( $insert_record ) {
					$insert_id				= $wpdb->insert_id;
					$form_id				= 'wordform-' . $insert_id;
					$update_data['form_id']	= $form_id;
					$where_data['id']		= $insert_id;
					$update_status			= $wpdb->update( $wpdb->prefix . SC_Wordform::$sc_wordform_tbl, $update_data, $where_data );
					if ( $update_status > 0 ) {
						$preview_url					= site_url() . '?sc-wordform-id=' . $form_id;
						self::$output['status']			= 'success';
						//self::$output['reason']			= 'Form saved successfully. <a href="'.$preview_url.'" target="_blank">Preview & Test</a>';
						self::$output['reason']			= '<span class="dashicons dashicons-saved"></span> ' . __('Form created successfully.');
						self::$output['formSaved']		= true;
						self::$output['savedFormID']	= $form_id;		
						self::$output['previewURL']		= $preview_url;
					}
					else {
						self::$output['status']			= 'fail';
						self::$output['reason']			= 'Updating Form ID field failed.';
					}
				}
			} // else
		}
		else {
			self::$output['status']			= 'fail';
			self::$output['reason']			= 'First drag & drop form input elements to create the form.';			
		}
		echo json_encode( self::$output );
		wp_die();
	}
	
	/**
	 * Sanitize created form data values
	 * return - sanitized original array data
	 */
	public static function sc_wordform_sanitize_created_form_data( &$form_data ) {
		foreach ( $form_data as $key => &$all_data ) {
			if ( isset( $all_data['multiOption'] ) ) {
				foreach ( $all_data['multiOption'] as $key => &$val ) {
					foreach ( $val as $key => &$option ) {
						$val[ $key ]	= sanitize_text_field( $option );
					}
				}
			}
			else {
				foreach ( $all_data as $key => &$val ) {
					$all_data[ $key ]	= sanitize_text_field( $val );
				}
			}			
		} // foreach
		
		return $form_data;
	}
	
	/**
	 * Allowed html tags
	 * return - array
	 */
	public static function sc_wordform_allowed_html_tags() {
		$allowed_tags	= array(
			'div' 		=> [ 'id' => [], 'class' => [], 'data-*' => true, 'wfd-invisible' => [], 'style' => [] ],
			'ul' 		=> [ 'id' => [], 'class' => [], 'style' => [] ],
			'input' 	=> [ 'id' => [], 'class' => [], 'type'  => [], 'name' => [], 'value'=> [], 'checked' => [], 'readonly' => [], 'disabled' => [], 'wfd-invisible' => [], 'placeholder' => [] ],
			'textarea'  => [ 'id' => [], 'class' => [], 'name'  => [], 'value'=> [], 'readonly' => [], 'disabled' => [], 'wfd-invisible' => [], 'rows' => [], 'cols' => [], 'placeholder' => [] ],
			'span' 		=> [ 'id' => [], 'class' => [], 'title' => [], 'style' => [] ],
			'button' 	=> [ 'id' => [], 'class' => [], 'type' => [], 'style' => [] ],
			'i' 		=> [ 'id' => [], 'class' => [], 'style' => [] ],
			'li' 		=> [ 'id' => [], 'class' => [], 'style' => [] ],
			'h4' 		=> [ 'id' => [], 'class' => [], 'style' => [] ],
			'strong' 	=> [ 'id' => [], 'class' => [], 'style' => [] ],
			'b' 		=> [ 'id' => [], 'class' => [] ],
			'table' 	=> [ 'id' => [], 'class' => [], 'style' => [] ],
			'thead' 	=> [ 'id' => [], 'class' => [], 'style' => [] ],
			'tbody' 	=> [ 'id' => [], 'class' => [], 'style' => [] ],
			'tr' 		=> [ 'id' => [], 'class' => [], 'style' => [] ],
			'td' 		=> [ 'id' => [], 'class' => [], 'style' => [] ],
			'form' 	    => [ 'id' => [], 'class' => [], 'name' => [], 'enctype' => [], 'method' => [], 'style' => [] ],
			'select'	=> [ 'id' => [], 'class' => [], 'name' => [], 'style' => [] ],
			'option'	=> [ 'id' => [], 'class' => [], 'value' => [], 'checked' => [], 'selected' => [], 'style' => [] ],
			'small'		=> [ 'id' => [], 'class' => [], 'style' => []  ],
			'hr'		=> [ 'id' => [], 'class' => [], 'style' => []  ],
			'br'		=> [ 'id' => [], 'class' => [], 'style' => []  ]
		  );
		return $allowed_tags;
	}
			
	/**
	 * Save built form data
	 * Save built form options data
	 */
	public static function sc_wordform_built_form_data_save() {
		global $wpdb;
		self::$output								= [];				
		$update_data								= [];
		//var_dump( $_POST );										
		
		$builtform_data								= isset( $_POST['params']['builtForm'] )? wp_kses( $_POST['params']['builtForm'], self::sc_wordform_allowed_html_tags() ) : '';
		$builtform_options_data						= isset( $_POST['params']['builtFormOptions'] )? wp_kses( $_POST['params']['builtFormOptions'], self::sc_wordform_allowed_html_tags() ) : '';
		//$builtform_data								= isset( $_POST['params']['builtForm'] )? $_POST['params']['builtForm'] : '';
		//$builtform_options_data						= isset( $_POST['params']['builtFormOptions'] )? $_POST['params']['builtFormOptions'] : '';
						
	    $builtform_html								= [ 'builtform_data' => $builtform_data, 'builtform_options_data' => $builtform_options_data ];	
		$builtform_html_json						= json_encode( $builtform_html, JSON_HEX_APOS );
		self::$output['builtFormData']				= $builtform_html_json;
		$wordform_id								= isset( $_POST['params']['formID'] )? sanitize_text_field( $_POST['params']['formID'] ) : '';
				
		if ( isset( $wordform_id ) && ! empty( $wordform_id ) ) {				
			$update_data['builtform_html_data']		=  $builtform_html_json;			
			$where_data['form_id']					=  $wordform_id;	
			$update_record							=  $wpdb->update(  $wpdb->prefix . SC_Wordform::$sc_wordform_tbl, $update_data, $where_data );	
			if ( $update_record ) {
				// If edited form html updated then also update block attached form html contents to render the edited form 				
			    $table								= $wpdb->prefix . SC_Wordform::$sc_wordform_tbl;
				$query_results						= $wpdb->get_results( $wpdb->prepare('SELECT form_id, attach_post_ids FROM %i WHERE form_id = %s LIMIT 1', [ $table, $wordform_id ] ), ARRAY_A );
				if ( array_filter($query_results ) && $query_results ) {
						$post_ids					= array_unique( (array) json_decode( $query_results[0]['attach_post_ids'], true ) );	
					    self::sc_wordform_block_editor_form_html_update( $post_ids, $wordform_id );
				}
				
				self::$output['status']				= 'success';
				self::$output['reason']				= 'Updated built form data successfully.';	
			} // if ( $update_record )
			else {
				self::$output['status']				= 'success';
				self::$output['reason']				= 'Nothing changes.';	
			}
		}
		else {
				self::$output['status']				= 'fail';
				self::$output['reason']				= 'Invalid word form ID.';				
		}
		
		echo json_encode( self::$output, JSON_HEX_APOS );		
		wp_die();					
	}
	
	/**
	* Update attached block html form content on edit created form data
	* Update attached block html form content on edit submit button attributes through settings - general tab
	* @since 1.0.0
	*/
	public static function sc_wordform_block_editor_form_html_update( $postids = [], $form_id = null ) {		
		foreach ( $postids as $postID ) {
			$post			= null;
			$post			= get_post( $postID );
			if ( ! is_null( $post ) && $post ) {				
				$allblocks		= [];  // Reste for multiple post IDs
				$blocks			= []; // Reset for multiple post IDs
				$blocks 		= parse_blocks( $post->post_content );				
				foreach( $blocks as $key => $block ) {
					if ( 'wordform-block/wordform' == $block['blockName'] )	 {

						$results						= SC_Wordform::sc_wordform_db_query( $form_id );												
						SC_BuildForm::$form_elements	= json_decode( $results[0]['form'], true );
						$created_form					= SC_BuildForm::sc_wordform_build_form_from_elements( $form_id );													
						$formdata						= '';
						foreach ( $created_form as $form ) {
							$formdata				   .= $form;
						}						
						$updated_formdata			    = '<div class="sc-wordform-block-editor-div-wrapper">' . $formdata . '</div>';							
						//var_dump( $formdata );
						//var_dump( $updated_formdata );
						$block['innerHTML']				=	$updated_formdata;
						if ( isset( $block['innerContent'][0] ) ) {
							$block['innerContent'][0]		=   $updated_formdata;	
						}
						//var_dump($postID);
						//var_dump( $block['innerHTML'] );
						//var_dump( $block['innerContent'][0] );	
						$allblocks[]				= $block;
						//$allblocks[]	= NULL; // work
					}
					else {
						$allblocks[]	= $block;
					}
				} // foreach

				$content 	 = serialize_blocks($allblocks);
				$updatedPost = array(
					'ID'            => $postID,
					'post_content'  => $content
				);
				 if ( wp_update_post( $updatedPost ) ) {
					 self::$output[ $form_id ]['blockFormDataUpdated']	= true;
				 }
				 else {
					 self::$output[ $form_id ]['blockFormDataUpdated']	= false;
				 }
			}
		} // foreach
		
	}
	
	/**
	* When delete form permanently
	* Delete attached block html form content also
	* @since 1.0.0
	*/
	public static function sc_wordform_block_editor_form_html_delete( $postids = [], $form_id = null ) {		
		foreach ( $postids as $postID ) {
			$post			= null;
			$post			= get_post( $postID );
			if ( ! is_null( $post ) && $post ) {				
				$allblocks		= [];  // Reste for multiple post IDs
				$blocks			= []; // Reset for multiple post IDs
				$blocks 		= parse_blocks( $post->post_content );				
				foreach( $blocks as $key => $block ) {
					if ( 'wordform-block/wordform' == $block['blockName'] )	 {
						$allblocks[]	= NULL;
					}
					else {
						$allblocks[]	= $block;
					}
				} // foreach

				$content 	 = serialize_blocks($allblocks);
				$updatedPost = array(
					'ID'            => $postID,
					'post_content'  => $content
				);
				 if ( wp_update_post( $updatedPost ) ) {
					 self::$output['BlockAttachedFormDeleted']	= true;
				 }
				 else {
					 self::$output['BlockAttachedFormDeleted']	= false;
				 }
			}
		} // foreach
		
	}
	
	
	/**
	 * Front end form submission by users
	 * Sanitize - validate data
	 * Store form submission data
	 * @since 1.0.0
	 */	
	public static function sc_wordform_created_form_submission() {
		//check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		global $wpdb;
		self::$output						= [];				
		$insert_data						= [];		
		parse_str( $_POST['scWordFormPostData'], $params );		
		$wordform_id						= isset( $_POST['WordFormID'] )? sanitize_text_field( $_POST['WordFormID'] ) : '';
		$wordform_name						= isset( $_POST['WordFormName'] )? sanitize_text_field(  $_POST['WordFormName'] ) : '';
		
		
		if ( isset( $wordform_id ) && ! empty( $wordform_id ) && isset( $params['wordform'][ $wordform_id ] ) ) {
			//print_r( $params['wordform'][ $wordform_id ] );
			
			// Sanitize submission data
			$submission_sanitized_form_data	= self::sc_wordform_front_end_submission_data_sanitize( $params['wordform'][ $wordform_id ] );	
			//print_r( $submission_sanitized_form_data );			
			
			SC_Wordform_FormValidation::$sc_wordform_validation_data	= [];			
			SC_Wordform_FormValidation::$wordform_id					= $wordform_id;  
			SC_Wordform_FormValidation::sc_wordform_set_validation_messages();
			
			// Validate submission data			
			foreach ( $submission_sanitized_form_data as $input_type => $input_data ) {
				switch ( $input_type ) {
					case 'text':
						SC_Wordform_FormValidation::text_validation( $input_data );
						break;
					case 'number':
						SC_Wordform_FormValidation::number_validation( $input_data );
						break;
					case 'textarea':
						SC_Wordform_FormValidation::textarea_validation( $input_data );
						break;
					case 'radio':
						SC_Wordform_FormValidation::radio_validation( $input_data );
						break;
					case 'checkbox':
						SC_Wordform_FormValidation::checkbox_validation( $input_data );
						break;
					case 'select':
						SC_Wordform_FormValidation::select_validation( $input_data );
						break;						
				} // switch
			} // foreach
			
			//print_r( SC_Wordform_FormValidation::$sc_wordform_validation_data );
			$validation_error_data							= [];
			$validation_error_data							= array_filter( SC_Wordform_FormValidation::$sc_wordform_validation_data , function( $validation_data ) { return ( $validation_data['validationStatus'] == 'error' ); });
			
			
			
			if ( array_filter( $validation_error_data ) ) {
				self::$output['status']						= 'fail';
				self::$output['failMsg']					= '<div class="sc-wordform-submisson-fail-msg-div-wrapper">' . __('Input validation error.') . '</div>';	
				self::$output['validationAllData']			=  SC_Wordform_FormValidation::$sc_wordform_validation_data;
				self::$output['validationErrorData']		=  $validation_error_data;
			}
			else {			    				
				$wordform_submission_data_json				= json_encode( $params['wordform'][ $wordform_id ], JSON_HEX_APOS );
				$insert_data['form_name']					= $wordform_name;
				$insert_data['form_id']						= $wordform_id;
				$insert_data['submission_data']				= $wordform_submission_data_json;	
				$submission_user_info_arr					= self::sc_wordform_form_submission_users_data();
				$insert_data['submission_user_data']		= json_encode( $submission_user_info_arr, JSON_HEX_APOS );								
				$insert_record								= $wpdb->insert( $wpdb->prefix . SC_Wordform::$sc_wordform_submission_tbl, $insert_data );			
				if ( isset($insert_record) && $insert_record ) {					
					self::$output['status']					= 'success';
					self::$output['insertSubmissionData']	= true;
					//self::$output['successMsg']				= '<div class="sc-wordform-submission-success-msg-div-wrapper">We have received your message.</div>';	
					self::$output['successMsg']				= '<div class="sc-wordform-submission-success-msg-div-wrapper">' . SC_Wordform_FormValidation::$validation_form_submission_success_message . '</div>';	
					
					// Send Email if true - on form submission
					SC_Wordform::sc_wordform_send_email_on_form_submission_by_users( $insert_data );
				}
				else {
					self::$output['status']					= 'fail';
					self::$output['insertSubmissionData']	= false;
					self::$output['failMsg']				= '<div class="sc-wordform-submisson-fail-msg-div-wrapper">'. __('Something went wrong, please try again.') .'</div>';	
				}
			}
		}
		
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();			
	}
	
    /**
	 * Sanitize users submission data
	 * modify reference submission data to be sanitized
	 */
	public static function sc_wordform_front_end_submission_data_sanitize( &$submission_form_data ) {
		//print_r( $submission_form_data );
		//exit();
		
		if ( isset( $submission_form_data ) && array_filter( $submission_form_data ) ) {
			foreach ( $submission_form_data as $input_type => &$input_data ) {
				switch ( $input_type ) {
					case 'text':						
							foreach ( $input_data as $key => &$input ) {
								if ( isset( $input['values'] ) && array_filter( $input['values'] ) ) {
									$input['values']	  = array_map( function( $data ) { return sanitize_text_field( $data ); }, $input['values'] );								
								}								
							}						
						break;
					case 'number':
							foreach ( $input_data as $key => &$input ) {
								if ( isset( $input['values'] ) && array_filter( $input['values'] ) ) {
									$input['values']	  = array_map( function( $data ) { return sanitize_text_field( $data ); }, $input['values'] );								
								}								
							}						
						break;
					case 'textarea':
							foreach ( $input_data as $key => &$input ) {
								if ( isset( $input['values'] ) && array_filter( $input['values'] ) ) {
									$input['values']	  = array_map( function( $data ) { return sanitize_textarea_field( $data ); }, $input['values'] );								
								}								
							}						
						break;
					case 'radio':
							foreach ( $input_data as $key => &$input ) {
								if ( isset( $input['values'] ) && array_filter( $input['values'] ) ) {
									$input['values']	  = array_map( function( $data ) { return sanitize_text_field( $data ); }, $input['values'] );								
								}								
							}						
						break;
					case 'checkbox':
							foreach ( $input_data as $key => &$input ) {
								if ( isset( $input['values'] ) && array_filter( $input['values'] ) ) {
									$input['values']	  = array_map( function( $data ) { return sanitize_text_field( $data ); }, $input['values'] );								
								}								
							}						
						break;
					case 'select':
							foreach ( $input_data as $key => &$input ) {
								if ( isset( $input['values'] ) && array_filter( $input['values'] ) ) {
									$input['values']	  = array_map( function( $data ) { return sanitize_text_field( $data ); }, $input['values'] );								
								}								
							}						
						break;						
				} // switch				
			} // foreach
		}
		return $submission_form_data;		
	}
		
	/**
	 * Collect visited user information data
	 * return - array
	 * since 1.0.0
	 *
	 */
	public static function sc_wordform_form_submission_users_data() {
		$user_visited_all_info			= $_SERVER;		
		$user_visited_target_info		= [];
		$target_keys					= [ 'HTTP_HOST', 'HTTP_USER_AGENT', 'HTTP_REFERER', 'SERVER_NAME', 'SERVER_ADDR', 'REMOTE_ADDR'];
		foreach ( $target_keys as $key ) {
			if ( in_array( $key, [ 'HTTP_REFERER', 'SERVER_NAME' ] ) ) {
				$user_visited_target_info[ $key ]	= sanitize_url( $user_visited_all_info[ $key ] );
			}
			else {	
				$user_visited_target_info[ $key ]	= sanitize_text_field( $user_visited_all_info[ $key ] );
			}
		}
		return $user_visited_target_info;
	}
	
				
	/**
	 * Users Submission form data load & display
	 * Request from Ajax datatable
	 * @since 1.0.0
	 * return - json
	 */
	public static function sc_wordform_users_submission_data_load() {
		//var_dump( $_POST );
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		global $wpdb;
		$table								= $wpdb->prefix . SC_Wordform::$sc_wordform_submission_tbl;		
		$query_results						= $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i ORDER BY id DESC', $table ), ARRAY_A );
		if ( $query_results ) {			
			$output_data					= [];
			foreach ($query_results as $data ) {
				$temp						= [];
				
				$temp['formName']			= sanitize_text_field( $data['form_name'] );
				//$temp['formID']			= sanitize_text_field( $data['form_id'] );
				
				SC_Wordform_FormSubmission::$sc_wordform_submission_array_data = json_decode( $data['submission_data'], true );								
				$temp['submissionData']		= SC_Wordform_FormSubmission::sc_wordform_process_submission_data();;
				
				$temp['date']				=  sanitize_text_field( $data['created_at'] );
				
				$output_data['data'][]		= $temp;
			}
		}
		echo json_encode( $output_data, JSON_HEX_APOS );
		wp_die();
	}
	
	
	/**
	 * Settings Menu - Validation messages data store
	 * @since 1.0.0
	 * return - json
	 */	
	public static function sc_wordform_settings_menu_validation_tab_data_save() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		self::$output							= [];		
		parse_str( $_POST['formData'], $params );
		
		if ( isset( $params ) && array_filter( $params ) ) {
			foreach ( $params as $key => $val ) {
				$params[ $key ] = sanitize_text_field( $val );
			}
			
			global $wpdb;
			$table								= $wpdb->prefix . SC_Wordform::$sc_wordform_validation_messages_tbl;		
			
			$validation_messages_data			= json_encode( $params, JSON_HEX_APOS );
			$form_id							= isset( $params['sc-wordform-validation-tab-selected-form-id'] )? sanitize_text_field( $params['sc-wordform-validation-tab-selected-form-id'] ) : '';
			$insert_data['form_name']			= isset( $params['sc-wordform-validation-tab-selected-form-name'] )? sanitize_text_field( $params['sc-wordform-validation-tab-selected-form-name'] ) : '';
			$insert_data['form_id']				= $form_id;
			$insert_data['validation_messages'] = $validation_messages_data;
			$query_results						= SC_Wordform::sc_wordform_get_validation_messages_data_by_formid( $form_id );
			// Update validation messages - if already exist
			if ( $query_results && array_filter( $query_results ) ) {
				$update_data['validation_messages']		= $validation_messages_data;
				$where_data['form_id']					= $form_id;
				$update_record							= $wpdb->update( $table, $update_data, $where_data );	
				if ( $update_record ) {
					self::$output['status']				= 'success';
					self::$output['Msg']				= __('Updated success.');
					self::$output['comment']			= 'Validation messages data updated for form ID: ' . $form_id;
				}
				else {
					self::$output['status']				= 'success';
					self::$output['Msg']				= __('Updated success.');
					self::$output['comment']			= 'Validation messages data not changes for form ID: ' . $form_id;
				}
			}
			// Insert
			else {
				$insert_record							= $wpdb->insert( $table, $insert_data );
				if ( $insert_record ) {
					self::$output['status']				= 'success';
					self::$output['Msg']				= __('Saved success.');
					self::$output['comment']			= 'Validation messages data inserted successfully.';
				}
				else {
					self::$output['status']				= 'fail';
					self::$output['Msg']				= __('Saved fail.');
					self::$output['comment']			= 'Validation messages data inserted fail.';					
				}
			}
						
		} // if ( isset( $params ) && array_filter( $params ) )
		else {
			self::$output['status']		= 'fail';
			self::$output['Msg']		= __('Saved fail.');
			self::$output['comment']	= 'Something went wrong.';
		}
		
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();
	}
	
	/**
	 * Settings menu : Validation Tab
	 * Selected form validation messages save
	 * @since 1.0.0
	 * return - JSON
	 */
	public static function sc_wordform_settings_menu_validation_tab_selected_form_data_save() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		self::$output							= [];
		//var_dump( $_POST );
		//var_dump( $_POST['params'] );	
		$form_name						        = isset(  $_POST['params']['FormName'] ) && ! empty( $_POST['params']['FormName'] )? sanitize_text_field( $_POST['params']['FormName'] ) : '';
		$form_id						        = isset(  $_POST['params']['FormID'] ) && ! empty( $_POST['params']['FormID'] )? sanitize_text_field( $_POST['params']['FormID'] ) : '';
		self::$output['selectedFormName']		= $form_name;
		self::$output['selectedFormID']			= $form_id;
		
		if ( $form_id ) {			
			$validation_messages    			= SC_Wordform::sc_wordform_get_validation_messages_data_by_formid( $form_id );
			if ( $validation_messages ) {
				self::$output['status']			= 'success';
				ob_start();
				include_once SCWORDFORM_PLUGIN_DIR . 'admin/views/add_scwordform_submenus_settings_callback_validation_tab_page_template.php';
				self::$output['htmlTemplate']	= ob_get_clean();
			}
			// If validation messages yet not saved for the selected form then show the default validation messages
			else {
				$validation_messages[0]['validation_messages'] = json_encode([]);
				ob_start();
				include_once SCWORDFORM_PLUGIN_DIR . 'admin/views/add_scwordform_submenus_settings_callback_validation_tab_page_template.php';
				self::$output['htmlTemplate']	= ob_get_clean();				
				self::$output['status']			= 'success';
				self::$output['comment']		= 'Validation messages not yet set for the selected form ID.';
			}
		}
		else {
			self::$output['status']				= 'fail';
			self::$output['comment']			= 'Valid Form ID missing.';
		}
		
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();
	}
	
	/**
	 * All Forms : Delete form
	 * Delete also added form from posts - pages
	 */
	public static function sc_wordform_all_forms_page_delete_form() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		self::$output							= [];
		//var_dump( $_POST );		
		$wordform_id							= isset( $_POST['params']['wordformID'] ) && ! empty( $_POST['params']['wordformID'] )? sanitize_text_field( $_POST['params']['wordformID'] ) : '';
		$form_name								= isset( $_POST['params']['wordformName'] ) && ! empty( $_POST['params']['wordformName'] )? sanitize_text_field( $_POST['params']['wordformName'] ) : '';
		
		if ( $wordform_id ) {
			global $wpdb;			
			// Check form is attached with posts - pages through block editor
			$table										= $wpdb->prefix . SC_Wordform::$sc_wordform_tbl;
			$query_results								= $wpdb->get_results( $wpdb->prepare('SELECT form_id, attach_post_ids FROM %i WHERE form_id = %s LIMIT 1', [ $table, $wordform_id ] ), ARRAY_A );
			// When form is attached with posts - pages through block editor
			if ( $query_results && isset(  $query_results[0]['attach_post_ids'] ) && ! empty(  $query_results[0]['attach_post_ids'] ) ) {
				    // If form deleted then also delete block attached form html contents 
					$post_ids							= array_unique( (array) json_decode( $query_results[0]['attach_post_ids'], true ) );	
					self::sc_wordform_block_editor_form_html_delete( $post_ids, $wordform_id );
				    if ( self::$output['BlockAttachedFormDeleted'] ) {						
    					$delete_record					= $wpdb->delete( $table, array( 'form_id' => $wordform_id ) );
						if ( $delete_record ) {
							self::$output['status']		= 'success';
							self::$output['comment']	= $wordform_id . ' deleted successfully. Form was attached with posts or pages.';
						}
						else {
							self::$output['status']		= 'fail';
							self::$output['comment']	= 'Attached form ' . $wordform_id . ' deleted fail.';							
						}
					}
				    else {
					   self::$output['status']			= 'fail';	
					   self::$output['comment']			= 'Block attached form content failed to delete properly.';	
					}
			}
			// When form is NOT attaached - simple delete it
			else {
				$delete_record							= $wpdb->delete( $table, array( 'form_id' => $wordform_id ) );
				if ( $delete_record ) {
					self::$output['status']				= 'success';
					self::$output['comment']			= $wordform_id . ' deleted successfully. Form did not have attached posts or pages.';
				}
				else {
					self::$output['status']				= 'fail';
					self::$output['comment']			= $wordform_id . ' deleted fail. Form was not attached.';							
				}				
			}
		}
		else {
			self::$output['status']				= 'fail';
			self::$output['comment']			= 'Valid Form ID missing.';
		}
	
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();
	}
	
	/**
	 * Setting Menu : General Tab
	 * Save General Tab Form data
	 */
	public static function sc_wordform_settings_general_tab_form() {
		check_ajax_referer( 'scwordform_wpnonce', 'security' );	
		self::$output								= [];		
		parse_str( $_POST['params']['formData'], $params );	
		//print_r($params);
		//exit();
		// Submit Button Background Color
		if ( isset( $params['sc-wordform-form-submit-button-background-color'] ) && ! empty( $params['sc-wordform-form-submit-button-background-color'] ) ) {
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $params['sc-wordform-form-submit-button-background-color'] ) ) {
				$submit_background_hex_color		= $params['sc-wordform-form-submit-button-background-color'];
			}
			else {
				self::$output['status']				= 'fail';
				self::$output['comment']			= 'Background HEX Color code required.';
				self::$output['failMsg']			= __('Valid background hex color required.');
			}
		}
		// Submit Button Background Hover Color
		if ( isset( $params['sc-wordform-form-submit-button-background-hover-color'] ) && ! empty( $params['sc-wordform-form-submit-button-background-hover-color'] ) ) {
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $params['sc-wordform-form-submit-button-background-hover-color'] ) ) {
				$submit_background_hover_hex_color	= $params['sc-wordform-form-submit-button-background-hover-color'];
			}
			else {
				self::$output['status']				= 'fail';
				self::$output['comment']			= 'Background HEX Hover Color code required.';
				self::$output['failMsg']			= __('Valid background hex hover color required.');
			}
		}
		
		// Submit Button Font Color
		if ( isset( $params['sc-wordform-form-submit-button-font-color'] ) && ! empty( $params['sc-wordform-form-submit-button-font-color'] ) ) {
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $params['sc-wordform-form-submit-button-font-color'] ) ) {
				$submit_button_font_hex_color		= $params['sc-wordform-form-submit-button-font-color'];
			}
			else {
				self::$output['status']				= 'fail';
				self::$output['comment']			= 'Submit Button Font HEX Color code required.';
				self::$output['failMsg']			= __('Valid font hex color required.');
			}
		}
		
		// Submit Button Font Weight
		if ( isset( $params['sc-wordform-form-submit-button-font-weight'] ) && ! empty( $params['sc-wordform-form-submit-button-font-weight'] ) ) {			
				$submit_button_font_weight		   = sanitize_text_field( $params['sc-wordform-form-submit-button-font-weight'] );			
		}
		else {
				self::$output['status']				= 'fail';
				self::$output['comment']			= 'Submit Button Font weight value required.';
				self::$output['failMsg']			= __('Check font-weight value.');
		}
		
		
				
		// Submit Button Font size
		if ( isset( $params['sc-wordform-form-submit-button-text-size'] ) && ! empty( $params['sc-wordform-form-submit-button-text-size'] ) ) {
			$submit_button_font_size				= sanitize_text_field( $params['sc-wordform-form-submit-button-text-size'] );
		}
		else {
			$submit_button_font_size				= 16;
		}
		
		// Submit Button Padding (Top-Bottom)
		if ( isset( $params['sc-wordform-form-submit-button-padding-top-bottom'] ) && ! empty( $params['sc-wordform-form-submit-button-padding-top-bottom'] ) ) {
			$submit_button_padding_top_bottom	    = sanitize_text_field( $params['sc-wordform-form-submit-button-padding-top-bottom'] );
		}
		else {
			$submit_button_padding_top_bottom		= 8;
		}
		
		// Submit Button Padding (Left-Right)
		if ( isset( $params['sc-wordform-form-submit-button-padding-left-right'] ) && ! empty( $params['sc-wordform-form-submit-button-padding-left-right'] ) ) {
			$submit_button_padding_left_right		= sanitize_text_field( $params['sc-wordform-form-submit-button-padding-left-right'] );
		}
		else {
			$submit_button_padding_left_right		= 25;
		}
		
		
		// Send Email
		if ( isset( $params['sc-wordform-form-submission-send-email'] ) ) {
			$form_submit_send_email					= 'yes';		
		}
		else {
			$form_submit_send_email					= 'no';		
		}
		
		// If no error		
		if ( ! isset( self::$output['status'] ) || self::$output['status'] != 'fail' )	{
			$general_tab_options					= [ 'submit_button_background_color' 			=> $submit_background_hex_color,
													    'submit_button_background_hover_color'		=> $submit_background_hover_hex_color,
													    'submit_button_font_color'					=> $submit_button_font_hex_color,
													    'submit_button_font_size'					=> $submit_button_font_size,
													    'submit_button_padding_top_bottom'          => $submit_button_padding_top_bottom,
													    'submit_button_padding_left_right'          => $submit_button_padding_left_right,
													    'submit_button_font_weight'					=> $submit_button_font_weight, 
													    'send_email_on_form_submission'				=> $form_submit_send_email
													  ];
			if ( update_option( 'sc_wordform_settings_menu_general_tab_info', $general_tab_options ) ) {
				self::$output['status']					= 'success';
				self::$output['comment']				= 'Hex Colors are valid.';
				self::$output['successMsg']				= __('Updated success');
				
				// If edited form submit button attributes then also update block attached form html contents to render the edited all attached form attributes data  				
				global $wpdb;
			    $table								= $wpdb->prefix . SC_Wordform::$sc_wordform_tbl;
				$query_results						= $wpdb->get_results( $wpdb->prepare( 'SELECT form_id, attach_post_ids FROM %i WHERE `attach_post_ids` IS NOT NULL', $table ), ARRAY_A );				
				if ( array_filter($query_results ) && $query_results ) {
					foreach ( $query_results as $data ) {
						$post_ids					= array_unique( (array) json_decode( $data['attach_post_ids'], true ) );	
					    $wordform_id				= isset( $data['form_id'] )? sanitize_text_field( $data['form_id'] ) : null;
					    self::sc_wordform_block_editor_form_html_update( $post_ids, $wordform_id );					    					    
					}
				}								
			}
			else {
				self::$output['status']					= 'success';
				self::$output['comment']				= 'Nothing changes.';
				self::$output['successMsg']				= __('Nothing changes');
 			}				
		}
		
		
		echo json_encode( self::$output, JSON_HEX_APOS );
		wp_die();		
	}
	
} // End Class
?>