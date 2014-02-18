{extends file='layout.tpl'}
{block name=content}
    <div class="content">
        {if $error}
            <div class="bs-callout bs-callout-danger">
                <h4>Error</h4>
                <p>Some fields are invalids.</p>
            </div>
        {elseif $success}
            <div class="bs-callout bs-callout-info">
                <h4>Success</h4>
                <p>Your seedbox information have been saved.</p>
            </div>
        {/if}
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Seedbox</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" role="form" action="updateSeedbox.php" method="post">
                    <div class="form-group">
                        <label for="inputSeedboxHost" class="col-sm-2 control-label">Host</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputSeedboxHost" name="inputSeedboxHost"
                                   {if isset($seedbox)}value="{$seedbox.host}" {else}placeholder="XXX.seedbox.fr"{/if}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSeedboxUsername" class="col-sm-2 control-label">Username</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputSeedboxUsername" name="inputSeedboxUsername"
                                   {if isset($seedbox)}value="{$seedbox.username}" {else}placeholder="Username"{/if}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSeedboxPassword" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="inputSeedboxPassword" name="inputSeedboxPassword"
                                   placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
{/block}