<?php
	 /*
	 Plugin Name: SysVisionz E-Z Login
	 Plugin URI: http://www.sysvisionz.com/ez-login
	 Description: A plugin built for the easiest of easy user registration and login interaction.
	 Version: 0.4
	 Author: SysVisionz
	 Author URI: http://www.sysvisionz.com
	 */

// changes redirect to homepage on logout

add_option('sysv_ezl_vals_option',array());

add_option('sysv_ezl_instances',array());

function registration_redirect(){
	wp_redirect( $sysv_ezl_global_use['text']['reg_redir']);
}	

add_action('wp_logout','auto_redirect_after_logout');

function auto_redirect_after_logout(){
	wp_redirect( home_url() );
	exit();
}

if( ! function_exists( 'custom_login_empty' ) ) {
    function custom_login_empty(){
        $referrer = $_SERVER['HTTP_REFERER'];
        if ( strstr($referrer,'mylogin') && $user==null ) { // mylogin is the name of the loginpage.
            if ( !strstr($referrer,'?login=empty') ) { // prevent appending twice
                wp_redirect( $referrer . '?login=empty' );
            } else {
                wp_redirect( $referrer );
            }
        }
    }
}
add_action( 'authenticate', 'custom_login_empty');

function sysv_login_function(){
		global $wpdb;
		global $widget_id;
		$submittype = $_POST['submittype'];
		if ($submittype=='log'){
			$credential=array(
				'user_login'=>$_POST['user_name'],
				'user_password'=>$_POST['user_password'],
				'remember'=>$_POST['rememberme']
			);
			$user = wp_signon( $credential, false );
			if ( is_wp_error( $user ) ){
				$result = false;
			}
			else{
				$result = 'Logging in...';
				exit;
			}
		}
		elseif ($submittype=='chk'){
			if(email_exists($_POST['user_name']) || user_exists($_POST['user_name'])){
				$result = 1;
			}
			elseif (empty($_POST['user_name'])){
				$result = -1;
			}
			else{
				$result = 0;
			}
		}
		elseif ($submittype=='reg')
		{
			$toregister=sanitize_email( $_POST['user_name'] );
			register_new_user($toregister, $toregister);
			if ( is_wp_error( $user ) ){
				$result = false;
			}
			else{
				$result = false;
			}
		}
		echo	$result ;
	wp_die();
}

//ajax function for usercheck/login/registration
add_action('wp_ajax_sysv_login_function', 'sysv_login_function');
add_action('wp_ajax_nopriv_sysv_login_function', 'sysv_login_function');

class sysv_ezl_widget extends WP_Widget {
	public function getvals(){
		return $sysv_ezl_global_use;
	}
	private $id = rand();
	// constructor
	function __construct() {
		$widget_ops = array('classname' => 'sysv_ezl_widget', 'description' => __('EZ Login Core', 'wp_widget_plugin'));
			parent::__construct(false, $name = __('SysVisionz EZ Login', 'wp_widget_plugin'), $widget_ops );
	}

	public function add_page() {
		add_options_page('E-Z Login Options', 'E-Z Login Options', 'manage_options', 'sysv_ezl_options', array($this, 'options_do_page'));
	}

	public function admin_init() {
		register_setting('sysv_ezl_options', $this->option_name, array($this, 'validate'));
	}

	// widget form creation
	function form($instance) {
		if( $instance ) {
			$sysv_ezl_vals = $instance['sysv_ezl_vals'];
			update_option('sysv_ezl_vals_option', $sysv_ezl_vals);
			$this->id;
		}
		else {
			//entry values for visible field boxes
			$sysv_ezl_vals = array(
				'text' => array(
					'form_name' => 'Please enter your Email:',
					'reg_text' => 'Register',
					'log_text' => 'Login',
					'isuser' => 'Registered User! Please enter your Password.',
					'notuser' => 'New User! Please Register.',
					'reg_success' => 'Successfully registered! Check your email.',
					'reg_redir' => ''
				),
				//modifiable css values to be moved to primary file in later implementations
				'css' => array(
					'display' => 'inline-block',
					'bg_color' => 'inherit',
					'border_style' => 'solid',
					'border_color' => '#990000',
					'border_width' => 5,
					'border_radius' => 5,
					'text_color' => 'inherit',
					'is_custom_css' => false,
					'custom_css' => ''
				),
				'set' => array(
					'pass_visible' => false,
					'procimg' => 'pacloader.gif'
				)
			);
			$this->id = rand();
			update_option('sysv_ezl_vals_option', $sysv_ezl_vals);
		}

		//future value implementations for easy construction of widget options
/*		$sysv_ezl_options = array(
			'display' => array(
				'type' => 'dropdown'
				'values' => array('inherit',
					'inline',
					'inline-block',
					'block',
					'flex'
				)
			),
			'color' => array(
				'type' => 'text',
				'values' => array(
					'red',
					'blue',
					'green',
					'alpha'
				)
			),
			'border' => array(
				'type' => 'dropdown',
				'values' => array(
					'inherit'
					'none',
					'solid',
					'dotted',
					'dashed',
					'double'
				)
			),
			'pixels' => array(
				'type' => 'entry',
				'values' => 'px'
			),
			'text' => array(
				'type' => 'entry',
				'values' => 'text'
			)
		)

		$sysv_ezl_formarrayvals = array(
			'text' => array(
				'titleof' => 'Widget Text',
				'eachval' => array(
					array(
						'name' => 'form_name',
						'text' => 'Title of Form',
						'options' => 'text'
					),
					array(
						'name' => 'reg_text',
						'text' => 'Register Button'
						'options' => 'text'
					),
					array(
						'name' => 'log_text',
						'text' => 'Login Button'),
						'options' => 'text'
					array(
						'name' => 'isuser',
						'text' => 'Password Prompt'
						'options' => 'text'
					),
					array(
						'name' => 'notuser',
						'text' => 'Register Prompt'
						'options' => 'text'
					)
				)
			),
			'css' => array(
				'titleof' => 'CSS',
				'eachval' => array(
					array(
						'name' => 'display',
						'text' => 'Visible Fields'
						'options' => 'display'
					),
					array(
						'name' => 'bg_color',
						'text' => 'Widget Background Color'
						'options' => 'color'
					),
					array(
						'name' => 'border_style',
						'text' => 'Border Style',
						'options' => 'border'
					),
					array(
						'name' => 'border_color',
						'text' => 'Border Color',
						'options' => 'color'
					),
					array(
						'name' => 'border_width',
						'text' => 'Register Prompt',
						'options' => 'pixels'
					),
					array(
						'name' => 'border_radius',
						'text' => 'Border Radius',
						'options' => 'pixels'
					),
					array(
						'name' => 'text_color',
						'text' => 'Text Color',
						'options' => 'color'
					),
					array(
						'name' => 'is_custom_css',
						'text' => 'Custom CSS instead?',
						'options' => 'checkbox'
					),
				)
			),
			'set' => array(
				'titleof' => 'Settings',
				'eachval' => array(
					array(
						'name' => 'form_name',
						'text' => 'Title of Form'),
					array(
						'name' => 'reg_text',
						'text' => 'Register Button'),
					array(
						'name' => 'log_text',
						'text' => 'Login Button'),
					array(
						'name' => 'isuser',
						'text' => 'Password Prompt'),
					array(
						'name' => 'notuser',
						'text' => 'Register Prompt')
				)
			)
		);*/
	?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( "form_name" ) ); ?>"><?php _e( 'Widget Title', 'wp_widget_plugin' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( "form_name" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "form_name" ) ); ?>" type="text" value="<?php echo esc_attr( $sysv_ezl_vals['text']['form_name'] ); ?>" />
		</p>
		<p> Button Text:</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( "reg_text" ) ); ?>"><?php _e( 'Registration', 'wp_widget_plugin' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( "reg_text" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "reg_text" ) ); ?>" type="text" value="<?php echo esc_attr( $sysv_ezl_vals['text']['reg_text'] ); ?>" />
		</p>

		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( "log_text" ) ); ?>"><?php _e( 'Login', 'wp_widget_plugin' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( "log_text" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "log_text" ) ); ?>" type="text" value="<?php echo esc_attr( $sysv_ezl_vals['text']['log_text'] ); ?>" />
		</p>
		<p>Text if:</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( "isuser" ) ); ?>"><?php _e( 'Registered', 'wp_widget_plugin' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( "isuser" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "isuser" ) ); ?>" type="text" value="<?php echo esc_attr( $sysv_ezl_vals['text']['isuser'] ); ?>" />
		</p>

		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( "notuser" ) ); ?>"><?php _e( 'Needs to Register', 'wp_widget_plugin' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( "notuser" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "notuser" ) ); ?>" type="text" value="<?php echo esc_attr( $sysv_ezl_vals['text']['notuser'] ); ?>" />
		</p>

		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( "reg_success" ) ); ?>"><?php _e( 'Successfully Registered', 'reg_success' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( "reg_success" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "reg_success" ) ); ?>" type="text" value="<?php echo esc_attr( $sysv_ezl_vals['text']['reg_success'] ); ?>" />
		</p>

		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( "reg_redir" ) ); ?>"><?php _e( 'Registered Redirect', 'reg_redir' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( "reg_redir" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "reg_redir" ) ); ?>" type="text" value="<?php echo esc_attr( $sysv_ezl_vals['text']['reg_redir'] ); ?>" />
		</p>

		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( "after_login" ) ); ?>"><?php _e( 'Logged in Redirect', 'after_login' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( "after_login" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "after_login" ) ); ?>" type="text" value="<?php echo esc_attr( $sysv_ezl_vals['text']['after_login'] ); ?>" />
		</p>
	<?php
	}
		// widget update
	function update($new, $old) {
		 	$instance = $old;
		$instance['sysv_ezl_vals'] = array(
			'text' => array(
				'form_name' => strip_tags($new["form_name"]),
				'reg_text' => strip_tags($new['reg_text']),
				'log_text' => strip_tags($new['log_text']),
				'isuser' => strip_tags($new['isuser']),
				'notuser' => strip_tags($new['notuser']),
				'reg_success' => strip_tags($new['reg_success']),
				'reg_redir' => strip_tags($new['reg_redir']),
				'after_login' => strip_tags($new['after_login'])
			),
		// current presets, to be deleted with implementation of formatting options.
			'css' => array(
				'display' => 'inline-block',
				'bg_color' => 'inherit',
				'border_style' => 'solid',
				'border_color' => '#990000',
				'border_width' => 5,
				'border_radius' => 5,
				'text_color' => 'inherit',
				'is_custom_css' => false,
				'custom_css' => ''
			),
			'set' => array(
				'pass_visible' => true,
				'procimg' => 'pacloader.gif'
			)
		);
		update_option('sysv_ezl_vals_option', $sysv_ezl_vals);
		return $instance;
	}

	// widget display
	function widget($args, $instance) {
		$sysv_ezl_global_use = $instance['sysv_ezl_vals'];
		extract( $args );
		echo $before_widget;
		// Display the widget
		echo '<div class="widget-text sysv_ezl_login_widget">';
		if ( is_user_logged_in() ){
			?>
			<a href="<?php echo wp_logout_url(); ?>"><button id="sysv_ezl_logoutbutton" class="sysv_ezl_reglogbut sysv_inactive">Logout</button></a>
			<?php
		}
		else{
			require 'sysv_ezl_form.html.php';
		}
		echo '</div>' . $after_widget;
		
		//enqueues js/css
		global $cur_ezl_widget_id;
		$cur_ezl_widget_id = rand();
	}

}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("sysv_ezl_widget");'));

//future integration of entry types for the widget values
/*function make_dropdown($entries){

}

function make_text($before_box, $after_box){

}

function make_textbox($title){

}
*/
add_action('wp_head', 'scriptstyle_php');

	function scriptstyle_php() {
		$sysv_ezl_global_use=get_option('sysv_ezl_vals_option');
		require 'sysv_ezl.js.php';
		require 'sysv_ezl.css.php';
	}
?>