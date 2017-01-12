<?php

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class familysearch_plugin extends research_base_plugin {
	static function getName() {
		return 'Family Search';
	}

	static function getPaySymbol() {
		return false;
	}

	static function getSearchArea() {
		return 'INT';
	}

	static function create_link($fullname, $givn, $first, $middle, $prefix, $surn, $surname, $birth_year) {
		return $link = 'https://familysearch.org/search/record/results#count=20&query=%2Bgivenname%3A%22' . $givn . '%22~%20%2Bsurname%3A%22' . $surname . '%22~';
	}

	static function create_sublink($fullname, $givn, $first, $middle, $prefix, $surn, $surname) {
		return false;
	}

	static function createLinkOnly() {
		return false;
	}

	static function encode_plus() {
		return false;
	}
}
