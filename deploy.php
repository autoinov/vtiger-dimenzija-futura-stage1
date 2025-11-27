<?php
// Security: Require a secret token to run the script
$secret = "12345!"; // Change this to a strong random value

if (!isset($_GET['token']) || $_GET['token'] !== $secret) {
    http_response_code(403);
    die("Access Denied.");
}

// Set the repository directory
$repo_dir = '/home/dimenzij/public_html/vtiger';

// Log file for debugging
$log_file = '/home/dimenzij/public_html/vtiger/deploy.log';

if (function_exists('exec')) {
    exec("cd {$repo_dir} && git pull origin main 2>&1", $output, $return_var);

    file_put_contents($log_file, implode("\n", $output), FILE_APPEND);

    echo "Deployment Complete.";
} else {
    file_put_contents($log_file, "ERROR: exec() function is disabled.\n", FILE_APPEND);
    echo "ERROR: exec() function is disabled.";
}
?>
