<?php
/**
 * Kiwitrees: Web based Family History software
 * Copyright (C) 2012 to 2021 kiwitrees.net
 *
 * Derived from webtrees (www.webtrees.net)
 * Copyright (C) 2010 to 2012 webtrees development team
 *
 * Derived from PhpGedView (phpgedview.sourceforge.net)
 * Copyright (C) 2002 to 2010 PGV Development Team
 *
 * Kiwitrees is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Kiwitrees. If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('KT_KIWITREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once KT_ROOT.'includes/functions/functions_export.php';

// Tidy up a gedcom record on import, so that we can access it consistently/efficiently.
function reformat_record_import($rec) {
	global $WORD_WRAPPED_NOTES, $GEDCOM_MEDIA_PATH;

	// Strip out UTF8 formatting characters
	$rec=str_replace(array(KT_UTF8_BOM, KT_UTF8_LRM, KT_UTF8_RLM), '', $rec);

	// Strip out control characters and mac/msdos line endings
	static $control1="\r\x01\x02\x03\x04\x05\x06\x07\x08\x0B\x0C\x0E\x0F\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1A\x1B\x1C\x1D\x1E\x1F\x7F";
	static $control2="\n?????????????????????????????";
	$rec=strtr($rec, $control1, $control2);

	// Extract lines from the record; lines consist of: level + optional xref + tag + optional data
	$num_matches=preg_match_all('/^[ \t]*(\d+)[ \t]*(@[^@]*@)?[ \t]*(\w+)[ \t]?(.*)$/m', $rec, $matches, PREG_SET_ORDER);

	// Process the record line-by-line
	$newrec='';
	foreach ($matches as $n=>$match) {
		list(, $level, $xref, $tag, $data)=$match;
		$tag=strtoupper($tag); // Tags should always be upper case
		switch ($tag) {
		// Convert PGV tags to WT
		case '_PGVU':
		case '_WT_USER':
			$tag='_KT_USER';
			break;
		case '_PGV_OBJS':
		case '_WT_OBJE_SORT':
			$tag='_KT_OBJE_SORT';
			break;
		// Convert FTM-style "TAG_FORMAL_NAME" into "TAG".
		case 'ABBREVIATION':
			$tag='ABBR';
			break;
		case 'ADDRESS':
			$tag='ADDR';
			break;
		case 'ADDRESS1':
			$tag='ADR1';
			break;
		case 'ADDRESS2':
			$tag='ADR2';
			break;
		case 'ADDRESS3':
			$tag='ADR3';
			break;
		case 'ADOPTION':
			$tag='ADOP';
			break;
		case 'ADULT_CHRISTENING':
			$tag='CHRA';
			break;
		case 'AFN':
			// AFN values are upper case
			$data=strtoupper($data);
			break;
		case 'AGENCY':
			$tag='AGNC';
			break;
		case 'ALIAS':
			$tag='ALIA';
			break;
		case 'ANCESTORS':
			$tag='ANCE';
			break;
		case 'ANCES_INTEREST':
			$tag='ANCI';
			break;
		case 'ANNULMENT':
			$tag='ANUL';
			break;
		case 'ASSOCIATES':
			$tag='ASSO';
			break;
		case 'AUTHOR':
			$tag='AUTH';
			break;
		case 'BAPTISM':
			$tag='BAPM';
			break;
		case 'BAPTISM_LDS':
			$tag='BAPL';
			break;
		case 'BAR_MITZVAH':
			$tag='BARM';
			break;
		case 'BAS_MITZVAH':
			$tag='BASM';
			break;
		case 'BIRTH':
			$tag='BIRT';
			break;
		case 'BLESSING':
			$tag='BLES';
			break;
		case 'BURIAL':
			$tag='BURI';
			break;
		case 'CALL_NUMBER':
			$tag='CALN';
			break;
		case 'CASTE':
			$tag='CAST';
			break;
		case 'CAUSE':
			$tag='CAUS';
			break;
		case 'CENSUS':
			$tag='CENS';
			break;
		case 'CHANGE':
			$tag='CHAN';
			break;
		case 'CHARACTER':
			$tag='CHAR';
			break;
		case 'CHILD':
			$tag='CHIL';
			break;
		case 'CHILDREN_COUNT':
			$tag='NCHI';
			break;
		case 'CHRISTENING':
			$tag='CHR';
			break;
		case 'CONCATENATION':
			$tag='CONC';
			break;
		case 'CONFIRMATION':
			$tag='CONF';
			break;
		case 'CONFIRMATION_LDS':
			$tag='CONL';
			break;
		case 'CONTINUED':
			$tag='CONT';
			break;
		case 'COPYRIGHT':
			$tag='COPR';
			break;
		case 'CORPORATE':
			$tag='CORP';
			break;
		case 'COUNTRY':
			$tag='CTRY';
			break;
		case 'CREMATION':
			$tag='CREM';
			break;
		case 'DATE':
			// Preserve text from INT dates
			if (strpos($data, '(')!==false) {
				list($date, $text)=explode('(', $data, 2);
				$text=' ('.$text;
			} else {
				$date=$data;
				$text='';
			}
			// Capitals
			$date=strtoupper($date);
			// Temporarily add leading/trailing spaces, to allow efficient matching below
			$date=" {$date} ";
			// Ensure space digits and letters
			$date=preg_replace('/([A-Z])(\d)/', '$1 $2', $date);
			$date=preg_replace('/(\d)([A-Z])/', '$1 $2', $date);
			// Ensure space before/after calendar escapes
			$date=preg_replace('/@#[^@]+@/', ' $0 ', $date);
			// "BET." => "BET"
			$date=preg_replace('/(\w\w)\./', '$1', $date);
			// "CIR" => "ABT"
			$date=str_replace(' CIR ', ' ABT ', $date);
			$date=str_replace(' APX ', ' ABT ', $date);
			// B.C. => BC (temporarily, to allow easier handling of ".")
			$date=str_replace(' B.C. ', ' BC ', $date);
			// "BET X - Y " => "BET X AND Y"
			$date=preg_replace('/^(.* BET .+) - (.+)/', '$1 AND $2', $date);
			$date=preg_replace('/^(.* FROM .+) - (.+)/', '$1 TO $2', $date);
			// "@#ESC@ FROM X TO Y" => "FROM @#ESC@ X TO @#ESC@ Y"
			$date=preg_replace('/^ +(@#[^@]+@) +FROM +(.+) +TO +(.+)/', ' FROM $1 $2 TO $1 $3', $date);
			$date=preg_replace('/^ +(@#[^@]+@) +BET +(.+) +AND +(.+)/', ' BET $1 $2 AND $1 $3', $date);
			// "@#ESC@ AFT X" => "AFT @#ESC@ X"
			$date=preg_replace('/^ +(@#[^@]+@) +(FROM|BET|TO|AND|BEF|AFT|CAL|EST|INT|ABT) +(.+)/', ' $2 $1 $3', $date);
			// Ignore any remaining punctuation, e.g. "14-MAY, 1900" => "14 MAY 1900"
			// (don't change "/" - it is used in NS/OS dates)
			$date=preg_replace('/[.,:;-]/', ' ', $date);
			// BC => B.C.
			$date=str_replace(' BC ', ' B.C. ', $date);
			// Append the "INT" text
			$data=$date.$text;
			break;
		case 'DEATH':
			$tag='DEAT';
			break;
		case '_DEGREE':
			$tag='_DEG';
			break;
		case 'DESCENDANTS':
			$tag='DESC';
			break;
		case 'DESCENDANT_INT':
			$tag='DESI';
			break;
		case 'DESTINATION':
			$tag='DEST';
			break;
		case 'DIVORCE':
			$tag='DIV';
			break;
		case 'DIVORCE_FILED':
			$tag='DIVF';
			break;
		case 'EDUCATION':
			$tag='EDUC';
			break;
		case 'EMIGRATION':
			$tag='EMIG';
			break;
		case 'ENDOWMENT':
			$tag='ENDL';
			break;
		case 'ENGAGEMENT':
			$tag='ENGA';
			break;
		case 'EVENT':
			$tag='EVEN';
			break;
		case 'FACSIMILE':
			$tag='FAX';
			break;
		case 'FAMILY':
			$tag='FAM';
			break;
		case 'FAMILY_CHILD':
			$tag='FAMC';
			break;
		case 'FAMILY_FILE':
			$tag='FAMF';
			break;
		case 'FAMILY_SPOUSE':
			$tag='FAMS';
			break;
		case 'FIRST_COMMUNION':
			$tag='FCOM';
			break;
		case '_FILE':
			$tag='FILE';
			break;
		case 'FORMAT':
			$tag='FORM';
		case 'FORM':
			// Consistent commas
			$data=preg_replace('/ *, */', ', ', $data);
			break;
		case 'GEDCOM':
			$tag='GEDC';
			break;
		case 'GIVEN_NAME':
			$tag='GIVN';
			break;
		case 'GRADUATION':
			$tag='GRAD';
			break;
		case 'HEADER':
			$tag='HEAD';
		case 'HEAD':
			// HEAD records don't have an XREF or DATA
			if ($level=='0') {
				$xref='';
				$data='';
			}
			break;
		case 'HUSBAND':
			$tag='HUSB';
			break;
		case 'IDENT_NUMBER':
			$tag='IDNO';
			break;
		case 'IMMIGRATION':
			$tag='IMMI';
			break;
		case 'INDIVIDUAL':
			$tag='INDI';
			break;
		case 'LANGUAGE':
			$tag='LANG';
			break;
		case 'LATITUDE':
			$tag='LATI';
			break;
		case 'LONGITUDE':
			$tag='LONG';
			break;
		case 'MARRIAGE':
			$tag='MARR';
			break;
		case 'MARRIAGE_BANN':
			$tag='MARB';
			break;
		case 'MARRIAGE_COUNT':
			$tag='NMR';
			break;
		case 'MARR_CONTRACT':
			$tag='MARC';
			break;
		case 'MARR_LICENSE':
			$tag='MARL';
			break;
		case 'MARR_SETTLEMENT':
			$tag='MARS';
			break;
		case 'MEDIA':
			$tag='MEDI';
			break;
		case '_MEDICAL':
			$tag='_MDCL';
			break;
		case '_MILITARY_SERVICE':
			$tag='_MILT';
			break;
		case 'NAME':
			// Tidy up whitespace
			$data=preg_replace('/  +/', ' ', trim($data));
			break;
		case 'NAME_PREFIX':
			$tag='NPFX';
			break;
		case 'NAME_SUFFIX':
			$tag='NSFX';
			break;
		case 'NATIONALITY':
			$tag='NATI';
			break;
		case 'NATURALIZATION':
			$tag='NATU';
			break;
		case 'NICKNAME':
			$tag='NICK';
			break;
		case 'OBJECT':
			$tag='OBJE';
			break;
		case 'OCCUPATION':
			$tag='OCCU';
			break;
		case 'ORDINANCE':
			$tag='ORDI';
			break;
		case 'ORDINATION':
			$tag='ORDN';
			break;
		case 'PEDIGREE':
			$tag='PEDI';
		case 'PEDI':
			// PEDI values are lower case
			$data=strtolower($data);
			break;
		case 'PHONE':
			$tag='PHON';
			break;
		case 'PHONETIC':
			$tag='FONE';
			break;
		case 'PHY_DESCRIPTION':
			$tag='DSCR';
			break;
		case 'PLACE':
			$tag='PLAC';
		case 'PLAC':
			// Consistent commas
			$data=preg_replace('/ *, */', ', ', $data);
			// The Master Genealogist stores LAT/LONG data in the PLAC field, e.g. Pennsylvania, USA, 395945N0751013W
			if (preg_match('/(.*), (\d\d)(\d\d)(\d\d)([NS])(\d\d\d)(\d\d)(\d\d)([EW])$/', $data, $match)) {
				$data=
					$match[1]."\n".
					($level+1)." MAP\n".
					($level+2)." LATI ".($match[5].(round($match[2]+($match[3]/60)+($match[4]/3600),4)))."\n".
					($level+2)." LONG ".($match[9].(round($match[6]+($match[7]/60)+($match[8]/3600),4)));
			}
			break;
		case 'POSTAL_CODE':
			$tag='POST';
			break;
		case 'PROBATE':
			$tag='PROB';
			break;
		case 'PROPERTY':
			$tag='PROP';
			break;
		case 'PUBLICATION':
			$tag='PUBL';
			break;
		case 'QUALITY_OF_DATA':
			$tag='QUAL';
			break;
		case 'REC_FILE_NUMBER':
			$tag='RFN';
			break;
		case 'REC_ID_NUMBER':
			$tag='RIN';
			break;
		case 'REFERENCE':
			$tag='REFN';
			break;
		case 'RELATIONSHIP':
			$tag='RELA';
			break;
		case 'RELIGION':
			$tag='RELI';
			break;
		case 'REPOSITORY':
			$tag='REPO';
			break;
		case 'RESIDENCE':
			$tag='RESI';
			break;
		case 'RESTRICTION':
			$tag='RESN';
		case 'RESN':
			// RESN values are lower case (confidential, privacy, locked, none)
			$data=strtolower($data);
			if ($data=='invisible') {
				$data='confidential'; // From old versions of Legacy.
			}
			break;
		case 'RETIREMENT':
			$tag='RETI';
			break;
		case 'ROMANIZED':
			$tag='ROMN';
			break;
		case 'SEALING_CHILD':
			$tag='SLGC';
			break;
		case 'SEALING_SPOUSE':
			$tag='SLGS';
			break;
		case 'SOC_SEC_NUMBER':
			$tag='SSN';
			break;
		case 'SEX':
			switch (trim($data)) {
			case 'M':
			case 'F':
			case 'U':
				break;
			case 'm':
				$data='M';
				break;
			case 'f':
				$data='F';
				break;
			default:
				$data='U';
				break;
			}
			break;
		case 'SOURCE':
			$tag='SOUR';
			break;
		case 'STATE':
			$tag='STAE';
			break;
		case 'STATUS':
			$tag='STAT';
		case 'STAT':
			if ($data=='CANCELLED') {
				// PGV mis-spells this tag - correct it.
				$data='CANCELED';
			}
			break;
		case 'SUBMISSION':
			$tag='SUBN';
			break;
		case 'SUBMITTER':
			$tag='SUBM';
			break;
		case 'SURNAME':
			$tag='SURN';
			break;
		case 'SURN_PREFIX':
			$tag='SPFX';
			break;
		case 'TEMPLE':
			$tag='TEMP';
		case 'TEMP':
			// Temple codes are upper case
			$data=strtoupper($data);
			break;
		case 'TITLE':
			$tag='TITL';
			break;
		case 'TRAILER':
			$tag='TRLR';
		case 'TRLR':
			// TRLR records don't have an XREF or DATA
			if ($level=='0') {
				$xref='';
				$data='';
			}
			break;
		case 'VERSION':
			$tag='VERS';
			break;
		case 'WEB':
			$tag='WWW';
			break;
		}
		// Suppress "Y", for facts/events with a DATE or PLAC
		if ($data=='y') {
			$data='Y';
		}
		if ($level=='1' && $data=='Y') {
			for ($i=$n+1; $i<$num_matches-1 && $matches[$i][1]!='1'; ++$i) {
				if ($matches[$i][3]=='DATE' || $matches[$i][3]=='PLAC') {
					$data='';
					break;
				}
			}
		}
		// Reassemble components back into a single line
		switch ($tag) {
		default:
			// Remove tabs and multiple/leading/trailing spaces
			if (strpos($data, "\t")!==false) {
				$data=str_replace("\t", ' ', $data);
			}
			if (substr($data, 0, 1)==' ' || substr($data, -1, 1)==' ') {
				$data=trim($data);
			}
			while (strpos($data, '  ')) {
				$data=str_replace('  ', ' ', $data);
			}
			$newrec.=($newrec ? "\n" : '').$level.' '.($level=='0' && $xref ? $xref.' ' : '').$tag.($data==='' && $tag!="NOTE" ? '' : ' '.$data);
			break;
		case 'NOTE':
		case 'TEXT':
		case 'DATA':
		case 'CONT':
			$newrec.=($newrec ? "\n" : '').$level.' '.($level=='0' && $xref ? $xref.' ' : '').$tag.($data==='' && $tag!="NOTE" ? '' : ' '.$data);
			break;
		case 'FILE':
			// Strip off the user-defined path prefix
			if ($GEDCOM_MEDIA_PATH && strpos($data, $GEDCOM_MEDIA_PATH)===0) {
				$data=substr($data, strlen($GEDCOM_MEDIA_PATH));
			}
			// convert backslashes in filenames to forward slashes
			$data = preg_replace("/\\\/", "/", $data);

			$newrec.=($newrec ? "\n" : '').$level.' '.($level=='0' && $xref ? $xref.' ' : '').$tag.($data==='' && $tag!="NOTE" ? '' : ' '.$data);
			break;
		case 'CONC':
			// Merge CONC lines, to simplify access later on.
			$newrec.=($WORD_WRAPPED_NOTES ? ' ' : '').$data;
			break;
		}
	}
	return $newrec;
}

/**
* import record into database
*
* this function will parse the given gedcom record and add it to the database
* @param string $gedrec the raw gedcom record to parse
* @param integer $ged_id import the record into this gedcom
* @param boolean $update whether or not this is an updated record that has been accepted
*/
function import_record($gedrec, $ged_id, $update) {
	global $USE_RIN, $GENERATE_UIDS;

	static $sql_insert_indi=null;
	static $sql_insert_fam=null;
	static $sql_insert_sour=null;
	static $sql_insert_media=null;
	static $sql_insert_other=null;
	if (!$sql_insert_indi) {
		$sql_insert_indi=KT_DB::prepare(
			"INSERT INTO `##individuals` (i_id, i_file, i_rin, i_sex, i_gedcom) VALUES (?,?,?,?,?)"
		);
		$sql_insert_fam=KT_DB::prepare(
			"INSERT INTO `##families` (f_id, f_file, f_husb, f_wife, f_gedcom, f_numchil) VALUES (?,?,?,?,?,?)"
		);
		$sql_insert_sour=KT_DB::prepare(
			"INSERT INTO `##sources` (s_id, s_file, s_name, s_gedcom) VALUES (?,?,?,?)"
		);
		$sql_insert_media=KT_DB::prepare(
			"INSERT INTO `##media` (m_id, m_ext, m_type, m_titl, m_filename, m_file, m_gedcom) VALUES (?, ?, ?, ?, ?, ?, ?)"
		);
		$sql_insert_other=KT_DB::prepare(
			"INSERT INTO `##other` (o_id, o_file, o_type, o_gedcom) VALUES (?, ?, LEFT(?, 15), ?)"
		);
	}

	// Escaped @ signs (only if importing from file)
	if (!$update) {
		$gedrec = str_replace('@@', '@', $gedrec);
	}

	// Standardise gedcom format
	$gedrec = reformat_record_import($gedrec);

	// import different types of records
	if (preg_match('/^0 @('.KT_REGEX_XREF.')@ ('.KT_REGEX_TAG.')/', $gedrec, $match) > 0) {
		list(,$xref, $type) = $match;
		// check for a _UID, if the record doesn't have one, add one
		if ($GENERATE_UIDS && !strpos($gedrec, "\n1 _UID ")) {
			$gedrec .= "\n1 _UID ".uuid();
		}
	} elseif (preg_match('/0 ('.KT_REGEX_TAG.')/', $gedrec, $match)) {
		$xref = $match[1];
		$type = $match[1];
	} else {
		echo KT_I18N::translate('Invalid GEDCOM format'), '<br><pre>', $gedrec, '</pre>';
		return;
	}

	// Convert inline media into media objects
	$gedrec = convert_inline_media($xref, $ged_id, $gedrec);

	// If the user has downloaded their GEDCOM data (containing media objects) and edited it
	// using an application which does not support (and deletes) media objects, then add them
	// back in.
	if (get_gedcom_setting(KT_GED_ID, 'keep_media') && $xref) {
		$old_linked_media =
			KT_DB::prepare("SELECT l_to FROM `##link` WHERE l_from=? AND l_file=? AND l_type='OBJE'")
			->execute(array($xref, $ged_id))
			->fetchOneColumn();
		foreach ($old_linked_media as $media_id) {
			$gedrec .= "\n1 OBJE @" . $media_id . "@";
		}
	}

	switch ($type) {
	case 'INDI':
		$record = new KT_Person($gedrec);
		if ($USE_RIN && preg_match('/\n1 RIN (.+)/', $gedrec, $match)) {
			$rin=$match[1];
		} else {
			$rin=$xref;
		}
		$sql_insert_indi->execute(array($xref, $ged_id, $rin, $record->getSex(), $gedrec));
		// Update the cross-reference/index tables.
		update_places($xref, $ged_id, $gedrec);
		update_dates ($xref, $ged_id, $gedrec);
		update_links ($xref, $ged_id, $gedrec);
		update_names ($xref, $ged_id, $record);
		break;
	case 'FAM':
		$record=new KT_Family($gedrec);
		if (preg_match('/\n1 HUSB @('.KT_REGEX_XREF.')@/', $gedrec, $match)) {
			$husb=$match[1];
		} else {
			$husb='';
		}
		if (preg_match('/\n1 WIFE @('.KT_REGEX_XREF.')@/', $gedrec, $match)) {
			$wife=$match[1];
		} else {
			$wife='';
		}
		if ($nchi=preg_match_all('/\n1 CHIL @('.KT_REGEX_XREF.')@/', $gedrec, $match)) {
			$chil=implode(';', $match[1]).';';
		} else {
			$chil='';
		}
		if (preg_match('/\n1 NCHI (\d+)/', $gedrec, $match)) {
			$nchi=max($nchi, $match[1]);
		}
		$sql_insert_fam->execute(array($xref, $ged_id, $husb, $wife, $gedrec, $nchi));
		// Update the cross-reference/index tables.
		update_places($xref, $ged_id, $gedrec);
		update_dates ($xref, $ged_id, $gedrec);
		update_links ($xref, $ged_id, $gedrec);
		//update_names ($xref, $ged_id, $record); We do not store family names in ##names
		break;
	case 'SOUR':
		$record=new KT_Source($gedrec);
		if (preg_match('/\n1 TITL (.+)/', $gedrec, $match)) {
			$name=$match[1];
		} elseif (preg_match('/\n1 ABBR (.+)/', $gedrec, $match)) {
			$name=$match[1];
		} else {
			$name=$xref;
		}
		$sql_insert_sour->execute(array($xref, $ged_id, $name, $gedrec));
		// Update the cross-reference/index tables.
		update_links ($xref, $ged_id, $gedrec);
		update_names ($xref, $ged_id, $record);
		break;
	case 'REPO':
		$record=new KT_Repository($gedrec);
		$sql_insert_other->execute(array($xref, $ged_id, $type, $gedrec));
		// Update the cross-reference/index tables.
		update_links ($xref, $ged_id, $gedrec);
		update_names ($xref, $ged_id, $record);
		break;
	case 'OBJE':
		$record=new KT_Media($gedrec);
		$sql_insert_media->execute(array($xref, $record->extension(), $record->getMediaType(), $record->title, $record->file, $ged_id, $gedrec));
		// Update the cross-reference/index tables.
		update_links ($xref, $ged_id, $gedrec);
		update_names ($xref, $ged_id, $record);
		break;
	default:
		// Custom records beginning with frequently do not contain unique
		// identifiers - so we cannot load them.
		if (substr($type, 0, 1)!='_') {
			$record=new KT_GedcomRecord($gedrec);
			if ($type=='HEAD' && !strpos($gedrec, "\n1 DATE ")) {
				$gedrec.="\n1 DATE ".date('j M Y');
			}
			$sql_insert_other->execute(array($xref, $ged_id, $type, $gedrec));
			// Update the cross-reference/index tables.
			update_links ($xref, $ged_id, $gedrec);
			update_names ($xref, $ged_id, $record);
		}
		break;
	}
}

/**
* extract all places from the given record and insert them
* into the places table
* @param string $gedrec
*/
function update_places($gid, $ged_id, $gedrec) {
	global $placecache;

	static $sql_insert_placelinks=null;
	static $sql_insert_places=null;
	static $sql_select_places=null;
	if (!$sql_insert_placelinks) {
		// Use INSERT IGNORE as a (temporary) fix
		// It ignores places that utf8_unicode_ci consider to be the same (i.e. accents).
		// Of course, there almost certainly are such places .....
		// We need a better solution that attaches multiple names to single places
		$sql_insert_placelinks=KT_DB::prepare(
			"INSERT IGNORE INTO `##placelinks` (pl_p_id, pl_gid, pl_file) VALUES (?,?,?)"
		);
		$sql_insert_places=KT_DB::prepare(
			"INSERT INTO `##places` (p_place, p_parent_id, p_file, p_std_soundex, p_dm_soundex) VALUES (?,?,?,?,?)"
		);
		$sql_select_places=KT_DB::prepare(
			"SELECT p_id FROM `##places` WHERE p_file=? AND p_parent_id=? AND p_place=?"
		);
	}

	if (!isset($placecache)) {
		$placecache = array();
	}
	$personplace = array();
	// import all place locations, but not control info such as
	// 0 HEAD/1 PLAC or 0 _EVDEF/1 PLAC
	$pt = preg_match_all("/^[2-9] PLAC (.+)/m", $gedrec, $match, PREG_SET_ORDER);
	for ($i = 0; $i < $pt; $i++) {
		$place = trim($match[$i][1]);
		$lowplace = utf8_strtolower($place);
		//-- if we have already visited this place for this person then we don't need to again
		if (isset($personplace[$lowplace])) {
			continue;
		}
		$personplace[$lowplace] = 1;
		$places = explode(',', $place);
		//-- reverse the array to start at the highest level
		$secalp = array_reverse($places);
		$parent_id = 0;
		$search = true;

		foreach ($secalp as $indexval => $place) {
			$place = trim($place);
			$key = strtolower($place."_".$parent_id);
			//-- if this place has already been added then we don't need to add it again
			if (isset($placecache[$key])) {
				$parent_id = $placecache[$key];
				if (!isset($personplace[$key])) {
					$personplace[$key]=1;
					$sql_insert_placelinks->execute(array($parent_id, $gid, $ged_id));
				}
				continue;
			}

			//-- only search the database while we are finding places in it
			if ($search) {
				//-- check if this place and level has already been added
				$tmp=$sql_select_places->execute(array($ged_id, $parent_id, $place))->fetchOne();
				if ($tmp) {
					$p_id = $tmp;
				} else {
					$search = false;
				}
			}

			//-- if we are not searching then we have to insert the place into the db
			if (!$search) {
				$std_soundex = KT_Soundex::soundex_std($place);
				$dm_soundex = KT_Soundex::soundex_dm($place);
				$sql_insert_places->execute(array($place, $parent_id, $ged_id, $std_soundex, $dm_soundex));
				$p_id=KT_DB::getInstance()->lastInsertId();
			}

			$sql_insert_placelinks->execute(array($p_id, $gid, $ged_id));
			//-- increment the level and assign the parent id for the next place level
			$parent_id = $p_id;
			$placecache[$key] = $p_id;
			$personplace[$key]=1;
		}
	}
}

// extract all the dates from the given record and insert them into the database
function update_dates($xref, $ged_id, $gedrec) {
	static $sql_insert_date=null;
	if (!$sql_insert_date) {
		$sql_insert_date=KT_DB::prepare(
			"INSERT INTO `##dates` (d_day,d_month,d_mon,d_year,d_julianday1,d_julianday2,d_fact,d_gid,d_file,d_type) VALUES (?,?,?,?,?,?,?,?,?,?)"
		);
	}

	if (strpos($gedrec, '2 DATE ') && preg_match_all("/\n1 (\w+).*(?:\n[2-9].*)*(?:\n2 DATE (.+))(?:\n[2-9].*)*/", $gedrec, $matches, PREG_SET_ORDER)) {
		foreach ($matches as $match) {
			$fact=$match[1];
			if (($fact=='FACT' || $fact=='EVEN') && preg_match("/\n2 TYPE ([A-Z]{3,5})/", $match[0], $tmatch)) {
				$fact=$tmatch[1];
			}
			$date=new KT_Date($match[2]);
			$sql_insert_date->execute(array($date->date1->d, $date->date1->Format('%O'), $date->date1->m, $date->date1->y, $date->date1->minJD, $date->date1->maxJD, $fact, $xref, $ged_id, $date->date1->Format('%@')));
			if ($date->date2) {
				$sql_insert_date->execute(array($date->date2->d, $date->date2->Format('%O'), $date->date2->m, $date->date2->y, $date->date2->minJD, $date->date2->maxJD, $fact, $xref, $ged_id, $date->date2->Format('%@')));
			}
		}
	}
	return;
}

// extract all the links from the given record and insert them into the database
function update_links($xref, $ged_id, $gedrec) {
	static $sql_insert_link = null;
	if (!$sql_insert_link) {
		$sql_insert_link = KT_DB::prepare("INSERT INTO `##link` (l_from,l_to,l_type,l_file) VALUES (?,?,?,?)");
	}

	if (preg_match_all('/^\d+ ('.KT_REGEX_TAG.') @('.KT_REGEX_XREF.')@/m', $gedrec, $matches, PREG_SET_ORDER)) {
		$data = array();
		foreach ($matches as $match) {
			// Include each link once only.
			if (!in_array($match[1].$match[2], $data)) {
				$data[] = $match[1].$match[2];
				// Ignore any errors, which may be caused by "duplicates" that differ on case/collation, e.g. "S1" and "s1"
				try {
					$sql_insert_link->execute(array($xref, $match[2], $match[1], $ged_id));
				} catch (PDOException $e) {
					// We could display a warning here....
				}
			}
		}
	}
}

// extract all the names from the given record and insert them into the database
function update_names($xref, $ged_id, $record) {
	static $sql_insert_name_indi=null;
	static $sql_insert_name_other=null;
	if (!$sql_insert_name_indi) {
		$sql_insert_name_indi=KT_DB::prepare("INSERT INTO `##name` (n_file,n_id,n_num,n_type,n_sort,n_full,n_surname,n_surn,n_givn,n_soundex_givn_std,n_soundex_surn_std,n_soundex_givn_dm,n_soundex_surn_dm) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$sql_insert_name_other=KT_DB::prepare("INSERT INTO `##name` (n_file,n_id,n_num,n_type,n_sort,n_full) VALUES (?,?,?,?,?,?)");
	}

	foreach ($record->getAllNames() as $n=>$name) {
		if ($record->getType() == 'INDI') {
			if ($name['givn'] == '@P.N.') {
				$soundex_givn_std = null;
				$soundex_givn_dm = null;
			} else {
				$soundex_givn_std = KT_Soundex::soundex_std($name['givn']);
				$soundex_givn_dm = KT_Soundex::soundex_dm($name['givn']);
			}
			if ($name['surn'] == '@N.N.') {
				$soundex_surn_std = null;
				$soundex_surn_dm = null;
			} else {
				$soundex_surn_std = KT_Soundex::soundex_std($name['surname']);
				$soundex_surn_dm = KT_Soundex::soundex_dm($name['surname']);
			}
			$sql_insert_name_indi->execute(array($ged_id, $xref, $n, $name['type'], $name['sort'], $name['fullNN'], $name['surname'], $name['surn'], $name['givn'], $soundex_givn_std, $soundex_surn_std, $soundex_givn_dm, $soundex_surn_dm));
		} else {
			$sql_insert_name_other->execute(array($ged_id, $xref, $n, $name['type'], $name['sort'], $name['fullNN']));
		}
	}
}

// Extract inline media data, and convert to media objects
function convert_inline_media($gid, $ged_id, $gedrec) {
	while (preg_match('/\n1 OBJE(?:\n[2-9].+)+/', $gedrec, $match)) {
		$gedrec = str_replace($match[0], create_media_object(1, $match[0], $ged_id), $gedrec);
	}
	while (preg_match('/\n2 OBJE(?:\n[3-9].+)+/', $gedrec, $match)) {
		$gedrec = str_replace($match[0], create_media_object(2, $match[0], $ged_id), $gedrec);
	}
	while (preg_match('/\n3 OBJE(?:\n[4-9].+)+/', $gedrec, $match)) {
		$gedrec = str_replace($match[0], create_media_object(3, $match[0], $ged_id), $gedrec);
	}
	return $gedrec;
}

// Create a new media object, from inline media data
function create_media_object($level, $gedrec, $ged_id) {
	static $sql_insert_media=null;
	static $sql_select_media=null;
	if (!$sql_insert_media) {
		$sql_insert_media=KT_DB::prepare(
			"INSERT INTO `##media` (m_id, m_ext, m_type, m_titl, m_filename, m_file, m_gedcom) VALUES (?, ?, ?, ?, ?, ?, ?)"
		);
		$sql_select_media=KT_DB::prepare(
			"SELECT m_id FROM `##media` WHERE m_filename=? AND m_titl=? AND m_file=?"
		);
	}

	if (preg_match('/\n\d FILE (.+)/', $gedrec, $file_match)) {
		$file = $file_match[1];
	} else {
		$file = '';
	}

	if (preg_match('/\n\d TITL (.+)/', $gedrec, $file_match)) {
		$titl = $file_match[1];
	} else {
		$titl = $file;
	}

	// Have we already created a media object with the same title/filename?
	$xref = $sql_select_media->execute(array($file, $titl, $ged_id))->fetchOne();

	if (!$xref) {
		$xref = get_new_xref("OBJE", $ged_id);
		// renumber the lines
		$gedrec = preg_replace_callback('/\n(\d+)/', function ($m) use ($level) {
			return "\n" . ($m[1] - $level);
		}, $gedrec);
		// convert to an object
		$gedrec = str_replace("\n0 OBJE\n", '0 @' . $xref . "@ OBJE\n", $gedrec);
		// Fix Legacy GEDCOMS
		$gedrec = preg_replace('/\n1 FORM (.+)\n1 FILE (.+)\n1 TITL (.+)/', "\n1 FILE $2\n2 FORM $1\n2 TITL $3", $gedrec);
		// Fix FTB GEDCOMS
		$gedrec = preg_replace('/\n1 FORM (.+)\n1 TITL (.+)\n1 FILE (.+)/', "\n1 FILE $3\n2 FORM $1\n2 TITL $2", $gedrec);
		// Create new record
		$record = new KT_Media($gedrec);
		$sql_insert_media->execute(array($xref, $record->extension(), $record->getMediaType(), $record->title, $record->file, $ged_id, $gedrec));
	}
	return "\n" . $level . ' OBJE @' . $xref . '@';
}

/**
* delete a gedcom from the database
*
* deletes all of the imported data about a gedcom from the database
* @param string $ged_id the gedcom to remove from the database
* @param boolean $keepmedia Whether or not to keep media and media links in the tables
*/
function empty_database($ged_id, $keepmedia) {
	KT_DB::prepare("DELETE FROM `##individuals` WHERE i_file   =?")->execute(array($ged_id));
	KT_DB::prepare("DELETE FROM `##families`    WHERE f_file   =?")->execute(array($ged_id));
	KT_DB::prepare("DELETE FROM `##sources`     WHERE s_file   =?")->execute(array($ged_id));
	KT_DB::prepare("DELETE FROM `##other`       WHERE o_file   =?")->execute(array($ged_id));
	KT_DB::prepare("DELETE FROM `##places`      WHERE p_file   =?")->execute(array($ged_id));
	KT_DB::prepare("DELETE FROM `##placelinks`  WHERE pl_file  =?")->execute(array($ged_id));
	KT_DB::prepare("DELETE FROM `##name`        WHERE n_file   =?")->execute(array($ged_id));
	KT_DB::prepare("DELETE FROM `##dates`       WHERE d_file   =?")->execute(array($ged_id));
	KT_DB::prepare("DELETE FROM `##change`      WHERE gedcom_id=?")->execute(array($ged_id));

	if ($keepmedia) {
		KT_DB::prepare("DELETE FROM `##link`          WHERE l_file =? AND l_type<>'OBJE'")->execute(array($ged_id));
	} else {
		KT_DB::prepare("DELETE FROM `##link`          WHERE l_file =?")->execute(array($ged_id));
		KT_DB::prepare("DELETE FROM `##media`         WHERE m_file =?")->execute(array($ged_id));
	}
}

// Accept all pending changes for a specified record
function accept_all_changes($xref, $ged_id) {
	$changes=KT_DB::prepare(
		"SELECT change_id, gedcom_name, old_gedcom, new_gedcom".
		" FROM `##change` c".
		" JOIN `##gedcom` g USING (gedcom_id)".
		" WHERE c.status='pending' AND xref=? AND gedcom_id=?".
		" ORDER BY change_id"
	)->execute(array($xref, $ged_id))->fetchAll();
	foreach ($changes as $change) {
		if (empty($change->new_gedcom)) {
			// delete
			update_record($change->old_gedcom, $ged_id, true);
		} else {
			// add/update
			update_record($change->new_gedcom, $ged_id, false);
		}
		KT_DB::prepare(
			"UPDATE `##change`".
			" SET status='accepted'".
			" WHERE status='pending' AND xref=? AND gedcom_id=?"
		)->execute(array($xref, $ged_id));
		AddToLog("Accepted change {$change->change_id} for {$xref} / {$change->gedcom_name} into database", 'edit');
	}
}

// Accept all pending changes for a specified record
function reject_all_changes($xref, $ged_id) {
	KT_DB::prepare(
		"UPDATE `##change`".
		" SET status='rejected'".
		" WHERE status='pending' AND xref=? AND gedcom_id=?"
	)->execute(array($xref, $ged_id));
}

// Find a string in a file, preceded by a any form of line-ending.
// Although kiwitrees always writes them as KT_EOL, it is possible that the file was
// edited externally by an editor that uses different endings.
function find_newline_string($haystack, $needle, $offset=0) {
	if ($pos=strpos($haystack, "\r\n{$needle}", $offset)) {
		return $pos+2;
	} elseif ($pos=strpos($haystack, "\n{$needle}", $offset)) {
		return $pos+1;
	} elseif ($pos=strpos($haystack, "\r{$needle}", $offset)) {
		return $pos+1;
	} else {
		return false;
	}
}

/**
* update a record in the database
* @param string $gedrec
*/
function update_record($gedrec, $ged_id, $delete) {
	global $GEDCOM;

	if (preg_match('/^0 @('.KT_REGEX_XREF.')@ ('.KT_REGEX_TAG.')/', $gedrec, $match)) {
		list(,$gid, $type)=$match;
	} else {
		echo "ERROR: Invalid gedcom record.";
		return false;
	}

	// TODO deleting unlinked places can be done more efficiently in a single query
	$placeids=
		KT_DB::prepare("SELECT pl_p_id FROM `##placelinks` WHERE pl_gid=? AND pl_file=?")
		->execute(array($gid, $ged_id))
		->fetchOneColumn();

	KT_DB::prepare("DELETE FROM `##placelinks` WHERE pl_gid=? AND pl_file=?")->execute(array($gid, $ged_id));
	KT_DB::prepare("DELETE FROM `##dates`      WHERE d_gid =? AND d_file =?")->execute(array($gid, $ged_id));

	//-- delete any unlinked places
	foreach ($placeids as $p_id) {
		$num=
			KT_DB::prepare("SELECT count(pl_p_id) FROM `##placelinks` WHERE pl_p_id=? AND pl_file=?")
			->execute(array($p_id, $ged_id))
			->fetchOne();
		if ($num==0) {
			KT_DB::prepare("DELETE FROM `##places` WHERE p_id=? AND p_file=?")->execute(array($p_id, $ged_id));
		}
	}

	KT_DB::prepare("DELETE FROM `##name` WHERE n_id=? AND n_file=?")->execute(array($gid, $ged_id));
	KT_DB::prepare("DELETE FROM `##link` WHERE l_from=? AND l_file=?")->execute(array($gid, $ged_id));

	switch ($type) {
	case 'INDI':
		KT_DB::prepare("DELETE FROM `##individuals` WHERE i_id=? AND i_file=?")->execute(array($gid, $ged_id));
		break;
	case 'FAM':
		KT_DB::prepare("DELETE FROM `##families` WHERE f_id=? AND f_file=?")->execute(array($gid, $ged_id));
		break;
	case 'SOUR':
		KT_DB::prepare("DELETE FROM `##sources` WHERE s_id=? AND s_file=?")->execute(array($gid, $ged_id));
		break;
	case 'OBJE':
		KT_DB::prepare("DELETE FROM `##media` WHERE m_id=? AND m_file=?")->execute(array($gid, $ged_id));
		break;
	default:
		KT_DB::prepare("DELETE FROM `##other` WHERE o_id=? AND o_file=?")->execute(array($gid, $ged_id));
		break;
	}

	if (!$delete) {
		import_record($gedrec, $ged_id, true);
	}
}

// Create a pseudo-random UUID
function uuid() {
	// Official Format with dashes ('%04x%04x-%04x-%04x-%04x-%04x%04x%04x')
	// Most users want this format (for compatibility with PAF)
	$fmt='%04X%04X%04X%04X%04X%04X%04X%04X';

	$uid = sprintf(
		$fmt,
    // 32 bits for "time_low"
    mt_rand(0, 0xffff), mt_rand(0, 0xffff),

    // 16 bits for "time_mid"
    mt_rand(0, 0xffff),

    // 16 bits for "time_hi_and_version",
    // four most significant bits holds version number 4
    mt_rand(0, 0x0fff) | 0x4000,

    // 16 bits, 8 bits for "clk_seq_hi_res",
    // 8 bits for "clk_seq_low",
    // two most significant bits holds zero and one for variant RFC4122
    mt_rand(0, 0x3fff) | 0x8000,

    // 48 bits for "node"
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
	return sprintf('%s%s', $uid, getCheckSums($uid));
}

/**
* Produces checksums compliant with a Family Search guideline from 2007
* these checksums are compatible with PAF, Legacy, RootsMagic and other applications
* following these guidelines. This prevents dropping and recreation of UID's
*
* @author Veit Olschinski
* @param string $uid the 32 hexadecimal character long uid
* @return string containing the checksum string for the uid
*/
function getCheckSums($uid) {
	$checkA=0; // a sum of the bytes
	$checkB=0; // a sum of the incremental values of checkA

	// Compute both checksums
	for ($i = 0; $i < 32; $i+=2) {
		$checkA += hexdec(substr($uid, $i, 2));
		$checkB += $checkA & 0xFF;
	}
	return strtoupper(sprintf('%s%s', substr(dechex($checkA), -2), substr(dechex($checkB), -2)));
}
