<?php

	/*
		Iterate through each item in $object. Concatenate into a string, adding a comma
		after each item unless it is the last item. Return as string. Leaving $property
		as null will put the value of the name property into the list ($object->name).
		Overwriting will make function use the given property.

		$object = {
			[0] => {"name" => "Red"},
			[1] => {"name" => "Blue"},            < becomes >         "Red, Blue, Orange"
			[2] => {"name" => "Orange"}
		}
	*/
	if(!function_exists('_log')){
		function _log( $message ) {
			if( WP_DEBUG === true ){
				if( is_array( $message ) || is_object( $message ) ){
					error_log( print_r( $message, true ) );
				} else {
					error_log( $message );
				}
			}
		}
	}

	function comma_list($object, $property = NULL) {
		$property = $property == NULL ? 'name' : $property;
		$string = '';
		$i = 1;

		if(is_object($object)) {
			$count = $object->count();

			foreach($object as $obj) {
				$string .= $obj->$property;
				if($i != $count) $string .= ', ';
				$i++;
			}
			return $string;
		}
	}

	function old_input($field) {
		if(isset($_POST[$field])) return sanitize_text_field($_POST[$field]);
		else if(isset($_GET[$field])) return sanitize_text_field($_GET[$field]);
		// else die(print_r($_POST));
	}

	function question_id() {
		if(isset($_GET['question'])) return sanitize_text_field($_GET['question']);
		else return false;
	}

	function success() {
		if(isset($_GET['success'])) return true;
		else return false;
	}

	function error($name, $bool = NULL) {
		$error_name = $name . '_error';
		if(isset($_GET[$error_name])) {
			if($bool == NULL) return '<p>' . $_GET[$error_name] .'</p>';
			else return true;
		}
		return false;
	}

	function if_active($url) {
		if($url == current_page()) return 'class="active"';
	}

	function unique_tags($questions, $relationship, $prop = NULL) {
		$prop = $prop == NULL ? 'name' : $prop;
		$tags;
		foreach($questions as $object) {
			foreach($object->$relationship as $item) {
				if(!in_array($item->$prop, $tags)) $tags = $item->$prop;
			}
		}

		$string = '';
		$i = 1;
		$count = count($tags);
		foreach($tags as $tag) {
			$string .= $tag;
			if($i != $count) $string .= ', ';
			$i++;
		}

		return $string;
	}

	function excerpt($text, $length = NULL) {
		if($length == NULL) $length = 30;
		$excerpt = mb_substr($text, 0, $length);
		if(strlen($text) > $length) $excerpt .= '...';

		return $excerpt;
	}

	/**
	 * BEGIN NEW CODE
	 * Author: Jonathan Siu
	 *
	 */

	function json_comma_list($url) {
		$result = Api::getData($url);
		$list = json_decode($result, true);
		$string = '';

		//if ($list != null) {
			$i = 1;
			$count = count($list);

			foreach($list as $key => $value) {
				$string .= $value[0]['name'];
				if($i != $count) $string .= ', ';
				$i++;
			}
		//}
		return $string;
	}

	function json_value($url) {
		$result = Api::getData($url);
		$obj = json_decode($result);

		return $obj->responses;
	}

?>