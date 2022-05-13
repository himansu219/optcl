@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
                    <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">User Management</a></li>
                        {{-- <li class="breadcrumb-item "><a href="{{route('user_details')}}">User Master</a></li> --}}
                        <li class="breadcrumb-item "><a href="{{route('user_add')}}">Add User</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                      </ol>
                    </nav>
            <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit user form</h4>
                  <form class="forms-sample" id="user_form" method="post" action="{{URL('user-update/'.$result->id)}}">
                      @csrf
                        <input type="hidden" name="user_hidden_id" id="" value="{{$result->id}}" >
                         <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputName1">User Name</label> <span class="span-red">*</span>
                                    <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter user name" autocomplete="off" value="{{$result->username}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">Email Id <span class="span-red">*</span></label> 
                                  <input type="text" class="form-control" id="email_id" name="email_id" placeholder="Enter email Id" minlength="5" autocomplete="off" value="{{$result->email_id}}">
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">Employee Code</label>
                                  <input type="text" class="form-control" id="employee_id" name="employee_id" placeholder="Enter employee code" maxlength="5" autocomplete="off" value="{{$result->employee_code}}">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">Aadhaar No</label>
                                  <input type="text" class="form-control" id="aadhaar_no" name="aadhaar_no" placeholder="Enter aadhaar no" maxlength="12" autocomplete="off" value="{{$result->aadhaar_no}}">
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">Mobile No</label> <span class="span-red">*</span>
                                  <input type="text" class="form-control" id="user_mobile_no" name="user_mobile_no" placeholder="Enter user mobile no" maxlength="10" autocomplete="off" value="{{$result->mobile}}">
                              </div>
                             </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>User Type <span class="span-red">*</span></label>
                                  <select class="js-example-basic-single" style="width:100%" id="user_role" name="user_role"  autocomplete="off">
                                    <option value="">Select User Type</option>
                                      @foreach($user_role as $list)
                                      <option value="{{$list->id}}" @if($result->system_user_role == $list->id) {{'selected'}} @endif>{{$list->type_name}}</option>
                                      @endforeach
                                  </select>
                                  <label id="user_role-error" class="error mt-2 text-danger" for="user_role" style="display: inline-block;"></label>
                              </div>
                            </div>
                            <div class="col-md-4">
                                  <div class="form-group">
                                      @php
                                        $user_role = $result->system_user_role;
                                        $designations = DB::table('optcl_user_designation_master')->where('user_role_id', $user_role)->get();
                                      @endphp
                                      <label>User Designation </label> <span class="span-red">*</span>
                                      <select class="js-example-basic-single form-control" id="designation" name="designation"  autocomplete="off">
                                        
                                          @foreach($designations as $list)
                                            <option value="{{$list->id}}" @if($result->designation_id == $list->id) {{'selected'}} @endif>{{$list->designation_name}}</option>
                                          @endforeach
                                      </select>
                                      <label id="designation-error" class="error mt-2 text-danger" for="designation" style="display: inline-block;"></label>
                                  </div>
                            </div>
                            @php
                              $dClass = '';
                              
                              if(in_array($result->designation_id, [5,6,7,8,9,10,11,12])){
                                  $dClass = 'd-none';
                              }
                            @endphp
                            <div class="col-md-4 {{$dClass}}" id="unitDivID">
                                <div class="form-group">
                                  <label>OPTCL Unit  <span class="span-red">*</span></label>
                                    <select class="js-example-basic-single form-control" style="width:100%;"  id="unit_name" name="unit_name" autocomplete="off">
                                      <option value="">Select Unit</option>
                                      @foreach($optcl_unit as $list)
                                        <option value="{{$list->id}}" @if($result->optcl_unit_id == $list->id) {{'selected'}} @endif>{{$list->unit_name}}</option>
                                      @endforeach                              
                                    </select>
                                    <label id="unit_name-error" class="error mt-2 text-danger" for="unit_name" style="display: inline-block;"></label>
                                </div>
                            </div>
                            @php
                              $addclass = '';
                              if(in_array($result->designation_id, [2,3,4,5,6,7,8,9,10,11])){
                                $addclass = 'd-none';
                                
                              }
                            @endphp
                            <div class="col-md-4 {{$addclass}}" id="pensionunitDivID">
                                <div class="form-group">
                                  <label>Pension Unit  <span class="span-red">*</span></label>
                                    <select class="js-example-basic-single form-control" style="width:100%;" id="pension_unit_name" name="pension_unit_name" autocomplete="off">
                                      <option value="">Select Pension Unit</option>
                                      @foreach($pension_unit as $list)
                                        <option value="{{$list->id}}" @if($result->pension_unit_id == $list->id) {{'selected'}} @endif>{{$list->pension_unit_name}}</option>
                                      @endforeach                              
                                    </select>
                                    <label id="pension_unit_name-error" class="error mt-2 text-danger" for="pension_unit_name" style="display: inline-block;"></label>
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
          $('#user_role').change(function(){
        let uid=$(this).val();
        //console.log(uid);
				$('#designation').html('<option value="">Select User Designation</option>')
        
				$.ajax({
					url:"{{route('user_desgnation_data')}}",
					type:'post',
					data:'uid='+uid+'&_token={{csrf_token()}}',
					success:function(result){
             $('#designation').html(result);
             $('#unitDivID').addClass('d-none');
             $('#pensionunitDivID').addClass('d-none');           
					}
				});
		  	});
      
        $("#designation").on("change", function(){
          var designation = $(this).val();
          console.log(designation);
          if(designation == 5 || designation == 6 || designation == 7 || designation == 8 || designation == 9 || designation == 10 || designation == 11) {
            $('#unitDivID').addClass('d-none');
            $('#pensionunitDivID').addClass('d-none');
            // $('#unitDivID').hide();
            // $('#pensionunitDivID').hide();
          }else if(designation == 12) {  //  DDO Pension Unit
            $('#unitDivID').addClass('d-none');
            $('#pensionunitDivID').removeClass('d-none');
            // $('#unitDivID').hide();
            // $('#pensionunitDivID').hide();
          } else {
            $('#unitDivID').removeClass('d-none');
            $('#pensionunitDivID').addClass('d-none');
             
          }
         
        });
     
      
      });
  </script>
  <script type="text/javascript">

        $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s-]*$/.test(value);
        }, "Please use only letters");

        $.validator.addMethod("onlyNumber", function (value, element) {
            return this.optional(element) || /^[0-9\s-]*$/.test(value);
        }, "Please use only numbers");
        // email validation
        $.validator.addMethod("emailValidation", function (value, element) {
            return this.optional(element) || /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
        }, "Invalid email Id");

        $("#user_form").validate({
            rules: {

              user_name: {
                  required: true,
                  minlength: 2,
                  maxlength: 70,
                  //addressReg: true
              },
              employee_id: {
                  
                  minlength: 5,
                  maxlength: 5,
                  onlyNumber: true
              },
              aadhaar_no: {
                  //required: true,
                  minlength: 12,
                  maxlength: 12,
                  onlyNumber: true
              },
              
              user_mobile_no: {
                  required: true,
                  minlength: 10,
                  maxlength: 10,
                  onlyNumber: true
              },
              email_id: {
                  required: true,
                  minlength: 5,
                  //maxlength: 100,
                  emailValidation: true
              },
              user_role: {
                required: true
              },
              designation: {
                required: true
              },
              unit_name: {
                required: true
              },
              pension_unit_name: {
                required: true
              }
            },
            messages: {
               
                user_name: {                    
                    required: 'Please enter user name',
                    minlength: 'User name minimum 2 characters',
                    maxlength: 'User name maximum 70 characters'
                },
                employee_id: {                    
                   
                    minlength: 'Employee code minimum 5 digits',
                    maxlength: 'Employee code maximum 5 digits'
                },
                aadhaar_no: {
                    //required: 'Please enter Aadhaar No',
                    minlength: 'Aadhaar No minimum of 12 digits',
                    maxlength: 'Aadhaar No maximum upto 12 digits'

                },
                user_mobile_no: {                    
                    required: 'Please enter Mobile no',
                    minlength: 'Mobile no minimum 10 digits',
                    maxlength: 'Mobile no maximum 10 digits'
                },
                email_id: {                    
                    required: 'Please enter email Id',
                    minlength: 'Email Id minimum 5 characters',
                    //maxlength: 'Email Id maximum 100 characters',
                },
                user_role: {
                    required: 'Please select user type'
                  
                },
                 
                designation: {
                    required: 'Please select designation'
                  
                },
                unit_name: {
                    required: 'Please select OPTCL unit'
                  
                },
                pension_unit_name: {
                    required: 'Please select pension unit'
                  
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



  </script>

@endsection