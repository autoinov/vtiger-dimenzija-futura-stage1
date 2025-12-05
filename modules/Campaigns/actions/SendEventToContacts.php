<?php

class Campaigns_SendEventToContacts_Action extends Vtiger_Action_Controller {
    public function checkPermission(Vtiger_Request $request) {
        return true;
    }

    public function process(Vtiger_Request $request) {
        global $adb;

        $campaignId = $request->get('record');
        $eventId = 4270; // Replace with your actual Event ID

        $eventData = getEventDetails($eventId);
        if (!$eventData) {
            echo json_encode(['result' => '❌ Event not found']);
            return;
        }

        $query = "SELECT contactid FROM vtiger_campaigncontrel WHERE campaignid = ?";
        $result = $adb->pquery($query, [$campaignId]);
        $count = $adb->num_rows($result);

        $emailsSent = 0;

        for ($i = 0; $i < $count; $i++) {
            $contactId = $adb->query_result($result, $i, 'contactid');
            $email = getContactEmail($contactId);

            if ($email) {
                $success = sendICSInvite($email, $eventData);
                if ($success) {
                    $emailsSent++;
                }
            }
        }

        echo json_encode(['result' => "$emailsSent invites sent"]);
    }
}

function getContactEmail($contactId) {
    global $adb;
    $result = $adb->pquery("SELECT email FROM vtiger_contactdetails WHERE contactid = ?", [$contactId]);
    if ($adb->num_rows($result)) {
        return $adb->query_result($result, 0, 'email');
    }
    return null;
}

function getEventDetails($eventId) {
    global $adb;
    $query = "SELECT subject, date_start, time_start, due_date, time_end, location FROM vtiger_activity WHERE activityid = ?";
    $result = $adb->pquery($query, [$eventId]);
    if ($adb->num_rows($result)) {
        return $adb->query_result_rowdata($result, 0);
    }
    return null;
}

function sendICSInvite($toEmail, $eventData) {
    $from = "noreply@dimenzija-futura.hr";
    $fromName = "CRM Calendar";
    $subject = "You're invited to: " . $eventData['subject'];

    $start = $eventData['date_start'] . ' ' . $eventData['time_start'];
    $end   = $eventData['due_date'] . ' ' . $eventData['time_end'];

    $startTime = date('Ymd\THis', strtotime($start));
    $endTime = date('Ymd\THis', strtotime($end));
    $nowStamp = date('Ymd\THis');
    $uid = uniqid() . "@dimenzija-futura.hr";

    $ics = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//VtigerCRM//
CALSCALE:GREGORIAN
METHOD:REQUEST
BEGIN:VEVENT
UID:$uid
DTSTAMP:$nowStamp
DTSTART:$startTime
DTEND:$endTime
SUMMARY:" . escapeIcs($eventData['subject']) . "
DESCRIPTION:" . escapeIcs("You are invited to this event from CRM.") . "
LOCATION:" . escapeIcs($eventData['location']) . "
STATUS:CONFIRMED
SEQUENCE:0
END:VEVENT
END:VCALENDAR";

    $htmlMessage = "<p>You are invited to the following event:</p>
<strong>Subject:</strong> {$eventData['subject']}<br/>
<strong>Starts:</strong> $start<br/>
<strong>Ends:</strong> $end<br/>
<strong>Location:</strong> {$eventData['location']}<br/>";

    $boundary = uniqid("boundary_");
    $headers = "From: $fromName <$from>\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";

    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $htmlMessage . "\r\n";

    $body .= "--$boundary\r\n";
    $body .= "Content-Type: text/calendar; method=REQUEST; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $ics . "\r\n";
    $body .= "--$boundary--";

    return mail($toEmail, $subject, $body, $headers);
}

function escapeIcs($string) {
    return preg_replace('/([\,;])/', '\\\\$1', str_replace("\n", "\\n", $string));
}
