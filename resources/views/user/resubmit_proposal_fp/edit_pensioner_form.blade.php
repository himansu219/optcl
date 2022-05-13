@extends('user.layout.layout')

@section('section_content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <img src="{{url('public')}}/images/logo_1.png" alt="image" class="brand_logo_1" />
                    <img src="{{url('public')}}/images/logo_2.png" alt="image" class="brand_logo_2" />
                    <h4 class="card-title align_center mb-2">ODISHA POWER TRANSMISSION CORPORATION LTD.</h4>
                    <h5 class="card-description align_center mb-1">(A Govt. of Odisha Undertaking)</h5>
                    <h5 class="card-description align_center mb-1">Gridco Pension Trust Fund</h5>
                    <p class="card-description align_center mb-1">Regd. Off – Janpath, Bhubaneswar – 751022</p>
                    
                    <div class="employe-code-check">
                        <ul class="nav nav-tabs d-flex justify-content-center mt-5" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active-tab" id="home-tab" href="">1. FAMILY PENSION FORM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" href="">2. FAMILY PENSIONER</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nominee-tab" href="">3. NOMINEES</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" href="">4. LIST OF DOCUMENTS</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                                <div class="media">
                                    <div class="media-body">
                                        <h4 class="mt-0 text-center">SERVICE PENSION FORM (Provisional / Final / Revised) PART - I</h4>
                                        <hr>
                                        <h6 class="text-center-normal">PARTICULARS OF THE DECEASED PENSIONER</h6>
                                        <hr>
                                        <br />
                                
                                        <form  class="forms-sample" autocomplete="off" id="pension_form" action="" method="post"    enctype="multipart/form-data">
                                           @csrf
                                            <input type="hidden" name="pension_form_id" value="{{$pensionerDetails->id}}">
                                              <div class="row form_1_">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Employee No/Code<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control anns" id="emp_code" name="emp_code" value="{{$pensionerDetails->employee_code}}" placeholder="Employee No/Codes" readonly>
                                                        <label id="emp_code-error" class="error text-danger" for="emp_code"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Aadhaar No<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="aadhaar_no" name="aadhaar_no" value="{{$pensionerDetails->aadhaar_no}}" placeholder="Enter Aadhaar No" value="" minlength="12" maxlength="12" readonly>
                                                        <label id="aadhaar_no-error" class="error text-danger" for="aadhaar_no"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                            $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(3);
                                                        @endphp
                                                        <label for="exampleInputName1">Name (In Block Letter)<span class="text-danger">*</span></label>

                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(3);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <input type="text" class="form-control alpha" id="name" name="name" placeholder="Name" value="{{strtoupper($pensionerDetails->employee_name)}}" @if($fieldStatus) readonly @endif>
                                                        <label id="name-error" class="error text-danger" for="name"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(4);
                                                        @endphp
                                                        <label>Designation<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(4);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <select class="js-example-basic-single form-control" id="designation" name="designation" readonly>
                                                            <option value="">Select Designation</option>
                                                            @foreach($pensioner_designation as $list)
                                                            <option value="{{$list->id}}" @if($list->id == $pensionerDetails->designation_id) {{'selected'}} @endif @if($fieldStatus && $list->id != $pensionerDetails->designation_id) disabled @endif>{{$list->designation_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="designation-error" class="error text-danger" for="designation"></label>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(5);
                                                        @endphp
                                                        <label for="exampleInputCity1">Father's Name<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(5);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <input type="text" class="form-control alpha" id="father_name" name="father_name" placeholder="Father's Name" value="{{$pensionerDetails->father_name}}" @if($fieldStatus) readonly @endif>
                                                        <label id="father_name-error" class="error text-danger" for="father_name"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(6);
                                                        @endphp
                                                        <label>Gender<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(6);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <select class="js-example-basic-single form-control" name="gender" id="gender">
                                                            <option value="">Select Gender</option>
                                                            @foreach($genders as $gender)
                                                                <option value="{{$gender->id}}" @if($gender->id == $pensionerDetails->gender_id) {{'selected'}} @endif @if($fieldStatus && $gender->id != $pensionerDetails->gender_id) disabled @endif>{{$gender->gender_name}}</option>
                                                            @endforeach  
                                                        </select>
                                                        <label id="gender-error" class="error text-danger" for="gender"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(7);
                                                        @endphp
                                                        <label>Marital Status<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(7);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <select class="js-example-basic-single form-control" name="marital_status" id="marital_status">
                                                            <option value="">Select Status</option>
                                                            @foreach($mstatus as $mstatusValue)
                                                                <option value="{{$mstatusValue->id}}" @if($mstatusValue->id == $pensionerDetails->marital_status_id) {{'selected'}} @endif   @if($fieldStatus && $mstatusValue->id != $pensionerDetails->marital_status_id) disabled @endif>{{$mstatusValue->marital_status_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="marital_status-error" class="error text-danger" for="marital_status"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 husband_name">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(80);
                                                        @endphp
                                                        <label for="exampleInputCity1">Husband's Name<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(80);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <input type="text" class="form-control alpha" id="husband_name" name="husband_name" placeholder="Husband's Name" value="{{$pensionerDetails->husband_name}}" @if($fieldStatus) readonly @endif>
                                                        <label id="husband_name-error" class="error text-danger" for="husband_name"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(8);
                                                        @endphp
                                                        <label>Religion<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(80);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <select class="js-example-basic-single form-control" id="religion" name="religion">
                                                            <option value="">Select Religion</option>
                                                            @foreach($religions as $list)
                                                            <option value="{{$list->id}}" @if($list->id == $pensionerDetails->religion_id) {{'selected'}} @endif @if($fieldStatus && $list->id != $pensionerDetails->religion_id) disabled @endif>{{$list->religion_name}}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <label id="religion-error" class="error text-danger" for="religion"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(9);
                                                        @endphp
                                                        <label for="exampleInputEmail3">PF A/C Type<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(9);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <select class="js-example-basic-single form-control" id="pf_acc_type" name="pf_acc_type">
                                                            <option value="">Select A/C Type</option>
                                                            @foreach($account_types as $account_type)
                                                                <option value="{{$account_type->id}}" @if($account_type->id == $pensionerDetails->pf_account_type_id) {{'selected'}} @endif @if($fieldStatus && $account_type->id != $pensionerDetails->pf_account_type_id) disabled @endif>{{$account_type->account_type}}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <label id="pf_acc_type-error" class="error text-danger" for="pf_acc_type"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(79);
                                                        @endphp
                                                        <label for="exampleInputEmail3">PF A/C No.(also specify the previous EPF/ CPF/ GPF A/C No. if any)<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(79);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <input type="text" class="form-control only_number" id="pf_acno" name="pf_acno" maxlength="5" placeholder="PF A/C No" value="{{$pensionerDetails->pf_account_no}}"@if($fieldStatus) readonly @endif>
                                                        <label id="pf_acno-error" class="error text-danger" for="pf_acno"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(10);
                                                        @endphp
                                                        <label>Name of the Office / Dept. last Served <span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(10);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <select class="js-example-basic-single form-control" id="name_of_office_dept" name="name_of_office_dept">
                                                            <option value="">Select Office</option>
                                                             @foreach($office_last_served as $list)
                                                                 <option value="{{$list->id}}" @if($list->id == $pensionerDetails->optcl_unit_id) {{'selected'}} @endif @if($fieldStatus && $list->id != $pensionerDetails->optcl_unit_id) disabled @endif>{{$list->unit_name}}</option>
                                                             @endforeach
                                                        </select>
                                                        <label id="name_of_office_dept-error" class="error text-danger" for="name_of_office_dept"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(11);
                                                        @endphp
                                                        <label class="">Date of Birth<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(11);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <div id="inline-datepicker" class="input-group date">
                                                            <input type="text" class="form-control" id="dob" name="dob" readonly value="{{$pensionerDetails->date_of_birth}}"@if($fieldStatus) readonly @endif>
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="dob-error" class="error text-danger" for="dob"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(12);
                                                        @endphp
                                                        <label class="">Date of Joining in Service<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(12);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <div id="datepicker-joining" class="input-group date">
                                                            <input type="text" class="form-control @if($fieldStatus) datepickerClass @endif" readonly autocomplete="off" id="doj" name="doj" value="{{$pensionerDetails->date_of_joining}}">
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="doj-error" class="error text-danger" for="doj"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @php 
                                                          $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(13);
                                                        @endphp
                                                        <label class="">Date of Retirement<span class="text-danger">*</span></label>
                                                        @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(13);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <div id="datepicker-popup" class="input-group date">
                                                            <input type="text" class="form-control" readonly id="dor" name="dor" value="{{$pensionerDetails->date_of_retirement}}">
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="dor-error" class="error text-danger ml-1" for="dor"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" name="submit" class="btn btn-primary btn-next mr-2" id="submit_form_1">NEXT</button>
                                                </div>                                                
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
              


 @endsection
 @section('page-script')

  <script type="text/javascript">
    $(document).ready(function() {
        $('.datepickerClass').datepicker({
            autoclose: true,
        });
        // for block letter in name field
       $("#name").on("keyup",function(){
        this.value = this.value.toUpperCase();
       }); 
        
        $('.husband_name').hide();

        $('#gender').on('change',function() {
            if ($(this).val() == 2) {
                //debugger
                $('.husband_name').show();
            } else {
                $('.husband_name').hide();
            }
        });

        $("#marital_status").on("change",function(){
            var mStatus = $(this).val();
            if(mStatus == 2){
                $(".husband_name").addClass("d-none");
            }else{
                $(".husband_name").removeClass("d-none");
            }
        });       

        $('.btn-next').click(function() {
            //debugger
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });

        $('.btn-prev').click(function() {
            //debugger
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });

        $('#father_name').keyup(function () { 
          this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $('#husband_name').keyup(function () { 
          this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $.validator.addMethod("onlyNumber", function (value, element) {
            return this.optional(element) || /^[0-9\s-]*$/.test(value);
        }, "Please use only numbers.");

        $("#pension_form").validate({
            rules: {
                emp_code: {
                    required: true,
                    onlyNumber: true,
                    minlength: 5,
                    maxlength: 5
                },
                designation: {
                    required: true,
                },
                marital_status:{
                    required: true,
                },
                religion:{
                    required: true,
                },
                aadhaar_no: {
                    required: true,
                    onlyNumber: true,
                    minlength: 12,
                    maxlength: 12
                },
                name: {
                    required: true,
                    minlength: 4,
                    maxlength: 50
                },
                father_name: {
                    required: true,
                    minlength: 4,
                    maxlength: 50
                },
                gender : {                    
                    required: true,
                },
                husband_name: {
                    required: function(element){
                        var gender = $("#gender").val();
                        var marital_status = $("#gender").val();
                        if(gender == 2 && marital_status == 2){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    minlength: 4,
                    maxlength: 50
                },
                pf_acc_type: {
                    required: true,
                },
                pf_acno: {
                    required: true,
                    onlyNumber: true,
                    minlength: 5,
                    maxlength: 5
                },
                name_of_office_dept: {
                    required: true,
                },
                dob: {
                    required: true,
                },
                doj: {
                    required: true,
                },
                dor: {
                    required: true,
                }
            },
            messages: {
                emp_code: {                    
                    required: 'Please enter employee code',
                    minlength: 'Employee Code minimum of 5 digits',
                    maxlength: 'Employee Code maximum upto 5 digits'
                },
                designation: {
                    required: 'Please select designation',
                },
                marital_status:{
                    required: 'Please select marital status',
                },
                religion:{
                    required: 'Please select religion',
                },
                aadhaar_no: {
                    required: 'Please enter aadhaar no',
                    minlength: 'Aadhaar No minimum of 12 digits',
                    maxlength: 'Aadhaar No maximum upto 12 digits'
                },
                name: {
                    required: 'Please enter name',
                    minlength: 'Name should be minimum of 4 Chars.',
                    maxlength: 'Name should be maximum upto 50 Chars.'
                },
                father_name: {
                    required: 'Please enter father name',
                    minlength: 'Father Name should be minimum of 4 Chars.',
                    maxlength: 'Father Name should be maximum upto 50 Chars.'
                },
                gender : {                    
                    required: 'Please select gender',
                },
                husband_name: {
                    required: 'Please enter husband name',
                    minlength: 'Husband name should be minimum of 4 chars.',
                    maxlength: 'Husband name should be maximum upto 50 chars.'
                },
                pf_acc_type: {
                    required: 'Please select a/c type',
                },
                pf_acno: {
                    required: 'Please enter PF a/c no.',
                    minlength: 'Pf Ac No  should be minimum  5 digits.',
                    maxlength: 'Pf Ac No should be maximum 5 digits.'
                },
                name_of_office_dept: {
                    required: 'Please select office/ dept served',
                },
                dob: {
                    required: 'Please select date of birth'
                },
                doj: {
                    required: 'Please select date of joining service'
                },
                dor: {
                    required: 'Please select date of retirement'
                }
            },
            submitHandler: function(form, event) { 
            $('.page-loader').addClass('d-flex');
              event.preventDefault();
              var formData = new FormData(form);
              //$("#logid").prop('disabled',true);
              $.ajax({
                  type:'POST',
                  url:'{{ route("family_pensioner_form_page_resubmission") }}',
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
                      }else if(response['fFormStatus']){
                        location.href = "{{route('nominee_application_view_details')}}";
                      }else{
                        // Success
                        //location.reload();
                        location.href = "{{route('family_pensioner_personal_resubmit_page')}}";
                      }
                  }
              });
            },
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-success');
                $(element).addClass('form-control-danger');
            }
        });

       

    });

    
    
    </script>


@endsection