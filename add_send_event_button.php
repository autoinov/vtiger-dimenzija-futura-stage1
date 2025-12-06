<?php
echo "🔧 Script starting...<br>";

error_reporting(E_ALL);
ini_set('display_errors', 1);

chdir(dirname(__FILE__));
echo "📁 Changed to directory: " . getcwd() . "<br>";

if (!file_exists('includes/main/WebUI.php')) {
    echo "❌ WebUI.php not found. Check path.<br>";
    exit;
}

include_once 'includes/main/WebUI.php';
include_once 'vtlib/Vtiger/Module.php';
echo "✅ Included Vtiger core files.<br>";

try {
    $campaign = Vtiger_Module::getInstance('Campaigns');
    if (!$campaign) {
        throw new Exception("❌ Campaigns module not found.<br>");
    }

    $campaign->deleteLink('DETAILVIEWBASIC', 'Send Event Invite');
    $campaign->addLink(
        'DETAILVIEWBASIC',
        'Send Event Invite',
        'index.php?module=Campaigns&view=SelectEventPopup&record=$RECORD$'
    );

    echo "🎉 Button added successfully with popup.<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}

