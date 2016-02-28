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
 {!! Html::style('/assets/css/jquery.ambiance.css') !!}

{!! Html::script('/assets/js/angular.min.js') !!}
{!! Html::script('/assets/js/angular-route.min.js') !!}
{!! Html::script('/assets/js/app.js') !!}
{!! Html::script('/assets/js/jquery.ambiance.js') !!}



<body style="background-color:#54B59A" >
  
   
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
            <p class="navbar-text"><button id='btn_signup' type="button" class="btn btn-primary" data-toggle="modal" data-target="#signUp"><span class="glyphicon glyphicon-lock"></span> &nbsp;Sign Up</button>
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
     <div id="successRegister" style="baclground-color:red"></div>



      <div class="modal fade" id="signIn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="login">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title" id="myModalLabel">Sign In</h4>
            </div> <!-- /.modal-header -->

            <div class="modal-body">
            {!!Form::open(array('url'=>'auth/signIn','method'=>'post','role'=>'form' ))!!}
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
                            <label> Firstname :</label>
                       <div class="input-group">

                        <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-tasks"></span></span>
                        {!!Form::text('first_name',null,array('first_name','class' =>'form-control'))!!}  
                      </div>
                           @if($errors->has('first_name'))
                           <p class="text-danger">{{$errors->first('first_name')}}</p>
                           @endif
                    </div>
                    <div class="col-md-6">
                         <label>Lastname :</label>
                      <div class="input-group">
                        <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-tasks"></span></span>
                        {!!Form::text('last_name',null,array('class'=>'form-control'))!!}
                      </div>
                         @if($errors->has('last_name'))
                           <p class="text-danger">{{$errors->first('last_name')}}</p>
                           @endif
                    </div>
                  </div>
                </div>

              </div>
              <div class="form-group">

                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-md-6">
                         <label>Username :</label>
                     <div class="input-group">
                      <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-user"></span></span>
                      {!!Form::text('username',null,array('class'=>'form-control'))!!}
                    </div>
                         @if($errors->has('username'))
                           <p class="text-danger">{{$errors->first('username')}}</p>
                           @endif
                  </div>
                      
                  <div class="col-md-6">
                   <label>Password :</label>
                    <div class="input-group">
                      <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-lock"></span></span>
                      {!!Form::password('password',array('class'=>'form-control'))!!}
                    </div>
                       @if($errors->has('password'))
                           <p class="text-danger">{{$errors->first('password')}}</p>
                           @endif
                  </div>
                      
                </div>
              </div>
            </div>
            <form role="form" class="form-horizontal">
              <div class="form-group">

                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-md-6">
                          <label> Contact Number :</label>
                     <div class="input-group">
                      <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-phone"></span></span>
                       {!!Form::text('contact_number',null,array('class'=>'form-control'))!!}
                    </div>
                         @if($errors->has('contact_number'))
                           <p class="text-danger">{{$errors->first('contact_number')}}</p>
                           @endif
                  </div>
                      
                       <div class="col-md-6">
                           <label>Email :</label>
                     <div class="input-group">
                      <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-envelope"></span></span>
                       {!!Form::text('email',null,array('class'=>'form-control'))!!}
                    </div>
                           @if($errors->has('email'))
                           <p class="text-danger">{{$errors->first('email')}}</p>
                           @endif
                  </div>
                      
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
                   $("#logmessage").fadeOut("slow");
//                $("#logmessage").empty();
//                $("#logmessage").removeClass("alert alert-danger");
              
            },2000)

        </script>
@endif
@if(session()->has('registered'))
    <script>
        $(document).ready(function(){   
            $.ambiance({message: "{{ Session::get('registered') }}", width: 500, timeout: 2});
        }); 
    </script>
@endif

@if(session()->has('errors'))
    <script>$('#btn_signup').click();</script>
@endif

  
@if(session()->has('from_logout'))
<!--    <h4> {{ Session::get('from_logout') }} </h4>-->
<script>     
        $(document).ready(function(){
            $.ambiance({message: "{{ Session::get('from_logout') }}", width: 500, timeout: 2});
        });       
    </script>
@endif

<style>
.ambiance-default {
  background: #dff0d8;
  color: #008000;
  padding: 10px;
  margin-right: 100px;
  font-weight: bold;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>






