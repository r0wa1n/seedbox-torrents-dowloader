index.tpl{extends file='layout.tpl'}
{block name=content}
    <table id="logs-list" class="table table-striped">
        <thead>
            <tr>
                <th>Description</th>
                <th style="width: 100px;"></th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$files item=file}
                <tr>
                    <td>{$file.name}</td>
                    <td><a class="btn btn-small btn-success" href="log.php?file={$file.encodedName}" style="color: white;"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Details</a></td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{/block}