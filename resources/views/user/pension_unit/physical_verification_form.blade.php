@extends('user.layout.layout')

@section('section_content')
<style>
  #income_property_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #other_income_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #lic_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #nsc_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ppf_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ety_d_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ety_dd_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  .mdi-pencil{
    font-size:20px;
    color:rgb(74, 172, 74);
  }
  .mdi-pencil:hover{
    color:rgb(0, 100, 0);
  }
  .mdi-delete{
    font-size:20px;
    color:rgb(225, 83, 83);
  }
  .mdi-delete:hover{
    color:rgb(191, 0, 0);
  }
  #sampleTable{
      border: 1px solid rgb(233, 233, 233);
  }
  .addClassbtn {
        margin-left:900px;
    }
 .fsize{
    font-size: 14px;
 }
  
</style>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            
            <div class="card">
                <div class="card-body">
                    @if(Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="">Physical Verification</a></li>
                                    <li class="breadcrumb-item"><a href="">Pending</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">View</li>
                                </ol>
                            </nav>
                            @php
                                $emp_code= $result->employee_code;
                                $pensioner_name = DB:: table('optcl_users')->where('employee_code',$emp_code)->first();
                                $pan_no = DB:: table('optcl_employee_personal_details')->where('employee_code',$emp_code)->first();
                                $emp_id = DB:: table('optcl_employee_master')->where('employee_code',$emp_code)->first();
                                //dd($emp_id);
                                $empId = $emp_id->id;
                                $bankPassbook = DB:: table('optcl_employee_document_details')->where('employee_id',$empId)->first();
                                
                            @endphp
                    <form id="physical_verification_form" method="post" autocomplete="off" action="{{ route('physical_verification_submission') }}">
                        @csrf   
                        <input type="hidden" name="application_no" id="application_no"  value="{{  $result->application_no }}"> 
                        <input type="hidden" name="id" id="id"  value="{{  $result->id }}"> 
                        

                            <table class="table table-bordered mt-3">
                                <tr>
                                    <th width="18%">Application No. :</th>
                                    <td width="15%">{{  $result->application_no }}</td>
                                    <th width="12%">Pensioner Name :</th>
                                    <td width="20%">{{  $pensioner_name->first_name }}</td>
                                    <th width="15%">Created At :</th>
                                    <td>{{ date('d-m-Y h:i A', strtotime($result->created_at)) }}</td>
                                </tr>
                            </table>
                       <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>
                                            <div class="col-md-6">  
                                                <div class="form-group">
                                                    <label>Bank Passbook Front Page Copy<span class="span-red">*</span></label>
                                                    <img class="document_img" src="{{ asset('public/' . $bankPassbook->attached_bank_passbook) }}">
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" id="passbook_is_checked" name="passbook_is_checked">
                                                    <label id="passbook_is_checked-error" class="error mt-2 text-danger" for="passbook_is_checked"></label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-6">  
                                                <div class="form-group">
                                                    <label for="exampleInputEmail3">Aadhaar No<span class="span-red">*</span></label>
                                                    <input type="text" class="form-control numbersOnly" id="aadhaar_no" name="aadhaar_no" placeholder="Enter aadhaar no" maxlength="12" autocomplete="off" readonly value="{{  $result->employee_aadhaar_no }}">
                                                    <label id="aadhaar_no-error" class="error mt-2 text-danger" for="aadhaar_no"></label>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" id="aadhaar_no_is_checked" name="aadhaar_no_is_checked">
                                                    <label id="aadhaar_no_is_checked-error" class="error mt-2 text-danger" for="aadhaar_no_is_checked"></label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-6">  
                                                <div class="form-group">
                                                    <label for="exampleInputEmail3">PAN No<span class="span-red">*</span></label>
                                                    <input type="text" class="form-control numbersOnly" id="pan_no" name="pan_no" autocomplete="off" readonly value="{{  $pan_no->pan_no }}">
                                                    <label id="pan_no-error" class="error mt-2 text-danger" for="pan_no"></label>
                                                </div>
                                            </div>
                                        </td>
                                       
                                        <td>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" id="pan_no_is_checked" name="pan_no_is_checked">
                                                    <label id="pan_no_is_checked-error" class="error mt-2 text-danger" for="pan_no_is_checked"></label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>     
                                    
                                </table>    
                            </div>
                       </div>
                       <button type="submit" class="btn btn-success mr-2">Submit</button>
                   </form>

                </div>
                     
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
<div class="modal fade" id="img_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <img id="img-show" width="450" height="250">
            </div>
        </div>
    </div>
</div>            


 @endsection
 @section('page-script')
 <script type="text/javascript">
	$(document).ready(function() {
		$('.document_img').on('click', function() {
            var src = $(this).attr('src');

            $('#img-show').attr('src', src);
            $('#img_show').modal('show');
        });

        $("#physical_verification_form").validate({
              rules: {
                passbook_is_checked: {
                  required: true
                },
                aadhaar_no_is_checked: {
                  required: true
                },
                pan_no_is_checked: {
                  required: true
                }

                
              },
              messages: {
                passbook_is_checked: {
                  required: 'Please checked bank passbook'
                },
                aadhaar_no_is_checked: {
                  required: 'Please checked Aadhaar No'
                },
                pan_no_is_checked: {
                  required: 'Please checked PAN No'
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