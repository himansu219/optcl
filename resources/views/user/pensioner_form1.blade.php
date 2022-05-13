@extends('UserView/layout.layout')

@section('container')


                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                <h4 class="card-title align_center">ODISHA POWER TRANSMISSION CORPORATION LTD.</h4>
                                    <h5 class="card-description align_center">(A Govt. of Odisha Undertaking)</h5>
                                    <h5 class="card-description align_center">Gridco Pension Trust Fund</h5>
                                    <p class="card-description align_center">Regd. Off – Janpath, Bhubaneswar – 751022</p>
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active step_active" id="home-tab" data-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="true">1. PENSION/FAMILY PENSION FORM</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link step_inactive" id="profile-tab" data-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="false">2. PERSONAL DETAILS</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link step_inactive" id="nominee-tab" data-toggle="tab" href="#nominee-1" role="tab" aria-controls="nominee-tab" aria-selected="false">3. NOMINEES</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link step_inactive" id="contact-tab" data-toggle="tab" href="#contact-1" role="tab" aria-controls="contact-1" aria-selected="false">4. LIST OF DOCUMENTS</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">

                                    <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="media">
                                                <div class="media-body">
                                                    <h4 class="mt-0 text-center">PENSION/FAMILY PENSION FORM (Provisional / Final / Revised) PART - I</h4>
                                                    <h6 class="text-center-normal">(To be completed by the Employee/Applicant for sanction & payment of Pension/Family pension, Death-cum-retirement Gratuity, Service Gratuity & Commuted value of pension)</h6>
                                                    <hr>
                                                    <h6 class="text-center-normal">PARTICULARS OF EMPLOYEE/EX-EMPLOYEE</h6>
                                                    <hr>
                                                    <br />
                                            
                                                    <form  class="forms-sample" autocomplete="off" id="form_1" action="{{URL('form_1_submit')}}" method="post"    enctype="multipart/form-data">
                                                       @csrf
                                                          <div class="row form_1_">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword4">Employee No/Code</label>
                                                                    <input type="text" class="form-control" id="emp_code" name="emp_code" value="{{$result->employee_id}}" placeholder="Employee No/Codes" >
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword4">Aadhaar No</label>
                                                                    <input type="text" class="form-control" id="aadhaar_no" name="aadhaar_no" value="{{$result->aadhaar_no}}" placeholder="Enter Aadhaar No" value="" minlength="12" maxlength="12">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputName1">Name (In Block Letter)</label>
                                                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{$result->pensioner_name}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Designation</label>
                                                                    <select class="js-example-basic-single" style="width:100%" id="designation" name="designation">
                                                                       @foreach($designation as $list)
                                                                        <option value="{{$list->code}}">{{$list->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputCity1">Father's Name</label>
                                                                    <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Father's Name">
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-sm-3 col-form-label">Gender</label>
                                                                    <div class="col-sm-4">
                                                                        <div class="form-radio">
                                                                            <label class="form-check-label">
                                                                                <input type="radio" class="form-check-input" name="gender" id="gender_male" value="male" checked>
                                                                                Male
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-5">
                                                                        <div class="form-radio">
                                                                            <label class="form-check-label">
                                                                                <input type="radio" class="form-check-input" name="gender" id="gender_female" value="female">
                                                                                Female
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Marital Status</label>
                                                                    <select class="js-example-basic-single" style="width:100%" name="marital_status" id="marital_status">
                                                                        <option value="married">Married</option>
                                                                        <option value="unmarried">Unmarried</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group husband_name">
                                                                    <label for="exampleInputCity1">Husband's Name</label>
                                                                    <input type="text" class="form-control" id="husband_name" name="husband_name" placeholder="Husband's Name">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Religion</label>
                                                                    <select class="js-example-basic-single" style="width:100%" id="religion" name="religion">
                                                                        <!-- <option value="">Select Religion</option> -->
                                                                        @foreach($religion as $list)
                                                                        <option value="{{$list->code}}">{{$list->name}}</option>
                                                                        @endforeach
                                                                      
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputEmail3">PF A/C No.(also specify the previous EPF/ CPF/ GPF A/C No. if any)</label>
                                                                    <input type="text" class="form-control" id="pf_acno" name="pf_acno" placeholder="PF A/C No">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Name of the Office / Dept. last Served </label>
                                                                    <select class="js-example-basic-single" style="width:100%" id="name_of_office_dept" name="name_of_office_dept">
                                                                         @foreach($office_last as $list)
                                                                             <option value="{{$list->code}}">{{$list->name}}</option>
                                                                         @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Date of Joining Service</label>
                                                                    <div id="datepicker-joining" class="input-group date datepicker ">
                                                                        <input type="text" class="form-control datepickerClass" autocomplete="off" id="doj" name="doj">
                                                                        <span class="input-group-addon input-group-append border-left">
                                                                            <span class="mdi mdi-calendar input-group-text"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Date of Retirement</label>
                                                                    <div id="datepicker-popup" class="input-group date datepicker">
                                                                        <input type="text" class="form-control" id="dor" name="dor">
                                                                        <span class="input-group-addon input-group-append border-left">
                                                                            <span class="mdi mdi-calendar input-group-text"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleTextarea1">Date of Death (wherever applicable)</label>
                                                                    <div id="inline-datepicker" class="input-group date datepicker">
                                                                        <input type="text" class="form-control" id="dod" name="dod">
                                                                        <span class="input-group-addon input-group-append border-left">
                                                                            <span class="mdi mdi-calendar input-group-text"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" name="submit" class="btn btn-success mr-2 btn-next" id="submit_form_1">SAVE</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    <div class="tab-pane fade show" id="nominee-tab" role="tabpanel" aria-labelledby="nominee-tab">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
              


 @endsection
 @section('js')

  <script type="text/javascript">
    $(document).ready(function() {
        $('.datepickerClass').datepicker();
        $('ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
            //$(this).closest('.nav-link').removeClass("step_active").addClass('step_inactive');
        });

        // for block letter in name field
       $("#name").on("keyup",function(){
        this.value = this.value.toUpperCase();
       }); 
        
        $('.husband_name').hide();

        $('input[type="radio"]').click(function() {
            debugger;
            if ($(this).is(':checked') && $(this).val() == 'female') {
                //debugger
                $('.husband_name').show();
            } else {
                $('.husband_name').hide();
            }
        });

        $("#marital_status").on("change",function(){
            var mStatus = $(this).val();
            if(mStatus == "unmarried"){
                $(".husband_name").addClass("d-none");
            }else{
                $(".husband_name").removeClass("d-none");
            }
        });

       

        $('.btn-next').click(function() {
            debugger
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });

        $('.btn-prev').click(function() {
            debugger
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });

        // $('.form_1_').hide();
        // $('#form_1_submit').hide();

        // $('#fetch_emp_info').click(function() {
        //     $('.form_1_').show();
        //     $('#form_1_submit').show();
        //     $('#fetch_emp_code').hide();
        //     $('#fetch_emp_info').hide();
        //     $('#fetch_emp_info_label').hide();
        // });

        
      
        
        
        //    $('#name').keyup(function () { 
        //   this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        //    });
           $('#father_name').keyup(function () { 
          this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
           });
           $('#husband_name').keyup(function () { 
          this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
           });
         

           $.validator.addMethod("onlyNumber", function (value, element) {
            return this.optional(element) || /^[0-9\s-]*$/.test(value);
        }, "Please use only numbers.");

        $("#form_1").validate({
            rules: {
                emp_code: {
                    required: true,
                    onlyNumber: true,
                    minlength: 5,
                    maxlength: 5
                },
                aadhaar_no: {
                    required: true,
                    onlyNumber: true,
                    minlength: 12,
                    maxlength: 12
                },
                name: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                },
                father_name: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                },
                husband_name: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                },
                pf_acno: {
                    required: true,
                    onlyNumber: true,
                    minlength: 10,
                    maxlength: 12
                },
                doj: {
                    required: true
                },
                dor: {
                    required: true
                },
                dod: {
                    required: true
                }
            },
            messages: {
                emp_code: {                    
                    required: 'Please enter employee code',
                    minlength: 'Employee Code minimum of 5 digits',
                    maxlength: 'Employee Code maximum upto 5 digits'
                },
                aadhaar_no: {
                    required: 'Please enter Aadhaar No',
                    minlength: 'Aadhaar No minimum of 12 digits',
                    maxlength: 'Aadhaar No maximum upto 12 digits'

                },
                name: {
                    required: 'Please enter Name',
                    minlength: 'Name should be minimum of 5 Chars.',
                    maxlength: 'Name should be maximum upto 50 Chars.'
                },
                father_name: {
                    required: 'Please enter father name',
                    minlength: 'Father Name should be minimum of 5 Chars.',
                    maxlength: 'Father Name should be maximum upto 50 Chars.'
                },
                husband_name: {
                    required: 'Please enter Husband name',
                    minlength: 'Husband Name should be minimum of 5 Chars.',
                    maxlength: 'Husband Name should be maximum upto 50 Chars.'
                },
                pf_acno: {
                    required: 'Please enter PF Account No.',
                    minlength: 'Pf Ac No  should be minimum  10 digits.',
                    maxlength: 'Pf Ac No should be maximum 12 digits.'
                },
                doj: {
                    required: 'Please enter date of joining service.'
                },
                dor: {
                    required: 'Please enter date of retirement.'
                },
                dod: {
                    required: 'Please enter date of Date of Death (wherever applicable).'
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