<?php
include('../src/constants.php');

$file = $_GET['file'];

if (empty($file) || !file_exists(LOGS_DIRECTORY . $file)) {
    header('Location: logs.php');
}

$currentPage = 'LOGS';
include('../src/header.php');

echo '<pre>' . file_get_contents(LOGS_DIRECTORY . $file) . '</pre>';

include('../src/footer.php');