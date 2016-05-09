<?php
// Function for printing facts
//
// Various printing functions used to print fact records
//
// Kiwitrees: Web based Family History software
// Copyright (C) 2016 kiwitrees.net
//
// Derived from webtrees
// Copyright (C) 2012 webtrees development team
//
// Derived from PhpGedView
// Copyright (C) 2002 to 2010  PGV Development Team
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

// Print a fact record, for the individual/family/source/repository/etc. pages.
//
// Although a WT_Event has a parent object, we also need to know
// the WT_GedcomRecord for which we are printing it.  For example,
// we can show the death of X on the page of Y, or the marriage
// of X+Y on the page of Z.  We need to know both records to
// calculate ages, relationships, etc.
//
// This is a copy of function print_fact() (functions_print_facts.php) without date and place for different formatting purposes
//
function print_resourcefactDetails(WT_Event $fact, WT_GedcomRecord $record) {
	global $HIDE_GEDCOM_ERRORS;
	$SHOW_PARENTS_AGE = false; // not required on resource prints
	$html = '';

	static $n_chil = 0, $n_gchi = 0;

	if (!$fact->canShow()) {
		return;
	}

	// Print the value of this fact/event
	switch ($fact->getTag()) {
	case 'ADDR':
		print_address_structure($fact->getGedcomRecord(), 1);
		break;
	case 'AFN':
		$html .= '<a href="https://familysearch.org/search/tree/results#count=20&query=afn:' . rawurlencode($fact->getDetail()) . '" target="new">' . htmlspecialchars($fact->getDetail()) . '</a>';
		break;
	case 'ASSO':
		// we handle this later, in print_asso_rela_record()
		break;
	case 'BURI':
		// include CEME if recorded
		if (preg_match('/\n2 CEME (.+)/', $fact->getGedcomRecord(), $match)) {
			$html .= WT_Gedcom_Tag::getLabelValue('CEME', $match[1]);
		} else {
			$html .= '&nbsp;';
		}
		break;
	case 'EMAIL':
	case 'EMAI':
	case '_EMAIL':
		$html .= '<a href="mailto:' . htmlspecialchars($fact->getDetail()) . '">' . htmlspecialchars($fact->getDetail()) . '</a>';
		break;
	case 'FILE':
		if (WT_USER_CAN_EDIT || WT_USER_CAN_ACCEPT) {
			$html .= htmlspecialchars($fact->getDetail());
		}
		break;
	case 'RESN':
		$html .= '';
		switch ($fact->getDetail()) {
		case 'none':
			// Note: "1 RESN none" is not valid gedcom.
			// However, webtrees privacy rules will interpret it as "show an otherwise private record to public".
			$html .= '<i class="icon-resn-none"></i> ' . WT_I18N::translate('Show to visitors');
			break;
		case 'privacy':
			$html .= '<i class="icon-class-none"></i> ' . WT_I18N::translate('Show to members');
			break;
		case 'confidential':
			$html .= '<i class="icon-confidential-none"></i> ' . WT_I18N::translate('Show to managers');
			break;
		case 'locked':
			$html .= '<i class="icon-locked-none"></i> ' . WT_I18N::translate('Only managers can edit');
			break;
		default:
			$html .= htmlspecialchars($fact->getDetail());
			break;
		}
		$html .= '';
		break;
	case 'PUBL': // Publication details might contain URLs.
		$html .= expand_urls(htmlspecialchars($fact->getDetail()));
		break;
	case 'REPO':
		if (preg_match('/^@('.WT_REGEX_XREF.')@$/' . $fact->getDetail() . $match)) {
			print_repository_record($match[1]);
		} else {
			$html .= '<div class="error">' . htmlspecialchars($fact->getDetail()) . '</div>';
		}
		break;
	case 'URL':
	case '_URL':
	case 'WWW':
		$html .= '<a href="' . htmlspecialchars($fact->getDetail()) . '">' . htmlspecialchars($fact->getDetail()) . '</a>';
		break;
	case 'TEXT': // 0 SOUR / 1 TEXT
		// PHP5.3 $html .= nl2br(htmlspecialchars($fact->getDetail()) . false) . '</div>';
		$html .= nl2br(htmlspecialchars($fact->getDetail()));
		break;
	default:
		// Display the value for all other facts/events
		switch ($fact->getDetail()) {
		case '':
			// Nothing to display
			$html .= '&nbsp;';
			break;
		case 'N':
			// Not valid GEDCOM
			$html .= WT_I18N::translate('No');
			break;
		case 'Y':
			// Do not display "Yes".
			break;
		default:
			if (preg_match('/^@('.WT_REGEX_XREF.')@$/', $fact->getDetail(), $match)) {
				$target = WT_GedcomRecord::getInstance($match[1]);
				if ($target) {
					$html .= '<div><a href="' . $target->getHtmlUrl() . '">' . $target->getFullName() . '</a></div>';
				} else {
					$html .= '<div class="error">' . htmlspecialchars($fact->getDetail()) . '</div>';
				}
			} else {
				$html .= '<span dir="auto">' . htmlspecialchars($fact->getDetail()) . '</span>';
			}
			break;
		}
		break;
	}

	return $html;

}
//end print resource fact function

function resource_images($person) {
	$level = 1;
	$regexp = '/\n' . $level . ' OBJE @(.*)@/';

	//-- get a list of the current objects in the record
	$current_objes = array();
	$ct = preg_match_all($regexp, $person->getGedcomRecord(), $match, PREG_SET_ORDER);
	for ($i = 0; $i < $ct; $i++) {
		if (!isset($current_objes[$match[$i][1]])) {
			$current_objes[$match[$i][1]] = 1;
		} else {
			$current_objes[$match[$i][1]]++;
		}
		$obje_links[$match[$i][1]][] = $match[$i][0];
	}
}

// Print a row for the sources tab on the individual page
function resource_sources(WT_Event $fact, $level, $source_num) {
	$fact	= $fact->getGedcomRecord();
	$data 		= array();
	// -- find sources for each fact
	$ct = preg_match_all("/($level SOUR (.+))/", $fact, $match, PREG_SET_ORDER);
	$spos2 = 0;
	for ($j = 0; $j < $ct; $j++) {
		$details = '';
		$sid	= trim($match[$j][2], '@');
		$spos1	= stripos($fact, $match[$j][1], $spos2);
		$spos2	= stripos($fact, "\n$level", $spos1);
		if (!$spos2) $spos2 = strlen($fact);
		$srec	= substr($fact, $spos1, $spos2-$spos1);
		$source	= WT_Source::getInstance($sid);
		if ($source) {
			// AUTH
			$auth = get_gedcom_value('AUTH', '1', $source->getGedcomRecord());
			if (!empty($auth)) {
				$details .= $auth . ', ';
			}
			// TITLE
			$details .= '<u>' . $source->getFullName() . '</u>';
			// PUBL
			$publ = get_gedcom_value('PUBL', '1', $source->getGedcomRecord());
			if (!empty($publ)) {
				$details .= ': ' . $publ;
			}
			// PAGE (citation)
			$page = expand_urls(getSourceStructure($srec)['PAGE']);
			if (!empty($page)) {
				$details .= ': ' . $page;
			}
			// TEXT
			foreach (getSourceStructure($srec)['TEXT'] as $text_list) {
				$text = expand_urls($text_list);
			}
			if (!empty($text)) {
				$details .= ': ' . $text;
			}
		}

		$data[$source_num + $j] = $details;
	}

	return $data;
}

function print_fact_label(WT_Event $fact, WT_GedcomRecord $record) {

	if (!$fact->canShow()) {
		return;
	}

	// Who is this fact about?  Need it to translate fact label correctly
	if ($fact->getSpouse()) {
		// Event of close relative
		$label_person = $fact->getSpouse();
	} else if (preg_match('/2 _WTS @('.WT_REGEX_XREF.')@/', $fact->getGedcomRecord(), $match)) {
		// Event of close relative
		$label_person = WT_Person::getInstance($match[1]);
	} else if ($fact->getParentObject() instanceof WT_Family) {
		// Family event
		$husb = $fact->getParentObject()->getHusband();
		$wife = $fact->getParentObject()->getWife();
		if (empty($wife) && !empty($husb)) $label_person=$husb;
		else if (empty($husb) && !empty($wife)) $label_person=$wife;
		else $label_person = $fact->getParentObject();
	} else {
		// The actual person
		$label_person = $fact->getParentObject();
	}

	// Does this fact have a type?
	if (preg_match('/\n2 TYPE (.+)/', $fact->getGedcomRecord(), $match)) {
		$type = $match[1];
	} else {
		$type='';
	}

	switch ($fact->getTag()) {
	case 'EVEN':
	case 'FACT':
		if (WT_Gedcom_Tag::isTag($type)) {
			// Some users (just Meliza?) use "1 EVEN/2 TYPE BIRT".  Translate the TYPE.
			$label = WT_Gedcom_Tag::getLabel($type, $label_person);
			$type=''; // Do not print this again
		} elseif ($type) {
			// We don't have a translation for $type - but a custom translation might exist.
			$label = WT_I18N::translate(htmlspecialchars($type));
			$type=''; // Do not print this again
		} else {
			// An unspecified fact/event
			$label = WT_Gedcom_Tag::getLabel($fact->getTag(), $label_person);
		}
		break;
	default:
		// Normal fact/event
		$label = WT_Gedcom_Tag::getLabel($fact->getTag(), $label_person);
		break;
	}

	echo $label;
}

function print_resourcenotes(WT_Event $fact, $level, $textOnly=false, $return=false) {
	global $GEDCOM;
	$ged_id = get_id_from_gedcom($GEDCOM);
	$fact	= $fact->getGedcomRecord();

	$data = "";
	$previous_spos = 0;
	$nlevel = $level + 1;
	$ct = preg_match_all("/$level NOTE(.*)/", $fact, $match, PREG_SET_ORDER);
	for ($j=0; $j<$ct; $j++) {
		$nid = str_replace("@","",$match[$j][1]);
		$spos1 = stripos($fact, $match[$j][0], $previous_spos);
		$spos2 = stripos($fact."\n$level", "\n$level", $spos1+1);
		if (!$spos2) $spos2 = strlen($fact);
		$nrec = substr($fact, $spos1, $spos2-$spos1);
		if (!isset($match[$j][1])) $match[$j][1]="";
		$previous_spos = $spos2;
		$nt = preg_match("/@(.*)@/", $match[$j][1], $nmatch);
		$closeSpan = false;
		if ($nt == 0) {
			//-- print embedded note records
			$closeSpan = print_note_record($match[$j][1], $nlevel, $nrec, $textOnly, true);
			$data .= $closeSpan;
		} else {
			$note = WT_Note::getInstance($nmatch[1]);
			if ($note) {
				if ($note->canDisplayDetails()) {
					$noterec = $note->getGedcomRecord();
					//-- print linked note records
					$nt = preg_match("/0 @$nmatch[1]@ NOTE (.*)/", $noterec, $n1match);
					$closeSpan = print_note_record(($nt>0)?$n1match[1]:"", 1, $noterec, $textOnly, true);
					$data .= $closeSpan;
					if (!$textOnly) {
						if (stripos($noterec, "1 SOUR")!==false) {
							require_once WT_ROOT.'includes/functions/functions_print_facts.php';
							$data .= print_fact_sources($noterec, 1, true);
						}
					}
				}
			} else {
				$data='<div class="fact_NOTE"><span class="label">'.WT_I18N::translate('Note').'</span>: <span class="field error">'.$nid.'</span></div>';
			}
		}
		if (!$textOnly) {
			if (stripos($fact, "$nlevel SOUR")!==false) {
				$data .= "<div class=\"indent\">";
				$data .= print_fact_sources($nrec, $nlevel, true);
				$data .= "</div>";
			}
		}
	}
	if (!$return) echo $data;
	else return $data;
}

function resource_findfact($level, $fact, $year_from, $year_to, $place, $detail) {
	$list = array();
	// Fetch all data, regardless of privacy
	$sql = "SELECT i_id AS xref, i_file AS ged_id, i_gedcom AS gedrec" .
				" FROM `##individuals`" .
				" WHERE `i_gedcom` REGEXP '(.*)\n" . $level . " " . $fact . "' AND i_file=?";
	$rows = WT_DB::prepare($sql)->execute(array(WT_GED_ID))->fetchAll();
	foreach ($rows as $row) {
		$person = WT_Person::getInstance($row->xref);
		$indifacts = $person->getIndiFacts();
		foreach ($indifacts as $item) {
			if ($item->getTag() == $fact) {
				if ($year_from || $year_to || $place || $detail) {
					$result_place = format_fact_place($item, true);
					$result_date = format_fact_date($item, $person, false, true, false);
					if ($year_from || $year_to) {
						preg_match_all("/\d{4}/", format_fact_date($item, $person, false, true, false), $matches);
						$ct = count($matches[0]);
						if (
								($ct == 1 && (
										($year_from && !$year_to && $matches[0][0] >= $year_from) ||
										($year_to && !$year_from && $matches[0][0] <= $year_to) ||
										($year_from && $year_to && $matches[0][0] >= $year_from && $matches[0][0] <= $year_to)
									)
								) ||
								($ct == 2 && (
										($year_from && $matches[0][0] >= $year_from) && ($year_to && $matches[0][0] <= $year_to) &&
										($year_from && $matches[0][1] >= $year_from) && ($year_to && $matches[0][1] <= $year_to)
									)
								)
							) {
								if (!$place && !$detail) {
									$list[] = $row;
								} elseif ($place && !$detail && stripos(strip_tags($result_place), $place) !== false) {
									$list[] = $row;
								} elseif (!$place && $detail && stripos(strip_tags($result_detail), $detail) !== false) {
									$list[] = $row;
								} elseif ($place && $detail && stripos(strip_tags($result_place), $place) !== false && stripos(strip_tags($result_detail), $detail) !== false) {
									$list[] = $row;
								}
						}
					}
					if ($place && !$year_from && !$year_to) {
						if (stripos(strip_tags($result_place), $place) !== false) {
							if (!$detail) {
								$list[] = $row;
							} elseif ($detail && stripos(strip_tags($result_detail), $detail) !== false ) {
								$list[] = $row;
							}
						}
					}
					if ($detail && !$year_from && !$year_to && !$place) {
						if (stripos(strip_tags($result_detail), $detail) !== false) {
							$list[] = $row;
						}
					}
				} else {
					$list[] = $row;
				}
			}
		}
	}
	// remove duplicates
	foreach ($list as $key=>$value) {
		$list[$key] = serialize($list[$key]);
	}
	$list = array_unique($list);
	foreach ($list as $key=>$value){
		$list[$key] = unserialize($list[$key]);
	}
	return $list;
}
