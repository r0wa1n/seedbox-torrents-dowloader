<?php
    shell_exec('../../scripts/checking-files-server.sh');
    shell_exec('echo ' . time() . ' > ../src/last-update');