<?php
/**
 * Theme Widget: Twitter feed
 */

// Theme init
if (!function_exists('pastore_church_widget_twitter_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_widget_twitte_theme_setup', 1 );
	function pastore_church_widget_twitte_theme_setup() {

		// Register shortcodes in the shortcodes list
		//add_action('pastore_church_action_shortcodes_list',		'pastore_church_widget_twitte_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_widget_twitte_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('pastore_church_widget_twitte_load')) {
	add_action( 'widgets_init', 'pastore_church_widget_twitter_load' );
	function pastore_church_widget_twitter_load() {
		register_widget( 'pastore_church_widget_twitter' );
	}
}

// Widget Class
class pastore_church_widget_twitter extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_twitter', 'description' => esc_html__('Last Twitter Updates. Version for new Twitter API 1.1', 'pastore-church') );
		parent::__construct( 'pastore_church_widget_twitter', esc_html__('ThemeREX - Twitter', 'pastore-church'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$twitter_username = !empty($instance['twitter_username']) ? $instance['twitter_username'] : pastore_church_get_theme_option('twitter_username');
		$twitter_consumer_key = !empty($instance['twitter_consumer_key']) ? $instance['twitter_consumer_key'] : pastore_church_get_theme_option('twitter_consumer_key');
		$twitter_consumer_secret = !empty($instance['twitter_consumer_secret']) ? $instance['twitter_consumer_secret'] : pastore_church_get_theme_option('twitter_consumer_secret');
		$twitter_token_key = !empty($instance['twitter_token_key']) ? $instance['twitter_token_key'] : pastore_church_get_theme_option('twitter_token_key');
		$twitter_token_secret = !empty($instance['twitter_token_secret']) ? $instance['twitter_token_secret'] : pastore_church_get_theme_option('twitter_token_secret');
		$twitter_count = max(1, !empty($instance['twitter_count']) ? (int) $instance['twitter_count'] : (int) pastore_church_get_theme_option('twitter_count'));	

		if (empty($twitter_consumer_key) || empty($twitter_consumer_secret) || empty($twitter_token_key) || empty($twitter_token_secret)) return;
		
		$data = pastore_church_get_twitter_data(array(
			'mode'            => 'user_timeline',
			'consumer_key'    => $twitter_consumer_key,
			'consumer_secret' => $twitter_consumer_secret,
			'token'           => $twitter_token_key,
			'secret'          => $twitter_token_secret
			)
		);
		
		if (!$data || !isset($data[0]['text'])) return;
		
		$output = '<ul>';
		$cnt = 0;
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $tweet) {
				if (pastore_church_substr($tweet['text'], 0, 1)=='@') continue;
				$output .= '<li class="theme_text' . ($cnt==$twitter_count-1 ? ' last' : '') . '"><a href="' . esc_url('https://twitter.com/'.($twitter_username)) . '" class="username" target="_blank">@' . ($tweet['user']['screen_name']) . '</a> ' . force_balance_tags(pastore_church_prepare_twitter_text($tweet)) . '</li>';
				if (++$cnt >= $twitter_count) break;
			}
		}
		$output .= '</ul>';
		
		if (!empty($output)) {
	
			// Before widget (defined by themes)
			echo trim($before_widget);
			
			// Display the widget title if one was input (before and after defined by themes)
			if ($title) echo trim($before_title . $title . $after_title);
	
			echo trim($output);
			
			// After widget (defined by themes)
			echo trim($after_widget);
		}
	}

	// Update the widget settings.
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['twitter_username'] = strip_tags( $new_instance['twitter_username'] );
		$instance['twitter_consumer_key'] = strip_tags( $new_instance['twitter_consumer_key'] );
		$instance['twitter_consumer_secret'] = strip_tags( $new_instance['twitter_consumer_secret'] );
		$instance['twitter_token_key'] = strip_tags( $new_instance['twitter_token_key'] );
		$instance['twitter_token_secret'] = strip_tags( $new_instance['twitter_token_secret'] );
		$instance['twitter_count'] = strip_tags( $new_instance['twitter_count'] );
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'twitter_username' => '',
			'twitter_consumer_key' => '',
			'twitter_consumer_secret' => '',
			'twitter_token_key' => '',
			'twitter_token_secret' => '',
			'twitter_count' => ''
			)
		);
		$title = $instance['title'];
		$twitter_username = $instance['twitter_username'];
		$twitter_consumer_key = $instance['twitter_consumer_key'];
		$twitter_consumer_secret = $instance['twitter_consumer_secret'];
		$twitter_token_key = $instance['twitter_token_key'];
		$twitter_token_secret = $instance['twitter_token_secret'];
		$twitter_count = $instance['twitter_count'];
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'pastore-church'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($title); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_count' )); ?>"><?php esc_html_e('Tweets count:', 'pastore-church'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_count' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_count' )); ?>" value="<?php echo esc_attr($twitter_count); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_username' )); ?>"><?php esc_html_e('Twitter Username:', 'pastore-church'); ?><br />(<?php esc_html_e('leave empty if you paste widget code', 'pastore-church'); ?>)</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_username' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_username' )); ?>" value="<?php echo esc_attr($twitter_username); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_key' )); ?>"><?php esc_html_e('Twitter Consumer Key:', 'pastore-church'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_key' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_consumer_key' )); ?>" value="<?php echo esc_attr($twitter_consumer_key); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_secret' )); ?>"><?php esc_html_e('Twitter Consumer Secret:', 'pastore-church'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_consumer_secret' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_consumer_secret' )); ?>" value="<?php echo esc_attr($twitter_consumer_secret); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_token_key' )); ?>"><?php esc_html_e('Twitter Token Key:', 'pastore-church'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_token_key' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_token_key' )); ?>" value="<?php echo esc_attr($twitter_token_key); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'twitter_token_secret' )); ?>"><?php esc_html_e('Twitter Token Secret:', 'pastore-church'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'twitter_token_secret' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_token_secret' )); ?>" value="<?php echo esc_attr($twitter_token_secret); ?>" class="widgets_param_fullwidth" />
		</p>

	<?php
	}
}



// trx_widget_twitter
//-------------------------------------------------------------
/*
[trx_widget_twitter id="unique_id" title="Widget title" bg_image="image_url" number="3" follow="0|1"]
*/
if ( !function_exists( 'pastore_church_sc_widget_twitter' ) ) {
	function pastore_church_sc_widget_twitter($atts, $content=null){	
		$atts = pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"count" => 2,
			"username" => "",
			"consumer_key" => "",
			"consumer_secret" => "",
			"token_key" => "",
			"token_secret" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		extract($atts);
		$type = 'pastore_church_widget_twitter';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$atts['twitter_username'] = $username;
			$atts['twitter_consumer_key'] = $consumer_key;
			$atts['twitter_consumer_secret'] = $consumer_secret;
			$atts['twitter_token_key'] = $token_key;
			$atts['twitter_token_secret'] = $token_secret;
			$atts['twitter_count'] = max(1, (int) $count);
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_twitter' 
								. (pastore_church_exists_visual_composer() ? ' vc_widget_twitter wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
						. '">';
			ob_start();
			the_widget( $type, $atts, pastore_church_prepare_widgets_args(pastore_church_storage_get('widgets_args'), $id ? $id.'_widget' : 'widget_twitter', 'widget_twitter') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_widget_twitter', $atts, $content);
	}
	pastore_church_require_shortcode("trx_widget_twitter", "pastore_church_sc_widget_twitter");
}


// Add [trx_widget_twitter] in the VC shortcodes list
if (!function_exists('pastore_church_widget_twitte_reg_shortcodes_vc')) {
	function pastore_church_widget_twitte_reg_shortcodes_vc() {
		
		vc_map( array(
				"base" => "trx_widget_twitter",
				"name" => esc_html__("Widget Twitter Feed", 'pastore-church'),
				"description" => wp_kses_data( __("Insert widget with Twitter feed", 'pastore-church') ),
				"category" => esc_html__('Content', 'pastore-church'),
				"icon" => 'icon_trx_widget_twitter',
				"class" => "trx_widget_twitter",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Widget title", 'pastore-church'),
						"description" => wp_kses_data( __("Title of the widget", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Tweets number", 'pastore-church'),
						"description" => wp_kses_data( __("Tweets number to show in the feed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "username",
						"heading" => esc_html__("Twitter Username", 'pastore-church'),
						"description" => wp_kses_data( __("Twitter Username", 'pastore-church') ),
						"group" => esc_html__('Twitter account', 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_key",
						"heading" => esc_html__("Consumer Key", 'pastore-church'),
						"description" => wp_kses_data( __("Specify Consumer Key from Twitter application", 'pastore-church') ),
						"group" => esc_html__('Twitter account', 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_secret",
						"heading" => esc_html__("Consumer Secret", 'pastore-church'),
						"description" => wp_kses_data( __("Specify Consumer Secret from Twitter application", 'pastore-church') ),
						"group" => esc_html__('Twitter account', 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_key",
						"heading" => esc_html__("Token Key", 'pastore-church'),
						"description" => wp_kses_data( __("Specify Token Key from Twitter application", 'pastore-church') ),
						"group" => esc_html__('Twitter account', 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_secret",
						"heading" => esc_html__("Token Secret", 'pastore-church'),
						"description" => wp_kses_data( __("Specify Token Secret from Twitter application", 'pastore-church') ),
						"group" => esc_html__('Twitter account', 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					pastore_church_get_vc_param('id'),
					pastore_church_get_vc_param('class'),
					pastore_church_get_vc_param('css')
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Twitter extends WPBakeryShortCode {}

	}
}
?>