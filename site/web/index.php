<?php
include('../src/constants.php');
include('../src/utils.php');

$currentPage = 'HOME';
include('../src/header.php');
?>
    <div id="notifications"></div>
    <table class="table table-striped">
        <tr>
            <th>Torrent name</th>
            <th style="width: 100px;"></th>
        </tr>
        <?php
        $filesDetails = json_decode(file_get_contents(TEMP_DIR . FILES_DETAILS_MIRROR_SEEDBOX), true);
        foreach ($filesDetails as $fileDetail) {
            if ($fileDetail['file'] != 'recycle_bin') {
                // Check if file has already been downloaded
                $downloaded = shell_exec('find ' . DOWNLOAD_DIRECTORY . ' -name "' . $fileDetail['file'] . '" | wc -l') >= 1;

                echo '<tr' . ($downloaded ? ' class="success"' : '') . '>';
                echo '    <td style="word-break: break-all; line-height: 34px;">' . ($downloaded ? '<span class="glyphicon glyphicon-ok"></span>&nbsp;' : '') . $fileDetail['file'] . ' (<span class="italic">' . octetsToSize($fileDetail['size']) . '</span>)</td>';
                echo '    <td>';
                // 2 cases
                // file doesn't exists, we can download it
                // file exists, and currently downloading
                if (file_exists(TEMP_DIR . SEEDBOX_NAME . '/' . $fileDetail['file'])) {
                    echo '<div class="progress progress-striped active">';
                    $size = shell_exec('du -sk ' . TEMP_DIR . SEEDBOX_NAME . '/' . $fileDetail['file'] . ' | awk \'{print$1}\'') * 1024;
                    $percentNow = 100 * $size / $fileDetail['size'];
                    echo '<div class="progress-bar downloading" role="progressbar" aria-valuenow="' . $size . '" aria-valuemin="0" aria-valuemax="' . $fileDetail['size'] . '" style="width: ' . $percentNow . '%" file="' . urlencode($fileDetail['file']) . '"><span class="glyphicon glyphicon-transfer">&nbsp;' . octetsToSize($size) . '</span></div>';
                    echo '</div>';
                } else if ($downloaded) {
                    echo '<button type="button" class="btn btn-small btn-success disabled"><span class="glyphicon glyphicon-save">&nbsp;Download</span></button>';
                } else {
                    echo '<button type="button" class="download btn btn-small btn-success" file="' . urlencode($fileDetail['file']) . '"><span class="glyphicon glyphicon-save">&nbsp;Download</span></button>';
                }
                echo '    </td>';
                echo '</tr>';
            }
        }
        ?>
    </table>
<?php include('../src/footer.php'); ?>