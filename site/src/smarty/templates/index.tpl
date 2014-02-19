{extends file='layout.tpl'}
{block name=content}
    <div id="notifications"></div>
    <table class="table table-striped" id="torrents-list">
        <thead>
            <tr>
                <th>Torrent name</th>
                <th>Size</th>
                <th style="width: 100px;"></th>
            </tr>
        </thead>
        <tbody>
            {include file='torrents-list.tpl' torrents=$torrents}
        </tbody>
    </table>
{/block}