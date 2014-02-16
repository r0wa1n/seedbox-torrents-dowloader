<!DOCTYPE html>
<html>
<head>
    <title>{$header.title}</title>
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
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">{$header.title}</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="{if $header.currentPage eq 'HOME'}active{/if}"><a href="index.php">Home</a></li>
                <li class="{if $header.currentPage eq 'LOGS'}active{/if}"><a href="logs.php">Logs</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="pull-left">
            <table id="main-information">
                <tr>
                    <td>Last update</td>
                    <td>
                        <span class="badge">{$header.lastUpdate}</span>
                    </td>
                </tr>
                <tr>
                    <td>Disk info</td>
                    <td>
                        <div class="progress" id="progress-disk-size">
                            <div class="progress-bar" role="progressbar"
                                 aria-valuenow="${$header.diskInfo.totalSizeUsed}" aria-valuemin="0"
                                 aria-valuemax="${$header.diskInfo.totalSize}"
                                 style="width: {$header.diskInfo.totalPercentSizeUsed}%;"></div>
                            <span>&nbsp;{convert_octet_to_human_readable_size size={$header.diskInfo.totalSizeUsed}} / {convert_octet_to_human_readable_size size={$header.diskInfo.totalSize}}&nbsp;</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-small btn-info" id="update" style="color: white;"><span
                        class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;Update
            </button>
        </div>
    </div>
</div>


{block name=content}{/block}

<p id="footer">{$footer.title}</p>

<div id="loading">
    <span>Loading</span>
    <img src="img/loading.gif"/>
</div>
<script src="js/jquery-min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>