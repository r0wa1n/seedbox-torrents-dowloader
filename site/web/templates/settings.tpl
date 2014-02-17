{extends file='layout.tpl'}
{block name=content}
    <div class="content">
		<div class="panel panel-info">
	      <div class="panel-heading">
	        <h3 class="panel-title">Seedbox</h3>
	      </div>
	      <div class="panel-body">
	        <form class="form-horizontal" role="form">
			  <div class="form-group">
			    <label for="inputSeedboxHost" class="col-sm-2 control-label">Host</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="inputSeedboxHost" placeholder="XXX.seedbox.fr">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputSeedboxUsername" class="col-sm-2 control-label">Username</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="inputSeedboxUsername" placeholder="Username">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputSeedboxPassword" class="col-sm-2 control-label">Password</label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" id="inputSeedboxPassword" placeholder="Password">
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