
@extends('layouts.master')

@section('contents')
<div class="container">
   
    <div class="row">
        <div class="col-md-12">
            <span style="margin-left:1.5%;margin-botoom:10px;">Select expense category:</span>    
        </div>
        <br>
        <br>   
    </div>
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

<script>
    $(document).ready(function(){
        $('.container > ul').find('#expenseTab').addClass('active');
        
    });
</script>

@stop



