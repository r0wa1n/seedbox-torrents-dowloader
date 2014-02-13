<?php
include('../src/constants.php');
include('../src/utils.php');

$currentPage = 'HOME';
include('../src/header.php');
?>
<table class="table table-striped">
    <tr>
        <th>Torrent name</th>
        <th style="width: 100px;"></th>
    </tr>
    <?php
        if ($handle = opendir(FILES_SERVER_DIRECTORY)) {
            $files = array();
            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..' && $entry != 'recycle_bin') {
                    $files[] = $entry;
                }
            }
            closedir($handle);

            // sort files
            sort($files);
            foreach ($files as $entry) {
                $sizeEntry = file_get_contents(FILES_SERVER_DIRECTORY . $entry);
                // Check if file has already been downloaded
                $downloaded = shell_exec('find ' . DOWNLOAD_DIRECTORY . ' -name "' . $entry . '" | wc -l') >= 1;
    ?>
    <tr <?php if($downloaded) { echo 'class="success"'; } ?>>
        <td style="word-break: break-all; line-height: 34px;"><?php if($downloaded) { echo '<span class="glyphicon glyphicon-ok"></span>&nbsp;'; } echo $entry . ' (<span class="italic">' . octetsToSize($sizeEntry) . '</span>)'; ?></td>
        <td>
            <?php
                // 3 cases
                // file doesn't exists, we can download it
                // file exists, and still not start downloading
                // file exists, and currently downloading
                if(file_exists(FILES_TO_DOWNLOAD_SERVER_DIRECTORY . $entry)) {
                    echo '<div class="progress progress-striped active">';
                    if(file_exists(FILES_TO_DOWNLOAD_SERVER_DIRECTORY . SEEDBOX_NAME . '/' . $entry)) {
                        $size = shell_exec('du -s '. FILES_TO_DOWNLOAD_SERVER_DIRECTORY . SEEDBOX_NAME .'/' . $entry . ' | awk \'{print$1}\'') * 512;
                        $percentNow = 100 * $size / $sizeEntry;
                        echo '<div class="progress-bar" role="progressbar" aria-valuenow="' . $size . '" aria-valuemin="0" aria-valuemax="' . $sizeEntry . '" style="width: ' . $percentNow . '%"><span class="glyphicon glyphicon-transfer">&nbsp;' . octetsToSize($size) . '</span></div>';
                    } else {
                        echo '<div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="glyphicon glyphicon-import">&nbsp;Pending...</span></div>';
                    }
                    echo '</div>';
                } else if($downloaded) {
                    echo '<a class="btn btn-small btn-success disabled"><span class="glyphicon glyphicon-save">&nbsp;Download</span></a>';
                } else {
                    echo '<a class="btn btn-small btn-success" href="download.php?file=' . urlencode($entry) . '"><span class="glyphicon glyphicon-save">&nbsp;Download</span></a>';
                }
            ?>

        </td>
    </tr>
    <?php
            }
        }
    ?>
</table>
<?php include('../src/footer.php'); ?>
