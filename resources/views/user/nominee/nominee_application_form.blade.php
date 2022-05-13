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
                                <a class="nav-link active-tab" id="home-tab" data-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="true">1. FAMILY PENSION FORM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="" role="tab" aria-controls="profile-1" aria-selected="false">2. FAMILY PENSIONER</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nominee-tab" data-toggle="tab" href="" role="tab" aria-controls="nominee-tab" aria-selected="false">3. NOMINEES</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="" role="tab" aria-controls="contact-1" aria-selected="false">4. LIST OF DOCUMENTS</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                                <div class="media">
                                    <div class="media-body">
                                        <h4 class="mt-0 text-center">FAMILY PENSION FORM (Provisional / Final / Revised) PART - I</h4>
                                        <h6 class="text-center-normal">(To be completed by the Employee/Applicant for sanction & payment of family pension, Death-cum-retirement Gratuity, Service Gratuity & Commuted value of pension)</h6>
                                        <hr>
                                        <h6 class="text-center-normal">PARTICULARS OF EMPLOYEE/EX-EMPLOYEE</h6>
                                        <hr>
                                        <br />
                                
                                        <form  class="forms-sample" autocomplete="off" id="nominee_form" action="" method="post"    enctype="multipart/form-data">
                                           @csrf
                                              <div class="row form_1_">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Employee No/Code<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control anns" id="emp_code" name="emp_code" value="{{ isset($user_details->employee_code) ? $user_details->employee_code: ''   }}" placeholder="Employee No/Codes" readonly>
                                                        <label id="emp_code-error" class="error text-danger" for="emp_code"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Aadhaar No<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="aadhaar_no" name="aadhaar_no" value="{{ isset($employee_master->aadhaar_no) ? $employee_master->aadhaar_no: ''}}" placeholder="Enter Aadhaar No" value="" minlength="12" maxlength="12" readonly>
                                                        <label id="aadhaar_no-error" class="error text-danger" for="aadhaar_no"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">Name (In Block Letter)<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control alpha" id="name" name="name" placeholder="Name" value="{{strtoupper( isset($employee_master->employee_name) ? $employee_master->employee_name: '')}}">
                                                        <label id="name-error" class="error text-danger" for="name"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Designation<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="designation" name="designation">
                                                            <option value="">Select Designation</option>
                                                            @foreach($pensioner_designation as $list)
                                                            <option value="{{$list->id}}" @if(isset($employee_master->designation_id) && $employee_master->designation_id == $list->id) selected @endif>{{$list->designation_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="designation-error" class="error text-danger" for="designation"></label>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputCity1">Father's Name<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control alpha" id="father_name" name="father_name" placeholder="Father's Name" value="{{ isset($employee_master->father_name) ? $employee_master->father_name: ''}}">
                                                        <label id="father_name-error" class="error text-danger" for="father_name"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Gender<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" name="gender" id="gender">
                                                            <option value="">Select Gender</option>
                                                            @foreach($genders as $gender)
                                                                <option value="{{$gender->id}}" @if(isset($employee_master->gender_id) && $employee_master->gender_id == $gender->id) selected @endif>{{$gender->gender_name}}</option>
                                                            @endforeach  
                                                        </select>
                                                        <label id="gender-error" class="error text-danger" for="gender"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Marital Status<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" name="marital_status" id="marital_status">
                                                            <option value="">Select Status</option>
                                                            @foreach($mstatus as $mstatusValue)
                                                                <option value="{{$mstatusValue->id}}" @if(isset($employee_master->marital_status_id) && $employee_master->marital_status_id == $mstatusValue->id) selected @endif>{{$mstatusValue->marital_status_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="marital_status-error" class="error text-danger" for="marital_status"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 husband_name">
                                                    <div class="form-group">
                                                        <label for="exampleInputCity1">Husband's Name<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control alpha" id="husband_name" name="husband_name" placeholder="Husband's Name" value="{{ isset($employee_master->husband_name) ? $employee_master->husband_name: ''}}">
                                                        <label id="husband_name-error" class="error text-danger" for="husband_name"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Religion<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="religion" name="religion">
                                                            <option value="">Select Religion</option>
                                                            @foreach($religions as $list)
                                                            <option value="{{$list->id}}" @if(isset($employee_master->religion_id) && $employee_master->religion_id == $list->id) selected @endif>{{$list->religion_name}}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <label id="religion-error" class="error text-danger" for="religion"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">PF A/C Type<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="pf_acc_type" name="pf_acc_type">
                                                            <option value="">Select A/C Type</option>
                                                            @foreach($account_types as $account_type)
                                                                <option value="{{$account_type->id}}" @if(isset($employee_master->pf_account_type_id) && $employee_master->pf_account_type_id == $account_type->id) selected @endif>{{$account_type->account_type}}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <label id="pf_acc_type-error" class="error text-danger" for="pf_acc_type"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">PF A/C No.(also specify the previous EPF/ CPF/ GPF A/C No. if any)<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="pf_acno" name="pf_acno" maxlength="5" placeholder="PF A/C No" value="{{ isset($employee_master->pf_account_no) ? $employee_master->pf_account_no: ''}}">
                                                        <label id="pf_acno-error" class="error text-danger" for="pf_acno"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Name of the Office / Dept. last Served <span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="name_of_office_dept" name="name_of_office_dept">
                                                            <option value="">Select Office</option>
                                                             @foreach($office_last_served as $list)
                                                                 <option value="{{$list->id}}" @if(isset($employee_master->optcl_unit_id) && $employee_master->optcl_unit_id == $list->id) selected @endif>{{$list->unit_name}}</option>
                                                             @endforeach
                                                        </select>
                                                        <label id="name_of_office_dept-error" class="error text-danger" for="name_of_office_dept"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="">Date of Birth<span class="text-danger">*</span></label>
                                                        <div id="inline-datepicker" class="input-group date ">
                                                            <input type="text" class="form-control" id="dob" name="dob" readonly value="{{ isset($employee_master->date_of_birth) ? date('d/m/Y',strtotime($employee_master->date_of_birth)): ''}}">
                                                            <span class="input-group-addon input-group-append border-left">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="dob-error" class="error text-danger" for="dob"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="">Date of Joining in Service<span class="text-danger">*</span></label>
                                                        <div id="datepicker-joining" class="input-group date ">
                                                            <input type="text" class="form-control datepickerClass" readonly autocomplete="off" id="doj" name="doj" value="{{ isset($employee_master->date_of_joining) ? date('d/m/Y',strtotime($employee_master->date_of_joining)): ''}}">
                                                            <span class="input-group-addon input-group-append border-left">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="doj-error" class="error text-danger" for="doj"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="">Date of Retirement<span class="text-danger">*</span></label>
                                                        <div id="datepicker-popup" class="input-group date">
                                                            <input type="text" class="form-control" readonly id="dor" name="dor" value="{{ isset($employee_master->date_of_retirement) ? date('d/m/Y',strtotime($employee_master->date_of_retirement)): ''}}">
                                                            <span class="input-group-addon input-group-append border-left">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="dor-error" class="error text-danger " for="dor"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="button" name="save_as_draft" class="btn btn-success btn-next float-right" id="save_as_draft">Save AS Draft</button>
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
        
        $("#dob").on('change', function() {
            $(this).valid();
            $('#doj').val('');
            $('#dor').val('');
        });

        // for block letter in name field
       $("#name").on("keyup",function(){
        this.value = this.value.toUpperCase();
       }); 
        
        $('.husband_name').hide();

        $('#gender').on('change',function() {
            if($(this).val() != '') {
                $('#gender-error').text('');
                if ($(this).val() == 2) {
                    //debugger
                    $('.husband_name').show();
                } else {
                    $('.husband_name').hide();
                }
            }
        });

        $("#marital_status").on("change",function(){
            var mStatus = $(this).val();
            if(mStatus != '') {
                $('#marital_status-error').text('');
                if(mStatus == 2){
                    $(".husband_name").addClass("d-none");
                }else{
                    $(".husband_name").removeClass("d-none");
                }
            }
        });

        $(document).on('change', '#designation', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            console.log(sid);
            if(sid != '') {
                $('#designation-error').text('');
            }
        });

        $(document).on('change', '#pf_acc_type', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');

            if(sid != '') {
                $('#pf_acc_type-error').text('');
            }
        });

       $(document).on('change', '#religion', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');

            if(sid != '') {
                $('#religion-error').text('');
            }
        });

       $(document).on('change', '#name_of_office_dept', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');

            if(sid != '') {
                $('#name_of_office_dept-error').text('');
            }
        });

        $('.btn-next').click(function() {
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });

        $('.btn-prev').click(function() {
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

        $("#save_as_draft").on('click',function(e){
            e.preventDefault();
            //$('.pension_form_class').attr('id', 'pension_form_save_as_draft');

            //$('#pension_form_save_as_draft').submit();
            //console.log($('pension_form').serialize());
            //$("#pension_form").submit();
            var emp_code = $("#emp_code").val();
            var aadhaar_no = $("#aadhaar_no").val();
            var name = $("#name").val();
            var designation = $("#designation").val();
            var father_name = $("#father_name").val();
            var gender = $("#gender").val();
            var marital_status = $("#marital_status").val();
            var husband_name = $("#husband_name").val();
            var religion = $("#religion").val();
            var pf_acc_type = $("#pf_acc_type").val();
            var pf_acno = $("#pf_acno").val();
            var name_of_office_dept = $("#name_of_office_dept").val();
            var dob = $("#dob").val();
            var doj = $("#doj").val();
            var dor = $("#dor").val();
            //console.log(emp_code);

            $('.page-loader').addClass('d-flex');
            $.post('{{ route("save_as_draft_nominee_form") }}',{
                "_token": "{{ csrf_token() }}",
                "emp_code":emp_code,
                "aadhaar_no":aadhaar_no,
                "name":name,
                "designation":designation,
                "father_name":father_name,
                "gender":gender,
                "marital_status":marital_status,
                "husband_name":husband_name,
                "religion":religion,
                "pf_acc_type":pf_acc_type,
                "pf_acno":pf_acno,
                "name_of_office_dept":name_of_office_dept,
                "dob":dob,
                "doj":doj,
                "dor":dor,
            },function(response){
                $('.page-loader').removeClass('d-flex');
                if(response.status == 'success'){
                    location.reload();
                }
            });
        });
        

        $("#nominee_form").validate({
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
                    required: {
                        depends:function() {
                            $(this).val($.trim($(this).val()));
                            return true; 
                        }
                    },
                    minlength: 4,
                    maxlength: 50
                },
                father_name: {
                    required: {
                        depends:function() {
                            $(this).val($.trim($(this).val()));
                            return true; 
                        }
                    },
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
                    required: 'Please select A/C type',
                },
                pf_acno: {
                    required: 'Please enter PF A/C no.',
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
                  url:'{{ route("save_nominee_form") }}',
                  data: formData,
                  dataType: 'JSON',
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    $('.page-loader').removeClass('d-flex');
                        console.log('test');
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
                        location.href = "{{route('nominee_form')}}";
                      }else{
                        // Success
                        //location.reload();
                        location.href = "{{route('nominee_family_pensioner_form')}}";
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