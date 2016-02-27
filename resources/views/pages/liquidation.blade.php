@extends('layouts.master')

@section('contents')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<span style="margin-left:1.5%;margin-botoom:10px;">Search event (Optional)</span>   
		</div>
		<br>
		<br>   
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group" style="width:200px;margin:0 auto">
							<div class="form-group">

								<div class="input-group">
									<input type="text" class="form-control"  id="searchEvent" placeholder="Input event name..">
								</div>
							</div>

						</div>
					</div>
				</div> 
			</div>
			<div class="col-md-6">
				<div class="col-md-5">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon" id="start_date"><span class="glyphicon glyphicon-calendar"></span></span>
							<input type="text" class="form-control" name='startdate' id='startdate' placeholder="Start Date" aria-describedby="basic-addon1">
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon" id="start_date"><span class="glyphicon glyphicon-calendar"></span></span>
							<input type="text" class="form-control" id="enddate" placeholder="End Date" aria-describedby="basic-addon1">
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<button class="btn btn-primary">Search</button>
				</div>
			</div>
		</div>
	</div>
	<br>
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">

				<div class="panel-body">
					<h2 align='center'><font color='blue'>Food Expenses</font></h2>
					<table class='table table-hover'>

						<thead>

							<th>OR Number</th>
							<th>Item Name</th>
							<th>Amount</th>
							<th>Merchant Name</th>
							<th>Date</th>
							<th>Place</th>
						</thead>
						<tbody>
							<tr>
								<td>
									Raffles
								</td>
								<td>
									5000
								</td>
								<td>
									December 25,2015
								</td>
								<td>
									Jolibee
								</td>
								<td>
									1001
								</td>
								<td>
									Mambaling Cebu City

								</td>
							</tr>
							<tr>
								<td>
									Mirienda softdrinks
								</td>
								<td>
									1500
								</td>
								<td>
									September 23,2015
								</td>
								<td>
									Marias Store
								</td>
								<td>
									1002
								</td>
								<td>
									Mambaling Cebu City

								</td>
							</tr>
							<tr>
								<td>
									Pancakes
								</td>
								<td>
									700
								</td>
								<td>
									Januray 1,2014
								</td>
								<td>
									Super Metro
								</td>
								<td>
									1003
								</td>
								<td>
									Basak Cebu City

								</td>
							</tr>
							<tr>
								<td>
									Rice 
								</td>
								<td>
									500
								</td>
								<td>
									November 1,2013
								</td>
								<td>
									Super Metro
								</td>
								<td>
									1004
								</td>
								<td>
									San Nicolas Cebu City

								</td>
							</tr><tr>
							<td>
								Limburger
							</td>
							<td>
								24
							</td>
							<td>
								Febuary 25,2015
							</td>
							<td>
								Gaisano Tabunok
							</td>
							<td>
								1001
							</td>
							<td>
								Mambaling Cebu City

							</td>
						</tr><tr>
						<td>
							Snacks
						</td>
						<td>
							5000
						</td>
						<td>
							December 25,2015
						</td>
						<td>
							Jolibee
						</td>
						<td>
							1001
						</td>
						<td>
							Mambaling Cebu City
							
						</td>
					</tr>
				</tr>
			</tbody>
			
		</table>
		<h4 id='total'>Total Amount: 12739</h4>
	</div>
	<div id="transportation">
		<h2 align='center'><font color='blue'>Transportation Expenses</font></h2>
		<table class='table table-hover'>

						<thead>

							<th>OR Number</th>
							<th>Item Name</th>
							<th>Amount</th>
							<th>Merchant Name</th>
							<th>Date</th>
							<th>Place</th>
						</thead>
						<tbody>
							<tr>
								<td>
									Raffles
								</td>
								<td>
									5000
								</td>
								<td>
									December 25,2015
								</td>
								<td>
									Jolibee
								</td>
								<td>
									1001
								</td>
								<td>
									Mambaling Cebu City

								</td>
							</tr>
							<tr>
								<td>
									Mirienda softdrinks
								</td>
								<td>
									1500
								</td>
								<td>
									September 23,2015
								</td>
								<td>
									Marias Store
								</td>
								<td>
									1002
								</td>
								<td>
									Mambaling Cebu City

								</td>
							</tr>
							<tr>
								<td>
									Pancakes
								</td>
								<td>
									700
								</td>
								<td>
									Januray 1,2014
								</td>
								<td>
									Super Metro
								</td>
								<td>
									1003
								</td>
								<td>
									Basak Cebu City

								</td>
							</tr>
							<tr>
								<td>
									Rice 
								</td>
								<td>
									500
								</td>
								<td>
									November 1,2013
								</td>
								<td>
									Super Metro
								</td>
								<td>
									1004
								</td>
								<td>
									San Nicolas Cebu City

								</td>
							</tr><tr>
							<td>
								Limburger
							</td>
							<td>
								24
							</td>
							<td>
								Febuary 25,2015
							</td>
							<td>
								Gaisano Tabunok
							</td>
							<td>
								1001
							</td>
							<td>
								Mambaling Cebu City

							</td>
						</tr><tr>
						<td>
							Snacks
						</td>
						<td>
							5000
						</td>
						<td>
							December 25,2015
						</td>
						<td>
							Jolibee
						</td>
						<td>
							1001
						</td>
						<td>
							Mambaling Cebu City
							
						</td>
					</tr>
				</tr>
			</tbody>
			
		</table>
			<h4 id='total'>Total Amount: 12739</h4>
</div>
<div>

</div>
</div>
</div>
</div>
</div>


<script>
    $(document).ready(function(){
        $('.container > ul').find('#liquidationTab').addClass('active');
        
    });
</script>
@stop