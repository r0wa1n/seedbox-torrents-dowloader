<!DOCTYPE html>
<html>
<head>
    <title><?php echo WEBSITE_TITLE; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <link href="css/main.css" rel="stylesheet" media="screen">
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><?php echo WEBSITE_TITLE; ?></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="<?php if($currentPage == 'HOME') { echo 'active';} ?>"><a href="index.php">Home</a></li>
                <li class="<?php if($currentPage == 'LOGS') { echo 'active';} ?>"><a href="logs.php">Logs</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="pull-left">
            <table id="main-information">
                <tr>
                    <td>Last update :</td>
                    <td><span class="badge"><?php echo date(DATE_PATTERN, file_get_contents('../src/last-update')); ?></span></td>
                </tr>
                <tr>
                    <td>Space size left :</td>
                    <td><span class="badge"><?php echo shell_exec('df -h '.ROOT_SERVER_DIRECTORY.' | awk \'NR==2{print$4}\'') ?></span></td>
                </tr>
                <tr>
                    <td>Total torrents :</td>
                    <td><span class="badge"><?php echo shell_exec('ls -1 '.FILES_SERVER_DIRECTORY.' | wc -l') ?></span></td>
                </tr>
            </table>
        </div>
        <div class="pull-right"><a class="btn btn-small btn-info" href="update.php" style="color: white;"><span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;Update</a></div>
    </div>
</div>