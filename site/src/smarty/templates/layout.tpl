<!DOCTYPE html>
<html>
<head>
    <title>{$header.title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <link href="css/theme.bootstrap.css" rel="stylesheet" media="screen">
    <link href="css/docs.min.css" rel="stylesheet" media="screen">
    <link href="css/main.css" rel="stylesheet" media="screen">
    <link rel="icon" type="image/png" href="img/favicon.png" />
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
                <li class="{if $header.currentPage eq 'SETTINGS'}active{/if}"><a href="settings.php"><span class="glyphicon glyphicon-cog"></span></a></li>
            </ul>
        </div>
    </div>
    {if not $header.isSeedboxInitialized and $header.currentPage ne 'SETTINGS'}
        <div class="container-fluid warning">
            Your seedbox information has not been set. Go to settings to set them. <a href="settings.php"><span class="glyphicon glyphicon-cog"></span></a>
        </div>
    {/if}
    <div id="notifications"></div>
</nav>

<div id="content">
    {if isset($header.lastUpdate) and isset($header.diskInfo)}
        <div class="panel panel-default" {if not $header.isSeedboxInitialized and $header.currentPage ne 'SETTINGS'}style="margin-top: 20px;"{/if}>
            <div class="panel-body">
                <table id="main-information">
                    <tr>
                        <td>Last update</td>
                        <td>
                            <span class="label label-default" style="background-color: #8D8D8D; font-weight: inherit; border: 1px solid #000000; font-size: inherit;">{$header.lastUpdate}</span>
                            <span id="update" class="glyphicon glyphicon-refresh" style="cursor: pointer; font-size: 1.2em; margin-left: 5px;"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Disk info</td>
                        <td>
                            <div class="progress progress-striped" id="progress-disk-size" title="{convert_octet_to_human_readable_size size={$header.diskInfo.totalSizeLeft}} left">
                                <div class="progress-bar progress-bar-{$header.diskInfo.progressClass}" role="progressbar"
                                     aria-valuenow="${$header.diskInfo.totalSizeUsed}" aria-valuemin="0"
                                     aria-valuemax="${$header.diskInfo.totalSize}"
                                     style="width: {$header.diskInfo.totalPercentSizeUsed}%;"></div>
                                <span>&nbsp;{convert_octet_to_human_readable_size size={$header.diskInfo.totalSizeUsed}} / {convert_octet_to_human_readable_size size={$header.diskInfo.totalSize}}&nbsp;</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    {/if}

    {block name=content}{/block}
</div>

<p id="footer">{$footer.title}</p>

<div id="loading">
    <span>Loading</span>
    <img src="img/loading.gif"/>
</div>
<script src="js/jquery-min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.tablesorter.min.js"></script>
<script src="js/jquery.tablesorter.widgets.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>