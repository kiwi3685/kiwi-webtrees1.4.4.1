<?php

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class freebmd_plugin extends research_base_plugin {
	static function getName() {
		return 'Free BMD';
	}

	static function getPaySymbol() {
		return false;
	}

	static function getSearchArea() {
		return 'GBR';
	}

	static function create_link($fullname, $givn, $first, $middle, $prefix, $surn, $surname, $birth_year, $death_year) {
		// This is a post form, so it will be sent with Javascript
		$birth_year == '' ? $birth_year = '' : $birth_year = $birth_year - 5;
		$death_year == '' ? $death_year = '' : $death_year = $death_year + 5;
		$url	 	= 'http://www.freebmd.org.uk/cgi/search.pl';
		$params	 	= array(
			'type'		=> 'All Types',
			'surname'	=> $surn,
			'given'		=> $first,
			'sq'		=> '1',
			'start'		=> $birth_year,
			'eq'		=> '4',
			'end'		=> $death_year,
		);
		return "postresearchform('" . $url . "'," . json_encode($params) . ")";
	}

	static function create_sublink($fullname, $givn, $first, $middle, $prefix, $surn, $surname, $birth_year, $death_year) {
		return false;
	}

	static function createLinkOnly() {
		return false;
	}

	static function createSubLinksOnly() {
		return false;
	}

	static function encode_plus() {
		return false;
	}

}