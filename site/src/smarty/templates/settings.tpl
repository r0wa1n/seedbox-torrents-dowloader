{extends file='layout.tpl'}
{block name=content}
    <div class="content">
        <div id="seedbox" class="panel {if isset($seedbox)}panel-success{else}panel-danger{/if}">
            <div class="panel-heading">
                <h3 class="panel-title">Seedbox</h3>
            </div>
            <div class="panel-body">
                {if $errorSeedbox}
                    <div class="bs-callout bs-callout-danger">
                        <h4>Error</h4>
                        <p>Some fields are invalids.</p>
                    </div>
                {elseif $successSeedbox}
                    <div class="bs-callout bs-callout-info">
                        <h4>Success</h4>
                        <p>Your seedbox information have been saved.</p>
                    </div>
                {/if}
                <form class="form-horizontal" role="form" action="updateSeedbox.php" method="post">
                    <div class="form-group">
                        <label for="inputSeedboxHost" class="col-sm-2 control-label">Host</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputSeedboxHost" name="inputSeedboxHost"
                                   {if isset($seedbox)}value="{$seedbox.host}" {else}placeholder="XXX.seedbox.fr"{/if}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSeedboxPort" class="col-sm-2 control-label">Port</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputSeedboxPort" name="inputSeedboxPort"
                                   {if isset($seedbox)}value="{$seedbox.port}" {else}placeholder="21"{/if}>
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
        <div id="mailing" class="panel {if isset($mailing)}panel-success{else}panel-danger{/if}">
            <div class="panel-heading">
                <h3 class="panel-title">Mailing</h3>
            </div>
            <div class="panel-body">
                {if $errorMailing}
                    <div class="bs-callout bs-callout-danger">
                        <h4>Error</h4>
                        <p>Some fields are invalids.</p>
                    </div>
                {elseif $successMailing}
                    <div class="bs-callout bs-callout-info">
                        <h4>Success</h4>
                        <p>Your mailing information have been saved.</p>
                    </div>
                {/if}
                <form class="form-horizontal" role="form" action="updateMailing.php" method="post">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input id="inputMailingEnableMailing" name="inputMailingEnableMailing" type="checkbox" {if isset($mailing)}checked="checked"{/if}>Enable mailing when download is completed
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputMailingSmtpHost" class="col-sm-2 control-label">Smtp Host</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputMailingSmtpHost" name="inputMailingSmtpHost"
                                   {if isset($mailing)}value="{$mailing.smtpHost}" {else}placeholder="smtp.gmail.com"{/if}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputMailingSmtpPort" class="col-sm-2 control-label">Smtp Port</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputMailingSmtpPort" name="inputMailingSmtpPort"
                                   {if isset($mailing)}value="{$mailing.smtpPort}" {else}placeholder="465"{/if}>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input name="inputMailingSSL" id="inputMailingSSL" type="checkbox" {if isset($mailing) and $mailing.ssl}checked="checked"{/if}>Enable SSL
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputMailingUsername" class="col-sm-2 control-label">Username</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputMailingUsername" name="inputMailingUsername"
                                   {if isset($mailing)}value="{$mailing.username}" {else}placeholder="Username"{/if}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputMailingPassword" class="col-sm-2 control-label">Password</label>

                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="inputMailingPassword" name="inputMailingPassword"
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