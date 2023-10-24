<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SC_Wordform {
	private static $initiated 								= false;
	public  static $sc_wordform_tbl							= 'scwordform_created_forms';
	public  static $sc_wordform_submission_tbl				= 'scwordform_submisson_forms_data';
	public  static $sc_wordform_validation_messages_tbl		= 'scwordform_validation_messages_data';
	
	public function __construct() {
		if ( ! self::$initiated ) {
			self::initiate_hooks();
		}
	}
	
	/**
	 * Init Hooks 
	 */
	private static function initiate_hooks() {			    					    
		add_action( 'init', [ __CLASS__, 'sc_wordform_create_block_init' ] );	
		add_action( 'admin_menu', array( __CLASS__, 'add_scwordform_submenus' ) );				
		
		add_action( 'admin_notices', array( __CLASS__, 'scwordform_admin_notices' ) );		
		add_action( 'plugins_loaded', array( __CLASS__, 'scwordform_load_textdomain') );
		//add_filter( 'plugin_row_meta',     array( __CLASS__, 'scwordform_row_link'), 10, 2 );				
		
		add_filter( 'template_include', array( __CLASS__, 'sc_wordform_check_preview_page' ), 10 , 1 );		
						
		self::$initiated = true;
	}
			
	/**
	 * Activate required dependency
	 */
	public static function activate() {
		self::check_preactivation_requirements();
		self::sc_wordform_create_block_init();
		self::sc_wordform_db_tables_install();
		flush_rewrite_rules( true );		
	}
	
	/**
	 * Check pre-activate requirements
	 */
	public static function check_preactivation_requirements() {				
		if ( version_compare( PHP_VERSION, SCWORDFORM_MINIMUM_PHP_VERSION, '<' ) ) {
			wp_die('Minimum PHP Version required: ' . SCWORDFORM_MINIMUM_PHP_VERSION );
		}
        global $wp_version;
		if ( version_compare( $wp_version, SCWORDFORM_MINIMUM_WP_VERSION, '<' ) ) {
			wp_die('Minimum Wordpress Version required: ' . SCWORDFORM_MINIMUM_WP_VERSION );
		}
	}
	
	public static function scwordform_load_textdomain() {
		load_plugin_textdomain( 'wordform-drag-drop-forms-builder', false, SCWORDFORM_PLUGIN_DIR . 'languages/' ); 
	}
		
	public static function sc_wordform_create_block_init() {								
		register_block_type( SCWORDFORM_PLUGIN_DIR . 'admin/block/build', [] );						
	}
	
	/**
	 * Get alll created from list
	 * display as select dropdown
	 * Block editor select form name dropdown
	 * return - array
	 */
	public static function sc_wordform_get_all_forms_for_block_dynamic_callback() {
		global $wpdb;
		$table					= $wpdb->prefix . self::$sc_wordform_tbl;
		$query_results			= $wpdb->get_results( $wpdb->prepare('SELECT id,form_id,form_name FROM %i ORDER BY id DESC', $table ), ARRAY_A );			
		
		$options				= [];
		foreach ( $query_results as $data ) {
			$temp				= [];
			$temp['formName']	= sanitize_text_field( $data['form_name'] );
			$temp['formID']		= sanitize_text_field( $data['form_id'] );
			$temp['ID']		    = sanitize_text_field( $data['id'] );
			$options[]		    = $temp;
		}
		return $options;
	}
	
	/**
	 * Check Post ID is attached with any wordform
	 * If attached find the form ID
	 */
	public static function sc_wordform_check_if_wordform_is_attached_with_post( $postID = null ) {
		global $wpdb;
		$table					= $wpdb->prefix . self::$sc_wordform_tbl;
		$query_results			= $wpdb->get_results( $wpdb->prepare('SELECT id, form_id, attach_post_ids FROM %i WHERE attach_post_ids IS NOT NULL', $table ), ARRAY_A );					
		$wordform_id			= [];
		if ( $query_results && array_filter( $query_results ) ) {
			foreach ( $query_results as $data ) {
				$post_ids				= array_unique( json_decode( $data['attach_post_ids'], true ) ); 
				if ( in_array( $postID, $post_ids ) ) {
					$wordform_id[]		= sanitize_text_field( $data['form_id'] );
				}
			}
		}
		return $wordform_id;
	}

	/**
	 * Check previous Form ID attached Post ID
	 * Remove attached post Id from the previous Form ID
	 */
	public static function sc_wordform_remove_attached_post_ids_of_previous_form_id( $previous_form_id = null, $previous_attached_post_ids = [] ) {
		global $wpdb;
		$updated_status			= null;
		$table					= $wpdb->prefix . self::$sc_wordform_tbl;
		$query_results			= $wpdb->get_results( $wpdb->prepare('SELECT id, form_id, attach_post_ids FROM %i WHERE form_id = %s LIMIT 1', [ $table, $previous_form_id ] ), ARRAY_A );	
		if ( isset( $query_results[0] ) && array_filter( $query_results[0])  ) {			
				$post_ids				= ! empty( $query_results[0]['attach_post_ids'] )? json_decode( $query_results[0]['attach_post_ids'], true ) : []; 
				$updated_post_ids		= array_diff( $post_ids, $previous_attached_post_ids );	
				$update_data['attach_post_ids']		= array_filter($updated_post_ids)? wp_json_encode( $updated_post_ids ) : NULL;
				$where_data['form_id']				= $previous_form_id;
				// Update Previous form ID data
				$updated_status						= $wpdb->update( $table, $update_data, $where_data );
			
		}
		return $updated_status;
	}
	

	/**
	 * Create Tables
	 * @since 1.0.0
	 */
	public static function sc_wordform_db_tables_install() {
		global $wpdb;		
		$wordform_created_form_table_name 				= $wpdb->prefix . self::$sc_wordform_tbl;				
		$wordform_submission_form_table_name 			= $wpdb->prefix . self::$sc_wordform_submission_tbl;	
		$wordform_validation_messages_table_name		= $wpdb->prefix . self::$sc_wordform_validation_messages_tbl;
		$charset_collate 								= $wpdb->get_charset_collate();

		// wordform created forms Table
		$wordform_created_forms_tbl_sql = "CREATE TABLE $wordform_created_form_table_name (
			id         				INT(11) NOT NULL AUTO_INCREMENT,	
			form_name  				CHAR(100) DEFAULT '' NOT NULL,
			form_id    				CHAR(100) DEFAULT '' NOT NULL,
			form       				TEXT DEFAULT NULL,			
			builtform_html_data  	LONGTEXT DEFAULT NULL,
			attach_post_ids  		TEXT DEFAULT NULL,
			
			created_at 				DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,						
			updated_at 				DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";
		
		// wordform submission forms data Table
		$wordform_submission_forms_tbl_sql = "CREATE TABLE $wordform_submission_form_table_name (
			id         				INT(11) NOT NULL AUTO_INCREMENT,									
			form_name  				CHAR(100) DEFAULT '' NOT NULL,
			form_id    		 		CHAR(100) DEFAULT '' NOT NULL,
			submission_data  		TEXT DEFAULT NULL,
			submission_user_data  	TEXT DEFAULT NULL,
			created_at 				DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,						
			updated_at 				DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";
		
		
		// wordform validation messages data Table		
		$wordform_validation_messages_tbl_sql = "CREATE TABLE $wordform_validation_messages_table_name (
			id         				INT(11) NOT NULL AUTO_INCREMENT,									
			form_name  				CHAR(100) DEFAULT '' NOT NULL,
			form_id    		 		CHAR(100) DEFAULT '' NOT NULL,
			validation_messages  	TEXT DEFAULT NULL,			
			created_at 				DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,						
			updated_at 				DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";
				
		
		
		
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		//dbDelta( $sql );		
		maybe_create_table( $wordform_created_form_table_name, $wordform_created_forms_tbl_sql );		
		maybe_create_table( $wordform_submission_form_table_name, $wordform_submission_forms_tbl_sql );		
		maybe_create_table( $wordform_validation_messages_table_name, $wordform_validation_messages_tbl_sql );		
	}	
	
	/**
	 * filter hook
	 * Created form display - preview & test
	 * return - template path
	 */
	public static function sc_wordform_check_preview_page( $template) {
				
		if ( isset( $_GET['sc-wordform-id'] ) && ! empty( $_GET['sc-wordform-id'] ) ) {
			global $created_form;
			global $sc_wordform_id;
			$form_id						= sanitize_text_field( $_GET['sc-wordform-id'] );	
			$sc_wordform_id				    = $form_id;
			$results						= self::sc_wordform_db_query( $form_id );						
			SC_BuildForm::$form_elements	= json_decode( $results[0]['form'], true );
			$created_form					= SC_BuildForm::sc_wordform_build_form_from_elements( $form_id );
			$template						= SCWORDFORM_PLUGIN_DIR . 'admin/views/sc-wordform-preview-page.php';
		}
		return $template;		
	}
	
	/**
	* Get created form  data by form_id
	* param - $form_id
	* return - array
	*/
	public static function sc_wordform_db_query( $form_id	= null ) {
		global $wpdb;
		$table				= $wpdb->prefix . self::$sc_wordform_tbl;
		$query_results		= $wpdb->get_results( $wpdb->prepare('SELECT * FROM %i WHERE form_id = %s LIMIT 1', [ $table, $form_id ] ), ARRAY_A );
		return $query_results;
	}
			
	/**
	 * Add admin menus
	 */
	public static function add_scwordform_submenus() {
		
		// Top Menu|Parent Menu - WordForm
		add_menu_page( __( 'WordForm', 'wordform-drag-drop-forms-builder' ), 'WordForm', 'manage_options', 'word-form-topmenu', '', 'dashicons-forms', 6 );
										  
		// Submenu - All Forms - sc-wordform-all-forms page slug 
		add_submenu_page(
		    'word-form-topmenu',
        __( 'WordForm - All Forms', 'wordform-drag-drop-forms-builder' ),
        __( 'All Forms', 'wordform-drag-drop-forms-builder' ),
            'manage_options',
            'word-form-topmenu',
			array( __CLASS__, 'add_scwordform_submenus_allforms_callback' )        
          );
		
		// Submenu - Create Forms - sc-wordform-create-forms page slug  
		add_submenu_page(
		    'word-form-topmenu',
        __( 'WordForm - Create Forms', 'wordform-drag-drop-forms-builder' ),
        __( 'Create Form', 'wordform-drag-drop-forms-builder' ),
            'manage_options',
            'sc-wordform-create-forms',
			array( __CLASS__, 'add_scwordform_submenus_create_forms_callback' )        
          );

		// Submenu - Submission data - sc-wordform-user-submission-data page slug
		add_submenu_page(
		    'word-form-topmenu',
        __( 'WordForm - Submission Form Data', 'wordform-drag-drop-forms-builder' ),
        __( 'Submission Data', 'wordform-drag-drop-forms-builder' ),
            'manage_options',
            'sc-wordform-user-submission-data',
			array( __CLASS__, 'add_scwordform_submenus_user_submission_data_callback' )        
          );

		// Submenu - Settings - sc-wordform-settings page slug
		add_submenu_page(
		    'word-form-topmenu',
        __( 'WordForm -Settings', 'wordform-drag-drop-forms-builder' ),
        __( 'Settings', 'wordform-drag-drop-forms-builder' ),
            'manage_options',
            'sc-wordform-settings',
			array( __CLASS__, 'add_scwordform_submenus_settings_callback' )        
          );
				
		
	}	
								
	/**
	 * All Froms: Callback function of all forms
	 * Display all created forms
	 */
	public static function add_scwordform_submenus_allforms_callback() {
		// check user capabilities
		if ( !current_user_can('manage_options' ) ) {
			return;
		}		
		$query_results			= self::sc_wordform_get_all_created_forms_data();
		include_once SCWORDFORM_PLUGIN_DIR . 'admin/views/add_scwordform_submenus_allforms_callback_page.php';		
	}
	
	/**
	 * Create Form: Create form page menu callback function 
	 * Check if edit page - if not then create form page
	 */
	public static function add_scwordform_submenus_create_forms_callback() {
		// check user capabilities
		if ( !current_user_can('manage_options' ) ) {
			return;
		}		
		if ( isset( $_GET['wordform-edit-id'] ) && ! empty( $_GET['wordform-edit-id']) ) {
			$wordform_id		= isset( $_GET['wordform-edit-id'] ) && ! empty( $_GET['wordform-edit-id'] )? sanitize_text_field( $_GET['wordform-edit-id'] ) : '';
			global $wpdb;
			$table				= $wpdb->prefix . self::$sc_wordform_tbl;		
			$query_results		= $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i WHERE form_id=%s ORDER BY id DESC', [ $table, $wordform_id ] ), ARRAY_A );									
		}
		include_once SCWORDFORM_PLUGIN_DIR . 'admin/views/add_scwordform_submenus_create_forms_callback_page.php';		
	}

	/**
	 * Submission Data: users submission data menu callback function
	 * Table data loaded by ajax - Datatable
	 */
	public static function add_scwordform_submenus_user_submission_data_callback() {
		// check user capabilities
		if ( !current_user_can('manage_options' ) ) {
			return;
		}				
		include_once SCWORDFORM_PLUGIN_DIR . 'admin/views/add_scwordform_submenus_user_submission_data_callback_page.php';		
	}
		   
	/**
	 * Settings : validation Tab
	 * Get default validation messages for all forms initially
	 */
	public static function add_scwordform_submenus_settings_callback() {
		// check user capabilities
		if ( !current_user_can('manage_options' ) ) {
			return;
		}		
		$all_created_forms		= self::sc_wordform_get_all_created_forms_data();
		$validation_messages    = self::sc_wordform_get_validation_messages_data_by_formid('all_form');
		include_once SCWORDFORM_PLUGIN_DIR . 'admin/views/add_scwordform_submenus_settings_callback_page.php';		
	}

	
	/**
	 * Get all created forms data
	 * @since 1.0.0
	 * return - array - All created forms data
	 */	
	public static function sc_wordform_get_all_created_forms_data() {
		global $wpdb;
		$table				= $wpdb->prefix . self::$sc_wordform_tbl;		
		$query_results		= $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i ORDER BY id DESC', $table ), ARRAY_A );	
		return $query_results;
	}
	
	/**
	 * Get validation messages data for the selected form id
	 * @since 1.0.0
	 * return - array - query results
	 */
	public static function sc_wordform_get_validation_messages_data_by_formid( $form_id	= null ) {
		global $wpdb;
		$table				= $wpdb->prefix . self::$sc_wordform_validation_messages_tbl;		
		$query_results		= $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i WHERE form_id = %s LIMIT 1', [ $table, $form_id ] ), ARRAY_A );	
		return $query_results;		
	}
	
	/**
	 * Settings : General Tab Info
	 * get option data
	 */
	public static function sc_wordform_get_settings_general_tab_info() {
		// Default values
		$general_tab_options												= [];
		$general_tab_options[ 'submit_button_background_color'] 			= '#2271b1';
		$general_tab_options[ 'submit_button_background_hover_color']		= '#006ba1';
		$general_tab_options[ 'submit_button_font_color']					= '#ffffff';		
		$general_tab_options[ 'submit_button_font_size']					= 16;
		$general_tab_options[ 'submit_button_padding_top_bottom']			= 8;
		$general_tab_options[ 'submit_button_padding_left_right']			= 25;
		$general_tab_options[ 'submit_button_font_weight']					= 'normal';
		$general_tab_options[ 'send_email_on_form_submission']				= 'no';		
		
		
		if ( $options = get_option('sc_wordform_settings_menu_general_tab_info') ) {	
			// Background Color
			$general_tab_options[ 'submit_button_background_color']					= isset( $options['submit_button_background_color'] )? sanitize_text_field($options['submit_button_background_color'] ) : '#2271b1';
			
			// Background Color (Hover)
			$general_tab_options[ 'submit_button_background_hover_color']			= isset( $options['submit_button_background_hover_color'] )? sanitize_text_field($options['submit_button_background_hover_color'] ) : '#006ba1';
			
			// Font Size
			$general_tab_options[ 'submit_button_font_size']						= isset( $options['submit_button_font_size'] )? sanitize_text_field($options['submit_button_font_size'] ) : 16;			
			
			// Padding ( Top-Bottom)
			$general_tab_options[ 'submit_button_padding_top_bottom']				= isset( $options['submit_button_padding_top_bottom'] )? sanitize_text_field($options['submit_button_padding_top_bottom'] ) : 8;			

			// Padding ( Left-Right)
			$general_tab_options[ 'submit_button_padding_left_right']				= isset( $options['submit_button_padding_left_right'] )? sanitize_text_field($options['submit_button_padding_left_right'] ) : 25;			
			
			
			// Font Color
			$general_tab_options[ 'submit_button_font_color']						= isset( $options['submit_button_font_color'] )? sanitize_text_field($options['submit_button_font_color'] ) : '#ffffff';	
			
			// Font Weight
			$general_tab_options[ 'submit_button_font_weight']						= isset( $options['submit_button_font_weight'] )? sanitize_text_field($options['submit_button_font_weight'] ) : 'normal';			
			
			// Send Email On Form Submission
			$general_tab_options[ 'send_email_on_form_submission']					= isset( $options['send_email_on_form_submission'] )? sanitize_text_field($options['send_email_on_form_submission'] ) : 'no';
		}
		
		return $general_tab_options;
	}
	
	/**
	 * Send Email on Each Form Submission by users at front-end
	 * Check if send email setting is true
	 */
	public static function sc_wordform_send_email_on_form_submission_by_users( $data = [] ) {
		$options				= self::sc_wordform_get_settings_general_tab_info();
		$email_option			= isset( $options['send_email_on_form_submission'] )? sanitize_text_field($options['send_email_on_form_submission'] ) : 'no';
		$recipient				= sanitize_email( get_option('admin_email' ) );
		if ( isset( $email_option ) && $email_option == 'yes' && array_filter( $data ) && is_email( $recipient ) ) {
			SC_Wordform_Ajaxhandler::$output['sendEmailOption']		= 'Yes';
			
			SC_Wordform_FormSubmission::$sc_wordform_submission_array_data = json_decode( $data['submission_data'], true );								
			$submissionData		= SC_Wordform_FormSubmission::sc_wordform_process_submission_data();
			
			$recipient			= $recipient;
			$from_name			= sanitize_text_field( get_option('blogname') );
			$subject			= 'WordForm - User Submitted Form [ ' . $data['form_name'] . ' ]';			
			$email_body			= $submissionData;			
			$headers 			= array('Content-Type: text/html; charset=UTF-8');
			$mail_send_status	= wp_mail( $recipient, $subject, $email_body, $headers, array() );	
			SC_Wordform_Ajaxhandler::$output['mailSendStatus']		= $mail_send_status;			
		}
		else {
			SC_Wordform_Ajaxhandler::$output['sendEmailOption']		= 'No';
		}
	}
	
	/**
	 * Admin notices
	 */	
	public static function scwordform_admin_notices() {
		$admin_notice 			= false;		
		$query_results			= self::sc_wordform_get_all_created_forms_data();
		if ( ! $query_results || ! array_filter( $query_results) ) {
			$admin_notice		= true;
		}
									
		if ( $admin_notice ) {	
			$page		= isset( $_GET['page'] )? sanitize_text_field( $_GET['page'] ): '';
			if ( $page != 'sc-wordform-create-forms') {
				$url 	= admin_url('admin.php?page=sc-wordform-create-forms');
				$alink 	= '<a href="' . esc_url( $url ) . '"> Click to create the Form.</a>';
				printf('<div class="notice notice-info is-dismissible">');
				printf('<div class="sc-wordform-notice-wrapper" style="color: cornflowerblue;"><h4><i class="dashicons dashicons-forms"></i> WordForm - Simple & Easy Drag-Drop Form builder. You did not create any Form yet, try simple & easy Drag-Drop Form builder, try your first WordForm. %s</h4></div>', $alink );
				printf('</div>');
			}
		}		
	}
	
	public static function scwordform_row_link( $actions, $plugin_file ) {
		$wordsmtp_plugin 	= plugin_basename( SCWORDFORM_PLUGIN_DIR );
		$plugin_name 		= basename($plugin_file, '.php');
		if ( $wordsmtp_plugin == $plugin_name ) {
			//$doclink[] 		= '<a href="https://softcoy.com/wordsmtp" title="WordSMTP - Docs" target="_blank">WordSMTP Docs</a>';	
			//$doclink[] 		= '<a href="https://softcoy.com/wordsmtp" title="WordSMTP Support" target="_blank">Support</a>';	
			//return array_merge( $actions, $doclink );
		}
		return $actions;
	}	
	
} // End class