  @extends('layouts.master')

  @section('contents')

  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            Add Activity
          </div>
          <div class="panel-body">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-tasks"></span></span>
                <input type="text" class="form-control"  placeholder="Activity Name" aria-describedby="basic-addon1">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon" id="budget"><span class="glyphicon glyphicon-rub"></span></span>
                <input type="text" class="form-control"  placeholder="Activity Budget" aria-describedby="basic-addon1">
              </div>
            </div>
          </br>
        </br>
        <div id='startdatediv'>
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon" id="start_date"><span class="glyphicon glyphicon-calendar"></span></span>
              <input type="text" class="form-control" name='startdate' id='startdate' placeholder="Start Date" aria-describedby="basic-addon1">
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon" id="end_date"><span class="glyphicon glyphicon-calendar"></span></span>
              <input type="text" class="form-control" name='enddate' id='enddate' placeholder="End Date" aria-describedby="basic-addon1">
            </div>
          </div>

        </div> 
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="text-center">
            <button class="btn btn-primary" id="add_actBtn">Submit Activity</button>
          </div>
        </div>
      </div> 
      <br>
    
    </div>
  </div>
    <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="form-group" style="width:200px;margin:0 autol">
            Select Event/Activity: </br></br>
            <select class="form-control" id="sel1">
              <option>Ringhop</option>
              <option>Graduation Day</option>
              <option>Cicct Days</option>
              <option>Seminar</option>
            </select>
          </div>
          <div class="col-md-6">
            </br>
            <p style="text-align:center">Capture Receipts</p>
            <input type="file" id="fileUpload" name="fileUpload"><br>
            <label for="fileUpload">
              <img class="img-responsive" id="cam" src='assets/images/cam.png'/>
            </label>
            </br></br>
             <p class="text-center" id="fds"></p>
            <div class="col-md-12">
                <p class="text-info">Opens device camera to CAPTURE or a scanner to SCAN receipts.</p>
            </div>
          </div>

          <div class="col-md-6">
            </br>
            <p style="text-align:center">Upload Receipt Image</p>
            <input type="file" id="fileUpload" name="fileUpload"><br>
            <label for="fileUpload">
            <img id="cam" src='assets/images/upload.png'/>
          </label>
            </br>
            </br>
            
            <div class="inputName"><p class="text-center" id="filename"></p></div>
            <div class="col-md-12">
              <p class="text-info">Upload image receipts from the local device storage.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){
        $('.container > ul').find('#homeTab').addClass('active');
    });

    $('input[name="fileUpload"]').change(function(){
       var filename = $(this).val();
       $('#filename').html(filename);
       $('#filename').append(" <button  onclick='enableInput()'   id='edit' type='button' class='btn btn-warning'><span class='glyphicon glyphicon-pencil'></button>");
         $('#filename').append("<button  onclick='alert(1)'   id='upload' type='button' class='btn btn-primary'><span class='glyphicon glyphicon-upload'></button>");

    });
    
    function enableInput(){
      var p = $('#filename').text();     
      $('.inputName').append("<input type='text' class='form-control' id='inputBoxFilename' value="+p+"> ");
      $('#filename').empty();
       $('#inputBoxFilename').keypress(function(e){
      var key = e.which;
      if(key == 13){
          var name = $('#inputBoxFilename').val();
          $('#filename').html(name);
           $('#filename').append(" <button  onclick='enableInput()'   id='edit' type='button' class='btn btn-warning'><span class='glyphicon glyphicon-pencil'></button>");
         $('#filename').append("<button  onclick='alert(1)'   id='upload' type='button' class='btn btn-primary'><span class='glyphicon glyphicon-upload'></button>");
          $('#inputBoxFilename').remove();

      }
    });

    }
 
   
    
</script>

@stop