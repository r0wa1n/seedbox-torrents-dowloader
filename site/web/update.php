<?php
    //shell_exec('/scripts/checking-files-server.sh');
    $currentTime = time();
    shell_exec('echo '.$currentTime.' > ../src/last-update');
    // Redirect to home page
    header('Location: index.php');
?>