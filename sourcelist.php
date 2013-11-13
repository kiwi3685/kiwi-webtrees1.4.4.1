<?php
// Parses gedcom file and displays a list of the sources in the file.
//
// webtrees: Web based Family History software
// Copyright (C) 2013 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// $Id$

define('WT_SCRIPT_NAME', 'sourcelist.php');
require './includes/session.php';
require_once WT_ROOT.'includes/functions/functions_print_lists.php';

$controller=new WT_Controller_Page();
$controller->setPageTitle(WT_I18N::translate('Sources'));
$controller->pageHeader();

echo '<div id="sourcelist-page">',
	'<h2>', WT_I18N::translate('Sources'), '</h2>';
	echo format_sour_table(get_source_list(WT_GED_ID));
echo '</div>';
