<?php defined('SYNCSYSTEM') || die('No direct script access.');

class SYNC {
	public static $CONFIG = array();
	public function init_ignores(){
		GLOBAL $IGNORES;
		$IGNORES = '';
		if(isset(self::$CONFIG['IGNORE_FILE_LIST'])){
			$IGNORES =  implode('|', self::$CONFIG['IGNORE_FILE_LIST']);
			$IGNORES = addcslashes($IGNORES, '.');
			$IGNORES = strtr($IGNORES, array('?' => '.?', '*' => '.*'));
			$IGNORES = '/^('.$IGNORES.')$/i';
		}
	}
}