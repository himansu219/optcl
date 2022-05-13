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
                            <a class="nav-link" id="home-tab" href="{{ route('edit_pensioner_form') }}">1. PENSION DETAILS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active-tab" id="profile-tab" data-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="true">2. PERSONAL DETAILS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="nominee-tab" data-toggle="tab" href="#nominee-1" role="tab" aria-controls="nominee-tab" aria-selected="false">3. NOMINEES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact-1" role="tab" aria-controls="contact-1" aria-selected="false">4. LIST OF DOCUMENTS</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="">
                            <div class="media-body">
                                <h4 class="mt-0 text-center">PERSONAL DETAILS OF THE APPLICANT FOR PENSION / FAMILY PENSION</h4>
                                <h4 class="text-center">PART – II</h4>
                                <br />
                                <h6>Permanent Address</h6>
                                <form method="POST" id="form_2" class="forms-sample" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword4">At<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control alpha_numeric" maxlength="100" id="atpost" name="atpost" placeholder="Enter At here" value="{{ isset($personal_details->permanent_addr_at) ? $personal_details->permanent_addr_at: ''}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword4">Post<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control alpha_numeric" maxlength="30" id="postoffice" name="postoffice" id="postoffice" placeholder="Enter Post here" value="{{ isset($personal_details->permanent_addr_post) ? $personal_details->permanent_addr_post: ''}}" >
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputName1">PIN Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control only_number" id="pincode" name="pincode" placeholder="Enter Pin no here" maxlength="6" value="{{ isset($personal_details->permanent_addr_pincode) ? $personal_details->permanent_addr_pincode: ''}}" >
                                            </div>
                                            <div class="form-group">
                                                <label>Country<span class="text-danger">*</span></label>
                                                <select class="js-example-basic-single form-control" id="country" name="country">
                                                    <option value="">Select Country</option>
                                                    @foreach($country as $list)
                                                    <option value="{{$list->id}}">{{$list->country_name}}</option>
                                                    @endforeach
                                                </select>
                                                <label id="country-error" class="error mt-2 text-danger" for="country"></label>
                                            </div>
                                            <div class="form-group">
                                                <label>State<span class="text-danger">*</span></label>
                                                <select class="js-example-basic-single form-control" id="state" name="state">
                                                    <option value="">Select State</option>
                                                </select>
                                                <label id="state-error" class="error mt-2 text-danger" for="state"></label>
                                            </div>
                                            <div class="form-group">
                                                <label>District<span class="text-danger">*</span> </label>
                                                <select class="js-example-basic-single form-control" id="district" name="district">
                                                    <option value="">Select District</option>
                                                </select>
                                                <label id="district-error" class="error mt-2 text-danger" for="district"></label>
                                            </div>
                                            <h6>Present Address for Communication After Retirement<span class="text-danger">*</span> </h6>
                                            <div class="form-group clearfix">
                                                <label for="exampleInputCity1" class="float-left d-inline-block">Same As Above</label>
                                                <input type="checkbox" class="form-control float-left d-inline-block ml-2" id="same_as_above" name="same_as_above" value="1" style="height:20px; width: 20px;">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword4">At<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control alpha_numeric" maxlength="30" id="atpost1" name="atpost1" placeholder="Enter At here" value="{{ isset($personal_details->present_addr_at) ? $personal_details->present_addr_at: ''}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword4">Post<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control alpha_numeric" maxlength="30" id="postoffice1" name="postoffice1" placeholder="Enter Post here" value="{{ isset($personal_details->present_addr_post) ? $personal_details->present_addr_post: ''}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputName1">PIN Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control only_number" id="pincode1" name="pincode1" placeholder="Enter Pin no here" maxlength="6" value="{{ isset($personal_details->present_addr_pincode) ? $personal_details->present_addr_pincode: ''}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Country<span class="text-danger">*</span> </label>
                                                <select class="js-example-basic-single form-control" id="country1" name="country1">
                                                    <option value="">Select Country</option>
                                                    @foreach($country as $list)
                                                        <option value="{{$list->id}}">{{$list->country_name}}</option>
                                                    @endforeach
                                                </select>
                                                <label id="country1-error" class="error mt-2 text-danger" for="country1"></label>
                                            </div>
                                            <div class="form-group">
                                                <label>State<span class="text-danger">*</span></label>
                                                <select class="js-example-basic-single form-control" id="state1" name="state1">
                                                    <option value="">Select State</option>
                                                </select>
                                                <label id="state1-error" class="error mt-2 text-danger" for="state1"></label>
                                            </div>
                                            <div class="form-group">
                                                <label>District<span class="text-danger">*</span> </label>
                                                <select class="js-example-basic-single" style="width:100%" id="district1" name="district1">
                                                    <option value="">Select District</option>
                                                </select>
                                                <label id="district1-error" class="error mt-2 text-danger" for="district1"></label>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputName1">Telephone No with STD Code (If Any)</label>
                                                <input type="text" class="form-control only_number" id="telephone_no" name="telephone_no" placeholder="Enter Telephone no here" minlength="11" maxlength="11" value="{{ isset($personal_details->telephone_std_code) ? $personal_details->telephone_std_code: ''}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputName1">Mobile No<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control only_number" id="mobile_no" name="mobile_no" placeholder="Enter Mobile no here" minlength="10" maxlength="10" value="{{ isset($personal_details->mobile_no) ? $personal_details->mobile_no: ''}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputName1">Email Address (If Any)</label>
                                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter email here" value="{{ isset($personal_details->email_address) ? $personal_details->email_address: ''}}" maxlength="96">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputName1">PAN No<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control anns" maxlength="10" id="pan_no" name="pan_no" placeholder="Enter pan no here" value="{{ isset($personal_details->pan_no) ? $personal_details->pan_no: ''}}" >
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail3">Savings Bank A/C No.(Single or Joint Account with Spouse)<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control only_number" id="saving_bank_ac_no" maxlength="18" name="saving_bank_ac_no" placeholder=" Saving A/C No" value="{{ isset($personal_details->savings_bank_account_no) ? $personal_details->savings_bank_account_no: ''}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Name of the Bank<span class="text-danger">*</span> </label>
                                                <select class="js-example-basic-single" style="width:100%" id="bank_name" name="bank_name">
                                                    <option value="">Select Bank</option>
                                                    @foreach($banks as $bank)
                                                        <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                                    @endforeach
                                                </select>
                                                <label id="bank_name-error" class="error mt-2 text-danger" for="bank_name"></label>
                                            </div>
                                            <div class="form-group">
                                                <label>Name Address of the Branch<span class="text-danger">*</span> </label>
                                                <select class="js-example-basic-single" style="width:100%" id="branch_name_address" name="branch_name_address">
                                                    <option value="">Select Branch</option>
                                                </select>
                                                <label id="branch_name_address-error" class="error mt-2 text-danger" for="branch_name_address"></label>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail3">IFSC Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" placeholder=" Enter ifsc code" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail3">MICR Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="micr_code" name="micr_code" placeholder=" Enter micr code" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail3">Amount of Basic pay at the time of Retirement<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control amount_type" id="basic_pay" name="basic_pay" placeholder="Enter Last Basic Pay" maxlength="8" value="{{ isset($personal_details->basic_pay_amount_at_retirement) ? $personal_details->basic_pay_amount_at_retirement: ''}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Name of the Unit (where life certificate & income tax declaration to be submitted)<span class="text-danger">*</span> </label>
                                                <select class="js-example-basic-single" style="width:100%" id="name_of_the_unit" name="name_of_the_unit">
                                                    <option value="">Select Name of the Unit</option>
                                                    @foreach($last_served as $unitData)
                                                        <option value="{{$unitData->id}}" @if(isset($personal_details->pension_unit_id) && $personal_details->pension_unit_id == $unitData->id) selected @endif>{{$unitData->pension_unit_name}}</option>
                                                    @endforeach
                                                </select>
                                                <label id="name_of_the_unit-error" class="error mt-2 text-danger" for="name_of_the_unit"></label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label">Particulars of previous civil service if any and amount and nature of any pension or gratuity received.<span class="text-danger">*</span></label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <div class="row" style="margin-left: 0px;">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input" name="if_yes" id="if_yes" value="1">
                                                                    Yes
                                                                </label>
                                                            </div>
                                                        </div>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input" name="if_yes" id="if_nos" value="0" checked="">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control civil_service alpha" id="civil_service" name="civil_service" placeholder="Enter Particulars of previous civil service name" maxlength="50">
                                                <label id="civil_service-error" class="error text-danger" for="civil_service"></label>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control gratuiyty_recieved anchs" id="gratuiyty_recieved" name="gratuiyty_recieved" placeholder="Enter amount and nature of any pension or gratuity received.">                         
                                                <label id="gratuiyty_recieved-error" class="error text-danger" for="gratuiyty_recieved"></label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label">Particulars of family pension if any Received / admissible from any other source to the retired employee and any members of his family<span class="text-danger">*</span></label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <div class="row" style="margin-left: 0px;">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input" name="addmissible" id="option_yes" value="1">
                                                                    Yes
                                                                </label>
                                                            </div>
                                                        </div>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input" name="addmissible" id="option_no" value="0" checked="">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control alpha_numeric" id="addmissble_value" name="addmissble_value" placeholder="Enter admissible from any other source to the retired employee">
                                                    <label id="addmissble_value-error" class="error mt-2 text-danger" for="addmissble_value"></label>
                                                </div>
                                                
                                                <div class="row addmissible_family">
                                                    <div class="col-sm-6">
                                                        <label class="admissible_label">Members of his family<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" name="addmissible_family" id="addmissible_family">
                                                            <option value="">Select Relation</option>
                                                            @foreach($relations as $relation)
                                                                <option value="{{$relation->id}}" >{{$relation->relation_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="addmissible_family-error" class="error mt-2 text-danger" for="addmissible_family"></label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="">Name of member<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="addmissible_family_name" name="addmissible_family_name" placeholder="Enter Name of member.">
                                                        <label id="addmissible_family_name-error" class="error mt-2 text-danger" for="addmissible_family_name"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label">Whether Commutation of pension to be made & percentage to be specified (not applicable for applicants for family pension)<span class="text-danger">*</span></label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <div class="row" style="margin-left: 0px;">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input" name="percentage" id="percentage_yes" value="1">
                                                                    Yes
                                                                </label>
                                                            </div>
                                                        </div>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input" name="percentage" id="percentage_no" value="0" checked="">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control only_number" name="percentage_value" id="percentage_value" placeholder="Enter % only" maxlength="3">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" name="save_as_draft" class="btn btn-success btn-next float-right" id="save_as_draft">Save AS Draft</button>
                                    <a href="{{ route('edit_pensioner_form') }}" class="btn btn-default mr-2 btn-prev">PREVIOUS</a>
                                    <button type="submit" class="btn btn-primary mr-2 btn-next">NEXT</button>
                                </form>
                            </div>
                            <!-- </div> -->
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
<!-- Custom js for this page-->
<script type="text/javascript">
    $(document).ready(function() {   
        $("#save_as_draft").on('click',function(){
            var same_as_above_value;
            if($("#same_as_above").is(":checked")){
                same_as_above_value = 1;
            }else{
                same_as_above_value = 0;
            }
            var atpost = $("#atpost").val();
            var postoffice = $("#postoffice").val();
            var pincode = $("#pincode").val();
            var country = $("#country").val();
            var state = $("#state").val();
            var district = $("#district").val();
            //var same_as_above = $("#same_as_above").val();
            var same_as_above = same_as_above_value;
            var atpost1 = $("#atpost1").val();
            var postoffice1 = $("#postoffice1").val();
            var pincode1 = $("#pincode1").val();
            var country1 = $("#country1").val();
            var state1 = $("#state1").val();
            var district1 = $("#district1").val();
            var telephone_no = $("#telephone_no").val();
            var mobile_no = $("#mobile_no").val();

            var email = $("#email").val();
            var pan_no = $("#pan_no").val();
            var saving_bank_ac_no = $("#saving_bank_ac_no").val();
            var bank_name = $("#bank_name").val();
            var branch_name_address = $("#branch_name_address").val();
            var ifsc_code = $("#ifsc_code").val();
            var micr_code = $("#micr_code").val();
            var basic_pay = $("#basic_pay").val();
            var name_of_the_unit = $("#name_of_the_unit").val();
            var if_yes = $('input[name="if_yes"]:checked').val();
            var civil_service = $("#civil_service").val();
            var gratuiyty_recieved = $("#gratuiyty_recieved").val();
            var addmissible = $('input[name="addmissible"]:checked').val();
            var addmissble_value = $("#addmissble_value").val();
            var addmissible_family = $("#addmissible_family").val();
            var addmissible_family_name = $("#addmissible_family_name").val();
            var percentage = $('input[name="percentage"]:checked').val();
            var percentage_value = $("#percentage_value").val();
            //console.log(religion);
            $('.page-loader').addClass('d-flex');
            $.post('{{ route("save_as_draft_personal_details") }}',{
                "_token": "{{ csrf_token() }}",
                "atpost":atpost,
                "postoffice":postoffice,
                "pincode":pincode,
                "country":country,
                "state":state,
                "district":district,
                "same_as_above":same_as_above,
                "atpost1":atpost1,
                "postoffice1":postoffice1,
                "pincode1":pincode1,
                "country1":country1,
                "state1":state1,
                "district1":district1,
                "telephone_no":telephone_no,
                "mobile_no":mobile_no,
                "email":email,
                "pan_no":pan_no,
                "saving_bank_ac_no":saving_bank_ac_no,
                "bank_name":bank_name,
                "branch_name_address":branch_name_address,
                "ifsc_code":ifsc_code,
                "micr_code":micr_code,
                "basic_pay":basic_pay,
                "name_of_the_unit":name_of_the_unit,
                "gratuiyty_recieved":gratuiyty_recieved,
                "civil_service":civil_service,
                "if_yes":if_yes,
                "addmissble_value":addmissble_value,
                "addmissible_family":addmissible_family,
                "addmissible":addmissible,
                "addmissible_family_name":addmissible_family_name,
                "percentage":percentage,
                "percentage_value":percentage_value,
            },function(response){
                $('.page-loader').removeClass('d-flex');
                if(response.status == 'success'){
                    location.reload();
                }
            });
        });

        $('.sidebar ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
            //$(this).closest('.nav-link').removeClass("step_active").addClass('step_inactive');
        }); 

        $("#pan_no").on("keyup",function(){
            this.value = this.value.toUpperCase();
        }); 

        $("#bank_name").on('change', function(){
            $('.page-loader').addClass('d-flex');
            var bank_id = $(this).val();
            $.post("{{ route('get_branch') }}",{
                "_token": "{{ csrf_token() }}",
                bank_id:bank_id
            },function(response){
                $('.page-loader').removeClass('d-flex');
                $("#branch_name_address").html(response);
                $("#ifsc_code").val("");
                $("#micr_code").val("");
            });
        });

        $("#branch_name_address").on('change',function(){
            var bank_branch_id = $(this).val();
            $('.page-loader').addClass('d-flex');
            $.post("{{ route('get_branch_details') }}",{
                "_token": "{{ csrf_token() }}",
                bank_branch_id:bank_branch_id
            },function(response){
                $('.page-loader').removeClass('d-flex');
                var obj = JSON.parse(response);
                $("#ifsc_code").val(obj.ifsc_code);
                $("#micr_code").val(obj.micr_code);
                $("#ifsc_code").valid();
            });
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

                $('#atpost1').val(at_post);
                $('#postoffice1').val(postoffice);
                $('#pincode1').val(pincode);
                $('#country1').val(country).trigger('change');
                $('#state1').val(state).trigger('change');
                //$('#state1').select2('val', state);
                $('#district1').val(district).trigger('change');

            }else{
                $('#atpost1').val("");
                $('#postoffice1').val("");
                $('#pincode1').val("");
                $('#country1').val("").trigger('change');
                $('#state1').val("").trigger('change');
                $('#district1').val("").trigger('change');
            }            
            $("#atpost1").valid();
            $("#postoffice1").valid();
            $("#pincode1").valid();
        });

        $('.civil_service').addClass('d-none');
        $('.gratuiyty_recieved').addClass('d-none');

        $('input[name="if_yes"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 1) {
                $('.civil_service').removeClass('d-none');
                $('.gratuiyty_recieved').removeClass('d-none');
                $('#civil_service-error').removeClass('d-none');
                $('#gratuiyty_recieved-error').removeClass('d-none');                
            } else {
                $('.civil_service').addClass('d-none');
                $('.gratuiyty_recieved').addClass('d-none');
                $('#civil_service-error').addClass('d-none');
                $('#gratuiyty_recieved-error').addClass('d-none');
            }
        });


        $('.admissible_label').addClass('d-none');
        $('#addmissble_value').addClass('d-none');
        $('.addmissible_family').addClass('d-none');

        $('input[name="addmissible"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 1) {

                $('.admissible_label').removeClass('d-none');
                $('#addmissble_value').removeClass('d-none');
                $('.addmissible_family').removeClass('d-none');
                $('#addmissble_value-error').removeClass('d-none');
                
            } else {

                $('.admissible_label').addClass('d-none');
                $('#addmissble_value').addClass('d-none');
                $('.addmissible_family').addClass('d-none');
                $('.addmissible_family').removeClass('d-flex');
                $('#addmissble_value-error').addClass('d-none');
            }
        });

        $('#percentage_value').addClass('d-none');

        $('input[name="percentage"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 1) {
                $('#percentage_value').removeClass('d-none');
                $('#percentage_value-error').removeClass('d-none');
            } else {
                $('#percentage_value').addClass('d-none');
                $('#percentage_value-error').addClass('d-none');
            }
        });

        $('.btn-next').click(function() {
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });
        $('.btn-prev').click(function() {
            debugger
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
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
        }, "Invalid PAN no");   

        $.validator.addMethod("mobileNumberValidation", function (value, element) {
           return this.optional(element) || /^[1-9][0-9]*/.test(value); // 91-1234567890
        }, "Invalid mobile no");

        $(".js-example-basic-single").on('change', function() {
            $(this).valid();
        });

        $("#bank_name").on('change', function() {
            $(this).valid();
        });
        
        $.validator.addMethod("amount_only", function (value, element) {
            return this.optional(element) || /^\d{1,8}(?:\.\d{1,4})?$/.test(value);
        }, "Please enter in amount format");

        $("#form_2").validate({
            onkeyup: false,
            rules: {
                atpost: {
                    required: {depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true; }
                    },
                    addressReg: true,
                    minlength: 4,
                    maxlength: 100
                },
                postoffice: {
                    required: {depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true; }
                    },
                    addressReg: true,
                    minlength: 4,
                    maxlength: 30
                },
                pincode: {
                    required: true,
                    minlength: 6,
                    maxlength: 6,
                    onlyNumber: true
                },
                country: {
                    required: true,
                },
                state: {
                    required: true,
                },
                district: {
                    required: true,
                },
                atpost1: {
                    required: {depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true; }
                    },
                    addressReg: true,
                    minlength: 4,
                    maxlength:100
                },
                postoffice1: {
                    required: {depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true; }
                    },
                    addressReg: true,
                    minlength: 4,
                    maxlength: 30
                },
                pincode1: {
                    required: true,
                    minlength: 6,
                    maxlength: 6,
                },
                country1: {
                    required: true,
                },
                state1: {
                    required: true,
                },
                district1: {
                    required: true,
                },
                telephone_no: {
                    required: false,
                    minlength: 11,
                    maxlength: 11,
                    onlyNumber: true
                },
                mobile_no: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    onlyNumber: true,
                    mobileNumberValidation: true,
                    remote: {
                        url:'{{ route("validate_mobile_number") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            }
                        }
                    },
                },
                email: {
                    required: {depends:function(){
                        $(this).val($.trim($(this).val()));
                        return false; }
                    },
                    maxlength: 96,
                    onlyEmail: true,
                    remote: {
                        url:'{{ route("validate_email") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            }
                        }
                    },
                },
                pan_no: {
                    required: {depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true; }
                    },
                    panNo: true,
                    remote: {
                        url:'{{ route("validate_pan") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            }
                        }
                    },
                },
                bank_name: {
                    required: true,
                },
                branch_name_address: {
                    required: true,
                },  
                ifsc_code: {
                    required: true,
                },
                saving_bank_ac_no: {
                    required: true,
                    minlength: 9,
                    maxlength: 18,
                    onlyNumber: true,
                    remote: {
                        url:'{{ route("validate_account") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            }
                        }
                    },
                },
                name_of_the_unit: {
                    required: true,
                },
                basic_pay: {
                    required: true,
                    amount_only: true,
                },
                civil_service: {
                    required: function(element){
                        if($('input[name="if_yes"]').val() == 1){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    minlength: 5,
                    maxlength: 50
                },
                gratuiyty_recieved: {
                    required: function(element){
                        if($('input[name="if_yes"]').val() == 1){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    onlyNumber: true
                },
                addmissble_value:{                    
                    required: function(element){
                        if($('input[name="addmissible"]').val() == 1){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    minlength: 5,
                    maxlength: 50
                },
                addmissible_family: {
                    required: function(element){
                        if($('input[name="addmissible"]').val() == 1){
                            return true;
                        }else{
                            return false;
                        }
                    },
                },
                addmissible_family_name: {
                    required: function(element){
                        if($('input[name="addmissible"]').val() == 1){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    minlength: 5,
                    maxlength: 50
                },
                percentage_value:{
                    required: function(element){
                        if($('input[name="percentage"]').val() == 1){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    max:100,
                }
            },
            messages: {
                atpost: {                    
                    required: 'Please enter at',
                    minlength: 'At minimum 4 characters',
                    maxlength: 'At maximum 100 characters'
                },
                postoffice: {
                    required: 'Please enter post office',
                    minlength: 'Post office minimum 4 characters',
                    maxlength: 'Post office maximum 30 characters'
                },
                pincode: {
                    required: 'Please enter pin code',
                    minlength: 'Pin code must be 6 digits',
                    maxlength: 'Pin code must be 6 digits'
                },
                country: {
                    required: 'Please select country',
                },
                state: {
                    required: 'Please select state',
                },
                district: {
                    required: 'Please select district',
                },
                atpost1: {                    
                    required: 'Please enter At',
                    minlength: 'At minimum 4 characters',
                    maxlength: 'At maximum 100 characters'
                },
                postoffice1: {
                    required: 'Please enter post office',
                    minlength: 'Post office minimum 4 characters',
                    maxlength: 'Post office maximum 30 characters'

                },
                pincode1: {
                    required: 'Please enter pin code',
                    minlength: 'Pin code must be 6 digits',
                    maxlength: 'Pin code must be 6 digits'
                },
                country1: {
                    required: 'Please select country',
                },
                state1: {
                    required: 'Please select state',
                },
                district1: {
                    required: 'Please select district',
                },
                telephone_no: {
                    required: 'Please enter telephone no',
                    minlength: 'Telephone no must be 11 digits',
                    maxlength: 'Telephone no must be 11 digits'
                },
                mobile_no: {
                    required: 'Please enter mobile no',
                    minlength: 'Mobile no must be 10 digits',
                    maxlength: 'Mobile no must be 10 digits',
                    remote: 'Mobile no already exits',
                },
                email: {
                    required: 'Please enter email id',  
                    remote: 'Email id already exits',                  
                },
                pan_no: {
                    required: 'Please enter pan no',
                    remote: 'PAN no already exits',
                },
                bank_name: {
                    required: "Please select bank",
                },
                branch_name_address: {
                    required: "Please select branch",
                },
                ifsc_code: {
                    required: "Please enter IFSC code",
                },
                saving_bank_ac_no: {
                    required: 'Please enter bank acoount no',
                    minlength: 'Bank account no minimum 9 digits',
                    maxlength: 'Bank account no maximum 11 digits',
                    remote: 'A/C no already exits',
                },
                name_of_the_unit: {
                    required: 'Please select name of the unit',
                },
                basic_pay: {
                    required: 'Please enter last basic pay'
                },
                civil_service: {
                    required: 'Please enter particular civil service name',
                    minlength: 'Particular civil service name must be 5 characters',
                    maxlength: 'Particular civil service name must be 50 characters',
                },
                gratuiyty_recieved: {
                    required: 'Please enter amount and nature of any pension or gratuity received'
                },
                addmissble_value:{                    
                    required: 'Please enter admissible value',
                    minlength: 'Admissible value must be 5 characters',
                    maxlength: 'Admissible value must be 50 characters',
                },
                addmissible_family: {
                    required: 'Please select member of his family',
                },
                addmissible_family_name: {
                    required: 'Please enter name of member',
                    minlength: 'Member name minimum 5 characters',
                    maxlength: 'Member name maximum 50 characters'
                },
                percentage_value:{
                    required: 'Please enter percentage of communication',
                    max: 'Percentage value should not greater than 100',
                }
            },
            submitHandler: function(form, event) { 

              $('.page-loader').addClass('d-flex');
              event.preventDefault();
              var formData = new FormData(form);
              //$("#logid").prop('disabled',true);
              $.ajax({
                  type:'POST',
                  url:'{{ route("save_personal_details") }}',
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
                        location.href = "{{route('save_personal_details')}}";
                      }else{
                        // Success
                        //location.reload();
                        location.href = "{{route('nominee_form')}}";
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

<script type="text/javascript">
		$(document).ready(function(){
			$('#country').change(function(){
                $('.page-loader').addClass('d-flex');
				let cid=$(this).val();
				$('#state').html('<option value="">Select State</option>')
				$.ajax({
					url:'{{ route("get_state") }}',
					type:'post',
					data:'cid='+cid+'&_token={{csrf_token()}}',
					success:function(result){
                        $('.page-loader').removeClass('d-flex');
                        $('#state').html(result);
                        $('#state1').html(result);
                        $('#district').html('<option value="">Select District</option>');
                        $('#district1').html('<option value="">Select District</option>');
					}
				});
			});
			
			$('#state').change(function(){
                $('.page-loader').addClass('d-flex');
				let sid=$(this).val();
				$.ajax({
					url:'{{ route("get_district") }}',
					type:'post',
					data:'sid='+sid+'&_token={{csrf_token()}}',
					success:function(result){
                        $('.page-loader').removeClass('d-flex');
                        $('#district').html(result);
                        $('#district1').html(result);
					}
				});
			});

            /*$('#country1').change(function(){
                $('.page-loader').addClass('d-flex');
                let cid=$(this).val();
                $('#state1').html('<option value="">Select State</option>')
                $.ajax({
                    url:'{{ route("get_state") }}',
                    type:'post',
                    data:'cid='+cid+'&_token={{csrf_token()}}',
                    success:function(result){
                        $('.page-loader').removeClass('d-flex');
                        $('#state1').html(result);
                        $('#state1').html(result);
                    }
                });
            });
            
            $('#state1').change(function(){
                $('.page-loader').addClass('d-flex');
                let sid=$(this).val();
                $.ajax({
                    url:'{{ route("get_district") }}',
                    type:'post',
                    data:'sid='+sid+'&_token={{csrf_token()}}',
                    success:function(result){
                        $('.page-loader').removeClass('d-flex');
                        //$('#district').html(result);
                        $('#district1').html(result);
                    }
                });
            });*/
			
        });
        
		</script>


@endsection
       