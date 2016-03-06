<!DOCTYPE html>
<html>
	<head>
		<title>Expense Tracking</title>
		{!! Html::script('/assets/js/jquery-1.10.2.js') !!}
		{!! Html::script('/assets/js/jquery-ui.js') !!}
		{!! Html::style('/assets/css/bootstrap.min.css') !!}
		{!! Html::style('/assets/css/layout.css') !!}
		{!! Html::style('/assets/css/datepicker.css') !!}
		{!! Html::style('/assets/css/jquery-ui.css') !!}
		{!! Html::script('/assets/js/bootstrap.min.js') !!}
		{!! Html::script('/assets/js/jquery.mobile-1.2.0.min.js') !!}
		{!! Html::script('/assets/js/fullcalendar.min.js') !!}
		{!! Html::style('/assets/css/fullcalendar.css') !!}
		{!! Html::style('/assets/css/metro.css') !!}
		{!! Html::style('/assets/css/metro-icons.css') !!}
       	{!! Html::style('/assets/css/jquery.ambiance.css') !!}
        
        {!! Html::script('/assets/js/angular.min.js') !!}
        {!! Html::script('/assets/js/angular-route.min.js') !!}
        {!! Html::script('/assets/js/jquery.ambiance.js') !!}
       
	</head>
    <body>
    
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Logo Here</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li><p class="navbar-text">Signed in as {!! Auth::user()->username !!}</p></li>
					<li class="dropdown">
						<a href="/logout" ><span class="glyphicon glyphicon-off" id="logout"></a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		<ul class="nav nav-tabs">
			<li id="homeTab">
				<a href='/home'>Home</a>
			</li>
			<li id="aboutTab">
				<a href='/about'>About Us</a>
			</li>
			<li id="expenseTab">
				<a href='/expenses'>Expense</a>
			</li>
			<li id="liquidationTab">
				<a href='/liquidation'>Liquidation</a>
			</li>
			<li id="receiptsTab">
				<a href='/receipts'>Receipts</a>
			</li>
			<li id="calendarTab">
				<a href='/calendar'>Calendar Events</a>
			</li>
            <li id="OrganizationTab">
				<a href='/organization'>Organization</a>
			</li>

		</ul>
	</div>
	</br>
	</br>
	<div id="wrapper">
	@yield('contents')
</div>

	<div class="footer">
		<div class="copyright">
			<p id="footerp">
				Copyright &copy; 2015 All rights reserved | Created by<a href="http://w3layouts.com">  Charles Angels</a>
			</p>
		</div>
	</div>

</body>
</html>


<script>
$(function() {
	$( "#startdate,#enddate" ).datepicker();
});
</script>

