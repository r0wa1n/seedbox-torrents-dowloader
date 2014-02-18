{extends file='layout.tpl'}
{block name=content}
    <div id="notifications"></div>
    <table class="table table-striped">
        <tr>
            <th>Torrent name</th>
            <th style="width: 100px;"></th>
        </tr>
        {foreach from=$torrents item=torrent}
            <tr {if {$torrent.downloaded}}class="success"{/if}>
                <td style="word-break: break-all; line-height: 34px;">
                    {if {$torrent.downloaded}}<span class="glyphicon glyphicon-ok"></span>&nbsp;{/if}
                    {$torrent.name}
                    <span class="italic">({convert_octet_to_human_readable_size size={$torrent.size}})</span>
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
    </table>
{/block}