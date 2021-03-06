


@extends('layouts.master')

@section('contents')

@if(session()->has('session_ImageName'))
<?php $arrayData = Session::get('arrayData'); ?>
   <div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading" data-toggle="collapse" data-target="#content" style="height: 35px">
            Click to show expenses
            </div>
            <div class="panel-body">
            <div class="collapse" id="content">
                <div class="col-md-12">
                <span style="margin-left:1.5%;margin-botoom:10px;">Select expense category:</span>    
            </div>
            <br>
            <br>  
            <div class="row">
            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" style="width:200px;margin:0 auto">
                                <select class="form-control" id="sel1">
                                    <option>All</option>
                                    <option>Food</option>
                                    <option>Transportation</option>
                                    <option>Utilities</option>
                                    <option>Others</option>
                                    <option>--Uncategorized--</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 text-left">   
                            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#addCatModal">
                                <i class="glyphicon glyphicon-plus"></i> Category
                            </button>

                            <div class="modal fade" id="addCatModal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Category of Expense</h4>
                                        </div>
                                        <div class="modal-body">    
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="newCat"  placeholder="Input new category">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal">Add</button>
                                            <button class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Event.." aria-describedby="basic-addon1">
                        <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-search"></span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-12"> 
                <br>
                <br>
                <span>Event Name : <label>PSITE 7</label></span>

                <div id="table">
                    <table class="table table-hover" id="tableExpense">
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
                                <td>s1s</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                            </tr>
                            <tr>
                                <td>s2</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                            </tr>
                            <tr>
                                <td>3s</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                            </tr>
                            <tr>
                                <td>s4ds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            </div> 
            </div>

                 
        </div>
        
           
        </div>
        </div>
            <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading" data-toggle="collapse" data-target="#extractContent"> 
                    Extracted data from currently uploaded Receipts
                </div>
                <div id="extractContent" class="collapse in">
                    <div class="panel-body" style="">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-5" id="imageExtracted">
                                    <img class="center-block" style=" width: 500px;
                                    height: 700px;
                                    background-position: center;
                                    background-size: cover;" id="" src='/assets/receiptsImg/{{session()->get('session_ImageName')}}'/>
                                </div>
                                <div class="col-md-6">
                                    <div id="extracted">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <label>Merchant Name :</label>
                                                <input type="text" class="form-control" value="{!! $extract['vendor'] !!}" id="dataExtract" disabled="disable"> 


                                                <label>Receipt/Invoice # :</label>
                                                <input type="text" class="form-control" value="{!! $extract['receipt_no'] !!}" id="dataExtract" disabled="disable"> 

                                                <label>Date of Purchased:</label>
                                                <input type="text" value="{!! $extract['date_purchased'] !!}" class="form-control" disabled="disable"> 
                                                <label>Place of Purchased:</label>
                                                <input type="text" value="{!! $extract['place_purchased'] !!}" class="form-control" disabled="disable"> 
                                                
                                                <label>Total Due:</label>
                                                <input type="text" value="{!! $extract['total'] !!}" class="form-control" disabled="disable"> 
  
                                            </div>
                                        </div>

                                        <br>
                                        <br>
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                Click to show Expense Items
                                            </div>
                                            <div class="panel-body">
                                                <div id="wrapItems">
                                                    <table class="table" id="expenseItems">
                                                    <thead> 
                                                          <tr>
                                                            <th style="text-align: center;">
                                                                Item Name
                                                            </th>
                                                            <th style="text-align: center;">
                                                            Price
                                                            </th>
                                                        </tr>
                                                        
                                                    </thead>
                                                   @foreach($extract['items'] as $item)
                                                        <tr>
                                                            <td>
                                                                <input type="text" value="{{ $item['name'] }}" class="form-control" disabled="disable" > 
                                                            </td>
                                                            <td> 
                                                                <input type="text" value= "{{ $item['price'] }}" class="form-control" disabled="disable"> 
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" id="editExtractedData" >Edit</button>
                                        <button class="btn btn-success">Save</button>

                                    </div>         
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <br>
    <br>  
    </div>

@else
    <div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading" data-toggle="collapse" data-target="#content" style="height: 35px">
            Click to show expenses
            </div>
            <div class="panel-body">
            <div class="collapse in" id="content">
                <div class="col-md-12">
                <span style="margin-left:1.5%;margin-botoom:10px;">Select expense category:</span>    
            </div>
            <br>
            <br>  
            <div class="row">
            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" style="width:200px;margin:0 auto">
                                <select class="form-control" id="sel1">
                                    <option>All</option>
                                    <option>Food</option>
                                    <option>Transportation</option>
                                    <option>Utilities</option>
                                    <option>Others</option>
                                    <option>--Uncategorized--</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 text-left">   
                            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#addCatModal">
                                <i class="glyphicon glyphicon-plus"></i> Category
                            </button>

                            <div class="modal fade" id="addCatModal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Category of Expense</h4>
                                        </div>
                                        <div class="modal-body">    
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="newCat"  placeholder="Input new category">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal">Add</button>
                                            <button class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Event.." aria-describedby="basic-addon1">
                        <span class="input-group-addon" id="tasksicon"><span class="glyphicon glyphicon-search"></span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-12"> 
                <br>
                <br>
                <span>Event Name : <label>PSITE 7</label></span>

                <div id="table">
                    <table class="table table-hover" id="tableExpense">
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
                                <td>s1s</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                            </tr>
                            <tr>
                                <td>s2</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                            </tr>
                            <tr>
                                <td>3s</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                            </tr>
                            <tr>
                                <td>s4ds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                                <td>sdfsdfds</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            </div> 
            </div>

                 
        </div>
        
           
        </div>
        </div>
        
    </div>




@endif


<script>
    $(document).ready(function(){
        $('.container > ul').find('#expenseTab').addClass('active');
        
    });

    $("#editExtractedData").click(function(){
        alert(1212);
        $(".form-control").removeAttr('disabled');
    })
</script>

@stop



