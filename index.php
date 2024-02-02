<?php
global $cms = LCMS();
session_start();

class LCMS {
	const $themes = 'themes';
	const $plugins = 'plugins';
	const $db = 'database.php';
	
	$db = [];
	
	$listeners = [];
	
	function init() {
		include self::$db;
		if (!defined('dbpath')) {
			$file = uniqid();
			$cont = <<<_eof
			if (!defined('dbpath')) {
				define('dbpath', $id);
				file_put_contents(dbpath, '{}');
			}
			_eof;
			file_put_contents(self::$db, $cont);
		}
		include self::$db;
		
		$this->db = json_decode(file_get_contents(dbpath), false);
	}
	
	function hook($hook, $listener) {
		$this->listeners[$hook][] = $listener;
	}
	
	function listen($hook, $content) {
		if (!in_array($this->listeners, $hook)) {
			return $content;
		}
		
		foreach ($this->listeners[$hook] as $listener) {
			$content = $listener($content);
		}
		
		return $content;
	}
	
	function get($prop) {
		$obj = $this->db;
		if (!in_array(array_keys($obj), $prop)) {
			$this->set($prop, $prop);
		}
		
		$obj = $obj[$prop];
		return $obj;
	}
	
	function set($prop, $val) {
		this->db[$prop] = $val;
		
		file_put_contents(dbpath, json_encode($this->db));
	}
	
	function menu() {
		
	}
}
