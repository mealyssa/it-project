  @extends('layouts.master')
  @section('contents')
  	<div class="container">
  		<!-- <div class="row">
  			<div class="col-md-4">
  			sdfdsfdf
  			</div> -->
  		</div>
  		<div class="row">
  			<div class="col-md-12 success">
  				<div class="col-md-6 col-md-offset-3">
  					<div class="panel panel-default">
  						<div class="panel-heading">
  						Note:
  						</div>
  						<div class="panel-body">
  							You can create your own group or organization and invite other Extrack users.
  							<button type="button" class="btn btn-info" data-toggle="modal" data-target="#orgModal">
  							<span class="glyphicon glyphicon-plus" aria-hidden="true" ></span> Star
  							</button>
  						</div>
  					</div>
  				</div>
  			</div>
  		</div>
  	</div>
  <div class="modal fade" id="orgModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content" id="orgContent">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Create Group Here !!!</h4>
        </div>
      <div class="modal-body row">
		  <div class="col-md-12">
				<form class="form-inline">
		  		<label for="name">Organization Name :</label>
		  		<div class="form-group">
		  		<input type="text" class="form-control" id="inputsOrg">
		  		</div>
		  		<br>
		  		<br>
		  		<label for="comment">Organization Desc   :</label>
		  		&nbsp;

		  		<div class="form-group">
		  			<textarea class="form-control" rows="5" cols="26" id="comment"></textarea>
		  		</div>
		  		</form>
		  	</div>
	
		</div>
        <div class="modal-footer">
        <button type="button" class="btn btn-primary">Create</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

  @stop

