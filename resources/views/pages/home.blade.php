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
       <!--  <div class="form-group" style="width:200px;margin:0 autol"> -->
       <!--  Select Event/Activity: </br></br>
        <select class="form-control" id="sel1">
        <option>Ringhop</option>
        <option>Graduation Day</option>
        <option>Cicct Days</option>
        <option>Seminar</option>
        </select> -->
      <!--   </div> -->
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
            {!!Form::file('fileUpload',array('id'=>'fileUpload','name'=>'fileUpload'))!!}
            {{-- <input type="file" id="fileUpload" name="fileUpload"><br> --}}
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
      {!!Form::open(array('url'=>'home/uploadReceipts','method'=>'POST'))!!}
      <div class="modal fade" tabindex="-1" id="uploadModal" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- <div class="modal-header">
          
            </div>  -->

            <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-sm-7" style="border-style: dotted;border-color: #B5ADAD;background-color: rgb(248, 248, 248)">
                    <div id="ari">

                    </div>
                  <div class="form-horizontal" style="width:400px;margin:0 auto">
                    </br></br>
                      <label>Select Event/Activity: </label></br></br>
                      <select class="form-control" id="activitySelect" style="width:300px">
                      <option>Ringhop</option>
                      <option>Graduation Day</option>
                      <option>Cicct Days</option>
                      <option>Seminar</option>
                      </select>
                  </div>
                   </br></br>
                    </div>
                  </div>
                </div>
             </div> 
            <div class="modal-footer">
              <button class="btn btn-primary" id="upload">Upload</button>
              <button class="btn btn-warning" data-dismiss="modal">Cancel</button>
            </div> <!-- /.modal-footer -->
            {!!Form::close()!!}
          </div>
        </div>
     

<script>
    $(document).ready(function(){
        $('.container > ul').find('#homeTab').addClass('active');
    });
    $("#fileUpload").change(function(e){
      tmppath = URL.createObjectURL(event.target.files[0]);
      $("#ari").append("<img id='previewImage' style='margin:0 auto' src='"+tmppath+"'/>");
      $("#uploadModal").modal('show');

    });
    $("#upload").click(function(){

        $.ajax({
      url   : "home/uploadReceipts", 
      type  : "POST",
     
      data  : tmppath
    });
    });

  

</script>

@stop