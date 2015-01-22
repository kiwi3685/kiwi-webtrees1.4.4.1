<?php
// Base controller for all chart pages
//
// Kiwitrees: Web based Family History software
// Copyright (C) 2015 kiwitrees.net
//
// Derived from webtrees
// Copyright (C) 2012 webtrees development team
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class WT_Controller_Chart extends WT_Controller_Page {
	public $root;
	public $rootid;
	public $error_message=null;

	public function __construct() {
		parent::__construct();

		$this->rootid = safe_GET_xref('rootid');
		if ($this->rootid) {
			$this->root = WT_Person::getInstance($this->rootid);
		} else {
			// Missing rootid parameter?  Do something.
			$this->root   = $this->getSignificantIndividual();
			$this->rootid = $this->root->getXref();
		}
		
		if (!$this->root || !$this->root->canDisplayName()) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			$this->error_message=WT_I18N::translate('This individual does not exist or you do not have permission to view it.');
			$this->rootid=null;
		}
	}

	public function getSignificantIndividual() {
		if ($this->root) {
			return $this->root;
		} else {
			return parent::getSignificantIndividual();
		}
	}
}
