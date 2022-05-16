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
                    <li class="breadcrumb-item active" aria-current="page">Add Applicant</li>
                    </ol>
                </nav>
             
                <div class="card">
                  <div class="card-body">                
                    <form class="forms-sample" id="add_applicant_form" method="post">
                      @csrf
                      <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">PPO No.<span class="span-red">*</span></label>
                                  <input type="text" class="form-control" id="applicant_name" name="applicant_name" placeholder="Enter applicant name" autocomplete="off">
                                  <label id="applicant_name-error" class="error mt-2 text-danger" for="applicant_name"></label>
                              </div>
                            </div>
                      </div>                 
                      <button type="submit" class="btn btn-success mr-2">Submit</button>
                    </form>
                  </div>
                </div>

                <div class="card">
                  <div class="card-body">     
                    <!-- <h4 class="card-title">Pension Proposal List</h4>            -->
                    <form class="forms-sample" id="add_applicant_form" method="post">
                      @csrf
                      <div class="row">
                            <div class="col-md-3">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">From Date<span class="span-red">*</span></label>
                                  <input type="text" class="form-control" id="applicant_name" name="applicant_name" placeholder="Enter applicant name" autocomplete="off">
                                  <label id="applicant_name-error" class="error mt-2 text-danger" for="applicant_name"></label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">To Date<span class="span-red">*</span></label>
                                  <input type="text" class="form-control" id="applicant_name" name="applicant_name" placeholder="Enter applicant name" autocomplete="off">
                                  <label id="applicant_name-error" class="error mt-2 text-danger" for="applicant_name"></label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">Old TI Percentage<span class="span-red">*</span></label>
                                  <input type="text" class="form-control" id="applicant_name" name="applicant_name" placeholder="Enter applicant name" autocomplete="off">
                                  <label id="applicant_name-error" class="error mt-2 text-danger" for="applicant_name"></label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">New TI Percentage<span class="span-red">*</span></label>
                                  <input type="text" class="form-control" id="applicant_name" name="applicant_name" placeholder="Enter applicant name" autocomplete="off">
                                  <label id="applicant_name-error" class="error mt-2 text-danger" for="applicant_name"></label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">Besic Pension<span class="span-red">*</span></label>
                                  <input type="text" class="form-control" id="applicant_name" name="applicant_name" placeholder="Enter applicant name" autocomplete="off">
                                  <label id="applicant_name-error" class="error mt-2 text-danger" for="applicant_name"></label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">Additional Pension<span class="span-red">*</span></label>
                                  <input type="text" class="form-control" id="applicant_name" name="applicant_name" placeholder="Enter applicant name" autocomplete="off">
                                  <label id="applicant_name-error" class="error mt-2 text-danger" for="applicant_name"></label>
                              </div>
                            </div>
                      </div>                 
                      <button type="submit" class="btn btn-success mr-2">Add Arrear</button>
                    </form>
                  </div>
                </div>

                <div class="card">
                  <div class="card-body">
                      <h4 class="card-title">Arrear List</h4>

                      <div class="row">
                        <div class="table-sorter-wrapper col-lg-12 table-responsive">
                          <table id="sampleTable" class="table table-striped">
                            <thead>
                              <tr>
                              <th>Sl No.</th>
                                <th>Pensioner Type</th>
                                <th>Application Type</th>
                                <th>PPO No</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                          </table>
                          
                        </div>
                      </div>
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

         $("#designation").on('change', function(){
            $(this).valid();
         });
         $("#dob").on('change', function(){
            $(this).valid();
         });
         $("#doj").on('change', function(){
            $(this).valid();
         });
         $("#dor").on('change', function(){
            $(this).valid();
         });

          
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
                  required: 'Please enter applicant name',
                  minlength: 'Applicant name should be minimum 2 characters',
                  maxlength: 'Applicant name should be maximum 70 characters'
                },
                employee_code: {
                  required: 'Please enter employee code',
                  minlength: 'Employee Code should be minimum 5 digits',
                  maxlength: 'Employee Code should be maximum 5 digits',
                  remote: 'Employee code already exits'

                },
                aadhaar_no: {                    
                  required: 'Please enter Aadhaar no',
                  minlength: 'Aadhaar no should be minimum of 12 digits',
                  maxlength: 'Aadhaar no should be maximum upto 12 digits',
                  remote: 'Aadhaar no already exits'
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
                      url:'{{ route("da_add_applicant_submit") }}',
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
                          }else if(response['loginCheckMessage']){
                            location.href = "{{route('da_add_applicant_submit')}}";
                          }else{
                            // Success
                            //location.reload();
                            location.href = "{{route('add_applicant')}}";
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