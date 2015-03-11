<?php

	class Api {

		public static $url = "http://opindo.org";
		public static $api_key = '538o92367845667598';
		private $opindo = '';
		private $redirect = '';
		private $model = '';
		private $action = '';
		private $params = '';

		public static function login_short($method, $origin) {
			$url = Api::$url . '/'. $method . '?' . 'api_key=' . Api::$api_key . '&redirect=' . $origin;
			return $url;
		}

		public static function login($method, $origin, $question_id, $answer_id) {
			$url = Api::$url . '/'. $method . '?' . 'api_key=' . Api::$api_key . '&redirect=' . $origin . '&question_id=' . $question_id . '&answer_id=' . $answer_id;
			return $url;
		}

		public function send() {
			$redirect = admin_url('admin.php?page=');
			$redirect .= isset($_GET['redirect_to']) ? $_GET['redirect_to'] : 'opindo-admin';
			$this->params .= 'api_key='. Api::$api_key . '&redirect=' . $redirect;

			$url = Api::$url . '/api/'. $this->action . '?' . $this->params;

			return wp_redirect($url);
		}

		public static function toggle($redirect_to = NULL, $send_id = NULL) {
			$api = new Api;

			$redirect = $send_user_id == NULL ? admin_url('admin.php?page=') : '';
			$redirect .= $redirect_to != NULL ? $redirect_to : $action;

			$url = Api::$url . '/api/toggle' . '?api_key='. $api->api_key .'&redirect='. $redirect;
			if($send_user_id != NULL) $url .= '&id='. $id;
			return $url;
		}

		public static function form($action, $redirect_to = NULL, $send_user_id = NULL) {
			//$redirect = $send_user_id == NULL ? admin_url('admin.php?page=') : '';
			$redirect = admin_url('admin.php?page=');
			$redirect .= $redirect_to != NULL ? $redirect_to : $action;

			$url = Api::$url . '/api/' . $action . '?api_key='. Api::$api_key .'&redirect='. $redirect;
			if($send_user_id != NULL) $url .= '&id='. $_SESSION['user_id'];
			return $url;
		}

		public static function store($model) {
			$api = new Api;
			$api->model = $model;
			$api->action = 'store';
			$api->params = 'model=' . $model . '&';
			return $api;
		}

		public static function store_question($model) {
			$api = new Api;
			$api->model = 'Question';
			$api->action = 'store_question';
			$api->params = 'model=' . $model . '&';
			return $api;
		}

		public static function update($model, $id) {
			$api = new Api;
			$api->model = $model;
			$api->action = 'update';
			$api->params = 'id='. $id .'&model=' . $model . '&';
			return $api;
		}

		public function with(array $params) {

			foreach($params as $key => $value) {
				$this->params .= $key .'='. ($value) .'&';
			}
			return $this;
		}

		public function get_calling_function() {
			$callers = debug_backtrace();
			return $callers[2]['function'];
		}

		public static function sendPostData($url, $post) {
			$url = Api::$url . '/api' . $url . '?' . 'api_key=' . Api::$api_key . '&redirect=' . get_site_url();
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}

		public static function getData($url) {
			$ch = curl_init();
			$url = Api::$url . '/api' . $url . '?' . 'api_key=' . Api::$api_key . '&redirect=' . get_site_url();

			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// Follow if 30x redirects
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
			// Will return the response, if false it will print the response
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// Set the url
			curl_setopt($ch, CURLOPT_URL,$url);
			// Execute
			$result = curl_exec($ch);
			// Closing
			curl_close($ch);
			return $result;
		}
	}

?>