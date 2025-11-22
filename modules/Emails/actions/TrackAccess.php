<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

file_put_contents(__DIR__ . '/debug.txt', date('Y-m-d H:i:s') . " - TRACK START\n", FILE_APPEND);


header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false);

//Opensource fix for tracking email access count
chdir(dirname(__FILE__). '/../../../');

require_once 'vendor/autoload.php';
require_once 'include/utils/utils.php';

vimport('includes.http.Request');
vimport('includes.runtime.Globals');
vimport('includes.runtime.BaseModel');
vimport ('includes.runtime.Controller');
vimport('includes.runtime.LanguageHandler');

class Emails_TrackAccess_Action extends Vtiger_Action_Controller {

	public function requiresPermission(\Vtiger_Request $request) {
		$permissions = parent::requiresPermission($request);
		$permissions[] = array('module_parameter' => 'module', 'action' => 'DetailView');
		return $permissions;
	}
	
	public function checkPermission(Vtiger_Request $request) {
		return parent::checkPermission($request);
	}
	
	public function process(Vtiger_Request $request) {
    file_put_contents(__DIR__ . '/debug.txt', date('Y-m-d H:i:s') . " - TRACK HIT\n", FILE_APPEND);

    $recordId = $request->get('record');
    $parentId = $request->get('parentId');

    file_put_contents(__DIR__ . '/debug.txt', "record: $recordId, parent: $parentId\n", FILE_APPEND);

    if ($parentId && $recordId) {
    try {
        file_put_contents(__DIR__ . '/debug.txt', "Trying to load recordModel for ID: $recordId\n", FILE_APPEND);
        include_once 'modules/Emails/models/Record.php';
$recordModel = new Emails_Record_Model();
$recordModel->setId($recordId);
        file_put_contents(__DIR__ . '/debug.txt', "Actual class file: " . (new \ReflectionClass($recordModel))->getFileName() . "\n", FILE_APPEND);
        if ($recordModel) {
            //file_put_contents(__DIR__ . '/debug.txt', "recordModel FOUND\n", FILE_APPEND);
            $recordModel->updateTrackDetails($parentId);
            //$recordModel->testLoggerPing(); // temporary test
            file_put_contents(__DIR__ . '/debug.txt', "updateTrackDetails called\n", FILE_APPEND);
        } else {
            //file_put_contents(__DIR__ . '/debug.txt', "recordModel is NULL\n", FILE_APPEND);
        }
    } catch (Exception $e) {
        //file_put_contents(__DIR__ . '/debug.txt', "EXCEPTION: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}


    Vtiger_ShortURL_Helper::sendTrackerImage();
}
	
	public function clickHandler(Vtiger_Request $request) {
		$parentId = $request->get('parentId');
		$recordId = $request->get('record');

		if ($parentId && $recordId) {
			$recordModel = Emails_Record_Model::getInstanceById($recordId);
			$recordModel->trackClicks($parentId);
		}
		
		$redirectUrl = $request->get('redirectUrl');
		if(!empty($redirectUrl)) {
			return Vtiger_Functions::redirectUrl(rawurldecode($redirectUrl));
		}
	}
}

$track = new Emails_TrackAccess_Action();
$track->process(new Vtiger_Request($_REQUEST));
