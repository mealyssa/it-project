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
          <div class="col-md-6">
            </br>
            <p style="text-align:center">Capture Receipts</p>
            <!-- <input type="file" id="fileUpload" name="fileUpload"><br> -->
            <label for="fileUpload">
            <img class="img-responsive" id="" src='assets/images/cam.png'/>
            </label>
            </br></br>
            <p class="text-center" id="fds"></p>
            <div class="col-md-12">
            <p class="text-info">Opens device camera to CAPTURE or a scanner to SCAN receipts.</p>
            </div>
          </div>

          <div class="col-md-6">
          </br>

            {!!Form::open(array('url'=>'home/uploadReceipts', 'method'=>'POST','enctype'=>"multipart/form-data"))!!} 
            <p style="text-align:center">Upload Receipt Image</p>
            {!!Form::file('fileUpload',array('id'=>'fileUpload','name'=>'fileUpload'))!!}
            <label for="fileUpload">
            <img id="cam" src='assets/images/upload.png'/>
            </label>
            </br>
            </br>

            <div class="modal fade" tabindex="-1" id="uploadModal" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" id="contentUpload">
            <!-- <div class="modal-header">
            </div>  -->

            <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-sm-7" style="border-style: dotted;border-color: #B5ADAD;background-color: rgb(248, 248, 248)">
                    <div id="previewImageDiv">

                    </div>
                   
                  <div class="form-horizontal">
                    </br></br>
                   {!!Form::select('activities',$activities)!!}
                   </br></br>
                  <input type="text" class="form-control" name = "filenameTxtbox" id="filenameTxtbox" placeholder="Ex. Mcdo Receipts" />
                    <p class="text-center">Receipt Name : <span class="glyphicon glyphicon-pencil"></span></p>
                  </div>
                   </br></br>
                    </div>
                  </div>
                </div>
             </div> 
            <div class="modal-footer">
              <button class="btn btn-primary" type="submit" id="modal_upload" disabled="disable">Upload</button>
              <button class="btn btn-warning" data-dismiss="modal">Cancel</button>
            </div> <!-- /.modal-footer -->
        
          </div>
        </div>
           
            {!!Form::close()!!}
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

@if(session()->has('empty_act'))
  <script>    

    $(document).ready(function(){
      $.ambiance({message: "{{ Session::get('empty_act') }}", width: 500, timeout: 100000,type:"emptyNotification"});
      $('#selectedDefault').html("PLease select first the activity where the expense belong");

    });       
  </script>
@endif  
      
     

<script>

  

    $(document).ready(function(){

        $('[name=activities]').change(function(){
          changeButton();
       });

        $('#filenameTxtbox').bind("keypress keydown",function(e){
          var filename;
          if (e.which == 8) {
            e.preventDefault();
            filename = $(this).val();
            str = filename.substring(0, filename.length - 1);
            $(this).val(str);
          }

          if (e.which == 32){
            e.preventDefault();
          }
          

          changeButton();
        });

    function changeButton(){

        setTimeout(function(){
          var event = $('[name=activities]').val();
          var file = '';
          file = $('#filenameTxtbox').val();
          if(event != '' && file != '') {
            $("#modal_upload").removeAttr("disabled");
          }
          else{
            $("#modal_upload").attr("disabled","disable");
          }
        },100);

      }

    });  
    $(document).ready(function(){
        $('.container > ul').find('#homeTab').addClass('active');
    });

    $("#fileUpload").change(function(e){
      readURL(this);
    });
    $("#cam").click(function(e){
     $("#fileUpload").val('');
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
              $("#previewImageDiv").html("<img  class='img-responsive' id='previewImage' style='width:auto; background-position: center;background-size: cover;'/>");
              $('#previewImage').attr('src', e.target.result);
              $("#uploadModal").modal('show');
              
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

   

</script>


@stop