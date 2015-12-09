<?php
/**
 * Plugin Name: Social Impact Widget
 * Plugin URI: http://ppfeufer.de/wordpress-plugin/social-impact-widget/
 * Description: Displaying the count of your follwers on twitter and app.net, your circles on googleplus and the fans of your facebook fanpage in your sidebar.
 * Version: 1.6.2
 * Author: H.-Peter Pfeufer
 * Author URI: http://ppfeufer.de
 * Text Domain: social-impact-widget
 * Domain Path: /l10n
 */

if(!class_exists('Social_Impact_Widget')) {
	class Social_Impact_Widget extends WP_Widget {
		private $var_sPluginVersion;
		private $var_sArrayOptionsKey = '';
		private $var_sTextdomain = 'social-impact-widget';
		private $var_iCachingTime = 1800;
		private $var_sPluginDir;
		private $var_sUserAgent;

		private $array_Options = array();

		/**
		 * Init
		 *
		 * @since 0.1
		 * @author ppfeufer
		 */
		public function Social_Impact_Widget() {
			self::__construct();
		}

		public function __construct() {
			$this->array_Options = get_option('widget_social_impact_widget');

			$this->var_sPluginVersion = $this->_get_plugin_version();
			$this->var_sUserAgent = 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20100101 Firefox/5.0 Social Impact Widget (Version ' . $this->var_sPluginVersion . ') for WordPress';
			$this->var_sPluginDir = '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));

			/**
			 * Loading Textdomain
			 */
			if(function_exists('load_plugin_textdomain')) {
				load_plugin_textdomain($this->var_sTextdomain, $this->var_sPluginDir . '/l10n', dirname(plugin_basename(__FILE__)) . '/l10n');
			}

			/**
			 * Enqueue CSS
			 */
			if(!is_admin()) {
				wp_enqueue_style('social-impact-css', $this->var_sPluginDir . '/css/social-impact-widget.css', false);
			}

			if(is_admin()) {
				if(ini_get('allow_url_fopen') || function_exists('curl_init')) {
					add_action('in_plugin_update_message-' . plugin_basename(__FILE__), array(
						$this,
						'_update_notice'
					));
				} // END if(ini_get('allow_url_fopen') || function_exists('curl_init'))
			} // END if(is_admin())

			/**
			 * Get the array where our settings are,
			 * we need to know it for later use.
			 *
			 * @since 0.1
			 * @author ppfeufer
			 */
			if(is_array($this->array_Options)) {
				foreach($this->array_Options as $key => $value) {
					if(is_array($value) && array_key_exists('title', $value)) {
						$this->var_sArrayOptionsKey = $key;

						break;
					} // END if(is_array($value) && array_key_exists('title', $value))
				} // END foreach($this->array_Options as $key => $value)
			} // END if(is_array($this->array_Options))

			$widget_ops = array(
				'classname' => 'social_impact_widget',
				'description' => __('Displaying the count of your follwers on twitter and app.net, your circles on googleplus and the fans of your facebook fanpage in your sidebar.', $this->var_sTextdomain)
			);

			$control_ops = array();

			$this->WP_Widget('social_impact_widget', __('Social Impact Widget', $this->var_sTextdomain), $widget_ops, $control_ops);
		}

		/**
		 * Widgetformular erstellen
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param array $instance
		 *
		 * @see WP_Widget::form()
		 */
		public function form($instance) {
			$array_profiles = array();
			$instance = wp_parse_args((array) $instance, array(
				'title' => '',

				'twitter-id' => '',
				'twitter-api-timer' => '',
				'twitter-count' => '',

				'appnet-id' => '',
				'appnet-api-timer' => '',
				'appnet-count' => '',

				'googleplus-id' => '',
				'googleplus-api-timer' => '',
				'googleplus-count' => '',

				'fanpage-id' => '',
				'fanpage-api-timer' => '',
				'fanpage-count' => '',
			));

			$title = strip_tags($instance['title']);

			// Title
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Title', $this->var_sTextdomain) . '</strong></p>';
			echo '<p><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $instance['title'] . '" /></p>';

			// Twitter-ID
			echo '<p style="clear:both;"></p>';
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Twitter-ID:', $this->var_sTextdomain) . '</strong><br /><span class="description">' . __('(without @)', $this->var_sTextdomain) . '</span></p>';
			echo '<p><input id="' . $this->get_field_id('twitter-id') . '" name="' . $this->get_field_name('twitter-id') . '" type="text" value="' . $instance['twitter-id'] . '" /></p>';
			echo 'http://query.yahooapis.com/v1/public/yql?q=SELECT%20*%20from%20html%20where%20url=%22http://twitter.com/' . $instance['twitter-id'] . '%22%20AND%20xpath=%22//a[@class=%27js-nav%27]/strong%22&format=json';
// 			$return_Twitter = $this->_helper_curl('http://query.yahooapis.com/v1/public/yql?q=SELECT%20*%20from%20html%20where%20url=%22http://twitter.com/' . $instance['twitter-id'] . '%22%20AND%20xpath=%22//a[@class=%27js-nav%27]/strong%22&format=json');
// 			$obj_TwitterData = json_decode($return_Twitter);
// 			echo $return_Twitter;
// 			print_r($obj_TwitterData);
// 			echo $this->_format_twitter_followers($obj_TwitterData->query->results->strong[2]);

			// Appnet-ID
			echo '<p style="clear:both;"></p>';
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('App.net-ID:', $this->var_sTextdomain) . '</strong><br /><span class="description">' . __('(without @)', $this->var_sTextdomain) . '</span></p>';
			echo '<p><input id="' . $this->get_field_id('appnet-id') . '" name="' . $this->get_field_name('appnet-id') . '" type="text" value="' . $instance['appnet-id'] . '" /></p>';

			// Googleplus-ID
			echo '<p style="clear:both;"></p>';
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Googleplus-ID:', $this->var_sTextdomain) . '</strong><br /><span class="description">' . __('(User or pages)', $this->var_sTextdomain) . '</span></p>';
			echo '<p><input id="' . $this->get_field_id('googleplus-id') . '" name="' . $this->get_field_name('googleplus-id') . '" type="text" value="' . $instance['googleplus-id'] . '" /></p>';

			// Facebook Fanpage-ID
			echo '<p style="clear:both;"></p>';
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Facebook Fanpage-ID:', $this->var_sTextdomain) . '</strong></p>';
			echo '<p><input id="' . $this->get_field_id('fanpage-id') . '" name="' . $this->get_field_name('fanpage-id') . '" type="text" value="' . $instance['fanpage-id'] . '" /></p>';

			/**
			 * Cachehandling
			 *
			 * @since 0.2
			 */
			echo '<p style="clear:both;"></p>';
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Cachehandling:', $this->var_sTextdomain) . '</strong></p>';
			echo '<p><input class="checkbox" type="checkbox" id="' . $this->get_field_id('clear-cache') . '" name="' . $this->get_field_name('clear-cache') . '" /> ' . __('Clear Cache', $this->var_sTextdomain) . '</p>';

			/**
			 * Flattr
			 *
			 * @since 0.6
			 */
			echo '<p style="clear:both;"></p>';
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Like the Plugin?:', $this->var_sTextdomain) . '</strong></p>';
			echo '<p><a href="http://flattr.com/thing/499207/WordPress-Plugin-Social-Impact-Widget" target="_blank"><img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></p>';

			echo '<p style="clear:both;"></p>';
		} // END function form($instance)

		/**
		 * Updating options
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 *
		 * @see WP_Widget::update()
		 */
		public function update($new_instance, $old_instance) {
			$instance = $old_instance;

			$new_instance = wp_parse_args((array) $new_instance, array(
				'title' => 'Social Impact',

				'twitter-id' => '',
				'twitter-api-timer' => '',
				'twitter-count' => '',

				'appnet-id' => '',
				'appnet-api-timer' => '',
				'appnet-count' => '',

				'googleplus-id' => '',
				'googleplus-api-timer' => '',
				'googleplus-count' => '',

				'fanpage-id' => '',
				'fanpage-api-timer' => '',
				'fanpage-count' => '',
			));

			$instance['title'] = (string) strip_tags($new_instance['title']);

			// Twitter
			$instance['twitter-id'] = (string) $this->_sanitize_twittername(strip_tags($new_instance['twitter-id']));
			$instance['twitter-api-timer'] = ($new_instance['twitter-id']) ? (string) mktime() : '';
			$instance['twitter-count'] = ($new_instance['twitter-id']) ? (string) $this->get_twitter_followers($new_instance['twitter-id'], ($new_instance['clear-cache']) ? true : false) : '';

			// Appnet
			$instance['appnet-id'] = (string) $this->_sanitize_twittername(strip_tags($new_instance['appnet-id']));
			$instance['appnet-api-timer'] = ($new_instance['appnet-id']) ? (string) mktime() : '';
			$instance['appnet-count'] = ($new_instance['appnet-id']) ? (string) $this->get_appnet_followers($new_instance['appnet-id'], ($new_instance['clear-cache']) ? true : false) : '';

			// Google+
			$instance['googleplus-id'] = (string) strip_tags($new_instance['googleplus-id']);
			$instance['googleplus-api-timer'] = ($new_instance['googleplus-id']) ? (string) mktime() : '';
			$instance['googleplus-count'] = ($new_instance['googleplus-id']) ? (string) $this->get_googleplus_circles($new_instance['googleplus-id'], ($new_instance['clear-cache']) ? true : false) : '';

			// Facebook
			$instance['fanpage-id'] = (string) strip_tags($new_instance['fanpage-id']);
			$instance['fanpage-api-timer'] = ($new_instance['fanpage-id']) ? (string) mktime() : '';
			$instance['fanpage-count'] = ($new_instance['fanpage-id']) ? (string) $this->get_facebook_fans($new_instance['fanpage-id'], ($new_instance['clear-cache']) ? true : false) : '';

			return $instance;
		} // END public function update($new_instance, $old_instance)

		/**
		 * Widget
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param array $args
		 * @param array $instance
		 *
		 * @see WP_Widget::widget()
		 */
		public function widget($args, $instance) {
			extract($args);

			echo $before_widget;

			$title = (empty($instance['title'])) ? '' : apply_filters('social_impact_widget_title', $instance['title']);

			if(!empty($title)) {
				echo $before_title . $title . $after_title;
			} // END if(!empty($title))

			echo $this->social_impact_output($instance, 'widget');
			echo $after_widget;
		} // END public function widget($args, $instance)

		/**
		 * Getting the HTML
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param array $args
		 * @param string $position
		 */
		private function social_impact_output($args = array(), $position) {
			/**
			 * Filling the array with the counts, to check later if a count is present.
			 * If not, don't display this field.
			 *
			 * @since 0.4
			 *
			 * @var array
			 */
			$array_Counter = array(
				'twitter-follower' => $this->get_twitter_followers($args['twitter-id']),
				'appnet-follower' => $this->get_appnet_followers($args['appnet-id']),
				'googleplus-circles' => $this->get_googleplus_circles($args['googleplus-id']),
				'facebook-fans' => $this->get_facebook_fans($args['fanpage-id']),
			);

			/**
			 * Filling te array with the profilelnks.
			 * Escaping any crappy characters
			 *
			 * @since 0.0
			 *
			 * @var array
			 */
			$array_Profilelinks = array(
				'twitter' => esc_url('https://twitter.com/#!/' . $args['twitter-id']),
				'appnet' => esc_url('https://alpha.app.net/' . $args['appnet-id']),
				'googleplus' => esc_url('https://plus.google.com/' . $args['googleplus-id']),
				'fanpage' => esc_url('https://www.facebook.com/' . $args['fanpage-id']),
			);

			echo '<ul>';

			// Twitter
			if((!empty($args['twitter-id'])) && (!empty($array_Counter['twitter-follower']))) {
				echo '<li>';
				echo '<div class="social-impact-widget twitter"><a href="' . $array_Profilelinks['twitter'] . '"><span><span class="social-impact-network">' . __('Twitter', $this->var_sTextdomain) . '</span><br /><span class="social-impact-count">' . __('Follower', $this->var_sTextdomain) . ': ' . $array_Counter['twitter-follower'] . '</span></span></a></div>';
				echo '</li>';
			} // END if((!empty($args['twitter-id'])) && (!empty($array_Counter['twitter-follower'])))

			// App.net
			if((!empty($args['appnet-id'])) && (!empty($array_Counter['appnet-follower']))) {
				echo '<li>';
				echo '<div class="social-impact-widget appnet"><a href="' . $array_Profilelinks['appnet'] . '"><span><span class="social-impact-network">' . __('App.net', $this->var_sTextdomain) . '</span><br /><span class="social-impact-count">' . __('Follower', $this->var_sTextdomain) . ': ' . $array_Counter['appnet-follower'] . '</span></span></a></div>';
				echo '</li>';
			} // END if((!empty($args['appnet-id'])) && (!empty($array_Counter['appnet-follower'])))

			// Google+
			if((!empty($args['googleplus-id'])) && (!empty($array_Counter['googleplus-circles']))) {
				echo '<li>';
				// 'https://plus.google.com/' . $var_sGoogleplusId . '/posts'
				echo '<div class="social-impact-widget googleplus"><a href="' . $array_Profilelinks['googleplus'] . '"><span><span class="social-impact-network">' . __('Google+', $this->var_sTextdomain) . '</span><br /><span class="social-impact-count">' . __('Circles', $this->var_sTextdomain) . ': ' . $array_Counter['googleplus-circles'] . '</span></span></a></div>';
				echo '</li>';
			} // END if((!empty($args['googleplus-id'])) && (!empty($array_Counter['googleplus-circles'])))

			// Facebook
			if((!empty($args['fanpage-id'])) && (!empty($array_Counter['facebook-fans']))) {
				echo '<li>';
				echo '<div class="social-impact-widget facebook"><a href="' . $array_Profilelinks['fanpage'] . '"><span><span class="social-impact-network">' . __('Facebook', $this->var_sTextdomain) . '</span><br /><span class="social-impact-count">' . __('Fans', $this->var_sTextdomain) . ': ' . $array_Counter['facebook-fans'] . '</span></span></a></div>';
				echo '</li>';
			} // END if((!empty($args['fanpage-id'])) && (!empty($array_Counter['facebook-fans'])))

			echo '</ul>';
		} // private function social_impact_output($args = array(), $position)

		/**
		 * App.net Follwer
		 *
		 * @since 1.5
		 * @author ppfeufer
		 *
		 * @param string $var_sAppnetId
		 */
		private function get_appnet_followers($var_sAppnetId, $var_bClearCache = false) {
			if(empty($var_sAppnetId)) {
				return false;
			} // END if(empty($var_sTwitterId))

			$var_iAppnetFollowerCount = $this->array_Options[$this->var_sArrayOptionsKey]['appnet-count'];

			if(get_transient('appnet-count') === false || $var_bClearCache === true) {
				delete_transient('appnet-count');

				$return_Appnet = $this->_helper_curl(sprintf('https://alpha-api.app.net/stream/0/users/@%1s',
					$var_sAppnetId
				), $this->var_sUserAgent);

				try {
					$obj_AppnetData = json_decode($return_Appnet);

					if($obj_AppnetData) {
						if(!empty($obj_AppnetData->data->counts->followers)) {
							$this->array_Options[$this->var_sArrayOptionsKey]['appnet-count'] = (int) $obj_AppnetData->data->counts->followers;
						} // END if(!empty($obj_AppnetData->data->counts->followers))
					} // END if($obj_AppnetData)
				} catch(Exception $e) {
					$this->array_Options[$this->var_sArrayOptionsKey]['appnet-count'] = (int) $var_iAppnetFollowerCount;
				}

				update_option('widget_social_impact_widget', $this->array_Options);
				set_transient('appnet-count', $this->array_Options[$this->var_sArrayOptionsKey]['appnet-count'], $this->var_iCachingTime);
			} // END if(get_transient('appnet-count') === false || $var_bClearCache === true)

			if($this->array_Options[$this->var_sArrayOptionsKey]['appnet-count'] == '0') {
				return ($var_iAppnetFollowerCount) ? $var_iAppnetFollowerCount : __('Some', $this->var_sTextdomain);
			} else {
				return $this->array_Options[$this->var_sArrayOptionsKey]['appnet-count'];
			} // END if($this->array_Options[$this->var_sArrayOptionsKey]['appnet-count'] == '0')
		} // END private function get_appnet_followers($var_sAppnetId, $var_bClearCache = false)

		/**
		 * Twitterfollower
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param string $var_sTwitterId
		 */
		private function get_twitter_followers($var_sTwitterId, $var_bClearCache = false) {
			if(empty($var_sTwitterId)) {
				return false;
			} // END if(empty($var_sTwitterId))

			$var_iTwitterFollowerCount = $this->array_Options[$this->var_sArrayOptionsKey]['twitter-count'];

			if(get_transient('twitter-count') === false || $var_bClearCache === true) {
				delete_transient('twitter-count');

				$return_Twitter = $this->_helper_curl('http://query.yahooapis.com/v1/public/yql?q=SELECT%20*%20from%20html%20where%20url=%22http://twitter.com/' . $var_sTwitterId . '%22%20AND%20xpath=%22//a[@class=%27js-nav%27]/strong%22&format=json');

				try {
					$obj_TwitterData = json_decode($return_Twitter);

					if($obj_TwitterData) {
						if(!empty($obj_TwitterData->query->results->strong[2])) {
							$this->array_Options[$this->var_sArrayOptionsKey]['twitter-count'] = $this->_format_twitter_followers($obj_TwitterData->query->results->strong[2]);
						} // END if(!empty($obj_TwitterData->query->results->strong[2]))
					} // ENDif($obj_TwitterData)
				} catch(Exception $e) {
					$this->array_Options[$this->var_sArrayOptionsKey]['twitter-count'] = (int) $var_iTwitterFollowerCount;
				}

				update_option('widget_social_impact_widget', $this->array_Options);
				set_transient('twitter-count', $this->array_Options[$this->var_sArrayOptionsKey]['twitter-count'], $this->var_iCachingTime);
			} // END if($this->array_Options[$this->var_sArrayOptionsKey]['twitter-api-timer'] < (mktime() - $this->var_iCachingTime) || $var_bClearCache === true)

			if($this->array_Options[$this->var_sArrayOptionsKey]['twitter-count'] == '0') {
				return ($var_iTwitterFollowerCount) ? $var_iTwitterFollowerCount : __('Some', $this->var_sTextdomain);
			} else {
				return $this->array_Options[$this->var_sArrayOptionsKey]['twitter-count'];
			} // END if($this->array_Options[$this->var_sArrayOptionsKey]['twitter-count'] == '0')
		} // END private function get_twitter_followers($var_sTwitterId)

		/**
		 * Google+ Circles
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param string $var_sGoogleplusId
		 */
		private function get_googleplus_circles($var_sGoogleplusId, $var_bClearCache = false) {
			if(empty($var_sGoogleplusId)) {
				return false;
			} // END if(empty($var_sGoogleplusId))

			$var_iGoogleplusCount = $this->array_Options[$this->var_sArrayOptionsKey]['googleplus-count'];

			if(get_transient('googleplus-count') === false || $var_bClearCache === true) {
				delete_transient('googleplus-count');

				$return_GooglePlus = $this->_helper_curl(sprintf('https://plus.google.com/_/socialgraph/lookup/incoming/?o=[null,null,"%1$s"]&n=100',
					$var_sGoogleplusId
				), $this->var_sUserAgent);

				try {
// 					preg_match('/\]\s*,,(\d+)\s*\]\s*\]/m', $return_GooglePlus, $matches);
					preg_match('/]\s]\s,,(\d+)]\s,/s', $return_GooglePlus, $matches);

					$var_iCircles = array_pop($matches);

					if(!empty($var_iCircles)) {
						$this->array_Options[$this->var_sArrayOptionsKey]['googleplus-count'] = (int) $var_iCircles;
					} // END if(!empty($var_iCircles))
				} catch(Exception $e) {}

				update_option('widget_social_impact_widget', $this->array_Options);
				set_transient('googleplus-count', $this->array_Options[$this->var_sArrayOptionsKey]['googleplus-count'], $this->var_iCachingTime);
			} // END if($this->array_Options[$this->var_sArrayOptionsKey]['googleplus-api-timer'] < (mktime() - $this->var_iCachingTime) || $var_bClearCache === true)

			if($this->array_Options[$this->var_sArrayOptionsKey]['googleplus-count'] == '0') {
				return ($var_iGoogleplusCount) ? $var_iGoogleplusCount : __('Some', $this->var_sTextdomain);
			} else {
				return $this->array_Options[$this->var_sArrayOptionsKey]['googleplus-count'];
			} // END if($this->array_Options[$this->var_sArrayOptionsKey]['googleplus-count'] == '0')
		} // END private function get_googleplus_circles($var_sGoogleplusId, $var_bClearCache = false)

		/**
		 * Facebook Fans
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param string $var_sFacebookId
		 */
		private function get_facebook_fans($var_sFacebookId, $var_bClearCache = false) {
			if(empty($var_sFacebookId)) {
				return false;
			} // END if(empty($var_sFacebookId))

			$var_iFacebookFancount = $this->array_Options[$this->var_sArrayOptionsKey]['fanpage-count'];

			if(get_transient('fanpage-count') === false || $var_bClearCache === true) {
				delete_transient('fanpage-count');

				$return_Facebook = $this->_helper_curl(sprintf('https://graph.facebook.com/%1$s',
					$var_sFacebookId
				), $this->var_sUserAgent);

				try {
					$json_Facebook = json_decode($return_Facebook);

					if(!empty($json_Facebook->likes)) {
						$this->array_Options[$this->var_sArrayOptionsKey]['fanpage-count'] = (int) $json_Facebook->likes;
					} else {
						$this->array_Options[$this->var_sArrayOptionsKey]['fanpage-count'] = (int) $var_iFacebookFancount;
					} // END if(!empty($json_Facebook->likes))
				} catch(Exception $e) {
					return false;
				}

				update_option('widget_social_impact_widget', $this->array_Options);
				set_transient('fanpage-count', $this->array_Options[$this->var_sArrayOptionsKey]['fanpage-count'], $this->var_iCachingTime);
			} // END if($this->array_Options[$this->var_sArrayOptionsKey]['facebook-api-timer'] < (mktime() - $this->var_iCachingTime) || $var_bClearCache === true)

			if($this->array_Options[$this->var_sArrayOptionsKey]['fanpage-count'] == '0') {
				return ($var_iFacebookFancount) ? $var_iFacebookFancount : __('Some', $this->var_sTextdomain);
			} else {
				return $this->array_Options[$this->var_sArrayOptionsKey]['fanpage-count'];
			} // END if($this->array_Options[$this->var_sArrayOptionsKey]['fanpage-count'] == '0')
		} // END private function get_facebook_fans($var_sFacebookId)

		/**
		 * Checking the twittername
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param string $var_sTwittername
		 */
		private function _sanitize_twittername($var_sTwittername) {
			if(strstr($var_sTwittername, 'http') || strstr($var_sTwittername, '/') || strstr($var_sTwittername, '@')) {
				$array_TwitterParts = explode('/', $var_sTwittername);

				if(is_array($array_TwitterParts)) {
					$var_sTwittername = str_replace('@', '', array_pop($array_TwitterParts));
				} // END if(is_array($array_TwitterParts))
			} // END if(strstr( $var_sTwittername, 'http') || strstr($var_sTwittername, '/') || strstr($var_sTwittername, '@'))

			return $var_sTwittername;
		} // END private function _sanitize_twittername($var_sTwittername)

		/**
		 * Helper for cURL
		 *
		 * <[[ NOTE ]]>
		 * We are not using wp_remote_get(); it will not work propper on every server.
		 * So we are using a simple cURL-call here. Make sure your PHP is compiled with cURL.
		 *
		 * @since 0.1
		 * @author ppfeufer
		 *
		 * @param string $var_sUrl
		 * @return mixed
		 */
		private function _helper_curl($var_sUrl, $var_sUserAgent = null) {
			if(ini_get('allow_url_fopen')) {
				$cUrl_Data = file_get_contents($var_sUrl);
			} else {
				if(function_exists('curl_init')) {
					$cUrl_Channel = curl_init($var_sUrl);
					curl_setopt($cUrl_Channel, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($cUrl_Channel, CURLOPT_HEADER, 0);

					// EDIT your domain to the next line:
					if($var_sUserAgent) {
						curl_setopt($cUrl_Channel, CURLOPT_USERAGENT, $var_sUserAgent);
					} else {
						curl_setopt($cUrl_Channel, CURLOPT_USERAGENT, $this->var_sUserAgent);
					}
					curl_setopt($cUrl_Channel, CURLOPT_TIMEOUT, 10);

					$cUrl_Data = curl_exec($cUrl_Channel);

					if(curl_errno($cUrl_Channel) !== 0 || curl_getinfo($cUrl_Channel, CURLINFO_HTTP_CODE) !== 200) {
						$cUrl_Data === false;
					} // END if(curl_errno($ch) !== 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200)

					curl_close($cUrl_Channel);
				}
			}

			return $cUrl_Data;
		} // END private function helper_curl($var_sUrl = '')

		function _update_notice() {
			$url = 'http://plugins.trac.wordpress.org/browser/social-impact-widget/trunk/readme.txt?format=txt';
			$data = $this->_helper_curl($url);

			if($data) {
				$matches = null;
				$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote($this->var_sPluginVersion) . '\s*=|$)~Uis';

				if(preg_match($regexp, $data, $matches)) {
					$changelog = (array) preg_split('~[\r\n]+~', trim($matches[1]));

					echo '</div><div class="update-message" style="font-weight: normal;"><strong>' . __('What\'s new:', $this->var_sTextdomain) . '</strong>';
					$ul = false;
					$version = 99;

					foreach($changelog as $index => $line) {
						if(version_compare($version, $this->var_sPluginVersion, ">")) {
							if(preg_match('~^\s*\*\s*~', $line)) {
								if(!$ul) {
									echo '<ul style="list-style: disc; margin-left: 20px;">';
									$ul = true;
								} // END if(!$ul)

								$line = preg_replace('~^\s*\*\s*~', '', $line);
								echo '<li>' . $line . '</li>';
							} else {
								if($ul) {
									echo '</ul>';
									$ul = false;
								} // END if($ul)

								$version = trim($line, " =");
								echo '<p style="margin: 5px 0;">' . htmlspecialchars($line) . '</p>';
							} // END if(preg_match('~^\s*\*\s*~', $line))
						} // END if(version_compare($version, TWOCLICK_SOCIALMEDIA_BUTTONS_VERSION,">"))
					} // END foreach($changelog as $index => $line)

					if($ul) {
						echo '</ul><div style="clear: left;"></div>';
					} // END if($ul)

					echo '</div>';
				} // END if(preg_match($regexp, $data, $matches))
			} // END if($data)
		} // END function _update_notice()

		/**
		 * <[ Helper ]>
		 * Returning the plugindata
		 *
		 * @author ppfeufer
		 * @since 1.0
		 *
		 * @return array
		 */
		private function _get_plugin_data() {
			$default_headers = array(
				'Name' => 'Plugin Name',
				'PluginURI' => 'Plugin URI',
				'Version' => 'Version',
				'Description' => 'Description',
				'Author' => 'Author',
				'AuthorURI' => 'Author URI',
				'TextDomain' => 'Text Domain',
				'DomainPath' => 'Domain Path',
			);

			$plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');

			$plugin_data['Title'] = $plugin_data['Name'];
			$plugin_data['AuthorName'] = $plugin_data['Author'];

			return $plugin_data;
		} // END private function _get_plugin_data()

		/**
		 * <[ Helper ]>
		 * Returning the current pluginversion
		 *
		 * @author ppfeufer
		 * @since 1.0
		 *
		 * @return string
		 */
		private function _get_plugin_version() {
			$array_PluginData = $this->_get_plugin_data();

			return $array_PluginData['Version'];
		} // END private function _get_plugin_version()

		/**
		 * Formatting Twitter Followers
		 *
		 * @since 1.6.1
		 * @author ppfeufer
		 *
		 * @param string $value
		 * @return number|boolean
		 */
		private function _format_twitter_followers($value) {
			if($value && $value != "" && $value != "-") {
				if(strpos($value, '.') < strpos($value, ',')) {
					$value = str_replace('.', '', $value);
					$value = strtr($value, ',', '.');
				} else {
					$value = str_replace(',', '', $value);
				} // END if(strpos($value, '.') < strpos($value, ','))

				return (float) $value;
			} // END if($value && $value != "" && $value != "-")

			return false;
		} // END function _format_twitter_followers($value)
	} // END class Social_Impact_Widget

	add_action('widgets_init', create_function('', 'return register_widget("Social_Impact_Widget");'));
} // END if(!class_exists('Social_Impact_Widget'))