{extends file='layout.tpl'}
{block name=content}
    <div class="panel panel-default">
        <div class="panel-body">
            <span class="glyphicon glyphicon-search">&nbsp;{$title}</span>
        </div>
    </div>
    <pre>{$logDetails}</pre>
{/block}