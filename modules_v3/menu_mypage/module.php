<?php
// Classes and libraries for module system
//
// webtrees: Web based Family History software
// Copyright (C) 2012 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2010 John Finlay
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

class menu_mypage_WT_Module extends WT_Module implements WT_Module_Menu {
	// Extend WT_Module
	public function getTitle() {
		return /* I18N: Name of a module/menu */ WT_I18N::translate('My Page menu');
	}

	// Extend WT_Module
	public function getDescription() {
		return /* I18N: Description of the “Edit” module */ WT_I18N::translate('The My page menu item');
	}

	// Implement WT_Module_Menu
	public function defaultMenuOrder() {
		return 2;
	}

	// Implement WT_Module_Menu
	public function getMenu() {
		$menu=WT_MenuBar::getMyPageMenu();
		return $menu;
	}
}