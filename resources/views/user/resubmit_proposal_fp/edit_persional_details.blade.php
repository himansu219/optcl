@extends('user.layout.layout')

@section('section_content')
<style>
      /* .error {
        display: none;
    } */
    .mrgtop {
        margin-top: 10px;
    }
    .document_img {
        width: 70px !important; 
        height: 70px !important; 
        border-radius: 0 !important;
    }
    .img-container img {
      max-width: 100%;
    }
    .check-circle {
        margin-top: 5px;
        margin-right: 7px;
    }
    .circle-icon {
        color: green;
    }
    .img-icon {
        margin-top: 14px;
    }
    #upload-demo{
        width: 450px;
        height: 300px;
        padding-bottom:25px;
    }
    .civilDiv {
        margin-bottom: 30px;
    }
    .addmissbleValDiv {
        margin-bottom: 10px;
    }
    .civil_service, .gratuity_recieved {
        display: none;
    }
    .admissible_label, #addmissble_value, .addmissible_family {
        display: none;
    }
    .particularOfFamilyPension {
        margin-top: -80px;
    }
    .particular_of_employment, .particular_of_pension {
        display: none;
    }
</style>
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
                                <a class="nav-link" id="home-tab" href="{{ route('family_pensioner_form_page') }}">1. FAMILY PENSION FORM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active-tab" id="profile-tab" href="">2. FAMILY PENSIONER</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nominee-tab" href="">3. NOMINEES</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" href="">4. LIST OF DOCUMENTS</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="">
                                <div class="media-body">
                                    <h4 class="mt-0 text-center">PARTICULARS OF THE DECEASED PENSIONER</h4>
                                    <br />
                                    <!-- <h6>Permanent Address</h6> -->
                                    <form  class="forms-sample" autocomplete="off" id="family_pensioner_form" action="" method="post" enctype="multipart/form-data">
                                           @csrf
                                           <input type="hidden" name="family_pensioner_form_id" id="family_pensioner_form_id" value="{{$nomineeFamilyPensioner->id}}">
                                            <div class="row form_1_">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Full Name<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{strtoupper( isset($employee_master->employee_name) ? $employee_master->employee_name: '')}}" placeholder="Full name" readonly>
                                                        <label id="full_name-error" class="error text-danger" for="full_name"></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">P.P.O No<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control only_number" id="ppo_no" name="ppo_no" value="{{ !empty($employee_master->ppo_no) ? $employee_master->ppo_no : 'NA' }}" placeholder="Enter PPO No"  maxlength="5" readonly>
                                                        <label id="ppo_no-error" class="error text-danger" for="ppo_no"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="">Date of Death<span class="text-danger">*</span></label>
                                                        @php 
                                                            $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(86);

                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(86);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <div id="datepicker-popup" class="input-group date ">
                                                            <input type="text" class="form-control" id="dod" name="dod"  value="{{ \Carbon\Carbon::parse($nomineeFamilyPensioner->dod)->format('d-m-Y') }}" @if(!$fieldStatus) readonly @endif>
                                                            <span class="input-group-addon input-group-append border-left">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="dod-error" class="error text-danger" for="dod"></label>
                                                    </div>
                                                </div>
                                                @php
                                                    $attach_one = DB::table('optcl_nominee_family_pensioner_form')->where('id',$nomineeFamilyPensioner->id)->where('deleted', 0)->first();
                                                    //dd($attach_one);
                                                @endphp
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Death Certificate<span class="text-danger">*</span></label>
                                                        @php 
                                                            $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(87);
                                                        @endphp
                                                         @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(87);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif

                                                        <input type="hidden" name="death_certificate_path_hidden" id="death_certificate_path_hidden" class=" death_certificate_path_hidden" value="">
                                            
                                                        <input type="file" name="death_certificate" id="death_certificate" class="file-upload-default death_certificate"  @if(!empty($attach_one->death_certificate)) data-edit="1" @endif>
                                                        <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                            <div class="input-group-append">
                                                                <button class="file-upload-browse btn btn-info" type="button" @if(!$fieldStatus) disabled @endif>Upload</button>
                                                            </div>
                                                        </div>
                                                        <label id="death_certificate-error" class="error error-msg" for="death_certificate"></label>
                                            
                                                        @if(!empty($attach_one->death_certificate))
                                                            <span class="check-circle" id="death_certificate_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                            <span class="document_img_span" id="death_certificate_img" data-img="{{ asset('public/' . $attach_one->death_certificate) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                            <!-- <img class="document_img" src="{{-- asset('public/' . $nominee_details->dob_attachment_path) --}}" width="60" height="60"> -->
                                                        @else
                                                            <span class="check-circle d-none" id="death_certificate_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                            <span class="document_img_span d-none" id="death_certificate_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                            <hr>
                                            <h6 class="text-center-normal">PARTICULARS OF THE APPLICANT</h6>
                                            <hr>
                                            <div class="row form_1_">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Name of the Applicant<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="{{ isset($nomineeFamilyPensioner->applicant_name) ? $nomineeFamilyPensioner->applicant_name: ''}}" placeholder="Applicant name" readonly>
                                                        <label id="applicant_name-error" class="error text-danger" for="applicant_name"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Relationship with the Deceased Pensioner<span class="text-danger">*</span></label>
                                                        @php 
                                                            $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(89);
                                                        @endphp
                                                         @php 
                                                            $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(89);
                                                        @endphp
                                                        @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                        <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                        @endif
                                                        <select class="js-example-basic-single form-control" id="relationship" name="relationship">
                                                            <option value="">Select Relationship</option>
                                                            @foreach($relations as $relation)
                                                                 <option value="{{$relation->id}}" @if(isset($nomineeFamilyPensioner->relationship_id) && $nomineeFamilyPensioner->relationship_id == $relation->id ) selected @endif  @if(!$fieldStatus && $relation->id != $nomineeFamilyPensioner->relationship_id) disabled @endif>{{$relation->relation_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="relationship-error" class="error text-danger" for="relationship"></label>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label">Employment Status<span class="text-danger">*</span>
                                                                @php 
                                                                    $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(90);
                                                                @endphp
                                                                @php 
                                                                    $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(90);
                                                                @endphp
                                                                @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                @endif
                                                            </label>

                                                            <input type="hidden" name="employment_status_hidden" id="employment_status_hidden" value="{{ $nomineeFamilyPensioner->is_employment_status }}">

                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="employment_status" id="employment_status_yes" value="1" @if($nomineeFamilyPensioner->is_employment_status == 1) {{'checked'}} @endif @if(!$fieldStatus) disabled @endif>
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="employment_status" id="employment_status_no" value="0" @if($nomineeFamilyPensioner->is_employment_status == 0) {{'checked'}} @endif @if(!$fieldStatus) disabled @endif>
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control @if($nomineeFamilyPensioner->is_employment_status == 1) {{'d-inline'}} @endif particular_of_employment" name="particular_of_employment" id="particular_of_employment" placeholder="Enter particulars of employment" value="{{ $nomineeFamilyPensioner->particular_of_employment }}" @if($fieldStatus) readonly @endif>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label">If the Applicant is in receipt of pension from any other sources<span class="text-danger">*</span>
                                                                @php 
                                                                    $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(92);
                                                                @endphp
                                                                 @php 
                                                                    $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(92);
                                                                @endphp
                                                                @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                @endif
                                                            </label>

                                                            <input type="hidden" name="pension_status_hidden" id="pension_status_hidden" value="{{ $nomineeFamilyPensioner->is_pension_status }}">

                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="pension_status" id="pension_status_yes" value="1" @if($nomineeFamilyPensioner->is_pension_status == 1) {{'checked'}} @endif @if(!$fieldStatus) disabled @endif>
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="pension_status" id="pension_status_no" value="0" @if($nomineeFamilyPensioner->is_pension_status == 0) {{'checked'}} @endif @if(!$fieldStatus) disabled @endif>
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control @if($nomineeFamilyPensioner->is_pension_status == 1) {{'d-inline'}} @endif particular_of_pension" name="particular_of_pension" id="particular_of_pension" placeholder="Enter particulars of pension" value="{{ $nomineeFamilyPensioner->particular_of_pension }}" @if(!$fieldStatus) readonly @endif>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Postal Address At<span class="text-danger">*</span>
                                                                @php 
                                                                    $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(14);
                                                                @endphp
                                                                 @php 
                                                                    $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(14);
                                                                @endphp
                                                                @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                @endif
                                                        </label>
                                                        <input type="text" class="form-control alpha_numeric" maxlength="30" id="atpost" name="atpost" placeholder="Enter At here" value="{{ isset($nomineeFamilyPensioner->postal_addr_at) ? $nomineeFamilyPensioner->postal_addr_at: ''}}" @if(!$fieldStatus) readonly @endif>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Post<span class="text-danger">*</span>
                                                                @php 
                                                                    $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(15);
                                                                @endphp
                                                                 @php 
                                                                    $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(15);
                                                                @endphp
                                                                @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                @endif
                                                            </label>
                                                        <input type="text" class="form-control alpha_numeric" maxlength="30" id="postoffice" name="postoffice" id="postoffice" placeholder="Enter Post here" value="{{ isset($nomineeFamilyPensioner->postal_addr_post) ? $nomineeFamilyPensioner->postal_addr_post: ''}}" @if(!$fieldStatus) readonly @endif>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">PIN Code<span class="text-danger">*</span>
                                                                @php 
                                                                    $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(94);
                                                                @endphp
                                                                 @php 
                                                                    $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(94);
                                                                @endphp
                                                                @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                @endif
                                                        </label>
                                                        <input type="text" class="form-control only_number" id="pincode" name="pincode" placeholder="Enter Pin no here" maxlength="6" value="{{ isset($nomineeFamilyPensioner->postal_addr_pincode) ? $nomineeFamilyPensioner->postal_addr_pincode: ''}}" @if(!$fieldStatus) readonly @endif>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Country<span class="text-danger">*</span>
                                                            @php 
                                                                $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(16);
                                                            @endphp
                                                             @php 
                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(16);
                                                            @endphp
                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                            @endif
                                                    </label>
                                                        <select class="js-example-basic-single form-control" id="country" name="country">
                                                            <option value="">Select Country</option>
                                                            @foreach($country as $list)
                                                                 <option value="{{$list->id}}"@if($list->id == $nomineeFamilyPensioner->postal_addr_country_id) {{'selected'}} @endif @if(!$fieldStatus && $list->id != $nomineeFamilyPensioner->postal_addr_country_id) disabled @endif>{{$list->country_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="country-error" class="error mt-2 text-danger" for="country"></label>
                                                    </div>
                                                    @php
                                                        $countryStates = DB::table('optcl_state_master')->where('country_id', $nomineeFamilyPensioner->postal_addr_country_id)->get();
                                                    @endphp
                                                    <div class="form-group">
                                                        <label>State<span class="text-danger">*</span>
                                                            @php 
                                                                $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(17);
                                                            @endphp
                                                             @php 
                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(17);
                                                            @endphp
                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                            @endif
                                                    </label>
                                                        <select class="js-example-basic-single form-control" id="state" name="state">
                                                            <option value="">Select State</option>
                                                            @foreach($countryStates as $countryStateValue)
                                                                 <option value="{{$countryStateValue->id}}" @if($countryStateValue->id == $nomineeFamilyPensioner->postal_addr_state_id) {{'selected'}} @endif  @if(!$fieldStatus && $countryStateValue->id != $nomineeFamilyPensioner->postal_addr_state_id) disabled @endif>{{$countryStateValue->state_name}}</option>
                                                             @endforeach
                                                        </select>
                                                        <label id="state-error" class="error mt-2 text-danger" for="state"></label>
                                                    </div>
                                                    @php
                                                        $countryStateDistricts = DB::table('optcl_district_master')->where('state_id', $nomineeFamilyPensioner->postal_addr_state_id)->get();
                                                    @endphp
                                                    <div class="form-group">
                                                        <label>District<span class="text-danger">*</span> 
                                                            @php 
                                                                $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(18);
                                                            @endphp
                                                            @php 
                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(18);
                                                            @endphp
                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                            @endif
                                                        </label>
                                                        <select class="js-example-basic-single form-control" id="district" name="district">
                                                            <option value="">Select District</option>
                                                            @foreach($countryStateDistricts as $countryStateDistrictValue)
                                                                <option value="{{$countryStateDistrictValue->id}}" @if($countryStateDistrictValue->id == $nomineeFamilyPensioner->postal_addr_district_id) {{'selected'}} @endif @if(!$fieldStatus && $countryStateDistrictValue->id != $nomineeFamilyPensioner->postal_addr_district_id) disabled @endif>{{$countryStateDistrictValue->district_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="district-error" class="error mt-2 text-danger" for="district"></label>
                                                    </div>
                                                </div>     
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Savings Bank A/C No.<span class="text-danger">*</span> 
                                                            @php 
                                                                $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(30);
                                                            @endphp
                                                             @php 
                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(30);
                                                            @endphp
                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                            @endif
                                                        </label>
                                                        <input type="text" class="form-control only_number" id="saving_bank_ac_no" maxlength="18" name="saving_bank_ac_no" placeholder=" Saving Bank A/C No" value="{{ isset($nomineeFamilyPensioner->saving_bank_ac_no) ? $nomineeFamilyPensioner->saving_bank_ac_no: '' }}" @if(!$fieldStatus) readonly @endif>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label>Name of the Bank<span class="text-danger">*</span>  
                                                            @php 
                                                                $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(31);
                                                            @endphp
                                                             @php 
                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(31);
                                                            @endphp
                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                            @endif
                                                        </label>
                                                        <select class="js-example-basic-single" style="width:100%" id="bank_name" name="bank_name">
                                                            <option value="">Select Bank</option>
                                                            @foreach($banks as $bank)
                                                                <option value="{{$bank->id}}" @if(!empty($nomineeFamilyPensioner->bank_id) && $nomineeFamilyPensioner->bank_id == $bank->id) selected @endif @if(!$fieldStatus && $bank->id != $nomineeFamilyPensioner->bank_id) disabled @endif >{{$bank->bank_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="bank_name-error" class="error mt-2 text-danger" for="bank_name"></label>
                                                    </div>
                                                    @php
                                                        $branchs = DB::table('optcl_bank_branch_master')->where('bank_id', $nomineeFamilyPensioner->bank_id)->get();

                                                        $getBankData = DB::table('optcl_bank_branch_master')->where('id', $nomineeFamilyPensioner->bank_branch_id)->first();
                                                    @endphp
                                                    <div class="form-group">
                                                        <label>Name Address of the Branch<span class="text-danger">*</span>  
                                                            @php 
                                                                $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(32);
                                                            @endphp
                                                             @php 
                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(32);
                                                            @endphp
                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                            @endif
                                                        </label>
                                                        <select class="js-example-basic-single" style="width:100%" id="branch_name_address" name="branch_name_address">
                                                            <option value="">Select Branch</option>
                                                            @foreach($branchs as $branch)
                                                                <option value="{{$branch->id}}"  @if($branch->id == $nomineeFamilyPensioner->bank_branch_id) {{'selected'}} @endif  @if(!$fieldStatus && $branch->id != $nomineeFamilyPensioner->bank_branch_id) disabled @endif>{{$branch->branch_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="branch_name_address-error" class="error mt-2 text-danger" for="branch_name_address"></label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">IFSC Code<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" placeholder=" Enter ifsc code" value="{{ !empty($getBankData->ifsc_code) ? $getBankData->ifsc_code : '' }}" readonly >
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">MICR Code<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="micr_code" name="micr_code" placeholder=" Enter micr code" value="{{ !empty($getBankData->micr_code) ? $getBankData->micr_code : '' }}" readonly>
                                                    </div>
                                                 </div>
                                                 
                                                 <div class="col-md-6">  
                                                    <div class="form-group">
                                                        <label>Name of the Unit (where life certificate & income tax declaration to be submitted)<span class="text-danger">*</span>  
                                                            @php 
                                                                $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(35);
                                                            @endphp
                                                             @php 
                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(35);
                                                            @endphp
                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                            @endif
                                                        </label>
                                                        <select class="js-example-basic-single" style="width:100%" id="name_of_the_unit" name="name_of_the_unit">
                                                            <option value="">Select Unit</option>
                                                            @foreach($last_served as $unitData)
                                                            <option value="{{$unitData->id}}"@if($unitData->id == $nomineeFamilyPensioner->pension_unit_id) {{'selected' }} @endif  @if(!$fieldStatus && $unitData->id != $nomineeFamilyPensioner->pension_unit_id) disabled @endif>{{$unitData->pension_unit_name}}</option>
                                                        @endforeach
                                                        </select>
                                                        <label id="name_of_the_unit-error" class="error mt-2 text-danger" for="name_of_the_unit"></label>
                                                    </div>  
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label">Particulars of previous civil service if any and amount and nature of any pension or gratuity received.<span class="text-danger">*</span> 
                                                                @php 
                                                                    $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(36);
                                                                @endphp
                                                                 @php 
                                                                    $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(36);
                                                                @endphp
                                                                @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                @endif
                                                            </label>

                                                            <input type="hidden" name="if_yes_hidden" id="if_yes_hidden"
                                                            value="{{ $nomineeFamilyPensioner->is_civil_service_amount_received }}">

                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="if_yes" id="if_yes" value="1"
                                                                            @if($nomineeFamilyPensioner->is_civil_service_amount_received == 1) {{'checked'}} @endif @if(!$fieldStatus) disabled @endif>
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="if_yes" id="if_nos" value="0" @if($nomineeFamilyPensioner->is_civil_service_amount_received == 0) {{'checked'}} @endif @if(!$fieldStatus) disabled @endif>
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php 
                                                            $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(37);
                                                        @endphp
                                                        <div class="civilDiv">
                                                            <input type="text" class="form-control civil_service    @if($nomineeFamilyPensioner->is_civil_service_amount_received == 1) {{'d-inline'}} @endif" id="civil_service" name="civil_service" placeholder="Enter particulars of previous civil service" value="{{ $nomineeFamilyPensioner->civil_service_name }}" @if(!$fieldStatus) readonly @endif>
                                                            <span id="civil_service-error" class="error mt-2 text-danger" for="civil_service"></span>
                                                        </div>
                                                        @php 
                                                            $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(38);
                                                        @endphp
                                                        <div class="gratuityDiv">
                                                            <input type="text" class="form-control gratuity_recieved   @if($nomineeFamilyPensioner->is_civil_service_amount_received == 1) {{'d-inline'}} @endif" id="gratuity_recieved" name="gratuity_recieved" placeholder="Enter amount of any pension / gratuity received" value="{{ $nomineeFamilyPensioner->pension_gratuity_received_amount }}" @if(!$fieldStatus) readonly @endif>
                                                            <span id="gratuity_recieved-error" class="error mt-2 text-danger" for="gratuity_recieved"></span>
                                                        </div>
                                                    </div>
                                                 </div>
                                                 <div class="col-md-6 particularOfFamilyPension">    
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label">Particulars of family pension if any Received / admissible from any other source to the retired employee and any members of his family<span class="text-danger">*</span> 
                                                                @php 
                                                                    $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(39);
                                                                @endphp
                                                                 @php 
                                                                    $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(39);
                                                                @endphp
                                                                @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                @endif
                                                            </label>

                                                            <input type="hidden" name="addmissible_hidden" id="addmissible_hidden"
                                                            value="{{ $nomineeFamilyPensioner->is_family_pension_received_by_family_members }}">

                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="addmissible" id="option_yes" value="1" @if($nomineeFamilyPensioner->is_family_pension_received_by_family_members == 1) {{'checked'}} @endif @if(!$fieldStatus) disabled @endif>
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="addmissible" id="option_no" value="0" @if($nomineeFamilyPensioner->is_family_pension_received_by_family_members == 0) {{'checked'}} @endif @if(!$fieldStatus) disabled @endif>
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php 
                                                            $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(40);
                                                        @endphp
                                                        <div class="addmissbleValDiv">
                                                            <input type="text" class="form-control @if($nomineeFamilyPensioner->is_family_pension_received_by_family_members == 1) {{'d-inline'}} @endif" id="addmissble_value" name="addmissble_value" placeholder="Enter admissible from any other source to the retired employee" value="{{ $nomineeFamilyPensioner->admissible_form_any_other_source_to_the_retired_employee }}" @if(!$fieldStatus) readonly @endif>
                                                            <span id="addmissble_value-error" class="error mt-2 text-danger" for="addmissble_value"></span>
                                                        </div>
                                                        
                                                        <div class="row addmissible_family @if($nomineeFamilyPensioner->is_family_pension_received_by_family_members == 1) {{'d-flex'}} @endif">
                                                            @php 
                                                                $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(41);
                                                            @endphp
                                                            <div class="col-sm-6">
                                                                <label class="admissible_label @if($nomineeFamilyPensioner->is_family_pension_received_by_family_members == 1) {{'d-flex'}} @endif">Members of his family<span class="text-danger">*</span></label>
                                                                <select class="js-example-basic-single form-control" name="addmissible_family" id="addmissible_family">
                                                                    <option value="">Select Relation</option>
                                                                    @foreach($relations as $relation)
                                                                     <option value="{{$relation->id}}" @if($relation->id == $nomineeFamilyPensioner->family_member_relation_id) {{'selected'}} @endif @if($fieldStatus && $relation->id != $nomineeFamilyPensioner->family_member_relation_id) disabled @endif>{{$relation->relation_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <label id="addmissible_family-error" class="error mt-2 text-danger" for="addmissible_family"></label>
                                                            </div>
                                                            @php 
                                                                $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(42);
                                                            @endphp
                                                            <div class="col-sm-6">
                                                                <label class="">Name of member<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="addmissible_family_name" name="addmissible_family_name" placeholder="Enter name of member" value="{{ $nomineeFamilyPensioner->family_member_name }}" @if(!$fieldStatus) readonly @endif>
                                                            </div>
                                                        </div>
                                                    </div>
                                                 </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a href="{{ route('edit_nominee_application_form') }}" class="btn btn-default mr-2 btn-prev">PREVIOUS</a>
                                                        <button type="submit" class="btn btn-primary btn-next mr-2">NEXT</button>
                                                    </div>                                                
                                                </div>
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

        $('ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
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
        });        

        /*$('.civil_service').hide();
        $('.gratuity_recieved').hide();*/

        $('input[name="if_yes"]').change(function() {
            if($(this).is(':checked')) {
                if ($(this).val() == 1) {
                    $('#if_yes_hidden').val($(this).val());
                    $('#civil_service').show();
                    $('#gratuity_recieved').show();
                    $('#civil_service-error').show();
                    $('#gratuity_recieved-error').show();
                } else {
                    $('#if_yes_hidden').val($(this).val());
                    $('#civil_service').removeClass('d-inline').hide();
                    $('#gratuity_recieved').removeClass('d-inline').hide();
                    $('#civil_service-error').hide();
                    $('#gratuity_recieved-error').hide();
                }
            }
        });


       /* $('#addmissble_value').hide();
        $('.admissible_label').hide();
        $('.addmissible_family').hide();*/

        $('input[name="addmissible"]').change(function() {
            if($(this).is(':checked')) {
                if ($(this).val() == 1) {
                    $('#addmissible_hidden').val($(this).val());
                    $('.admissible_label').show();
                    $('.addmissible_family').addClass('d-flex').show();
                    $('#addmissble_value').show();
                    $('#addmissble_value-error').show();
                    $(".js-example-basic-single").select2();
                } else {
                    $('#addmissible_hidden').val($(this).val());
                    $('.admissible_label').hide();
                    $('.addmissible_family').removeClass('d-flex').hide();
                    $('#addmissble_value').removeClass('d-inline').hide();
                    $('#addmissble_value-error').hide();
                }
            }
        });

        $('#percentage_value').hide();

        $('input[name="percentage"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 1) {
                $('#percentage_value').show();
                $('#percentage_value-error').show();
            } else {
                $('#percentage_value').hide();
                $('#percentage_value-error').hide();
            }
        });

        // for employment status
        // $('.particular_of_employment').hide();
        $('input[name="employment_status"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 1) {
                $('#employment_status_hidden').val($(this).val());
                $('.particular_of_employment').show();
                $('#particular_of_employment-error').show();
            } else {
                $('#employment_status_hidden').val($(this).val());
                $('.particular_of_employment').removeClass('d-inline').hide();
                $('#particular_of_employment-error').hide();
            }
        });
        // for pension status
        // $('.particular_of_pension').hide();
        $('input[name="pension_status"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 1) {
                $('#pension_status_hidden').val($(this).val());
                $('.particular_of_pension').show();
                $('#particular_of_pension-error').show();
            } else {
                $('#pension_status_hidden').val($(this).val());
                $('.particular_of_pension').removeClass('d-inline').hide();
                $('#particular_of_pension-error').hide();
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

        $.validator.addMethod("onlyDecimal", function (value, element) {
            return this.optional(element) || /^[0-9\.s-]*$/.test(value);
        }, "Please use only numbers.");

        $.validator.addMethod("panNo", function (value, element) {
            return this.optional(element) || /[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(value);
        }, "Invalid PAN no.");   

        $("#family_pensioner_form").validate({
            rules: {
                full_name: {
                    required: true,
                    minlength: 4,
                    maxlength: 50
                },
                ppo_no: {
                    required: true,
                    //onlyNumber: true,
                    //minlength: 5,
                    maxlength: 5
                },
                dod: {
                    required: true,
                },
                // death_certificate:{
                //     required: true,
                // },
                applicant_name:{
                    required: true,
                    minlength: 4,
                    maxlength: 50
                },
                relationship: {
                    required: true
                },
                particular_of_employment: {
                    required: function(element){
                        if($('input[name="employment_status"]').val() == 1){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    minlength: 5,
                    maxlength: 50
                },
                particular_of_pension: {
                    required: function(element){
                        if($('input[name="pension_status"]').val() == 1){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    minlength: 5,
                    maxlength: 50
                },
                atpost: {
                    required: true,
                    addressReg: true,
                    minlength: 4,
                    maxlength: 30
                },
                postoffice: {
                    required: true,
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
                saving_bank_ac_no: {
                    required: true,
                    minlength: 9,
                    maxlength: 18,
                    onlyNumber: true,
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
                // basic_pay: {
                //     required: true,
                //     onlyNumber: true,
                // },
                name_of_the_unit: {
                    required: true,
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
                gratuity_recieved: {
                    required: function(element){
                        if($('input[name="if_yes"]').val() == 1){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    onlyDecimal: true
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
                }
            },
            messages: {
                full_name: {
                    required: 'Please enter full name',
                    minlength: 'Full name should be minimum of 4 Chars.',
                    maxlength: 'Full name should be maximum upto 50 Chars.'
                },
                ppo_no: {                    
                    required: 'Please enter PPO No',
                    //minlength: 'PPO No minimum of 5 digits',
                    maxlength: 'PPO No maximum upto 5 digits'
                },
                dod: {
                    required: 'Please select date of death',
                },
                // death_certificate:{
                //     required: 'Please upload death certificate',
                // },
                applicant_name:{
                    required: 'Please enter applicant name',
                    minlength: 'Applicant name should be minimum of 4 Chars.',
                    maxlength: 'Applicant name should be maximum upto 50 Chars.'
                },
                relationship: {
                    required: 'Please select relationship'
                },
                particular_of_employment: {
                    required: 'Please enter particular of employment',
                    minlength: 'Particular of employment should be minimum of 5 Chars.',
                    maxlength: 'Particular of employment should be maximum upto 50 Chars.'
                },
                particular_of_pension: {
                    required: 'Please enter particular of pension',
                    minlength: 'Particular of pension should be minimum of 5 Chars.',
                    maxlength: 'Particular of pension should be maximum upto 50 Chars.'
                },
                atpost: {                    
                    required: 'Please enter at',
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
                country: {
                    required: 'Please select country',
                },
                state: {
                    required: 'Please select state',
                },
                district: {
                    required: 'Please select district',
                },
                saving_bank_ac_no: {
                    required: 'Please enter bank acoount no',
                    minlength: 'Bank account no minimum 10 digits',
                    maxlength: 'Bank account no maximum 18 digits'
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
                // basic_pay: {
                //     required: 'Please enter last basic pay'
                // },
                name_of_the_unit: {
                    required: 'Please select unit',
                },
                civil_service: {
                    required: 'Please enter particular civil service name',
                    minlength: 'Civil service name should be minimum of 5 Chars.',
                    maxlength: 'Civil service name should be maximum upto 50 Chars.'
                },
                gratuity_recieved: {
                    required: 'Please enter amount and nature of any pension or gratuity received'
                },
                addmissble_value:{                    
                    required: 'Please enter addmissble value',
                    minlength: 'Addmissble name should be minimum of 5 Chars.',
                    maxlength: 'Addmissble name should be maximum upto 50 Chars.'
                },
                addmissible_family: {
                    required: 'Please select member',
                },
                addmissible_family_name: {
                    required: 'Please enter name of member',
                    minlength: 'Member name minimum 5 characters',
                    maxlength: 'Member name maximum 50 characters'
                }
            },
            submitHandler: function(form, event) { 
                $('.page-loader').addClass('d-flex');
              event.preventDefault();
              var formData = new FormData(form);
              //$("#logid").prop('disabled',true);
              $.ajax({
                  type:'POST',
                  url:'{{ route("family_pensioner_update_persional_details_resubmission") }}',
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
                        location.href = "{{route('family_pensioner_nominee_form_resubmit')}}";
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
            },
            invalidHandler: function(event, validator) {
                // 'this' refers to the form
                var errors = validator.numberOfInvalids();
                if (errors) {
                  var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. They have been highlighted';
                  $("div.error span").html(message);
                  $("div.error").show();
                } else {
                  $("div.error").hide();
                }
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
});
</script>
@endsection       