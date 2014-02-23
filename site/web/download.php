<?php
$file = $_POST['file'];

if (empty($file)) {
    http_response_code(400);
} else {
    // Start download
    shell_exec('/usr/bin/php ../src/download.php "' . $file . '" > /dev/null 2>/dev/null &');
}