<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 1999-2004 Kasper Skaarhoj (kasper@typo3.com)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license 
*  from the author is found in LICENSE.txt distributed with these scripts.
*
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/** 
 * Contains base class for creating a browsable array/page/folder tree in HTML
 *
 * $Id$
 * Revised for TYPO3 3.6 November/2003 by Kasper Skaarhoj
 *
 * @author	Kasper Skaarhoj <kasper@typo3.com>
 * @coauthor	Ren� Fritz <r.fritz@colorcube.de>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  114: class t3lib_treeView 
 *  253:     function init($clause='')	
 *  279:     function setTreeName($treeName='') 
 *  293:     function addField($field,$noCheck=0)	
 *  307:     function reset()	
 *
 *              SECTION: output
 *  330:     function getBrowsableTree()	
 *  389:     function printTree($treeArr='')	
 *
 *              SECTION: rendering parts
 *  442:     function PMicon($row,$a,$c,$nextCount,$exp)	
 *  464:     function PM_ATagWrap($icon,$cmd,$bMark='')	
 *  486:     function wrapTitle($title,$row,$bank=0)	
 *  499:     function wrapIcon($icon,$row)	
 *  510:     function addTagAttributes($icon,$attr)	
 *  522:     function wrapStop($str,$row)	
 *
 *              SECTION: tree handling
 *  550:     function expandNext($id)	
 *  560:     function initializePositionSaving()	
 *  587:     function savePosition()	
 *
 *              SECTION: Functions that might be overwritten by extended classes
 *  616:     function getRootIcon($rec) 
 *  629:     function getIcon($row) 
 *  648:     function getTitleStr($row,$titleLen=30)	
 *  660:     function getTitleAttrib($row) 
 *  670:     function getId($row) 
 *  680:     function getJumpToParm($row) 
 *
 *              SECTION: tree data buidling
 *  713:     function getTree($uid, $depth=999, $depthData='',$blankLineCode='')	
 *
 *              SECTION: Data handling
 *  802:     function getCount($uid)	
 *  824:     function getRootRecord($uid) 
 *  837:     function getRecord($uid) 
 *  854:     function getDataInit($parentId) 
 *  883:     function getDataCount($res) 
 *  900:     function getDataNext($res)
 *  920:     function getDataFree($res)
 *  940:     function setDataFromArray(&$dataArr,$traverse=FALSE,$pid=0)	
 *  976:     function setDataFromTreeArray(&$treeArr, &$treeLookupArr)	
 *
 * TOTAL FUNCTIONS: 31
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
 
 









require_once (PATH_t3lib.'class.t3lib_iconworks.php');
require_once (PATH_t3lib.'class.t3lib_befunc.php');
require_once (PATH_t3lib.'class.t3lib_div.php');

/**
 * Base class for creating a browsable array/page/folder tree in HTML
 * 
 * @author	Kasper Skaarhoj <kasper@typo3.com>
 * @coauthor	Ren� Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage t3lib
 * @see t3lib_browsetree, t3lib_pagetree, t3lib_foldertree
 */
class t3lib_treeView {

		// EXTERNAL, static:
	var $expandFirst=0;		// If set, the first element in the tree is always expanded.
	var $expandAll=0;		// If set, then ALL items will be expanded, regardless of stored settings.
	var $thisScript='';		// Holds the current script to reload to.
	var $titleAttrib = 'title';		// Which HTML attribute to use: alt/title. See init().
	var $ext_IconMode = false;		// If true, no context menu is rendered on icons. If set to "titlelink" the icon is linked as the title is.
	var $addSelfId = 0;				// If set, the id of the mounts will be added to the internal ids array
	var $title='no title';			// Used if the tree is made of records (not folders for ex.)

	/**
	 * Needs to be initialized with $GLOBALS['BE_USER']
	 * Done by default in init()
	 */
	var $BE_USER='';

	/**
	 * Needs to be initialized with e.g. $GLOBALS['WEBMOUNTS']
	 * Default setting in init() is 0 => 0
	 * The keys are mount-ids (can be anything basically) and the values are the ID of the root element (COULD be zero or anything else. For pages that would be the uid of the page, zero for the pagetree root.)
	 */
	var $MOUNTS='';



	/**
	 * Database table to get the tree data from.
	 * Leave blank if data comes from an array.
	 */
	var $table='';

	/**
	 * Defines the field of $table which is the parent id field (like pid for table pages).
	 */
	var $parentField='pid';

	/**
	 * WHERE clause used for selecting records for the tree. Is set by function init.
	 * Only makes sense when $this->table is set.
	 * @see init()
	 */
	var $clause=' AND NOT deleted';

	/**
	 * Default set of fields selected from the tree table.
	 * Make SURE that these fields names listed herein are actually possible to select from $this->table (if that variable is set to a TCA table name)
	 * @see addField()
	 */
	var $fieldArray = Array('uid','title');

	/**
	 * List of other fields which are ALLOWED to set (here, based on the "pages" table!)
	 * @see addField()
	 */
	var $defaultList = 'uid,pid,tstamp,sorting,deleted,perms_userid,perms_groupid,perms_user,perms_group,perms_everybody,crdate,cruser_id';


	/**
	 * Unique name for the tree.
	 * Used as key for storing the tree into the BE users settings.
	 * Used as key to pass parameters in links.
	 * MUST NOT contain underscore chars.
	 * etc.
	 */
	var $treeName = '';

	/**
	 * A prefix for table cell id's which will be wrapped around an item.
	 * Can be used for highlighting by JavaScript.
	 * Needs to be unique if multiple trees are on one HTML page.
	 * @see printTree()
	 */
	var $domIdPrefix = 'row';

	/**
	 * Back path for icons
	 */
	var $backPath;

	/**
	 * Icon file path.
	 */
	var $iconPath = '';


	/**
	 * Icon file name for item icons.
	 */
	var $iconName = 'default.gif';

	/**
	 * If true, HTML code is also accumulated in ->tree array during rendering of the tree.
	 */
	var $makeHTML=1;

	/**
	 * If true, records as selected will be stored internally in the ->recs array
	 */
	var $setRecs = 0;

	/**
	 * Sets the associative array key which identifies a new sublevel if arrays are used for trees.
	 * This value has formerly been "subLevel" and "--sublevel--"
	 */
	var $subLevelID = '_SUB_LEVEL';	




		// *********
		// Internal
		// *********
		// For record trees:
	var $ids = Array();				// one-dim array of the uid's selected.
	var $ids_hierarchy = array();	// The hierarchy of element uids
	var $buffer_idH = array();		// Temporary, internal array

		// For FOLDER trees:
	var $specUIDmap=array();		// Special UIDs for folders (integer-hashes of paths)

		// For arrays:
	var $data = false;				// Holds the input data array
	var $dataLookup = false;		// Holds an index with references to the data array.

		// For both types
	var $tree = Array();			// Tree is accumulated in this variable
	var $stored = array();			// Holds (session stored) information about which items in the tree are unfolded and which are not.
	var $bank=0;					// Points to the current mountpoint key
	var $recs = array();			// Accumulates the displayed records.

	





	/**
	 * Initialize the tree class. Needs to be overwritten
	 * Will set ->fieldsArray, ->backPath and ->clause
	 * 
	 * @param	string		record select clause
	 * @return	void		
	 */
	function init($clause='')	{
		$this->BE_USER = $GLOBALS['BE_USER'];	// Setting BE_USER by default
		$this->titleAttrib = 'title';	// Setting title attribute to use.
		$this->backPath = $GLOBALS['BACK_PATH'];	// Setting backpath.

		$this->clause = $clause ? $clause : $this->clause;	// Setting clause

		if (!is_array($this->MOUNTS))	{
			$this->MOUNTS = array(0 => 0); // dummy
		}

		$this->setTreeName();

		if($this->table) {
			t3lib_div::loadTCA($this->table);
		}
	}


	/**
	 * Sets the tree name which is used to identify the tree
	 * Used for JavaScript and other things
	 * 
	 * @param	string		Default is the table name. Underscores are stripped.
	 * @return	void		
	 */
	function setTreeName($treeName='') {
		$this->treeName = $treeName ? $treeName : $this->treeName;
		$this->treeName = $this->treeName ? $this->treeName : $this->table;
		$this->treeName = str_replace('_','',$this->treeName);
	}


	/**
	 * Adds a fieldname to the internal array ->fieldArray
	 * 
	 * @param	string		Field name to
	 * @param	boolean		If set, the fieldname will be set no matter what. Otherwise the field name must either be found as key in $TCA[$table]['columns'] or in the list ->defaultList
	 * @return	void		
	 */
	function addField($field,$noCheck=0)	{
		global $TCA;
		if ($noCheck || is_array($TCA[$this->table]['columns'][$field]) || t3lib_div::inList($this->defaultList,$field))	{
			$this->fieldArray[]=$field;
		}
	}
	
	
	
	/**
	 * Resets the tree, recs, ids, and ids_hierarchy internal variables. Use it if you need it.
	 * 
	 * @return	void		
	 */
	function reset()	{
		$this->tree = array();
		$this->recs = array();
		$this->ids = array();
		$this->ids_hierarchy = array();
	
	# These lines should be removed; The first one does not do anything since $This is not $this; The second makes tree generation based on an array impossible...	
#		$This->data = false;
#		$this->dataLookup = false;
	}


	/*******************************************
	 *
	 * output
	 *
	 *******************************************/

	/**
	 * Will create and return the HTML code for a browsable tree
	 * Is based on the mounts found in the internal array ->MOUNTS (set in the constructor)
	 * 
	 * @return	string		HTML code for the browsable tree
	 */
	function getBrowsableTree()	{

			// Get stored tree structure AND updating it if needed according to incoming PM GET var.
		$this->initializePositionSaving();

			// Init done:
		$titleLen=intval($this->BE_USER->uc['titleLen']);
		$treeArr=array();

			// Traverse mounts:
		foreach($this->MOUNTS as $idx => $uid)	{
				
				// Set first:
			$this->bank=$idx;
			$isOpen = $this->stored[$idx][$uid] || $this->expandFirst;

				// Save ids while resetting everything else.
			$curIds = $this->ids;	
			$this->reset();
			$this->ids = $curIds;

				// Set PM icon for root of mount:
			$cmd=$this->bank.'_'.($isOpen?"0_":"1_").$uid.'_'.$this->treeName;
			$icon='<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/ol/'.($isOpen?'minus':'plus').'only.gif','width="18" height="16"').' alt="" />';
			$firstHtml= $this->PM_ATagWrap($icon,$cmd);

				// Preparing rootRec for the mount
			if ($uid)	{
				$rootRec=$this->getRecord($uid);
				$firstHtml.=$this->getIcon($rootRec);
			} else {
					// Artificial record for the tree root, id=0
				$rootRec=$this->getRootRecord($uid);
				$firstHtml.=$this->getRootIcon($rootRec);
			}

				// Add the root of the mount to ->tree
			$this->tree[]=array('HTML'=>$firstHtml,'row'=>$rootRec,'bank'=>$this->bank);

				// If the mount is expanded, go down:
			if ($isOpen)	{
					// Set depth:
				$depthD='<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/ol/blank.gif','width="18" height="16"').' alt="" />';
				if ($this->addSelfId)	$this->ids[] = $uid;
				$this->getTree($uid,999,$depthD);
			}

				// Add tree:
			$treeArr=array_merge($treeArr,$this->tree);
		}
		return $this->printTree($treeArr);
	}
	
	/**
	 * Compiles the HTML code for displaying the structure found inside the ->tree array
	 * 
	 * @param	array		"tree-array" - if blank string, the internal ->tree array is used.
	 * @return	string		The HTML code for the tree
	 */
	function printTree($treeArr='')	{
		$titleLen=intval($this->BE_USER->uc['titleLen']);
		if (!is_array($treeArr))	$treeArr=$this->tree;
		$out='';

			// put a table around it with IDs to access the rows from JS
			// not a problem if you don't need it
			// In XHTML there is no "name" attribute of <td> elements - but Mozilla will not be able to highlight rows if the name attribute is NOT there.
		$out .= '
		
			<!-- 
			  TYPO3 tree structure.
			-->
			<table cellpadding="0" cellspacing="0" border="0" id="typo3-tree">';

		foreach($treeArr as $k => $v)	{
			$idAttr = htmlspecialchars($this->domIdPrefix.$this->getId($v['row']).'_'.$v['bank']);
			$out.='
				<tr>
					<td id="'.$idAttr.'">'.
						$v['HTML'].
						$this->wrapTitle($this->getTitleStr($v['row'],$titleLen),$v['row'],$v['bank']).
					'</td>
				</tr>
			';
		}
		$out .= '
			</table>';
		return $out;
	}



	/*******************************************
	 *
	 * rendering parts
	 *
	 *******************************************/



	/**
	 * Generate the plus/minus icon for the browsable tree.
	 * 
	 * @param	array		record for the entry
	 * @param	integer		The current entry number
	 * @param	integer		The total number of entries. If equal to $a, a "bottom" element is returned.
	 * @param	integer		The number of sub-elements to the current element.
	 * @param	boolean		The element was expanded to render subelements if this flag is set.
	 * @return	string		Image tag with the plus/minus icon.
	 * @access private
	 * @see t3lib_pageTree::PMicon()
	 */
	function PMicon($row,$a,$c,$nextCount,$exp)	{
		$PM = $nextCount ? ($exp?'minus':'plus') : 'join';
		$BTM = ($a==$c)?'bottom':'';
		$icon = '<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/ol/'.$PM.$BTM.'.gif','width="18" height="16"').' alt="" />';

		if ($nextCount)	{
			$cmd=$this->bank.'_'.($exp?'0_':'1_').$row['uid'].'_'.$this->treeName;
			$bMark=($this->bank.'_'.$row['uid']);
			$icon = $this->PM_ATagWrap($icon,$cmd,$bMark);
		}
		return $icon;
	}

	/**
	 * Wrap the plus/minus icon in a link
	 * 
	 * @param	string		HTML string to wrap, probably an image tag.
	 * @param	string		Command for 'PM' get var
	 * @param	boolean		If set, the link will have a anchor point (=$bMark) and a name attribute (=$bMark)
	 * @return	string		Link-wrapped input string
	 * @access private
	 */
	function PM_ATagWrap($icon,$cmd,$bMark='')	{
		if ($this->thisScript) {
			if ($bMark)	{
				$anchor = '#'.$bMark;
				$name=' name="'.$bMark.'"';
			}
			$aUrl = $this->thisScript.'?PM='.$cmd.$anchor;
			return '<a href="'.htmlspecialchars($aUrl).'"'.$name.'>'.$icon.'</a>';
		} else {
			return $icon;
		}
	}

	/**
	 * Wrapping $title in a-tags.
	 * 
	 * @param	string		Title string
	 * @param	string		Item record
	 * @param	integer		Bank pointer (which mount point number)
	 * @return	string		
	 * @access private
	 */
	function wrapTitle($title,$row,$bank=0)	{
		$aOnClick = 'return jumpTo('.$this->getJumpToParm($row).',this,\''.$this->domIdPrefix.$this->getId($row).'_'.$bank.'\');';
		return '<a href="#" onclick="'.htmlspecialchars($aOnClick).'">'.$title.'</a>';
	}

	/**
	 * Wrapping the image tag, $icon, for the row, $row (except for mount points)
	 * 
	 * @param	string		The image tag for the icon
	 * @param	array		The row for the current element
	 * @return	string		The processed icon input value.
	 * @access private
	 */
 	function wrapIcon($icon,$row)	{
		return $icon;
	}
	
	/**
	 * Adds attributes to image tag.
	 * 
	 * @param	string		Icon image tag
	 * @param	string		Attributes to add, eg. ' border="0"'
	 * @return	string		Image tag, modified with $attr attributes added.
	 */
	function addTagAttributes($icon,$attr)	{
		return ereg_replace(' ?\/?>$','',$icon).' '.$attr.' />'; 
	}

	/**
	 * Adds a red "+" to the input string, $str, if the field "php_tree_stop" in the $row (pages) is set
	 * 
	 * @param	string		Input string, like a page title for the tree
	 * @param	array		record row with "php_tree_stop" field
	 * @return	string		Modified string
	 * @access private
	 */
	function wrapStop($str,$row)	{
		if ($row['php_tree_stop'])	{
			$str.='<span class="typo3-red">+ </span>';
		}
		return $str;
	}






	/*******************************************
	 *
	 * tree handling
	 *
	 *******************************************/


	/**
	 * Returns true/false if the next level for $id should be expanded - based on data in $this->stored[][] and ->expandAll flag.
	 * Extending parent function
	 * 
	 * @param	integer		record id/key
	 * @return	boolean		
	 * @access private
	 * @see t3lib_pageTree::expandNext()
	 */
	function expandNext($id)	{
		return ($this->stored[$this->bank][$id] || $this->expandAll)? 1 : 0;
	}

	/**
	 * Get stored tree structure AND updating it if needed according to incoming PM GET var.
	 * 
	 * @return	void		
	 * @access private
	 */
	function initializePositionSaving()	{
			// Get stored tree structure:
		$this->stored=unserialize($this->BE_USER->uc['browseTrees'][$this->treeName]);

			// PM action
			// (If an plus/minus icon has been clicked, the PM GET var is sent and we must update the stored positions in the tree):
		$PM = explode('_',t3lib_div::GPvar('PM'));	// 0: mount key, 1: set/clear boolean, 2: item ID (cannot contain "_"), 3: treeName
		if (count($PM)==4 && $PM[3]==$this->treeName)	{
			if (isset($this->MOUNTS[$PM[0]]))	{
				if ($PM[1])	{	// set
					$this->stored[$PM[0]][$PM[2]]=1;
					$this->savePosition();
				} else {	// clear
					unset($this->stored[$PM[0]][$PM[2]]);
					$this->savePosition();
				}
			}
		}
	}

	/**
	 * Saves the content of ->stored (keeps track of expanded positions in the tree)
	 * $this->treeName will be used as key for BE_USER->uc[] to store it in
	 * 
	 * @return	void		
	 * @access private
	 */
	function savePosition()	{
		$this->BE_USER->uc['browseTrees'][$this->treeName] = serialize($this->stored);
		$this->BE_USER->writeUC();
	}


	










	/******************************
	 * 
	 * Functions that might be overwritten by extended classes
	 * 
	 ********************************/

	/**
	 * Returns the root icon for a tree/mountpoint (defaults to the globe)
	 * 
	 * @param	array		Record for root.
	 * @return	string		Icon image tag.
	 */
	function getRootIcon($rec) {
		return $this->wrapIcon('<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/i/_icon_website.gif','width="18" height="16"').' alt="" />',$rec);
	}



	/**
	 * Get icon for the row.
	 * If $this->iconPath and $this->iconName is set, try to get icon based on those values.
	 * 
	 * @param	array		Item row.
	 * @return	string		Image tag.
	 */
	function getIcon($row) {
		if ($this->iconPath && $this->iconName) {
			$icon = '<img'.t3lib_iconWorks::skinImg('',$this->iconPath.$this->iconName,'width="18" height="16"').' alt="" />';
		} else {
			$icon = t3lib_iconWorks::getIconImage($this->table,$row,$this->backPath,'align="top" class="c-recIcon"');
		}

		return $this->wrapIcon($icon,$row);
	}


	/**
	 * Returns the title for the input record. If blank, a "no title" labele (localized) will be returned.
	 * Do NOT htmlspecialchar the string from this function - has already been done.
	 * 
	 * @param	array		The input row array (where the key "title" is used for the title)
	 * @param	integer		Title length (30)
	 * @return	string		The title.
	 */
	function getTitleStr($row,$titleLen=30)	{
		$title = (!strcmp(trim($row['title']),'')) ? '<em>['.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.no_title',1).']</em>' : htmlspecialchars(t3lib_div::fixed_lgd($row['title'],$titleLen));
		return $title;
	}

	/**
	 * Returns the value for the image "title" attribute
	 * 
	 * @param	array		The input row array (where the key "title" is used for the title)
	 * @return	string		The attribute value (is htmlspecialchared() already)
	 * @see wrapIcon()
	 */
	function getTitleAttrib($row) {
		return htmlspecialchars($row['title']);
	}
	
	/**
	 * Returns the id from the record (typ. uid)
	 * 
	 * @param	array		Record array
	 * @return	integer		The "uid" field value.
	 */
	function getId($row) {
		return $row['uid'];
	}

	/**
	 * Returns jump-url parameter value.
	 * 
	 * @param	array		The record array.
	 * @return	string		The jump-url parameter.
	 */
	function getJumpToParm($row) {
		return "'".$this->getId($row)."'";
	}















	/********************************
	 *
	 * tree data buidling
	 *
	 ********************************/

	/**
	 * Fetches the data for the tree
	 * 
	 * @param	integer		item id for which to select subitems (parent id)
	 * @param	integer		Max depth (recursivity limit)
	 * @param	string		HTML-code prefix for recursive calls.
	 * @param	string		? (internal)
	 * @return	integer		The count of items on the level
	 */
	function getTree($uid, $depth=999, $depthData='',$blankLineCode='')	{

			// Buffer for id hierarchy is reset:
		$this->buffer_idH=array();
		

			// Init vars
		$depth=intval($depth);
		$HTML='';
		$a=0;

		$res = $this->getDataInit($uid);
		$c = $this->getDataCount($res);
		$crazyRecursionLimiter = 999;

			// Traverse the records:
		while ($crazyRecursionLimiter>0 && $row = $this->getDataNext($res))	{
			$a++;
			$crazyRecursionLimiter--;

			$newID =$row['uid'];
			$this->tree[]=array();		// Reserve space.
			end($this->tree);
			$treeKey = key($this->tree);	// Get the key for this space
			$LN = ($a==$c)?'blank':'line';

				// If records should be accumulated, do so
			if ($this->setRecs)	{
				$this->recs[$row['uid']] = $row;
			}

				// Accumulate the id of the element in the internal arrays
			$this->ids[]=$idH[$row['uid']]['uid']=$row['uid'];
			$this->ids_hierarchy[$depth][]=$row['uid'];

				// Make a recursive call to the next level
			if ($depth>1 && $this->expandNext($newID) && !$row['php_tree_stop'])	{
				$nextCount=$this->getTree(
						$newID,
						$depth-1,
						$this->makeHTML?$depthData.'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/ol/'.$LN.'.gif','width="18" height="16"').' alt="" />':'',
						$blankLineCode.','.$LN
					);
				if (count($this->buffer_idH))	$idH[$row['uid']]['subrow']=$this->buffer_idH;
				$exp=1;	// Set "did expand" flag
			} else {
				$nextCount=$this->getCount($newID);
				$exp=0;	// Clear "did expand" flag
			}

				// Set HTML-icons, if any:
			if ($this->makeHTML)	{
				$HTML = $depthData.$this->PMicon($row,$a,$c,$nextCount,$exp);
				$HTML.=$this->wrapStop($this->getIcon($row),$row);
				#	$HTML.=$this->wrapStop($this->wrapIcon($this->getIcon($row),$row),$row);
			}

				// Finally, add the row/HTML content to the ->tree array in the reserved key.
			$this->tree[$treeKey] = Array(
				'row'=>$row,
				'HTML'=>$HTML,
				'invertedDepth'=>$depth,
				'blankLineCode'=>$blankLineCode,
				'bank' => $this->bank
			);
		}

		$this->getDataFree($res);
		$this->buffer_idH=$idH;
		return $c;
	}



	/********************************
	 *
	 * Data handling
	 * Works with records and arrays
	 *
	 ********************************/



	/**
	 * Returns the number of records having the parent id, $uid
	 * 
	 * @param	integer		id to count subitems for
	 * @return	integer		
	 * @access private
	 */
	function getCount($uid)	{
		if (is_array($this->data)) {
			$res = $this->getDataInit($uid);
			return $this->getDataCount($res);
		} else {
			$query = 'SELECT count(*) FROM '.$this->table.
					' WHERE '.$this->parentField.'="'.addslashes($uid).'"'.
					$this->clause;
			$res = mysql(TYPO3_db, $query);
			$row=mysql_fetch_row($res);
			return $row[0];
		}
	}	



	/**
	 * Returns root record for uid (<=0)
	 * 
	 * @param	integer		uid, <= 0 (normally, this does not matter)
	 * @return	array		Array with title/uid keys with values of $this->title/0 (zero)
	 */
	function getRootRecord($uid) {
		return array('title'=>$this->title, 'uid'=>0);
	}


	/**
	 * Returns the record for a uid.
	 * For tables: Looks up the record in the database.
	 * For arrays: Returns the fake record for uid id.
	 * 
	 * @param	integer		UID to look up
	 * @return	array		The record
	 */
	function getRecord($uid) {
		if (is_array($this->data)) {
			return $this->dataLookup[$uid];
		} else {
			return t3lib_befunc::getRecord($this->table,$uid);
		}
	}

	/**
	 * Getting the tree data: Selecting/Initializing data pointer to items for a certain parent id. 
	 * For tables: This will make a database query to select all children to "parent"
	 * For arrays: This will return key to the ->dataLookup array
	 * 
	 * @param	integer		parent item id
	 * @return	mixed		data handle (Tables: An sql-resource, arrays: A parentId integer. -1 is returned if there were NO subLevel.)
	 * @access private
	 */
	function getDataInit($parentId) {
		if (is_array($this->data)) {
			if (!is_array($this->dataLookup[$parentId][$this->subLevelID])) {
				$parentId = -1;
			} else {
				reset($this->dataLookup[$parentId][$this->subLevelID]);
			}
			return $parentId;
		} else {
			$query = 'SELECT '.implode($this->fieldArray,',').' FROM '.$this->table.
					' WHERE '.$this->parentField.'="'.addslashes($parentId).'"'.
					$this->clause;
			$res = mysql(TYPO3_db, $query);
			if (mysql_error())	{
				echo mysql_error();
				debug($query);
			}
			return $res;
		}
	}

	/**
	 * Getting the tree data: Counting elements in resource
	 * 
	 * @param	mixed		data handle
	 * @return	integer		number of items
	 * @access private
	 * @see getDataInit()
	 */
	function getDataCount($res) {
		if (is_array($this->data)) {
			return count($this->dataLookup[$res][$this->subLevelID]);
		} else {
			$c=mysql_num_rows($res);
			return $c;
		}
	}

	/**
	 * Getting the tree data: next entry
	 * 
	 * @param	mixed		data handle
	 * @return	array		item data array OR FALSE if end of elements.
	 * @access private
	 * @see getDataInit()
	 */
	function getDataNext($res){
		if (is_array($this->data)) {
			if ($res<0) {
				$row=FALSE;
			} else {
				list(,$row) = each($this->dataLookup[$res][$this->subLevelID]);

				/*
				if (!is_array($row))	{
					$row=FALSE;
				} else {
					unset($row['subLevel']);
				}	
				*/
			}
			return $row;
		} else {
			return @mysql_fetch_assoc($res);
		}
	}

	/**
	 * Getting the tree data: frees data handle
	 * 
	 * @param	mixed		data handle
	 * @return	void		
	 * @access private
	 */
	function getDataFree($res){
		if (is_array($this->data)) {
		#	unset();
		} else {
			mysql_free_result($res);
		}
	}

	/**
	 * Used to initialize class with an array to browse.
	 * The array inputted will be traversed and an internal index for lookup is created.
	 * The keys of the input array are perceived as "uid"s of records which means that keys GLOBALLY must be unique like uids are. 
	 * "uid" and "pid" "fakefields" are also set in each record.
	 * All other fields are optional.
	 * 
	 * @param	array		The input array, see examples below in this script.
	 * @param	boolean		Internal, for recursion.
	 * @param	integer		Internal, for recursion.
	 * @return	void		
	 */
	function setDataFromArray(&$dataArr,$traverse=FALSE,$pid=0)	{
		if (!$traverse) {
			$this->data = &$dataArr;
			$this->dataLookup=array();
				// add root
			$this->dataLookup[0][$this->subLevelID]=&$dataArr;
		}

		foreach($dataArr as $uid => $val)	{

			$dataArr[$uid]['uid']=$uid;
			$dataArr[$uid]['pid']=$pid;

				// gives quick access to id's
			$this->dataLookup[$uid] = &$dataArr[$uid];

			if (is_array($val[$this->subLevelID])) {
				$this->setDataFromArray($dataArr[$uid][$this->subLevelID],TRUE,$uid);
				unset($dataArr[$uid][$this->subLevelID]);
			}
		}
		if (!$traverse) {
			$this->data = &$dataArr;
		}
	}

	/**
	 * Sets the internal data arrays 
	 * 
	 * @param	array		Content for $this->data
	 * @param	array		Content for $this->dataLookup
	 * @return	void
	 */
	function setDataFromTreeArray(&$treeArr, &$treeLookupArr)	{
		$this->data = &$treeArr;
		$this->dataLookup=&$treeLookupArr;
	}


	/*
		array(
			[id1] => array(
				'title'=>'title...',
				'id' => 'id1',
				'icon' => 'icon ref, relative to typo3/ folder...'
			),
			[id2] => array(
				'title'=>'title...',
				'id' => 'id2',
				'icon' => 'icon ref, relative to typo3/ folder...'
			),
			[id3] => array(
				'title'=>'title...',
				'id' => 'id3',
				'icon' => 'icon ref, relative to typo3/ folder...'
				$this->subLevelID => array(
					[id3_asdf#1] => array(
						'title'=>'title...',
						'id' => 'asdf#1',
						'icon' => 'icon ref, relative to typo3/ folder...'
					),
					[5] => array(
						'title'=>'title...',
						'id' => 'id...',
						'icon' => 'icon ref, relative to typo3/ folder...'
					),
					[6] => array(
						'title'=>'title...',
						'id' => 'id...', 
						'icon' => 'icon ref, relative to typo3/ folder...'
					),
				)
			),
		)
*/
}


			

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['t3lib/class.t3lib_treeview.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['t3lib/class.t3lib_treeview.php']);
}
?>
