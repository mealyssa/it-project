<link href="http://vjs.zencdn.net/5.7.1/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>

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

{!! Html::script('/assets/js/angular.min.js') !!}
{!! Html::script('/assets/js/angular-route.min.js') !!}
{!! Html::script('/assets/js/app.js') !!}



<body style="background-color:#54B59A" >
    
    @if(session()->has('from_logout'))
        <h4> {{ Session::get('from_logout') }} </h4>
    @endif
    
   

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

          <li>
            <p class="navbar-text"><button id='btn_signin' type="button" class="btn btn-primary" data-toggle="modal" data-target="#signIn"><span class="glyphicon glyphicon-lock"></span> &nbsp;Sign In</button>
            </p>
          </li>
          <li>
            <p class="navbar-text"><button  type="button" class="btn btn-primary" data-toggle="modal" data-target="#signUp"><span class="glyphicon glyphicon-lock"></span> &nbsp;Sign Up</button>
            </p>
          </li>

        </ul>
      </div>
    </div>
    <div class="row" id="banner">
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <div id="tvdiv">
            <video  autobuffer controls autoplay>
              <source id="" src="/assets/video/extrack.mp4" type="video/mp4">
                <source id="mp4" src="/assets/video/extrack.ogv" type="video/mp4">
                </video>
              </div>
            </div>
          </div>
        </div>
      </nav>



      <div class="modal fade" id="signIn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="login">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title" id="myModalLabel">Sign In</h4>
            </div> <!-- /.modal-header -->

            <div class="modal-body">
            {!!Form::open(array('url'=>'auth/login','method'=>'post','role'=>'form' ))!!}
                <div class="col-md-12" style="text-align:center;" >
                 <label id="logmessage"></label>
                </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                  {!!Form::text('username',null,array('required','class'=>'form-control','placeholder'=>'Username','aria-describedby'=>'basic-addon1' ))!!}
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>

                     {!!Form::password('password',array('required','class'=>'form-control','placeholder'=>'Password','aria-describedby'=>'basic-addon1' ))!!}
                </div>
              </div>



              <div class="checkbox">
                <label>
                  <input type="checkbox"> Remember me
                </label>
              </div> <!-- /.checkbox -->


            </div> <!-- /.modal-body -->

            <div class="modal-footer">
              <button class="form-control btn btn-primary">Ok</button>
            </div> <!-- /.modal-footer -->
            {!!Form::close()!!}
          </div>
        </div>
      </div>
      <div class="modal fade" id="signUp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="register">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title" id="myModalLabel">Sign Up</h4>
            </div> <!-- /.modal-header -->

            <div class="modal-body">
              <div class="tab-pane" id="Registration">
                {!!Form::open(array('url'=>'auth/register','method'=>'post','class'=>'form-horizontal','role'=>'form' ))!!}
          
                {{--  <form role="form" class="form-horizontal"> --}}
                <div class="form-group">

                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-md-6">
                       <div class="input-group">

                        <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-tasks"></span></span>
                        {!!Form::text('first_name',null,array('first_name','class' =>'form-control','placeholder'=>'Firstname'))!!}
                        {{--  @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif --}}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-tasks"></span></span>
                        {!!Form::text('last_name',null,array('class'=>'form-control','placeholder'=>'Lastname'))!!}
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <div class="form-group">

                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-md-6">
                     <div class="input-group">
                      <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-user"></span></span>
                      {!!Form::text('username',null,array('class'=>'form-control','placeholder'=>'Username'))!!}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-lock"></span></span>
                      {!!Form::password('password',array('class'=>'form-control','placeholder'=>'Password'))!!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <form role="form" class="form-horizontal">
              <div class="form-group">

                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-md-6">
                     <div class="input-group">
                      <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-taskglyphicon glyphicon-phones"></span></span>
                       {!!Form::text('contact_number',null,array('class'=>'form-control','placeholder'=>'Contact_number'))!!}
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-tasks"></span></span>
                      {!!Form::text('role',null,array('class'=>'form-control','placeholder'=>'Role'))!!}
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="form-group">
              <div class="col-md-12">
               <div class="input-group">
                <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-envelope"></span></span>
                {!!Form::text('email',null,array('class'=>'form-control','placeholder'=>'Email'))!!}
              </div>
            </div>
          </div>


          <div class="row">

          </div>

        </div>

      </div> <!-- /.modal-body -->

      <div class="modal-footer">
        {!! Form::submit('Submit!',array('class'=>'btn btn-primary')) !!}
        {!! Form::submit('Cancel',array('class'=>'btn btn-default','data-dismiss'=>'modal','aria-hidden'=>'true')) !!}
      </div>
      
      {!!Form::close()!!}
    </div>
  </div>
</div>
</div>
</div>

<div class="col-md-12" style="margin-top:28%;height:6%;background-color:#84603C">
</div>
<div class="col-md-12">
  <div class="col-md-4">

  </div>
  <div class="col-md-4"style="text-align:center;font-weight:bold">
   <h1 style="color:#fff"> Extrack</h1>
   <h2>(Expense Tracking)</h2>
   <p style="color:#0F375B">Capture Expense.Whenever.Wherever</p>
 </div>
</div>
</body> 

<script>
$(document).ready(function(){

  var chrome = !!window.chrome;
  var isChrome = /*@cc_on!@*/false;

  if( isChrome ) {
    video autobuffer controls autoplay>
    <source id="mp4" src="/assets/video/extrack.mp4" type="video/mp4">
    </video>
    $("#tvdiv").replaceWith($('<video autobuffer controls autoplay><source src="/assets/video/extrack.webm" type="video/webm"></video>'));
  }

});
</script>

 @if(session()->has('error'))
        <script>
            
            $('#btn_signin').click();
            $("#logmessage").html('{{ Session::get('error') }}');
            $("#logmessage").addClass("alert alert-danger");
            setTimeout(function(){
                $("#logmessage").empty();
                $("#logmessage").removeClass("alert alert-danger");
            },3000)
            
            
          
        </script>
@endif




