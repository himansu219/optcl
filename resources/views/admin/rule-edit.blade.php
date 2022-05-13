@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
                    <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item "><a href="{{route('rule_details')}}">Rules</a></li>
                        <li class="breadcrumb-item "><a href="{{route('rule_add')}}">Add Rules</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Rules</li>
                      </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Rule</h4>
                    <form class="forms-sample" id="rule_form" method="post" action="{{URL('rule-update/'.$result->id)}}">
                      @csrf
                      <input type="hidden" name="rule_hidden_id" id="" value="{{$result->id}}" >
                             <div class="row">
                               <div class="col-md-6">
                                  <div class="form-group">
                                    @php
                                    $pension_type = $result->pension_type_id;
                                    $pension_type_name = DB::table('optcl_pension_type_master')->where('status', 1)->where('deleted', 0)->get();
                                    @endphp
                                    <label>Pension Type</label> <span class="span-red">*</span>
                                      <select class="js-example-basic-single form-control" id="pension_type" name="pension_type" autocomplete="off">
                                         @foreach($pension_type_name as $list)
                                          <option value="{{$list->id}}" @if($result->pension_type_id == $list->id) {{'selected'}} @endif>{{$list->pension_type}}</option>
                                          @endforeach
                                                                                    
                                      </select>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    @php
                                    $calculation_type = $result->calculation_type_id;
                                    $calculation_type_name = DB::table('optcl_calculation_type_master')->where('status', 1)->where('deleted', 0)->get();
                                    @endphp
                                    <label>Calculation Type</label> <span class="span-red">*</span>
                                      <select class="js-example-basic-single form-control" id="calculation_type" name="calculation_type" autocomplete="off">
                                        @foreach($calculation_type_name as $list)
                                          <option value="{{$list->id}}" @if($result->calculation_type_id == $list->id) {{'selected'}} @endif>{{$list->calculation_type}}</option>
                                          @endforeach
                                                                                    
                                      </select>
                                  </div>
                                </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Rule Name</label> <span class="span-red">*</span>
                                      <input type="text" class="form-control" id="rule_name" name="rule_name" placeholder="Enter rule name" autocomplete="off" value="{{$result->rule_name}}">
                              </div>
                               </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Rule Description</label> <span class="span-red">*</span>
                                        <textarea class="form-control" id="rule_description" name="rule_description" placeholder="Enter description" autocomplete="off" rows="8" cols="50" value="{{$result->rule_description}}">{{$result->rule_description}}</textarea>
                                     </div>
                                </div>
                            </div>
                        <button type="submit" class="btn btn-success mr-2">Update</button>
                  </form>
                </div>
              </div>
   </div>    


  @endsection
  @section('page-script')
  <script type="text/javascript">
    $(document).ready(function(){
      //  $.validator.addMethod("ruleDesc", function (value, element) {
      //        return this.optional(element) || /^[a-zA-Z0-9\s,()/-]*$/.test(value);
      //    }, "Please use only letters, numbers and special characters(,()/-).");
       
     $("#rule_form").validate({
             rules: {
               pension_type: {
                 required: true 
               },
               calculation_type: {
                 required: true
               },
               rule_name: {
                 required: true,
                 minlength: 3,
                 maxlength: 200
                 //ruleDesc: true
               },
               rule_description: {
                 required: true,
                 minlength: 10,
                 maxlength: 400
                 //ruleDesc: true
               }
             },
             messages: {
               pension_type: {
                 required: 'Please select pension type'
               },
               calculation_type: {
                 required: 'Please select calculation type'
               },
               rule_name: {                    
                 required: 'Please enter rule name',
                 minlength: 'Rule name minimum 3 characters',
                 maxlength: 'Rule name maximum 200 characters'
                 },
               rule_description: {
                 required: 'Please enter rule description',
                 minlength: 'Rule Description minimum 10 characters',
                 maxlength: 'Rule Description maximum 400 characters'
               }
             },
             errorPlacement: function(label, element) {
                 label.addClass('mt-2 text-danger');
                 label.insertAfter(element);
             },
             highlight: function(element, errorClass) {
                 $(element).parent().addClass('has-success')
                 $(element).addClass('form-control-danger')
             }
         });
     
   });
 </script>           
 
  @endsection