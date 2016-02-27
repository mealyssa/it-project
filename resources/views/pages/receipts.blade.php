@extends('layouts.master')

@section('contents')
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-1">
					<a href="/psite">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
					</a>
					<span>PSITE</span>

				</div>
				<div class="col-md-1">
					<a href="/cicct_days">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
					</a>
					<span>cicct_days</span>
				</div>
				<div class="col-md-1">
					<a href="/graduation">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
					</a>
					<span>graduation</span>
				</div>
				
				<div class="col-md-1">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
				</div>
				<div class="col-md-1">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
				</div>
				<div class="col-md-1">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
				</div>
				<div class="col-md-1">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
				</div>	
				<div class="col-md-1">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
				</div>
				<div class="col-md-1">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
				</div>
				<div class="col-md-1">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
				</div>
				<div class="col-md-1">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
				</div>
				<div class="col-md-1">
					 <span class="glyphicon glyphicon-folder-open" id="folders"></span>
				</div>
			</div>
		</div>
	</div>

<script>
    $(document).ready(function(){
        $('.container > ul').find('#receiptsTab').addClass('active');
        
    });
</script>
@stop