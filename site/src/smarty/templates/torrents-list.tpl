{foreach from=$torrents item=torrent}
    <tr {if {$torrent.status eq 'DOWNLOADED'}}class="success"{/if} level="{$level}" parent="{$parent}" file="{if isset($parent)}{$parent}/{/if}{$torrent.encodedName}">
        <td style="word-break: break-all; line-height: 34px;" {if {$torrent.isDirectory}}class="directory"{/if}>
            {for $var=1 to $level}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/for}
            {if {$torrent.isDirectory}}<span class="glyphicon glyphicon-folder-close"></span>{else}<span class="glyphicon glyphicon-file"></span>{/if}&nbsp;
            {$torrent.name}
        </td>
        <td>
            <span class="italic">{convert_octet_to_human_readable_size size={$torrent.size}}</span>
            <input type="hidden" value="{$torrent.size}">
        </td>
        <td>
            {if {$torrent.status} eq 'DOWNLOADING'}
                <div class="progress progress-striped active">
                    <div class="progress-bar downloading" role="progressbar" aria-valuenow="{$torrent.detailsStatus.currentSize}" aria-valuemin="0" aria-valuemax="{$torrent.size}" style="width: {$torrent.detailsStatus.currentPercent}%"><span class="glyphicon glyphicon-transfer">&nbsp;{convert_octet_to_human_readable_size size={$torrent.detailsStatus.currentSize}}</span></div>
                </div>
            {elseif {$torrent.status} eq 'PENDING'}
                <div class="progress progress-striped active">
                    <div class="progress-bar pending" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="glyphicon glyphicon-transfer">&nbsp;Pending...</span></div>
                </div>
            {elseif {$torrent.status} eq 'DOWNLOADED'}
                <button type="button" class="btn btn-small btn-success disabled"><span class="glyphicon glyphicon-save">&nbsp;Download</span></button>
            {else}
                <button type="button" class="download btn btn-small btn-success hidden-xs"><span class="glyphicon glyphicon-save">&nbsp;Download</span></button>
                <button type="button" class="download btn btn-small btn-success btn-xs visible-xs"><span class="glyphicon glyphicon-save"></span></button>
            {/if}
        </td>
        <td style="line-height: 34px;text-align: center;">
            <button type="button" class="delete btn btn-small btn-danger hidden-xs"><span class="glyphicon glyphicon-remove"></span></button>
            <button type="button" class="delete btn btn-small btn-danger btn-xs visible-xs"><span class="glyphicon glyphicon-remove"></span></button>
        </td>
    </tr>
{/foreach}