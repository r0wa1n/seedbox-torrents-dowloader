$(document).ready(function () {
    // Link download button
    $('.download').click(function () {
        var tdLine = $(this);
        $.ajax({
            type: 'POST',
            url: 'download.php',
            data: { file: tdLine.attr('file') },
            success: function(data) {
                // Change button to pending button
                tdLine.parent().html('<div class="progress progress-striped active"><div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="glyphicon glyphicon-import">&nbsp;Pending...</span></div></div>');
            },
            error: function() {
                alert('Error when downloading file...');
            }
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
                var percent = 100 * data.s / parseInt(progressBar.attr('aria-valuemax'));
                progressBar.css('width', percent + '%');
                progressBar.find('span').html('&nbsp;' + data.h);
            }
        });
    });
}