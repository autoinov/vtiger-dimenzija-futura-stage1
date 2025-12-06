<?php

class Campaigns_SelectEventPopup_View extends Vtiger_Index_View {
    public function process(Vtiger_Request $request) {
        global $adb;

        // DEBUG: Log when popup is opened
        //file_put_contents(__DIR__ . '/../../debug_event_popup.txt', "Popup opened at " . date('c') . "\n", FILE_APPEND);

        $campaignId = $request->get('record');  // Must match `record={$CAMPAIGNID}` in the .tpl

        // DEBUG: Log campaign ID
        file_put_contents(__DIR__ . '/../../debug_event_popup.txt', "Campaign ID: $campaignId\n", FILE_APPEND);

        $events = [];

        $query = "
            SELECT activityid, subject 
            FROM vtiger_activity
            INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_activity.activityid
            WHERE vtiger_crmentity.deleted = 0 
              AND vtiger_activity.activitytype = 'Meeting'
            ORDER BY date_start DESC
            LIMIT 50
        ";

        $result = $adb->pquery($query, []);
        $count = $adb->num_rows($result);

        // DEBUG: Log event count
        file_put_contents(__DIR__ . '/../../debug_event_popup.txt', "Events Found: $count\n", FILE_APPEND);

        while ($row = $adb->fetch_array($result)) {
            $events[] = [
                'id' => $row['activityid'],
                'label' => $row['subject']
            ];
        }

        $viewer = $this->getViewer($request);
        $viewer->assign('EVENTS', $events);
        $viewer->assign('CAMPAIGNID', $campaignId);
        $viewer->view('SelectEventPopup.tpl', $request->getModule());
    }
}
