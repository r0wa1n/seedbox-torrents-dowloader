$(document).ready(function () {
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
});

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
                } else if(data == '-1') {
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