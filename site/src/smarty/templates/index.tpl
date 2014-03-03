{extends file='layout.tpl'}
{block name=content}
    <table class="table table-striped" id="torrents-list">
        <thead>
            <tr>
                <th style="width: 90%;">File</th>
                <th style="width: 10%;">Size</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {include file='torrents-list.tpl' torrents=$torrents}
        </tbody>
    </table>

    <div id="delete-popup" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Delete file on your Seedbox</h4>
                </div>
                <div class="modal-body" id="delete-popup-content"></div>
                <div class="modal-footer">
                    <input type="hidden" id="delete-file-input"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="delete-button">Delete anyway</button>
                </div>
            </div>
        </div>
    </div>
{/block}