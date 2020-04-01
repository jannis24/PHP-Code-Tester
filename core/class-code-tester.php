<?php
/**
 * The main class which contains everything related about the code tester
 */

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'Ironikus_PHP_Code_Tester' ) ){

	class Ironikus_PHP_Code_Tester{

		public function __construct() {

			$this->settings = array( 
				'execute_nonce_field_name' => 'irnks_phpct_nonce_check',
				'execute_nonce_field_action' => 'irnks_phpct_nonce_check_action',
			 );
			 $this->fatal_error = null;

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );

			add_action( 'shutdown', array( $this, 'display_php_tester_content' ), 20 );

			//Execution
			add_action( 'plugins_loaded', array( $this, 'execute_php_code' ) );

		}

		/**
		 * ######################
		 * ###
		 * #### SETTINGS
		 * ###
		 * ######################
		 */

		 /**
		  * The main capability needed to use the functionality of this plugin
		  *
		  * @return string - the capability
		  */
		public function needed_permission(){
			return apply_filters( 'irnks/phpct/needed_permission', 'manage_options' );
		}

		/**
		 * ######################
		 * ###
		 * #### HELPERS
		 * ###
		 * ######################
		 */

		 /**
		  * Verify all necessary permissions
		  *
		  * @return boolean - true if permission granted, false if not
		  */
		public function has_permission(){

			if( ! is_user_logged_in() ){
				return false;
			}

			if( ! current_user_can( $this->needed_permission() ) ){
				return false;
			}

			return true;
		}

		/**
		 * ######################
		 * ###
		 * #### SCRIPTS & STYLES
		 * ###
		 * ######################
		 */

		/**
		 * Register all necessary scripts and styles
		 */
		public function enqueue_scripts_and_styles() {
			if( $this->has_permission() ) {
				wp_enqueue_style( 'phpct-admin-styles', IRNKS_PHPCT_PLUGIN_URL . 'core/assets/dist/css/admin-styles.min.css', array(), IRNKS_PHPCT_PLUGIN_VERSION, 'all' );
				wp_enqueue_script( 'phpct-admin-scripts', IRNKS_PHPCT_PLUGIN_URL . 'core/assets/dist/js/admin-scripts.min.js', array( 'jquery' ), IRNKS_PHPCT_PLUGIN_VERSION, true );
			}
		}

		/**
		 * ######################
		 * ###
		 * #### HTML output
		 * ###
		 * ######################
		 */

		/**
		 * The visible output for executing and writing the PHP code
		 *
		 * @return string - the HTML of the code editor/manager
		 */
		public function display_php_tester_content( ){

			if( ! $this->has_permission() ){
				return;
			}

			$default_content = '';
			if( isset( $_POST['irnks-php-code-executable'] ) && isset( $_POST[ $this->settings['execute_nonce_field_name'] ] ) && wp_verify_nonce( $_POST[ $this->settings['execute_nonce_field_name'] ], $this->settings['execute_nonce_field_action'] ) ){
				$default_content = stripslashes( $_POST['irnks-php-code-executable'] );
			}

			if( isset( $_POST['irnks-php-code-clean'] ) && $_POST['irnks-php-code-clean'] === 'yes' ){
				return;
			}

			ob_start();
			?>
<div id="irnks-php-code-tester">
	<div class="irnksphpt-resize">
		<strong><?php echo IRNKS_PHPCT_PLUGIN_NAME; ?></strong>
	</div>
	<div class="irnks-php-code-tester-actions">
		<div id="irnks-php-code-execute" class="irnks-action-button">
			<?php echo __( 'Execute Code', 'php-code-tester' ) ?>
		</div>
		<div id="irnks-php-code-execute-new-tab" class="irnks-action-button" title="<?php echo __( 'Opens in a clean window without the code tester console.', 'php-code-tester' ) ?>"><?php echo $this->get_new_tab_image(); ?></div>
	</div>
	<div class="irnks-php-code-error-message">
		<?php echo htmlspecialchars( $this->fatal_error ); ?>
	</div>
	<form id="irnks-php-code-tester-form" method="post">
		<?php wp_nonce_field( $this->settings['execute_nonce_field_action'], $this->settings['execute_nonce_field_name'] ); ?>
		<input id="php-code-tester-code-clean-output" name="irnks-php-code-clean" type="hidden" value="no" />
		<textarea id="php-code-tester-code" name="irnks-php-code-executable"><?php echo $default_content; ?></textarea>
		<input id="irnks-php-code-tester-fallback-form" type="submit" value="<?php echo __( 'Execute Code', 'php-code-tester' ) ?>">
	</form>
</div>
			<?php
			$html = ob_get_clean();

			echo $html;
		}

		/**
		 * Return the SVG icon for opening a new tab
		 *
		 * @return string - the HTML of the SVG
		 */
		public function get_new_tab_image(){
			ob_start();
			?>
				<svg xmlns="http://www.w3.org/2000/svg" fill="#ffffff" width="32" height="32"><path d="M18 5v2h5.563L11.28 19.28l1.438 1.438L25 8.438V14h2V5zM5 9v18h18V14l-2 2v9H7V11h9l2-2z"/></svg>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'irnks/phpct/output_html', $html );
		}

		/**
		 * ######################
		 * ###
		 * #### EXECUTION TESTING
		 * ###
		 * ######################
		 */

		 /**
		  * Execute the PHP code
		  *
		  * @return void
		  */
		 public function execute_php_code(){

			if( ! isset( $_POST['irnks-php-code-executable'] ) || ! isset( $_POST[ $this->settings['execute_nonce_field_name'] ] ) || ! wp_verify_nonce( $_POST[ $this->settings['execute_nonce_field_name'] ], $this->settings['execute_nonce_field_action'] ) ){
				return;
			}

			if( ! $this->has_permission() ){
				return;
			}

			eval( $_POST['irnks-php-code-executable'] );

		 }

	}

	new Ironikus_PHP_Code_Tester();

}