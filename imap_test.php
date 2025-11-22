<?php
$hostname = '{mail.dimenzija-futura.hr/imap/ssl/novalidate-cert}INBOX';
$username = 'crm@dimenzija-futura.hr';
$password = 'sLW2dd3o[*7Z0D';

$inbox = imap_open($hostname, $username, $password);

if (!$inbox) {
    die('IMAP connection failed: ' . imap_last_error());
} else {
    echo "IMAP connected successfully!";
}

$mailbox = imap_open("{mail.dimenzija-futura.hr:993/imap/ssl/novalidate-cert}", "crm@dimenzija-futura.hr", "sLW2dd3o[*7Z0D");

if ($mailbox) {
    $folders = imap_list($mailbox, "{mail.dimenzija-futura.hr:993/imap/ssl/novalidate-cert}", "*");
    if ($folders === false) {
        echo "Could not list folders: " . imap_last_error();
    } else {
        echo "<pre>";
        print_r($folders);
        echo "</pre>";
    }
    imap_close($mailbox);
} else {
    echo "IMAP connection failed: " . imap_last_error();
}
?>