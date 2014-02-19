$(document).ready(function () {
    // Init table sorter
    initTableSorter();
    // Link download buttons
    $('.download').click(function () {
        var tdLine = $(this);
        $.ajax({
            type: 'POST',
            url: 'download.php',
            data: { file: tdLine.attr('file') },
            success: function (data) {
                // Change button to pending button
                tdLine.parent().html('<div class="progress progress-striped active"><div class="progress-bar pending" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%" file="' + tdLine.attr('file') + '"><span class="glyphicon glyphicon-import">&nbsp;Pending...</span></div></div>');
            },
            error: function () {
                alert('Error when downloading file...');
            }
        });
    });
    // Link update button
    $('#update').click(function () {
        // display loading screen
        var loading = $('div#loading');
        loading.css('height', $(document).height());
        loading.show();
        loading.animate({opacity: '0.6'}, 500, function () {
            $.ajax({
                type: 'GET',
                url: 'update.php',
                success: function () {
                    // Remove loading screen and redirect to home page
                    $('div#loading').animate({opacity: '0'}, 500, function () {
                        $('div#loading').hide();
                        window.location.href = 'index.php';
                    });
                }
            });
        });
    });
    // Put timer in order to update size of current downloading files
    setInterval(updateDownloadedFiles, 2000);
    // init settings page
    initSettings();
});

function initTableSorter() {
    $.extend($.tablesorter.themes.bootstrap, {
        // these classes are added to the table. To see other table classes available,
        // look here: http://twitter.github.com/bootstrap/base-css.html#tables
        table: 'table table-bordered',
        caption: 'caption',
        header: 'bootstrap-header', // give the header a gradient background
        footerRow: '',
        footerCells: '',
        icons: '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
        sortNone: 'bootstrap-icon-unsorted',
        sortAsc: 'icon-chevron-up glyphicon glyphicon-chevron-up',     // includes classes for Bootstrap v2 & v3
        sortDesc: 'icon-chevron-down glyphicon glyphicon-chevron-down', // includes classes for Bootstrap v2 & v3
        active: '', // applied when column is sorted
        hover: '', // use custom css here - bootstrap class may not override it
        filterRow: '', // filter row class
        even: '', // odd row zebra striping
        odd: ''  // even row zebra striping
    });
    $.tablesorter.addParser({
        id: 'octet_size',
        is: function (s) {
            return false;
        },
        format: function (s, table, cell, cellIndex) {
            var cell = $(cell);
            return cell.parent().find('input').val()
        },
        type: 'numeric'
    });
    // Torrents list sortable
    $("#torrents-list").tablesorter({
        theme: "bootstrap",
        widthFixed: true,
        headerTemplate: '{content} {icon}',
        widgets: [ "uitheme", "filter", "zebra" ],
        widgetOptions: {
            zebra: ["even", "odd"],
            filter_reset: ".reset"
        },
        sortList: [
            // Default sort is first column asc order
            [0, 0]
        ],
        headers: {
            // For size column, create customer parser
            1: {
                sorter: 'octet_size',
                filter: false
            },
            // Disable download column sort
            2: {
                sorter: false,
                filter: false
            }
        } });
    // Logs list sortable
    $("#logs-list").tablesorter({
        theme: "bootstrap",
        widthFixed: true,
        headerTemplate: '{content} {icon}',
        widgets: [ "uitheme", "filter", "zebra" ],
        widgetOptions: {
            zebra: ["even", "odd"],
            filter_reset: ".reset"
        },
        sortList: [
            // Default sort is first column asc order
            [0, 0]
        ],
        headers: {
            1: {
                sorter: false,
                filter: false
            }
        } });
}

function updateDownloadedFiles() {
    // Check all current downloading files
    $('.downloading').each(function () {
        var progressBar = $(this);
        $.ajax({
            type: 'GET',
            url: 'size.php',
            data: { file: progressBar.attr('file') },
            success: function (data) {
                if (data != '' && data != '-1') {
                    data = JSON.parse(data);
                    progressBar.attr('aria-valuenow', data.s);
                    var percent = 100 * data.s / data.t;
                    progressBar.css('width', percent + '%');
                    progressBar.find('span').html('&nbsp;' + data.h);
                } else if (data == '-1') {
                    // if it's equal to -1 it means file is present in download dir
                    var file = progressBar.attr('file');
                    var tr = progressBar.closest('tr');
                    tr.addClass('success');
                    tr.find('td:first-child').prepend('<span class="glyphicon glyphicon-ok"></span>&nbsp;');
                    tr.find('td:last-child').empty();
                    tr.find('td:last-child').append('<button type="button" class="btn btn-small btn-success disabled"><span class="glyphicon glyphicon-save">&nbsp;Download</span></button>');
                    // notify user that the download is complete
                    addNotification(file + ' just complete.', 'alert-success');
                }
            }
        });
    });
    // Check all pending files
    $('.pending').each(function () {
        var progressBar = $(this);
        $.ajax({
            type: 'GET',
            url: 'size.php',
            data: { file: progressBar.attr('file') },
            success: function (data) {
                if (data != '' && data != '-1') {
                    data = JSON.parse(data);
                    progressBar.removeClass('pending');
                    progressBar.addClass('downloading');
                    progressBar.attr('aria-valuenow', data.s);
                    progressBar.attr('aria-valuemax', data.t);
                    var percent = 100 * data.s / data.t;
                    progressBar.css('width', percent + '%');
                    progressBar.find('span').html('&nbsp;' + data.h);
                    // notify user that the download starts
                    addNotification(progressBar.attr('file') + ' just starts.', 'alert-info');
                } else if (data == '-1') {
                    // if it's equal to -1 it means file is present in download dir
                    var file = progressBar.attr('file');
                    var tr = progressBar.closest('tr');
                    tr.addClass('success');
                    tr.find('td:first-child').prepend('<span class="glyphicon glyphicon-ok"></span>&nbsp;');
                    tr.find('td:last-child').empty();
                    tr.find('td:last-child').append('<button type="button" class="btn btn-small btn-success disabled"><span class="glyphicon glyphicon-save">&nbsp;Download</span></button>');
                    // notify user that the download is complete
                    addNotification(file + ' just complete.', 'alert-success');
                }
            }
        });
    });
}

function initSettings() {
    var inputEnableMailing = $('#inputMailingEnableMailing');
    initMailingForm(inputEnableMailing.is(':checked'));
    // Add listener on mail checkbox
    inputEnableMailing.click(function () {
        initMailingForm($(this).is(':checked'));
    });
}

function initMailingForm(enabled) {
    if (!enabled) {
        $(':input', '#mailing').not(':button, :submit, :reset, :hidden, #inputMailingEnableMailing')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected')
            .attr('disabled', 'disabled')
    } else {
        $(':input', '#mailing').not(':button, :submit, :reset, :hidden, #inputMailingEnableMailing')
            .removeAttr('disabled')
    }
}

function addNotification(text, classAlert) {
    var div = $('<div/>', {
        class: 'alert ' + classAlert,
        text: text,
        style: 'display: none;'
    });
    var close = $('<span/>', {
        class: 'glyphicon glyphicon-remove'
    });
    div.append(close);
    close.click(function () {
        div.slideUp('slow', function () {
            div.remove();
        });
    });
    $('#notifications').append(div);
    div.slideDown('slow');
}