@extends('user.layout.layout')

@section('section_content')
<style type="text/css">
    .tablerow {
         background-color: white;
     }
</style>
<div class="content-wrapper">
    <div class="row">
       <div class="col-12 grid-margin">
            @if(Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Applicant</li>
                    </ol>
                </nav>
             
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Edit Applicant</h4>
                
                <form class="forms-sample" id="add_applicant_form" method="post" autocomplete="off">
                    @csrf
                    <input type="hidden" name="applicant_id" id="applicant_id" value="{{ $data->id }}">
                    <input type="hidden" name="employee_master_id" value="{{ $data->employee_master_id }}">
                  <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                              <label for="exampleInputEmail3">Applicant Name<span class="span-red">*</span></label>
                              <input type="text" class="form-control" id="applicant_name" name="applicant_name" placeholder="Enter applicant name" value="{{ $data->first_name.' '.$data->last_name }}">
                              <label id="applicant_name-error" class="error mt-2 text-danger" for="applicant_name"></label>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                              <label for="exampleInputEmail3">Employee Code<span class="span-red">*</span></label> 
                              <input type="text" class="form-control numbersOnly" id="employee_code" name="employee_code" placeholder="Enter employee code" minlength="5" maxlength="5" value="{{ $data->employee_code }}">
                              <label id="employee_code-error" class="error mt-2 text-danger" for="employee_code"></label>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                              <label for="exampleInputEmail3">Aadhaar No<span class="span-red">*</span></label>
                              <input type="text" class="form-control numbersOnly" id="aadhaar_no" name="aadhaar_no" placeholder="Enter aadhaar no" maxlength="12" value="{{ $data->aadhaar_no }}">
                              <label id="aadhaar_no-error" class="error mt-2 text-danger" for="aadhaar_no"></label>
                          </div>
                        </div>
                        
                 </div>
                 <div class="row"> 
                    <div class="col-md-4">
                      <div class="form-group">
                          <label for="exampleInputName1">Mobile No<span class="span-red">*</span></label>
                          <input type="text" class="form-control numbersOnly" id="mobile_no" name="mobile_no" placeholder="Enter mobile no" maxlength="10" value="{{ $data->mobile }}">
                          <label id="mobile_no-error" class="error mt-2 text-danger" for="mobile_no"></label>
                      </div>
                    </div>
                    <div class="col-md-4" id="userDesg">
                      <div class="form-group"  >
                        <label>Designation<span class="span-red">*</span></label>
                          <select class="js-example-basic-single form-control" id="designation" name="designation" >
                            <option value="">Select Designation</option>
                              @foreach($designation as $list)
                                 <option value="{{$list->id}}" @if($list->id == $data->designation_id) selected @endif>{{$list->designation_name}}</option>
                              @endforeach
                          </select>
                          <label id="designation-error" class="error mt-2 text-danger" for="designation"></label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                          <label class="">Date of Birth<span class="text-danger">*</span></label>
                          <div id="inline-datepicker" class="input-group date ">
                              <input type="text" class="form-control" id="dob" name="dob" value="{{ date('d/m/Y', strtotime($data->date_of_birth)) }}">
                              <span class="input-group-addon input-group-append border-left">
                                  <span class="mdi mdi-calendar input-group-text"></span>
                              </span>
                          </div>
                          <label id="dob-error" class="error text-danger" for="dob"></label>
                      </div>
                    </div>
                 </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                        <label class="">Date of Joining in Service<span class="text-danger">*</span></label>
                        <div id="datepicker-joining" class="input-group date ">
                            <input type="text" class="form-control " id="doj" name="doj" value="{{ date('d/m/Y', strtotime($data->date_of_joining)) }}">
                            <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                        </div>
                        <label id="doj-error" class="error text-danger" for="doj"></label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label class="">Date of Retirement<span class="text-danger">*</span></label>
                        <div id="datepicker-popup" class="input-group date">
                            <input type="text" class="form-control" id="dor" name="dor" value="{{ date('d/m/Y', strtotime($data->date_of_retirement)) }}">
                            <span class="input-group-addon input-group-append border-left">
                                <span class="mdi mdi-calendar input-group-text"></span>
                            </span>
                        </div>
                        <label id="dor-error" class="error text-danger " for="dor"></label>
                    </div>
                  </div>
               </div>
            
                  <button type="submit" class="btn btn-success mr-2">Submit</button>
                </form>
              </div>
            </div>
       </div>
    </div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    
    $(document).ready(function(){
         $('.numbersOnly').keyup(function () { 
            this.value = this.value.replace(/[^0-9\.]/g,'');
         });
         $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s-]*$/.test(value);
         }, "Please use only letters");
          
          // form validation 
          $("#add_applicant_form").validate({
              rules: {
                applicant_name: {
                  required: true,
                  minlength: 2,
                  maxlength: 70,
                  addressReg: true
                },
                employee_code: {
                  required: true,
                  minlength: 5,
                  maxlength: 5,
                  remote: {
                        url:'{{ route("validate_da_employee_code") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            },
                            'applicant_id': function() {
                               return $('#applicant_id').val();
                            }
                        }
                  },
                },
                aadhaar_no: {
                  required: true,
                  minlength: 12,
                  maxlength: 12,
                  remote: {
                        url:'{{ route("validate_da_aadhaar_no") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            },
                            'applicant_id': function() {
                               return $('#applicant_id').val();
                            }
                        }
                  },
                },
                mobile_no: {
                  required: true,
                  minlength: 10,
                  maxlength: 10,
                  remote: {
                        url:'{{ route("validate_da_mobile_number") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            },
                            'applicant_id': function() {
                               return $('#applicant_id').val();
                            }
                        }
                  },
                },
                designation: {
                  required: true
                },
                dob: {
                    required: true,
                },
                doj: {
                    required: true,
                    remote:{
                        url:'{{ route("validate_doj") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            },
                            'dob': function() {
                               return $('#dob').val();
                            }
                        }
                    },
                },
                dor: {
                    required: true,
                    remote:{
                        url:'{{ route("validate_dor") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            },
                            'doj': function() {
                               return $('#doj').val();
                            }
                        }
                    },
                }
                
              },
              messages: {
                applicant_name: {
                  required: 'Please enter Applicant Name',
                  minlength: 'Applicant Name should be minimum 2 characters',
                  maxlength: 'Applicant Name should be maximum 70 characters'
                },
                employee_code: {
                  required: 'Please enter Employee Code',
                  minlength: 'Employee Code should be minimum 5 digits',
                  maxlength: 'Employee Code should be maximum 5 digits',
                  remote: 'Employee Code already exits'

                },
                aadhaar_no: {                    
                  required: 'Please enter Aadhaar No',
                  minlength: 'Aadhaar No should be minimum of 12 digits',
                  maxlength: 'Aadhaar No should be maximum upto 12 digits',
                  remote: 'Aadhaar No already exits'
                },
                mobile_no: {                    
                    required: 'Please enter Mobile no',
                    minlength: 'Mobile no should be minimum 10 digits',
                    maxlength: 'Mobile no should be maximum 10 digits',
                    remote: 'Mobile no already exits'
                },
                designation: {
                    required: 'Please select designation'
                },
                dob: {
                    required: 'Please select date of birth'
                },
                doj: {
                    required: 'Please select date of joining in service',
                    remote: 'Please select valid date of joining in service',
                },
                dor: {
                    required: 'Please select date of retirement',
                    remote: 'Please select valid date of retirement',
                }
              },
               submitHandler: function(form, event) { 
                $('.page-loader').addClass('d-flex');
                  event.preventDefault();
                  var formData = new FormData(form);
                  //$("#logid").prop('disabled',true);
                  $.ajax({
                      type:'POST',
                      url:'{{ route("update_applicant_details") }}',
                      data: formData,
                      dataType: 'JSON',
                      processData: false,
                      contentType: false,
                      success: function(response) {
                        $('.page-loader').removeClass('d-flex');
                          if(response['error']){
                            //$("#logid").prop('disabled',false);
                              for (i in response['error']) {
                                  var element = $('#' + i);
                                  var id = response['error'][i]['id'];
                                  var eValue = response['error'][i]['eValue'];
                                  //console.log(id);
                                  //console.log(eValue);
                                  $("#"+id).show();
                                  $("#"+id).html(eValue);
                              }
                          }else{
                            // Success
                            //location.reload();
                            location.href = "{{route('view_applicant')}}";
                          }
                      }
                  });
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