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
 * TCE gateway (TYPO3 Core Engine) for database handling
 * This script is a gateway for POST forms to class.t3lib_TCEmain that manipulates all information in the database!!
 * For syntax and API information, see the document 'TYPO3 Core APIs' 
 *
 * $Id$
 * Revised for TYPO3 3.6 July/2003 by Kasper Skaarhoj
 * 
 * @author	Kasper Skaarhoj <kasper@typo3.com>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   80: class SC_tce_db 
 *  107:     function init()	
 *  160:     function initClipboard()	
 *  180:     function main()	
 *  216:     function finish()	
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
 
 
require ('init.php');
require ('template.php');
require_once (PATH_t3lib.'class.t3lib_tcemain.php');













/**
 * Script Class, creating object of t3lib_TCEmain and sending the posted data to the object.
 * Used by many smaller forms/links in TYPO3, including the QuickEdit module.
 * Is not used by alt_doc.php though (main form rendering script) - that uses the same class (TCEmain) but makes its own initialization (to save the redirect request).
 * For all other cases than alt_doc.php it is recommended to use this script for submitting your editing forms - but the best solution in any case would probably be to link your application to alt_doc.php, that will give you easy form-rendering as well.
 * 
 * @author	Kasper Skaarhoj <kasper@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class SC_tce_db {
	
		// Internal, static: GPvar
	var $flags;
	var $data;
	var $cmd;
	var $mirror;
	var $cacheCmd;
	var $redirect;
	var $prErr;
	var $_disableRTE;
	var $CB;
	var $vC;
	var $uPT;

		// Internal, dynamic:	
	var $include_once=array();		// Files to include after init() function is called:
	var $tce;						// TCEmain object




	/**
	 * Initialization of the class
	 * 
	 * @return	void		
	 */
	function init()	{
		global $BE_USER;

			// GPvars:
		$this->flags = t3lib_div::GPvar('flags');
		$this->data = t3lib_div::GPvar('data');
		$this->cmd = t3lib_div::GPvar('cmd');
		$this->mirror = t3lib_div::GPvar('mirror');
		$this->cacheCmd = t3lib_div::GPvar('cacheCmd');
		$this->redirect = t3lib_div::GPvar('redirect');
		$this->prErr = t3lib_div::GPvar('prErr');
		$this->_disableRTE = t3lib_div::GPvar('_disableRTE');
		$this->CB = t3lib_div::GPvar('CB');
		$this->vC = t3lib_div::GPvar('vC');
		$this->uPT = t3lib_div::GPvar('uPT');
		
			// Creating TCEmain object
		$this->tce = t3lib_div::makeInstance('t3lib_TCEmain');
		
			// Configuring based on user prefs.
		if ($BE_USER->uc['recursiveDelete'])	{
			$this->tce->deleteTree = 1;	// True if the delete Recursive flag is set.
		}
		if ($BE_USER->uc['copyLevels'])	{
			$this->tce->copyTree = t3lib_div::intInRange($BE_USER->uc['copyLevels'],0,100);	// Set to number of page-levels to copy.
		}
		if ($BE_USER->uc['neverHideAtCopy'])	{
			$this->tce->neverHideAtCopy = 1;
		}
		
		$TCAdefaultOverride = $BE_USER->getTSConfigProp('TCAdefaults');
		if (is_array($TCAdefaultOverride))	{
			$this->tce->setDefaultsFromUserTS($TCAdefaultOverride);
		}
		
			// Reverse order.
		if ($this->flags['reverseOrder'])	{
			$this->tce->reverseOrder=1;
		}
		
		$this->tce->disableRTE = $this->_disableRTE;

			// Clipboard?
		if (is_array($this->CB))	{
			$this->include_once[]=PATH_t3lib.'class.t3lib_clipboard.php';
		}
	}

	/**
	 * Clipboard pasting and deleting.
	 * 
	 * @return	void		
	 */
	function initClipboard()	{
		if (is_array($this->CB))	{
			$clipObj = t3lib_div::makeInstance('t3lib_clipboard');
			$clipObj->initializeClipboard();
			if ($this->CB['paste'])	{
				$clipObj->setCurrentPad($this->CB['pad']);
				$this->cmd = $clipObj->makePasteCmdArray($this->CB['paste'],$this->cmd);
			}
			if ($this->CB['delete'])	{
				$clipObj->setCurrentPad($this->CB['pad']);
				$this->cmd = $clipObj->makeDeleteCmdArray($this->cmd);
			}
		}
	}

	/**
	 * Executing the posted actions ...
	 * 
	 * @return	void		
	 */
	function main()	{
		global $BE_USER,$TYPO3_CONF_VARS;

			// LOAD TCEmain with data and cmd arrays:
		$this->tce->start($this->data,$this->cmd);
		if (is_array($this->mirror))	{$this->tce->setMirror($this->mirror);}
		
			// Checking referer / executing
		$refInfo=parse_url(t3lib_div::getIndpEnv('HTTP_REFERER'));
		$httpHost = t3lib_div::getIndpEnv('TYPO3_HOST_ONLY');
		if ($httpHost!=$refInfo['host'] && $this->vC!=$BE_USER->veriCode() && !$TYPO3_CONF_VARS['SYS']['doNotCheckReferer'])	{
			$this->tce->log('',0,0,0,1,'Referer host "%s" and server host "%s" did not match and veriCode was not valid either!',1,array($refInfo['host'],$httpHost));
		} else {
				// Register uploaded files
			$this->tce->process_uploads($GLOBALS['HTTP_POST_FILES']);
			
				// Execute actions:
			$this->tce->process_datamap();
			$this->tce->process_cmdmap();
			
				// Clearing cache:
			$this->tce->clear_cacheCmd($this->cacheCmd);
			
				// Update page tree?
			if ($this->uPT && (isset($this->data['pages'])||isset($this->cmd['pages'])))	{
				t3lib_BEfunc::getSetUpdateSignal('updatePageTree');
			}
		}
	}

	/**
	 * Redirecting the user after the processing has been done.
	 * Might also display error messages directly, if any.
	 * 
	 * @return	void		
	 */
	function finish()	{
			// Prints errors, if...
		if ($this->prErr)	{
			$this->tce->printLogErrorMessages($this->redirect);
		}

		if ($this->redirect && !$this->tce->debug) {
			Header('Location: '.t3lib_div::locationHeaderUrl($this->redirect));
		}
	}	
}

// Include extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/tce_db.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/tce_db.php']);
}







// Make instance:
$SOBE = t3lib_div::makeInstance('SC_tce_db');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->initClipboard();
$SOBE->main();
$SOBE->finish();
?>
