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
                                            <a class="nav-link active step_inactive" id="home-tab" data-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="false">1. PENSION/FAMILY PENSION FORM</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link step_active" id="profile-tab" data-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="true">2. PERSONAL DETAILS</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link step_inactive" id="nominee-tab" data-toggle="tab" href="#nominee-1" role="tab" aria-controls="nominee-tab" aria-selected="false">3. NOMINEES</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link step_inactive" id="contact-tab" data-toggle="tab" href="#contact-1" role="tab" aria-controls="contact-1" aria-selected="false">4. LIST OF DOCUMENTS</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">

                                    <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="profile-tab">
                                            <!-- <div class="media"> -->
                                            <!-- <img class="mr-3 w-25 rounded" src="../../../../images/faces/face12.jpg" alt="sample image"> -->
                                            <div class="media-body">
                                                <h4 class="mt-0 text-center">PERSONAL DETAILS OF THE APPLICANT FOR PENSION / FAMILY PENSION</h4>
                                                <h4 class="text-center">PART – II</h4>
                                                <br />
                                                <h6>Permanent Address</h6>
                                                <form action="" method="POST" id="form_2" class="forms-sample" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputPassword4">At</label>
                                                                <input type="text" class="form-control" id="atpost" name="atpost" placeholder="Enter At here" >
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputPassword4">Post</label>
                                                                <input type="text" class="form-control" id="postoffice" name="postoffice" placeholder="Enter Post here" >
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputName1">PIN No</label>
                                                                <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter Pin no here" maxlength="6" >
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Country </label>
                                                                <select class="js-example-basic-single" id="country" name="country" style="width:100%">
                                                                    <option value="">Select Country</option>
                                                                    @foreach($country as $list)
                                                                    <option value="{{$list->id}}">{{$list->country_name}}</option>
                                                                    @endforeach
                                                                    
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>State</label>
                                                                <select class="js-example-basic-single" id="state" name="state" style="width:100%">
                                                                    <option value="">Select State</option>
                                                                    
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>District </label>
                                                                <select class="js-example-basic-single" id="district" name="district" style="width:100%">
                                                                    <option value="">Select District</option>
                                                                   
                                                                </select>
                                                            </div>
                                                            <h6>Present Address for Communication After Retirement </h6>
                                                            <br>
                                                            <div class="form-group">
                                                                <label for="exampleInputCity1">Same As Above</label>
                                                                <input type="checkbox" class="form-control" id="same_as_above" style="height:20px;margin-top: -22px;
                                                                        margin-left: -116px;">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputPassword4">At</label>
                                                                <input type="text" class="form-control" id="atpost1" name="atpost1" placeholder="Enter At here" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputPassword4">Post</label>
                                                                <input type="text" class="form-control" id="postoffice1" name="postoffice1" placeholder="Enter Post here" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputName1">PIN No</label>
                                                                <input type="text" class="form-control" id="pincode1" name="pincode1" placeholder="Enter Pin no here" maxlength="6" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Country </label>
                                                                <select class="js-example-basic-single" style="width:100%" id="country1" name="country1">
                                                                    <option value="">Select Country</option>
                                                                    @foreach($country as $list)
                                                                    <option value="{{$list->id}}">{{$list->country_name}}</option>
                                                                    @endforeach
                                                                    
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>State</label>
                                                                <select class="js-example-basic-single" style="width:100%" id="state1" name="state1">
                                                                    <option value="">Select State</option>
                                                                   
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>District </label>
                                                                <select class="js-example-basic-single" style="width:100%" id="district1" name="district1">
                                                                    <option value="">Select District</option>
                                                                    
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputName1">Telephone No with STD Code (If Any)</label>
                                                                <input type="text" class="form-control" id="telephone_no" name="telephone_no" placeholder="Enter Telephone no here" minlength="10" maxlength="10">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputName1">Mobile No</label>
                                                                <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter Mobile no here" minlength="10" maxlength="10">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputName1">Email Address (If Any)</label>
                                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email here" >
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputName1">PAN No</label>
                                                                <input type="text" class="form-control" id="pan_no" name="pan_no" placeholder="Enter pan no here" >
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail3">Savings Bank A/C No.(Single or Joint Account with Spouse)</label>
                                                                <input type="text" class="form-control" id="saving_bank_ac_no" name="saving_bank_ac_no" placeholder=" Saving A/C No">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Name of the Bank </label>
                                                                <select class="js-example-basic-single" style="width:100%" id="bank_name" name="bank_name">
                                                                    <option value="AL">State Bank Of India (SBI)</option>
                                                                    <option value="WY">ICIC</option>
                                                                    <option value="AM">Bank Of Baroda</option>
                                                                    <option value="CA">Punjab National Bank</option>
                                                                    <option value="CA">HDFC Bank</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Name Address of the Branch </label>
                                                                <select class="js-example-basic-single" style="width:100%" id="branch_name_address" name="branch_name_address">
                                                                    <option value="AL">Bhubaneswar</option>
                                                                    <option value="WY">Cuttack</option>
                                                                    <option value="AM">Keonjhar</option>
                                                                    <option value="CA">Jajpur</option>
                                                                    <option value="CA">Ganjam</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail3">IFSC Code</label>
                                                                <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" placeholder=" Enter ifsc code" value="SBIN0567656">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail3">MICR Code</label>
                                                                <input type="text" class="form-control" id="micr_code" name="micr_code" placeholder=" Enter micr code">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail3">Amount of Basic Pension at the time of Retirement</label>
                                                                <input type="text" class="form-control" id="basic_pay" name="basic_pay" placeholder="Enter Last Basic Pay">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Name of the Unit (where life certificate & income tax declaration to be submitted) </label>
                                                                <select class="js-example-basic-single" style="width:100%" id="name_of_the_unit" name="name_of_the_unit">
                                                                    <option value="AL">D.D.O Hqrs. OPTCL, Bhubaneswar</option>
                                                                    <option value="WY">EHT(O&M) Circle, Cuttack</option>
                                                                    <option value="AM">EHT(O&M) Division, Choudwar</option>
                                                                    <option value="CA">E&MR Division, Bhubaneswar</option>
                                                                    <option value="RU">EHT(O&M) Circle, Berhampur</option>
                                                                    <option value="AM">EHT(O&M)Division, BhanjaNagar</option>
                                                                    <option value="CA">EHT(O&M) Division, Theruvali</option>
                                                                    <option value="RU">EHT(O&M) Division, Jayanagar</option>
                                                                    <option value="AM">EHT(O&M) Circle, Burla</option>
                                                                    <option value="CA">EHT(O&M) Division, Bolangir</option>
                                                                    <option value="RU">EHT(O&M)Division,Balasore</option>
                                                                    <option value="RU">EHT(C ) Division, Jeypore</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-12 col-form-label">Particulars of previous civil service if any and amount and nature of any pension or gratuity received.</label>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <div class="row" style="margin-left: 0px;">
                                                                        <div class="col-sm-4">
                                                                            <div class="form-radio">
                                                                                <label class="form-check-label">
                                                                                    <input type="radio" class="form-check-input" name="if_yes" id="if_yes" value="yes_chk">
                                                                                    Yes
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <div class="col-sm-4">
                                                                            <div class="form-radio">
                                                                                <label class="form-check-label">
                                                                                    <input type="radio" class="form-check-input" name="if_yes" id="if_nos" value="no_chk" checked="">
                                                                                    No
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="text" class="form-control civil_service" id="civil_service" name="civil_service" placeholder="Enter Particulars of previous civil service"><br>
                                                                <input type="text" class="form-control gratuiyty_recieved" id="gratuiyty_recieved" name="gratuiyty_recieved" placeholder="Enter amount and nature of any pension or gratuity received.">
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-12 col-form-label">Particulars of family pension if any Received / admissible form any other source to the retired employee and any members of his family</label>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <div class="row" style="margin-left: 0px;">
                                                                        <div class="col-sm-4">
                                                                            <div class="form-radio">
                                                                                <label class="form-check-label">
                                                                                    <input type="radio" class="form-check-input" name="addmissible" id="option_yes" value="option_yes">
                                                                                    Yes
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <div class="col-sm-4">
                                                                            <div class="form-radio">
                                                                                <label class="form-check-label">
                                                                                    <input type="radio" class="form-check-input" name="addmissible" id="option_no" value="option_no" checked="">
                                                                                    No
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="text" class="form-control" id="addmissble"  placeholder="Enter admissible form any other source to the retired employee"><br>
                                                                <label class="admissible_label">Members of his family</label>
                                                                <br />
                                                                <div class="row addmissible_family">
                                                                    <div class="col-sm-6">
                                                                        <select class="js-example-basic-single" style="width:100%" name="addmissible_family">
                                                                            <option value="AL">Son</option>
                                                                            <option value="WY">Daughter</option>
                                                                            <option value="WY">Father</option>
                                                                            <option value="WY">Mother</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" class="form-control" id="addmissible_family_name" name="addmissible_family_name" placeholder="Enter Name of member.">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-12 col-form-label">Whether Communication of pension to be made & percentage to be specified (not application for applicants for family pension)</label>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <div class="row" style="margin-left: 0px;">
                                                                        <div class="col-sm-4">
                                                                            <div class="form-radio">
                                                                                <label class="form-check-label">
                                                                                    <input type="radio" class="form-check-input" name="percentage" id="percentage_yes" value="percentage_yes">
                                                                                    Yes
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <div class="col-sm-4">
                                                                            <div class="form-radio">
                                                                                <label class="form-check-label">
                                                                                    <input type="radio" class="form-check-input" name="percentage" id="percentage_no" value="percentage_no" checked="">
                                                                                    No
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="text" class="form-control" name="percentage_no" id="percentage" placeholder="Enter % only">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-success mr-2 btn-prev">PREVIOUS</button>
                                                    <button type="submit" class="btn btn-success mr-2 btn-next">SAVE</button>
                                                </form>
                                            </div>
                                            <!-- </div> -->
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
 
   <!-- Custom js for this page-->
   <script type="text/javascript">
    $(document).ready(function() {
        
        $('ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
            //$(this).closest('.nav-link').removeClass("step_active").addClass('step_inactive');
        });
        

      

       

        $('input#same_as_above').click(function() {
            //debugger
            if ($("input#same_as_above:checked").length) {
                var at_post = $('#atpost').val();
                var postoffice = $('#postoffice').val();
                var pincode = $('#pincode').val();
                var country = $('#country').val();
                var state = $('#state').val();
                var district = $('#district').val();

                console.log(state);

                $('#atpost1').val(at_post);
                $('#postoffice1').val(postoffice);
                $('#pincode1').val(pincode);
                $('#country1').val(country).trigger('change');
                $('#state1').val(state).trigger('change');
                $('#state1').select2('val', state);
                $('#district1').val(district).trigger('change');

            }
        });
        

        $('.civil_service').hide();
        $('.gratuiyty_recieved').hide();

        $('input[name="if_yes"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 'yes_chk') {
                $('.civil_service').show();
                $('.gratuiyty_recieved').show();
            } else {
                $('.civil_service').hide();
                $('.gratuiyty_recieved').hide();
            }
        });


        $('#addmissble').hide();
        $('.admissible_label').hide();
        $('.addmissible_family').hide();

        $('input[name="addmissible"]').click(function() {
            debugger
            if ($(this).is(':checked') && $(this).val() == 'option_yes') {
                $('.admissible_label').show();
                $('#addmissble').show();
                $('.addmissible_family').show();
            } else {
                $('.admissible_label').hide();
                $('#addmissble').hide();
                $('.addmissible_family').hide();
            }
        });

        $('#percentage').hide();

        $('input[name="percentage"]').click(function() {
            debugger
            if ($(this).is(':checked') && $(this).val() == 'percentage_yes') {
                $('#percentage').show();
            } else {
                $('#percentage').hide();
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


        $('#saving_bank_ac_no').keyup(function () { 
          this.value = this.value.replace(/[^0-9\ ]/g,'');
           });
        $('#addmissible_family_name').keyup(function () { 
          this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
           });

        $.validator.addMethod("onlyNumber", function (value, element) {
            return this.optional(element) || /^[0-9\s-]*$/.test(value);
        }, "Please use only numbers");    

        $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s,/-]*$/.test(value);
        }, "Please use only letters, numbers and special characters(,/-).");

        $.validator.addMethod("onlyEmail", function (value, element) {
            return this.optional(element) || /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);
        }, "Please enter valid email id");

        $.validator.addMethod("panNo", function (value, element) {
            return this.optional(element) || /[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(value);
        }, "Please enter pan no correctly");   

        $("#form_2").validate({
            rules: {
                atpost: {
                    required: true,
                    addressReg: true,
                    minlength: 5,
                    maxlength: 100
                },
                postoffice: {
                    required: true,
                    addressReg: true,
                    minlength: 5,
                    maxlength: 50
                },
                pincode: {
                    required: true,
                    minlength: 6,
                    maxlength: 6,
                    onlyNumber: true
                },
                atpost1: {
                    required: true,
                    addressReg: true,
                    minlength: 5,
                    maxlength: 100
                },
                postoffice1: {
                    required: true,
                    addressReg: true,
                    minlength: 5,
                    maxlength: 50
                },
                pincode1: {
                    required: true,
                    minlength: 6,
                    maxlength: 6,
                    onlyNumber: true
                },
                telephone_no: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    onlyNumber: true
                },
                mobile_no: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    onlyNumber: true
                },
                email: {
                    required: true,
                    onlyEmail: true
                },
                pan_no: {
                    required: true,
                    panNo: true
                },
                saving_bank_ac_no: {
                    required: true,
                    minlength: 10,
                    maxlength: 11,
                    onlyNumber: true
                },
                basic_pay: {
                    required: true,
                    onlyNumber: true
                },
                civil_service: {
                    required: true
                },
                gratuiyty_recieved: {
                    required: true,
                    onlyNumber: true
                },
                addmissible_family_name: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                }
            },
            messages: {
                atpost: {                    
                    required: 'Please enter At',
                    minlength: 'At minimum 5 characters',
                    maxlength: 'At maximum 100 characters'
                },
                postoffice: {
                    required: 'Please enter post office',
                    minlength: 'Post office minimum 5 characters',
                    maxlength: 'Post office maximum 50 characters'

                },
                pincode: {
                    required: 'Please enter pin code',
                    minlength: 'Pin code must be 6 digits',
                    maxlength: 'Pin code must be 6 digits'
                },
                atpost1: {                    
                    required: 'Please enter At',
                    minlength: 'At minimum 5 characters',
                    maxlength: 'At maximum 100 characters'
                },
                postoffice1: {
                    required: 'Please enter post office',
                    minlength: 'Post office minimum 5 characters',
                    maxlength: 'Post office maximum 50 characters'

                },
                pincode1: {
                    required: 'Please enter pin code',
                    minlength: 'Pin code must be 6 digits',
                    maxlength: 'Pin code must be 6 digits'
                },
                telephone_no: {
                    required: 'Please enter telephone no',
                    minlength: 'Telephone no must be 10 digits',
                    maxlength: 'Telephone no must be 10 digits'

                },
                mobile_no: {
                    required: 'Please enter mobile no',
                    minlength: 'Mobile no must be 10 digits',
                    maxlength: 'Mobile no must be 10 digits'
                },
                email: {
                    required: 'Please enter email id'
                    
                },
                pan_no: {
                    required: 'Please enter pan no'
                },
                saving_bank_ac_no: {
                    required: 'Please enter bank acoount no',
                    minlength: 'Bank account no minimum 10 digits',
                    maxlength: 'Bank account no maximum 11 digits'
                },
                basic_pay: {
                    required: 'Please enter last basic pay'
                },
                civil_service: {
                    required: 'Please enter perticular civil service name'
                },
                gratuiyty_recieved: {
                    required: 'Please enter amount and nature of any pension or gratuity received'
                },
                addmissible_family_name: {
                    required: 'Please enter name of member',
                    minlength: 'Member name minimum 5 characters',
                    maxlength: 'Member name maximum 50 characters'
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

<script>
		jQuery(document).ready(function(){
			jQuery('#country').change(function(){
				let cid=jQuery(this).val();
				jQuery('#state').html('<option value="">Select State</option>')
				jQuery.ajax({
					url:'/getState',
					type:'post',
					data:'cid='+cid+'&_token={{csrf_token()}}',
					success:function(result){
                        jQuery('#state').html(result);
                        jQuery('#state1').html(result);
					}
				});
			});
			
			jQuery('#state').change(function(){
				let sid=jQuery(this).val();
				jQuery.ajax({
					url:'/getDistrict',
					type:'post',
					data:'sid='+sid+'&_token={{csrf_token()}}',
					success:function(result){
                        jQuery('#district').html(result);
                        jQuery('#district1').html(result);
					}
				});
			});
			
        });
        
        //jQuery(document).ready(function(){
			// jQuery('#country1').change(function(){
			// 	let cid=jQuery(this).val();
			// 	jQuery('#state1').html('<option value="">Select State</option>')
			// 	jQuery.ajax({
			// 		url:'/getState',
			// 		type:'post',
			// 		data:'cid='+cid+'&_token={{csrf_token()}}',
			// 		success:function(result){
			// 			jQuery('#state1').html(result)
			// 		}
			// 	});
			// });
			
			// jQuery('#state1').change(function(){
            //     let sid=jQuery(this).val();
            //     jQuery('#district1').html('<option value="">Select District</option>')
			// 	jQuery.ajax({
			// 		url:'/getDistrict',
			// 		type:'post',
			// 		data:'sid='+sid+'&_token={{csrf_token()}}',
			// 		success:function(result){
			// 			jQuery('#district1').html(result)
			// 		}
			// 	});
			// });
			
		//});
			
		</script>


@endsection
       