<?php

	require_once 'Api.php';

	class Me {

		public $email;
		public $name;

		public function __construct() {
			$this->email = get_option('admin_email');
			$this->name = get_option('blogname');
		}

		public function init() {
			// check if there is an Opindo user
			$o_user = $this->get_opindo_user($this->email);

			// if opindo user is not set, then create opindo user in opindo website
			if (empty($o_user)) {
				$url = '/user/create';
				$post = array(
					'name' => $this->name,
					'email' => $this->email
				);
				$result = Api::sendPostData($url, $post);
				$r_user = json_decode($result);

				$_SESSION['user_id'] = $r_user->{'id'};
			}
			// else set $name and $using_plugin fields for Opindo user and save to Opindo website
			else {
				$url = '/user/update';
				$post = array(
					'name' => $this->name,
					'email' => $this->email,
					'using_plugin' => true
				);
				$result = Api::sendPostData($url, $post);
				$r_user = json_decode($result);

				$_SESSION['user_id'] = $r_user->{'id'};
			}
		}

		public function get_opindo_user($email) {
			// set URL and appropriate field(s)
			$url = '/user/get/' . $email;
			$result = Api::getData($url);

			$user = json_decode($result);
			return $user;
		}

	    public function get_opindo_user_id() {
	    	if (!isset($_SESSION['user_id'])) {
				$o_user = $this->get_opindo_user($this->email);
				$_SESSION['user_id'] = $o_user->{'id'};

				return $_SESSION['user_id'];
			}
			else
				return $_SESSION['user_id'];
	    }

	    public static function get_opindo_plugin_homepage_banner() {
	    	$email = get_option('admin_email');
			$url = '/user/get/' . $email;
			$result = Api::getData($url);

			$o_user = json_decode($result);
			return $o_user->{'plugin_homepage_banner'};
	    }

	}

?>