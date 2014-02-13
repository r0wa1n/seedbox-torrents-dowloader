<?php
    include('../src/constants.php');
    include('../src/utils.php');

    $currentPage = 'LOGS';
    include('../src/header.php');
?>
<table class="table table-striped">
    <tr>
        <th>Description</th>
        <th style="width: 100px;"></th>
    </tr>
    <?php
        if ($handle = opendir(LOGS_DIRECTORY)) {
            $files = array();
            while (false !== ($entry = readdir($handle))) {
                if($entry != '.' && $entry != '..') {
                    $files[] = $entry;
                }
            }
            closedir($handle);

            // Sort files
            arsort($files);
            foreach($files as $entry) {
    ?>
    <tr>
        <td><?php echo $entry; ?></td>
        <td><a class="btn btn-small btn-success" href="log.php?file=<?php echo urlencode($entry); ?>" style="color: white;"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Details</a></td>
    </tr>
    <?php
            }
        }
    ?>
</table>
<?php include('../src/footer.php'); ?>