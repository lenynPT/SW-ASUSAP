<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-graduation-cap zmdi-hc-fw"></i> Administration <small>Section</small></h1>
	</div>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<ul class="nav nav-tabs" style="margin-bottom: 15px;">
			  	<li class="active"><a href="#new" data-toggle="tab">New</a></li>
			  	<li><a href="#list" data-toggle="tab">List</a></li>
			</ul>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade active in" id="new">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-md-10 col-md-offset-1">
							    <form action="">
							    	<div class="form-group label-floating">
									  <label class="control-label">Name</label>
									  <input class="form-control" type="text">
									</div>
									<div class="form-group label-floating">
									  <label class="control-label">Section</label>
									  <input class="form-control" type="text">
									</div>
									<div class="form-group">
								      <label class="control-label">Status</label>
								        <select class="form-control">
								          <option>Active</option>
								          <option>Disable</option>
								        </select>
								    </div>
									<div class="form-group">
								      <label class="control-label">Year</label>
								        <select class="form-control">
								          <option>2017</option>
								          <option>2016</option>
								        </select>
								    </div>
								    <p class="text-center">
								    	<button href="#!" class="btn btn-info btn-raised btn-sm"><i class="zmdi zmdi-floppy"></i> Save</button>
								    </p>
							    </form>
							</div>
						</div>
					</div>
				</div>
			  	<div class="tab-pane fade" id="list">
					<div class="table-responsive">
						<table class="table table-hover text-center">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Name</th>
									<th class="text-center">Section</th>
									<th class="text-center">Status</th>
									<th class="text-center">Year</th>
									<th class="text-center">Update</th>
									<th class="text-center">Delete</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>First</td>
									<td>A</td>
									<td>Active</td>
									<td>2017</td>
									<td><a href="#!" class="btn btn-success btn-raised btn-xs"><i class="zmdi zmdi-refresh"></i></a></td>
									<td><a href="#!" class="btn btn-danger btn-raised btn-xs"><i class="zmdi zmdi-delete"></i></a></td>
								</tr>
								<tr>
									<td>2</td>
									<td>First</td>
									<td>B</td>
									<td>Active</td>
									<td>2017</td>
									<td><a href="#!" class="btn btn-success btn-raised btn-xs"><i class="zmdi zmdi-refresh"></i></a></td>
									<td><a href="#!" class="btn btn-danger btn-raised btn-xs"><i class="zmdi zmdi-delete"></i></a></td>
								</tr>
								<tr>
									<td>3</td>
									<td>Third</td>
									<td>A</td>
									<td>Active</td>
									<td>2017</td>
									<td><a href="#!" class="btn btn-success btn-raised btn-xs"><i class="zmdi zmdi-refresh"></i></a></td>
									<td><a href="#!" class="btn btn-danger btn-raised btn-xs"><i class="zmdi zmdi-delete"></i></a></td>
								</tr>
							</tbody>
						</table>
						<ul class="pagination pagination-sm">
						  	<li class="disabled"><a href="#!">«</a></li>
						  	<li class="active"><a href="#!">1</a></li>
						  	<li><a href="#!">2</a></li>
						  	<li><a href="#!">3</a></li>
						  	<li><a href="#!">4</a></li>
						  	<li><a href="#!">5</a></li>
						  	<li><a href="#!">»</a></li>
						</ul>
					</div>
			  	</div>
			</div>
		</div>
	</div>
</div>