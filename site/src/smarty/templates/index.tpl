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
            {foreach from=$torrents item=torrent}
                <tr {if {$torrent.downloaded}}class="success"{/if}>
                    <td style="word-break: break-all; line-height: 34px;">
                        {if {$torrent.downloaded}}<span class="glyphicon glyphicon-ok"></span>&nbsp;{/if}
                        {if {$torrent.isDirectory}}<span class="glyphicon glyphicon-folder-open"></span>{else}<span class="glyphicon glyphicon-file"></span>{/if}&nbsp;
                        {$torrent.name}
                    </td>
                    <td>
                        <span class="italic">{convert_octet_to_human_readable_size size={$torrent.size}}</span>
                        <input type="hidden" value="{$torrent.size}">
                    </td>
                    <td>
                        {if {$torrent.downloaded}}
                            <button type="button" class="btn btn-small btn-success disabled"><span class="glyphicon glyphicon-save">&nbsp;Download</span></button>
                        {elseif {$torrent.downloading.status}}
                            <div class="progress progress-striped active">
                                <div class="progress-bar downloading" role="progressbar" aria-valuenow="{$torrent.downloading.currentSize}" aria-valuemin="0" aria-valuemax="{$torrent.size}" style="width: {$torrent.downloading.currentPercent}%" file="{$torrent.encodedName}"><span class="glyphicon glyphicon-transfer">&nbsp;' . {$torrent.downloading.currentSize} . '</span></div>
                            </div>
                        {else}
                            <button type="button" class="download btn btn-small btn-success" file="{$torrent.encodedName}"><span class="glyphicon glyphicon-save">&nbsp;Download</span></button>
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{/block}