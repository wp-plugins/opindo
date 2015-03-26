<?php

	/* 	Plugin Name: Opindo
		Plugin URI: http://www.opindo.com/
		Description: Integrate the Opindo platform with your Wordpress site!
		Version: 1.0
		Author: Opindo Developer
		License: GPLv2 or later
	*/

	error_reporting(E_ALL);
	session_start();

	define( 'OPINDO__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	define( 'OPINDO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

	require_once(OPINDO__PLUGIN_DIR . '/classes/Api.php');
	require_once(OPINDO__PLUGIN_DIR . '/classes/Me.php');
	require_once(OPINDO__PLUGIN_DIR . '/classes/Site.php');
	require_once(OPINDO__PLUGIN_DIR . '/functions.php');

	register_activation_hook(__FILE__, 'opindo_activation');
	register_deactivation_hook(__FILE__, 'opindo_deactivation');

	//Configuration
	add_action('init', 'banner_init');
	add_action('admin_init', 'opindo_init');
	//add_action('loop_start', 'opindo_home_banner'); // this is not working
	add_action('admin_enqueue_scripts', 'opindo_scripts');
	add_action('admin_enqueue_scripts', 'opindo_styles');
	add_action('admin_menu', 'opindo_admin_menu');
	add_action('save_post', 'opindo_add_article_to_question');
	add_action('the_content', 'opindo_banner_question');

	function opindo_activation() {
		$me = new Me();
		$me->init();
	}

	function opindo_deactivation() {
	}

	function banner_init() {
		if (!isset($_SESSION['user_id'])) {
			$me = new Me();
			$me->get_opindo_user_id();
		}
	}

	function opindo_init() {
		if(isset($_GET['page'])) {
			if($_GET['page'] != 'opindo-add-question') echo '<script>localStorage.removeItem("data")</script>';
			if($_GET['page'] != 'opindo-edit-question') echo '<script>localStorage.removeItem("new_data")</script>';
		}
		if (!isset($_SESSION['user_id'])) {
			$me = new Me();
			$me->get_opindo_user_id();
		}
		opindo_settings_init();
		add_meta_box('feature_question', 'Add a question', 'opindo_view_choose_question', 'post', 'side', 'high', '');
	}

	function opindo_scripts() {
		if ( is_admin() ) {
			wp_enqueue_script('jquery');
			wp_register_script('general', OPINDO__PLUGIN_URL . 'js/general.js', array("jquery"));
			wp_enqueue_script('general');
		}
	}

	function opindo_styles() {
		if ( is_admin() ) {
			wp_enqueue_style('general', OPINDO__PLUGIN_URL . 'css/general.css');
		}
	}

	function opindo_settings_init() {
	    register_setting( 'pluginPage', 'opindo_settings' );

	    add_settings_section(
	        'opindo_pluginPage_section',
	        __( 'Blog Posts Questionnaire', 'opindo' ),
	        'opindo_settings_section_callback',
	        'pluginPage'
	    );

	    add_settings_field(
	        'opindo_facebook_app_id',
	        __( 'Facebook AppId', 'opindo' ),
	        'opindo_facebook_app_id_render',
	        'pluginPage',
	        'opindo_pluginPage_section'
	    );
	}

	function opindo_facebook_app_id_render() {
	    $options = get_option( 'opindo_settings' );
	    ?>
	    <input type='text' name='opindo_settings[opindo_facebook_app_id]' value='<?php echo $options['opindo_facebook_app_id']; ?>'>
	    <?php
	}

	function opindo_settings_section_callback() {
	    echo __( 'Enter AppId to enable Opindo user authentication', 'opindo' );
	}

	function opindo_admin_menu() {
		$image = OPINDO__PLUGIN_URL . 'images/logo.png';
		//Admin Menu
	    add_menu_page('Opindo Admin', 'Opindo', 'manage_options', 'opindo-admin', 'opindo_view_my_questions', $image, 6 );
	    add_submenu_page('opindo-admin', 'My Questions', 'My Questions', 'manage_options', 'opindo-admin', 'opindo_view_my_questions');
	    add_submenu_page('opindo-admin', 'Add a Question', 'Add a Question', 'manage_options', 'opindo-add-question', 'opindo_view_add_question');
	    add_submenu_page('opindo-admin', 'Trending Posts', 'Trending Posts', 'manage_options', 'opindo-trending', 'opindo_view_trending');
	    add_submenu_page('opindo-admin', 'Settings', 'Settings', 'manage_options', 'opindo-user-settings', 'opindo_user_settings');
	    //Invisible Menu
	    add_submenu_page('opindo-trending', 'Question Overview', 'Question Overview', 'manage_options', 'opindo-question-overview', 'opindo_view_question_overview');
	    add_submenu_page('opindo-trending', 'Edit Question', 'Edit Question', 'manage_options', 'opindo-edit-question', 'opindo_view_edit_question');
	}

	function opindo_get_view($view, array $variables = NULL) {
		if($variables != NULL) {
			foreach($variables as $variable => $value) $$variable = $value;
		}
		require_once 'views/layouts/header.php';
		require_once 'views/'. $view .'.php';
		require_once 'views/layouts/footer.php';
	}

	function opindo_get_banner($content, $view, array $variables = NULL) {
		if($variables != NULL) {
			foreach($variables as $variable => $value) $$variable = $value;
		}
		if($content != NULL) echo $content;
		require_once 'views/'. $view .'.php';
	}

	function opindo_user_settings() {
		$plugin_homepage_banner = Me::get_opindo_plugin_homepage_banner();
		opindo_get_view('admin_user_settings', array('show_banner' => $plugin_homepage_banner));
	}

	// function opindo_home_banner($content) {
	// 	if(is_home()) {
	// 		$plugin_homepage_banner = Me::get_opindo_plugin_homepage_banner();
	// 		if($plugin_homepage_banner) {
	// 			$url = '/questions/get/' . $_SESSION['user_id'];
	// 			$result = Api::getData($url);
	// 			$questions = json_decode($result, true);

	// 		 	if(count($questions['questions']) > 0) opindo_get_banner(NULL, 'banner_homepage', $questions);
	// 		}
	// 	}
	// }

	function opindo_banner_question($content) {
		if(!is_home()) {
			// get question
			$url = '/question/banner/get/' . $_SESSION['user_id'] . '/' . get_the_ID();
			$result = Api::getData($url);
			$question = json_decode($result, true);

			if(count($question['question']) > 0) {
				opindo_get_banner($content, 'banner_question', array('question' => $question['question']));
			}
			else {
				return $content;
			}
		}
		else {
			return $content;
		}
	}

	function wpb_adding_scripts() {
		if(!is_home()) {
			wp_enqueue_script( 'jquery' );
			wp_register_script('google-charts', OPINDO__PLUGIN_URL . 'js/google-charts.js','1.0', false);
			wp_enqueue_script('google-charts');
			wp_register_script('opindo-chart', OPINDO__PLUGIN_URL . 'js/chart-config.js','1.0', false);
			wp_enqueue_script('opindo-chart');
			wp_register_script('opindo-plugin', OPINDO__PLUGIN_URL . 'js/opindo-plugin.js','1.0', false);
			wp_enqueue_script('opindo-plugin');
		}
		wp_register_style('opindo-css', OPINDO__PLUGIN_URL . 'css/plugin-horizontal.css','1.0', false);
		wp_enqueue_style('opindo-css');
	}
	add_action( 'wp_enqueue_scripts', 'wpb_adding_scripts' );

	function opindo_view_my_questions() {
		$url = '/questions/get/' . $_SESSION['user_id'];
		$result = Api::getData($url);
		$questions = json_decode($result, true);
		opindo_get_view('admin_my_questions', $questions);
	}

	function opindo_view_question_overview() {
		$url = '/question/get/' . question_id();
		$result = Api::getData($url);
		$question = json_decode($result, true);
		opindo_get_view('admin_question_overview', $question);
	}

	function opindo_view_add_question() {
		if(success()) return opindo_view_my_questions();
		opindo_get_view('admin_add_question');
	}

	function opindo_view_edit_question() {
		if(success()) return opindo_view_my_questions();
		$url = '/question/get/' . question_id();
		$result = Api::getData($url);
		$question = json_decode($result, true);
		opindo_get_view('admin_edit_question', $question);
	}

	function opindo_view_trending() {
		$url = '/questions/trending/get/' . $_SESSION['user_id'];
		$result = Api::getData($url);
		$resources = json_decode($result, true);
		opindo_get_view('admin_view_trending', $resources);
	}

	function opindo_view_choose_question() {
		// Set selected question if exists
		if(isset($_GET['post'])){
			$user_id = $_SESSION['user_id'];

			// Get Resources for current post
			$url = '/resource/get/' . $user_id . '/' . sanitize_text_field($_GET['post']);
			$result = Api::getData($url);
			$current_resource = json_decode($result, true);

			if(count($current_resource['resource']) > 0) {
				// Get QuestionResources for User and Resource
				$url = '/questionresource/get/' . $user_id . '/' . $current_resource['resource']['id'] ;
				$result = Api::getData($url);
				$current_question = json_decode($result, true);
			}
			else $current_question = false;

			// Get questions for Opindo User
			$url = '/questions/get/' . $user_id;
			$result = Api::getData($url);
			$questions = json_decode($result, true);

			// Get Resource Types for Opindo user
			$url = '/resourcetype/get/';
			$result = Api::getData($url);
			$types = json_decode($result, true);

			opindo_get_view('admin_choose_question', array(
				'questions' => $questions,
				'types' => $types,
				'current_question' => $current_question,
				'current_resource' => $current_resource
			));
		}
	}

	function opindo_add_article_to_question() {
		if(isset($_POST['choose_question'])) {
			$user_id = $_SESSION['user_id'];
			$question_id = 0; // Set question_id to 0 - None
			$post_id = sanitize_text_field($_POST['post_ID']);
			$resource_type_id = sanitize_text_field($_POST['choose_question_type']);

			// a post can only have one question
			// check if post has a Question via Resource
			// find resource with user_id, post_id
			$url = '/resource/get/' . $user_id . '/' . $post_id;
			$result = Api::getData($url);
			$resource = json_decode($result, true);

			// Does the Resource already exist for this post?
			// If yes, delete it and we'll create a new one with updated info
			if(count($resource['resource']) > 0) {
				// Delete user/post Resource
				$url = '/resource/delete/' . $resource['resource']['id'];
				$result = Api::getData($url);

				// check if QuestionResource exists
				$url = '/questionresource/get/' . $user_id . '/' . $resource['resource']['id'];
				$result = Api::getData($url);
				$q_resource = json_decode($result, true);

				// if it does, delete related QuestionResource
				if(count($q_resource['questionresource']) > 0) {
					$url = '/questionresource/delete/' . $user_id . '/' . $question_id . '/' . $resource['id'];
					$result = Api::getData($url);
					$questionresource = json_decode($result, true);
				}
			}

			// Get new question_id if it's not 'None'
			if($_POST['choose_question'] != 0) {
				$me = new Me();
				$question_id = $_POST['choose_question'];
				$post_url = get_permalink($post_id);

				// Create NEW resource (content could have changed)
				$url = '/resource/create';
				$post = array(
					'name' => sanitize_text_field($_POST['post_title']),
					'description' => excerpt(sanitize_text_field($_POST['post_content']), 100),
					'url' => $post_url,
					'image' => "",
					'author' => $me->name,
					'date' => date('Y-m-d'),
					'resource_type_id' => sanitize_text_field($resource_type_id),
					'post_id' => $post_id,
					'created_by' => $user_id,
				);
				$result = Api::sendPostData($url, $post);
				$resource = json_decode($result, true);

				$resource_id = $resource['resource']['id'];

				// create new QuestionResource
				$url = '/questionresource/create';
				$post = array(
					'created_by' => $user_id,
					'question_id' => $question_id,
					'resource_id' => $resource_id
				);
				$result = Api::sendPostData($url, $post);
				$questionresource = json_decode($result);
			}
		}

	}
?>
