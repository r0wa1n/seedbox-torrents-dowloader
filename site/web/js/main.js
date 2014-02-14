$(document).ready(function () {
    // Link download buttons
    $('.download').click(function () {
        var tdLine = $(this);
        $.ajax({
            type: 'POST',
            url: 'download.php',
            data: { file: tdLine.attr('file') },
            success: function(data) {
                // Change button to pending button
                tdLine.parent().html('<div class="progress progress-striped active"><div class="progress-bar pending" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%" file="' + tdLine.attr('file') + '"><span class="glyphicon glyphicon-import">&nbsp;Pending...</span></div></div>');
            },
            error: function() {
                alert('Error when downloading file...');
            }
        });
    });
    // Link update button
    $('#update').click(function() {
        // display loading screen
        $('div#loading').show();
        $('div#loading').animate({opacity: '0.6'}, 500, function() {
            $.ajax({
                type: 'GET',
                url: 'update.php',
                success: function() {
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
    $('.downloading').each(function() {
        var progressBar = $(this);
        $.ajax({
            type: 'GET',
            url: 'size.php',
            data: { file: progressBar.attr('file') },
            success: function(data) {
                data = JSON.parse(data);
                progressBar.attr('aria-valuenow', data.s);
                var percent = 100 * data.s / data.t;
                progressBar.css('width', percent + '%');
                progressBar.find('span').html('&nbsp;' + data.h);
                // TODO verify if file is not compeleted, if it's the case, change CSS
            }
        });
    });
    // Check all pending files
    $('.pending').each(function() {
        var progressBar = $(this);
        $.ajax({
            type: 'GET',
            url: 'size.php',
            data: { file: progressBar.attr('file') },
            success: function(data) {
                if(data != '') {
                    data = JSON.parse(data);
                    progressBar.removeClass('pending');
                    progressBar.addClass('downloading');
                    progressBar.attr('aria-valuenow', data.s);
                    progressBar.attr('aria-valuemax', data.t);
                    var percent = 100 * data.s / data.t;
                    progressBar.css('width', percent + '%');
                    progressBar.find('span').html('&nbsp;' + data.h);
                    // TODO verify if file is not compeleted, if it's the case, change CSS
                }
            }
        });
    });
}