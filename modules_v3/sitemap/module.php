<?php
// Classes and libraries for module system
//
// Kiwitrees: Web based Family History software
// Copyright (C) 2015 kiwitrees.net
//
// Derived from webtrees
// Copyright (C) 2012 webtrees development team
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

class sitemap_WT_Module extends WT_Module implements WT_Module_Config {
	const RECORDS_PER_VOLUME=500;    // Keep sitemap files small, for memory, CPU and max_allowed_packet limits.
	const CACHE_LIFE        =1209600; // Two weeks
	
	// Extend WT_Module
	public function getTitle() {
		return /* I18N: Name of a module - see http://en.wikipedia.org/wiki/Sitemaps */ WT_I18N::translate('Sitemaps');
	}

	// Extend WT_Module
	public function getDescription() {
		return /* I18N: Description of the “Sitemaps” module */ WT_I18N::translate('Generate sitemap files for search engines.');
	}

	// Extend WT_Module
	public function modAction($mod_action) {
		switch($mod_action) {
		case 'admin':
			$this->admin();
			break;
		case 'generate':
			Zend_Session::writeClose();
			$this->generate(safe_GET('file'));
			break;
		default:
			header('HTTP/1.0 404 Not Found');
		}
	}

	private function generate($file) {
		if ($file=='sitemap.xml') {
			$this->generate_index();
		} elseif (preg_match('/^sitemap-(\d+)-([isrmn])-(\d+).xml$/', $file, $match)) {
			$this->generate_file($match[1], $match[2], $match[3]);
		} else {
			header('HTTP/1.0 404 Not Found');
		}
	}

	// The index file contains references to all the other files.
	// These files are the same for visitors/users/admins.
	private function generate_index() {
		// Check the cache
		$timestamp=get_module_setting($this->getName(), 'sitemap.timestamp');
		if ($timestamp > WT_TIMESTAMP - self::CACHE_LIFE) {
			$data=get_module_setting($this->getName(), 'sitemap.xml');
		} else {
			$data='';
			$lastmod='<lastmod>'.date('Y-m-d').'</lastmod>';
			foreach (WT_Tree::getAll() as $tree) {
				if (get_gedcom_setting($tree->tree_id, 'include_in_sitemap')) {
					$n=WT_DB::prepare("SELECT COUNT(*) FROM `##individuals` WHERE i_file=?")->execute(array($tree->tree_id))->fetchOne();
					for ($i=0; $i<=$n/self::RECORDS_PER_VOLUME; ++$i) {
						$data.='<sitemap><loc>'.WT_SERVER_NAME.WT_SCRIPT_PATH.'module.php?mod='.$this->getName().'&amp;mod_action=generate&amp;file=sitemap-'.$tree->tree_id.'-i-'.$i.'.xml</loc>'.$lastmod.'</sitemap>'.PHP_EOL;
					}
					$n=WT_DB::prepare("SELECT COUNT(*) FROM `##sources` WHERE s_file=?")->execute(array($tree->tree_id))->fetchOne();
					if ($n) {
						for ($i=0; $i<=$n/self::RECORDS_PER_VOLUME; ++$i) {
							$data.='<sitemap><loc>'.WT_SERVER_NAME.WT_SCRIPT_PATH.'module.php?mod='.$this->getName().'&amp;mod_action=generate&amp;file=sitemap-'.$tree->tree_id.'-s-'.$i.'.xml</loc>'.$lastmod.'</sitemap>'.PHP_EOL;
						}
					}
					$n=WT_DB::prepare("SELECT COUNT(*) FROM `##other` WHERE o_file=? AND o_type='REPO'")->execute(array($tree->tree_id))->fetchOne();
					if ($n) {
						for ($i=0; $i<=$n/self::RECORDS_PER_VOLUME; ++$i) {
							$data.='<sitemap><loc>'.WT_SERVER_NAME.WT_SCRIPT_PATH.'module.php?mod='.$this->getName().'&amp;mod_action=generate&amp;file=sitemap-'.$tree->tree_id.'-r-'.$i.'.xml</loc>'.$lastmod.'</sitemap>'.PHP_EOL;
						}
					}
					$n=WT_DB::prepare("SELECT COUNT(*) FROM `##other` WHERE o_file=? AND o_type='NOTE'")->execute(array($tree->tree_id))->fetchOne();
					if ($n) {
						for ($i=0; $i<=$n/self::RECORDS_PER_VOLUME; ++$i) {
							$data.='<sitemap><loc>'.WT_SERVER_NAME.WT_SCRIPT_PATH.'module.php?mod='.$this->getName().'&amp;mod_action=generate&amp;file=sitemap-'.$tree->tree_id.'-n-'.$i.'.xml</loc>'.$lastmod.'</sitemap>'.PHP_EOL;
						}
					}
					$n=WT_DB::prepare("SELECT COUNT(*) FROM `##media` WHERE m_file=?")->execute(array($tree->tree_id))->fetchOne();
					if ($n) {
						for ($i=0; $i<=$n/self::RECORDS_PER_VOLUME; ++$i) {
							$data.='<sitemap><loc>'.WT_SERVER_NAME.WT_SCRIPT_PATH.'module.php?mod='.$this->getName().'&amp;mod_action=generate&amp;file=sitemap-'.$tree->tree_id.'-m-'.$i.'.xml</loc>'.$lastmod.'</sitemap>'.PHP_EOL;
						}
					}
				}
			}
			$data='<'.'?xml version="1.0" encoding="UTF-8" ?'.'>'.PHP_EOL.'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL.$data.'</sitemapindex>'.PHP_EOL;
			// Cache this data
			set_module_setting($this->getName(), 'sitemap.xml', $data);
			set_module_setting($this->getName(), 'sitemap.timestamp', WT_TIMESTAMP);
		}
		header('Content-Type: application/xml');
		header('Content-Length: '.strlen($data));
		echo $data;
	}

	// A separate file for each family tree and each record type.
	// These files depend on access levels, so only cache for visitors.
	private function generate_file($ged_id, $rec_type, $volume) {
		// Check the cache
		$timestamp=get_module_setting($this->getName(), 'sitemap-'.$ged_id.'-'.$rec_type.'-'.$volume.'.timestamp');
		if ($timestamp > WT_TIMESTAMP - self::CACHE_LIFE && !WT_USER_ID) {
			$data=get_module_setting($this->getName(), 'sitemap-'.$ged_id.'-'.$rec_type.'-'.$volume.'.xml');
		} else {
			$tree=WT_Tree::get($ged_id);
			$data='<url><loc>'.WT_SERVER_NAME.WT_SCRIPT_PATH.'index.php?ctype=gedcom&amp;ged='.$tree->tree_name_url.'</loc></url>'.PHP_EOL;
			$records=array();
			switch ($rec_type) {
			case 'i':
				$rows=WT_DB::prepare(
					"SELECT 'INDI' AS type, i_id AS xref, i_file AS ged_id, i_gedcom AS gedrec".
					" FROM `##individuals`".
					" WHERE i_file=?".
					" ORDER BY i_id".
					" LIMIT ".self::RECORDS_PER_VOLUME." OFFSET ".($volume*self::RECORDS_PER_VOLUME)
				)->execute(array($ged_id))->fetchAll(PDO::FETCH_ASSOC);
				foreach ($rows as $row) {
					$records[]=WT_Person::getInstance($row);
				}
				break;
			case 's':
				$rows=WT_DB::prepare(
					"SELECT 'SOUR' AS type, s_id AS xref, s_file AS ged_id, s_gedcom AS gedrec".
					" FROM `##sources`".
					" WHERE s_file=?".
					" ORDER BY s_id".
					" LIMIT ".self::RECORDS_PER_VOLUME." OFFSET ".($volume*self::RECORDS_PER_VOLUME)
				)->execute(array($ged_id))->fetchAll(PDO::FETCH_ASSOC);
				foreach ($rows as $row) {
					$records[]=WT_Source::getInstance($row);
				}
				break;
			case 'r':
				$rows=WT_DB::prepare(
					"SELECT 'REPO' AS type, o_id AS xref, o_file AS ged_id, o_gedcom AS gedrec".
					" FROM `##other`".
					" WHERE o_file=? AND o_type='REPO'".
					" ORDER BY o_id".
					" LIMIT ".self::RECORDS_PER_VOLUME." OFFSET ".($volume*self::RECORDS_PER_VOLUME)
				)->execute(array($ged_id))->fetchAll(PDO::FETCH_ASSOC);
				foreach ($rows as $row) {
					$records[]=WT_Repository::getInstance($row);
				}
				break;
			case 'n':
				$rows=WT_DB::prepare(
					"SELECT 'NOTE' AS type, o_id AS xref, o_file AS ged_id, o_gedcom AS gedrec".
					" FROM `##other`".
					" WHERE o_file=? AND o_type='NOTE'".
					" ORDER BY o_id".
					" LIMIT ".self::RECORDS_PER_VOLUME." OFFSET ".($volume*self::RECORDS_PER_VOLUME)
				)->execute(array($ged_id))->fetchAll(PDO::FETCH_ASSOC);
				foreach ($rows as $row) {
					$records[]=WT_Note::getInstance($row);
				}
				break;
			case 'm':
				$rows=WT_DB::prepare(
					"SELECT 'OBJE' AS type, m_id AS xref, m_file AS ged_id, m_gedcom AS gedrec, m_titl, m_filename".
					" FROM `##media`".
					" WHERE m_file=?".
					" ORDER BY m_id".
					" LIMIT ".self::RECORDS_PER_VOLUME." OFFSET ".($volume*self::RECORDS_PER_VOLUME)
				)->execute(array($ged_id))->fetchAll(PDO::FETCH_ASSOC);
				foreach ($rows as $row) {
					$records[]=WT_Media::getInstance($row);
				}
				break;
			}
			foreach ($records as $record) {
				if ($record->canDisplayName()) {
					$data.='<url>';
					$data.='<loc>'.WT_SERVER_NAME.WT_SCRIPT_PATH.$record->getHtmlUrl().'</loc>';
					$chan=$record->getChangeEvent();
					if ($chan) {
						$date=$chan->getDate();
						if ($date->isOK()) {
							$data.='<lastmod>'.$date->minDate()->Format('%Y-%m-%d').'</lastmod>';
						}
					}
					$data.='</url>'.PHP_EOL;
				}
			}
			$data='<'.'?xml version="1.0" encoding="UTF-8" ?'.'>'.PHP_EOL.'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'.PHP_EOL.$data.'</urlset>'.PHP_EOL;
			// Cache this data - but only for visitors, as we don’t want
			// visitors to see data created by logged-in users.
			if (!WT_USER_ID) {
				set_module_setting($this->getName(), 'sitemap-'.$ged_id.'-'.$rec_type.'-'.$volume.'.xml', $data);
				set_module_setting($this->getName(), 'sitemap-'.$ged_id.'-'.$rec_type.'-'.$volume.'.timestamp', WT_TIMESTAMP);
			}
		 }
		header('Content-Type: application/xml');
		header('Content-Length: '.strlen($data));
		echo $data;
	}

	private function admin() {
		$controller=new WT_Controller_Page();
		$controller
			->requireAdminLogin()
			->setPageTitle($this->getTitle())
			->pageHeader();

		// Save the updated preferences
		if (safe_POST('action', 'save')=='save') {
			foreach (WT_Tree::getAll() as $tree) {
				set_gedcom_setting($tree->tree_id, 'include_in_sitemap', safe_POST_bool('include'.$tree->tree_id));
			}
			// Clear cache and force files to be regenerated
			WT_DB::prepare(
				"DELETE FROM `##module_setting` WHERE setting_name LIKE 'sitemap%'"
			)->execute();
		}

		$include_any=false;
		echo
			'<h3>', $this->getTitle(), '</h3>',
			'<p>',
			/* I18N: The www.sitemaps.org site is translated into many languages (e.g. http://www.sitemaps.org/fr/) - choose an appropriate URL. */
			WT_I18N::translate('Sitemaps are a way for webmasters to tell search engines about the pages on a website that are available for crawling.  All major search engines support sitemaps.  For more information, see <a href="http://www.sitemaps.org/">www.sitemaps.org</a>.').
			'</p>',
			'<p>', WT_I18N::translate('Which family trees should be included in the sitemaps?'), '</p>',
			'<form method="post" action="module.php?mod=' . $this->getName() . '&amp;mod_action=admin">',
			'<input type="hidden" name="action" value="save">';
		foreach (WT_Tree::getAll() as $tree) {
			echo '<p><input type="checkbox" name="include', $tree->tree_id, '"';
			if (get_gedcom_setting($tree->tree_id, 'include_in_sitemap')) {
				echo ' checked="checked"';
				$include_any=true;
			}
			echo '>', $tree->tree_title_html, '</p>';
		}
		echo
			'<input type="submit" value="', WT_I18N::translate('save'), '">',
			'</form>',
			'<hr>';

		if ($include_any) {
			$site_map_url1=WT_SERVER_NAME.WT_SCRIPT_PATH.'module.php?mod='.$this->getName().'&amp;mod_action=generate&amp;file=sitemap.xml';
			$site_map_url2=rawurlencode(WT_SERVER_NAME.WT_SCRIPT_PATH.'module.php?mod='.$this->getName().'&mod_action=generate&file=sitemap.xml');
			echo '<p>', WT_I18N::translate('To tell search engines that sitemaps are available, you should add the following line to your robots.txt file.'), '</p>';
			echo
				'<pre>Sitemap: ', $site_map_url1, '</pre>',
				'<hr>',
				'<p>', WT_I18N::translate('To tell search engines that sitemaps are available, you can use the following links.'), '</p>',
				'<ul>',
				// This list comes from http://en.wikipedia.org/wiki/Sitemaps
				'<li><a target="_new" href="http://www.bing.com/webmaster/ping.aspx?siteMap='.$site_map_url2.'">Bing</a></li>',
				'<li><a target="_new" href="http://www.google.com/webmasters/tools/ping?sitemap='.$site_map_url2.'">Google</a></li>',
				'</ul>';

		}
	}

	// Implement WT_Module_Config
	public function getConfigLink() {
		return 'module.php?mod='.$this->getName().'&amp;mod_action=admin';
	}
}
