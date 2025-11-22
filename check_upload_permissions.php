<?php
$paths = [
    'storage/',
    'test/',
    'test/upload/',
    'test/upload/images/',
];

foreach ($paths as $path) {
    echo "<h3>Checking: $path</h3>";

    if (!file_exists($path)) {
        echo "❌ Directory does not exist.<br>";
        continue;
    }

    if (is_writable($path)) {
        echo "✅ Writable by PHP.<br>";

        $rand = mt_rand(1000, 9999);
        $filename = $path . "test.$rand.txt";

        if (@file_put_contents($filename, "Testing Vtiger upload behavior.")) {
            echo "✅ Created file: <code>$filename</code><br>";
        } else {
            echo "⚠️ Writable, but <code>file_put_contents()</code> failed.<br>";
        }
    } else {
        echo "❌ Not writable by PHP.<br>";
    }
}

echo "<hr><b>Running as:</b><br>";
echo 'PHP user: ' . get_current_user() . "<br>";
echo 'Effective user: ' . exec('whoami') . "<br>";
echo 'UID: ' . getmyuid() . "<br>";
?>