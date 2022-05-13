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
                                <a class="nav-link" id="home-tab" href="{{ route('edit_nominee_application_form') }}">1. FAMILY PENSION FORM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active-tab" id="profile-tab" data-toggle="tab" href="" role="tab" aria-controls="profile-1" aria-selected="false">2. FAMILY PENSIONER</a>
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
                                        <h4 class="mt-0 text-center">APPLICATION FOR GRANT OF FAMILY PENSION TO THE WIDOW / WIDOWER OF A DECEASED PENSIONER PART – I-B</h4>
                                        <hr>
                                        <h6 class="text-center-normal">PARTICULARS OF THE DECEASED PENSIONER</h6>
                                        <hr>
                                        <br />
                                
                                        <form  class="forms-sample" autocomplete="off" id="family_pensioner_form" action="" method="post"    enctype="multipart/form-data">
                                           @csrf
                                           <input type="hidden" name="family_pensioner_form_id" id="family_pensioner_form_id" value="{{$nomineeFamilyPensioner->id}}">
                                            <div class="row form_1_">
                                                @php
                                                    $parent_user_details = DB::table('optcl_users')
                                                                                ->where('user_type', 1)
                                                                                ->where('employee_code', Auth::user()->employee_code)
                                                                                ->first();
                                                    $emp_full_name = $parent_user_details->first_name." ".$parent_user_details->last_name;
                                                @endphp
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Full Name<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{strtoupper( $emp_full_name )}}" placeholder="Full name" readonly>
                                                        <label id="full_name-error" class="error text-danger" for="full_name"></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">P.P.O No<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control only_number" id="ppo_no" name="ppo_no" value="{{ !empty($employee_master->ppo_no) ? $employee_master->ppo_no : 'NA' }}" placeholder="Enter PPO No" readonly>
                                                        <label id="ppo_no-error" class="error text-danger" for="ppo_no"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="">Date of Death<span class="text-danger">*</span></label>
                                                        <div id="datepicker-popup" class="input-group date ">
                                                            <input type="text" class="form-control" readonly id="dod" name="dod"  value="{{ \Carbon\Carbon::parse($nomineeFamilyPensioner->dod)->format('d-m-Y') }}">
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
                                                    {{-- <div class="form-group">
                                                      <label>Death Certificate<span class="span-red">*</span></label>
                                                        <input type="file" name="death_certificate" id="death_certificate" class="file-upload-default death_certificate" @if(empty($attach_one)) required @endif>
                                                          <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload death certificate">
                                                            <div class="input-group-append">
                                                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                            </div>
                                                           
                                                        </div>
                                                        <label id="death_certificate-error" class="error mt-2 text-danger" for="death_certificate"></label>
                                                    </div>
                                                    <div class="col-sm-4">
       
                                                        @if(!empty($attach_one) && !empty($attach_one->death_certificate))
                                                        <span class="check-circle" id="death_certificate_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                        <span class="document_img_span" id="death_certificate_img" data-img="{{ asset('public/' . $attach_one->death_certificate) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                        @else
                                                            <span class="check-circle d-none" id="death_certificate_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                            <span class="document_img_span d-none" id="death_certificate_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                        @endif
                                                    </div> --}}

                                                    <div class="form-group">
                                                        <label>Death Certificate<span class="text-danger">*</span></label>
                                                    <input type="hidden" name="death_certificate_path_hidden" id="death_certificate_path_hidden" class=" death_certificate_path_hidden" value="">
                                            
                                                        <input type="file" name="death_certificate" id="death_certificate" class="file-upload-default death_certificate"  @if(!empty($attach_one->death_certificate)) data-edit="1" @endif>
                                                        <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                            <div class="input-group-append">
                                                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
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
                                                        <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="{{ Auth::user()->first_name.' '.Auth::user()->last_name }}" placeholder="Applicant name" readonly>
                                                        <label id="applicant_name-error" class="error text-danger" for="applicant_name"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Relationship with the Deceased Pensioner<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="relationship" name="relationship">
                                                            <option value="">Select Relationship</option>
                                                            @foreach($relations as $relation)
                                                                 <option value="{{$relation->id}}" @if(isset($nomineeFamilyPensioner->relationship_id) && $nomineeFamilyPensioner->relationship_id == $relation->id ) selected @endif>{{$relation->relation_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="relationship-error" class="error text-danger" for="relationship"></label>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label">Employment Status<span class="text-danger">*</span></label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="employment_status" id="employment_status_yes" value="1" @if($nomineeFamilyPensioner->is_employment_status == 1) {{'checked'}} @endif>
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="employment_status" id="employment_status_no" value="0" @if($nomineeFamilyPensioner->is_employment_status == 0) {{'checked'}} @endif>
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control alpha_numeric @if($nomineeFamilyPensioner->is_employment_status == 1) {{'d-inline'}} @endif particular_of_employment" name="particular_of_employment" id="particular_of_employment" placeholder="Enter particulars of employment" value="{{ $nomineeFamilyPensioner->particular_of_employment }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label">If the Applicant is in receipt of pension from any other sources<span class="text-danger">*</span></label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="pension_status" id="pension_status_yes" value="1" @if($nomineeFamilyPensioner->is_pension_status == 1) {{'checked'}} @endif>
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="pension_status" id="pension_status_no" value="0" @if($nomineeFamilyPensioner->is_pension_status == 0) {{'checked'}} @endif>
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control alpha_numeric @if($nomineeFamilyPensioner->is_pension_status == 1) {{'d-inline'}} @endif particular_of_pension" name="particular_of_pension" id="particular_of_pension" placeholder="Enter particulars of pension" value="{{ $nomineeFamilyPensioner->particular_of_pension }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Postal Address At<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control alpha_numeric" maxlength="30" id="atpost" name="atpost" placeholder="Enter At here" value="{{ isset($nomineeFamilyPensioner->postal_addr_at) ? $nomineeFamilyPensioner->postal_addr_at: ''}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Post<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control alpha_numeric" maxlength="30" id="postoffice" name="postoffice" id="postoffice" placeholder="Enter Post here" value="{{ isset($nomineeFamilyPensioner->postal_addr_post) ? $nomineeFamilyPensioner->postal_addr_post: ''}}" >
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">PIN Code<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="pincode" name="pincode" placeholder="Enter Pin no here" maxlength="6" value="{{ isset($nomineeFamilyPensioner->postal_addr_pincode) ? $nomineeFamilyPensioner->postal_addr_pincode: ''}}" >
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Country<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="country" name="country">
                                                            <option value="">Select Country</option>
                                                            @foreach($country as $list)
                                                                 <option value="{{$list->id}}"@if($list->id == $nomineeFamilyPensioner->postal_addr_country_id) {{'selected'}} @endif>{{$list->country_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="country-error" class="error mt-2 text-danger" for="country"></label>
                                                    </div>
                                                    @php
                                                        $countryStates = DB::table('optcl_state_master')->where('country_id', $nomineeFamilyPensioner->postal_addr_country_id)->get();
                                                    @endphp
                                                    <div class="form-group">
                                                        <label>State<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="state" name="state">
                                                            <option value="">Select State</option>
                                                            @foreach($countryStates as $countryStateValue)
                                                                 <option value="{{$countryStateValue->id}}" @if($countryStateValue->id == $nomineeFamilyPensioner->postal_addr_state_id) {{'selected'}} @endif>{{$countryStateValue->state_name}}</option>
                                                             @endforeach
                                                        </select>
                                                        <label id="state-error" class="error mt-2 text-danger" for="state"></label>
                                                    </div>
                                                    @php
                                                        $countryStateDistricts = DB::table('optcl_district_master')->where('state_id', $nomineeFamilyPensioner->postal_addr_state_id)->get();
                                                    @endphp
                                                    <div class="form-group">
                                                        <label>District<span class="text-danger">*</span> </label>
                                                        <select class="js-example-basic-single form-control" id="district" name="district">
                                                            <option value="">Select District</option>
                                                            @foreach($countryStateDistricts as $countryStateDistrictValue)
                                                                <option value="{{$countryStateDistrictValue->id}}" @if($countryStateDistrictValue->id == $nomineeFamilyPensioner->postal_addr_district_id) {{'selected'}} @endif>{{$countryStateDistrictValue->district_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="district-error" class="error mt-2 text-danger" for="district"></label>
                                                    </div>
                                                </div>     
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Savings Bank A/C No.<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="saving_bank_ac_no" maxlength="18" name="saving_bank_ac_no" placeholder=" Saving Bank A/C No" value="{{ isset($nomineeFamilyPensioner->saving_bank_ac_no) ? $nomineeFamilyPensioner->saving_bank_ac_no: '' }}">
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label>Name of the Bank<span class="text-danger">*</span> </label>
                                                        <select class="js-example-basic-single" style="width:100%" id="bank_name" name="bank_name">
                                                            <option value="">Select Bank</option>
                                                            @foreach($banks as $bank)
                                                                <option value="{{$bank->id}}" @if(!empty($nomineeFamilyPensioner->bank_id) && $nomineeFamilyPensioner->bank_id == $bank->id) selected @endif >{{$bank->bank_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="bank_name-error" class="error mt-2 text-danger" for="bank_name"></label>
                                                    </div>
                                                    @php
                                                        $branchs = DB::table('optcl_bank_branch_master')->where('bank_id', $nomineeFamilyPensioner->bank_id)->get();

                                                        $getBankData = DB::table('optcl_bank_branch_master')->where('id', $nomineeFamilyPensioner->bank_branch_id)->first();
                                                    @endphp
                                                    <div class="form-group">
                                                        <label>Name Address of the Branch<span class="text-danger">*</span> </label>
                                                        <select class="js-example-basic-single" style="width:100%" id="branch_name_address" name="branch_name_address">
                                                            <option value="">Select Branch</option>
                                                            @foreach($branchs as $branch)
                                                                <option value="{{$branch->id}}"  @if($branch->id == $nomineeFamilyPensioner->bank_branch_id) {{'selected'}} @endif>{{$branch->branch_name}}</option>
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

                                                    <div class="form-group">
                                                        <label>Name of the Unit (where life certificate & income tax declaration to be submitted)<span class="text-danger">*</span> </label>
                                                        <select class="js-example-basic-single" style="width:100%" id="name_of_the_unit" name="name_of_the_unit">
                                                            <option value="">Select Unit</option>
                                                            @foreach($last_served as $unitData)
                                                            <option value="{{$unitData->id}}"@if($unitData->id == $nomineeFamilyPensioner->pension_unit_id) {{'selected' }} @endif>{{$unitData->pension_unit_name}}</option>
                                                        @endforeach
                                                        </select>
                                                        <label id="name_of_the_unit-error" class="error mt-2 text-danger" for="name_of_the_unit"></label>
                                                    </div>  
                                                    

                                                 </div>
                                                 
                                                 <div class="col-md-6">  
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label">Particulars of previous civil service if any and amount and nature of any pension or gratuity received.<span class="text-danger">*</span></label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="if_yes" id="if_yes" value="1"
                                                                            @if($nomineeFamilyPensioner->is_civil_service_amount_received == 1) {{'checked'}} @endif>
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="if_yes" id="if_nos" value="0" @if($nomineeFamilyPensioner->is_civil_service_amount_received == 0) {{'checked'}} @endif>
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control civil_service  alpha  @if($nomineeFamilyPensioner->is_civil_service_amount_received == 1) {{'d-inline'}} @endif" id="civil_service" name="civil_service" placeholder="Enter particulars of previous civil service name" value="{{ $nomineeFamilyPensioner->civil_service_name }}">
                                                            <label id="civil_service-error" class="error mt-2 text-danger" for="civil_service"></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control gratuity_recieved only_number  @if($nomineeFamilyPensioner->is_civil_service_amount_received == 1) {{'d-inline'}} @endif" id="gratuity_recieved" name="gratuity_recieved" placeholder="Enter amount and nature of any pension / gratuity received" value="{{ $nomineeFamilyPensioner->pension_gratuity_received_amount }}"  maxlength="9">
                                                            <label id="gratuity_recieved-error" class="error mt-2 text-danger" for="gratuity_recieved"></label>
                                                        </div>
                                                        
                                                    </div>
                                                 </div>
                                                 <div class="col-md-6">    
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label">Particulars of family pension if any Received / admissible from any other source to the retired employee and any members of his family<span class="text-danger">*</span></label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="addmissible" id="option_yes" value="1" @if($nomineeFamilyPensioner->is_family_pension_received_by_family_members == 1) {{'checked'}} @endif>
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="addmissible" id="option_no" value="0" @if($nomineeFamilyPensioner->is_family_pension_received_by_family_members == 0) {{'checked'}} @endif>
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control @if($nomineeFamilyPensioner->is_family_pension_received_by_family_members == 1) {{'d-inline'}} @endif alpha_numeric" id="addmissble_value" name="addmissble_value" placeholder="Enter admissible from any other source to the retired employee" value="{{ $nomineeFamilyPensioner->admissible_form_any_other_source_to_the_retired_employee }}">
                                                            <label id="addmissble_value-error" class="error mt-2 mb-2 text-danger" for="addmissble_value"></label>
                                                        </div>

                                                        <div class="row addmissible_family @if($nomineeFamilyPensioner->is_family_pension_received_by_family_members == 1) {{'d-flex'}} @endif">
                                                            <div class="col-sm-6">
                                                                <label class="admissible_label @if($nomineeFamilyPensioner->is_family_pension_received_by_family_members == 1) {{'d-flex'}} @endif">Members of his family<span class="text-danger">*</span></label>
                                                                <select class="js-example-basic-single form-control" name="addmissible_family" id="addmissible_family">
                                                                    <option value="">Select Relation</option>
                                                                    @foreach($relations as $relation)
                                                                     <option value="{{$relation->id}}"   @if($relation->id == $nomineeFamilyPensioner->family_member_relation_id) {{'selected'}} @endif>{{$relation->relation_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <label id="addmissible_family-error" class="error mt-2 text-danger" for="addmissible_family"></label>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label class="">Name of member<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="addmissible_family_name" name="addmissible_family_name" placeholder="Enter name of member" value="{{ $nomineeFamilyPensioner->family_member_name }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                 </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="button" name="save_as_draft" class="btn btn-success btn-next float-right" id="save_as_draft">Save AS Draft</button>
                                                        <a href="{{ route('edit_nominee_application_form') }}" class="btn btn-default mr-2 btn-prev">PREVIOUS</a>
                                                        <button type="submit" class="btn btn-primary btn-next mr-2">NEXT</button>
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
<div class="modal fade" id="crop_image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crop the image</h5>
                <button type="button" class="close cancel-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <input type="hidden" id="file_name" name="file_name" value="">
                    <!-- <img id="image"> -->
                    <div id="upload-demo" class="center-block"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-close" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>    
</div>
 @endsection
 @section('page-script')

  <script type="text/javascript">
    $(document).ready(function() {
        $("#addmissible_family").on('change',function(){
            $(this).valid();
        });
        $('.datepickerClass').datepicker({
            autoclose: true,
        });
        $('.sidebar ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
            //$(this).closest('.nav-link').removeClass("step_active").addClass('step_inactive');
        });

        $('.btn-next').click(function() {
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });

        $('.btn-prev').click(function() {
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });

        $('#full_name').keyup(function () { 
          this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });
        $('#applicant_name').keyup(function () { 
          this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });        

        $.validator.addMethod("onlyNumber", function (value, element) {
            return this.optional(element) || /^[0-9\s-]*$/.test(value);
        }, "Please use only numbers.");

        $.validator.addMethod("onlyDecimal", function (value, element) {
            return this.optional(element) || /^[0-9\.s-]*$/.test(value);
        }, "Please use only numbers.");

        $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s,/-]*$/.test(value);
        }, "Please use only letters, numbers and special characters(,/-).");

        $("#save_as_draft").on('click',function(e){
            e.preventDefault();
            //$('.pension_form_class').attr('id', 'pension_form_save_as_draft');

            //$('#pension_form_save_as_draft').submit();
            //console.log($('pension_form').serialize());
            //$("#pension_form").submit();
            //var family_pensioner_form_id = $("#family_pensioner_form_id").val();
            var full_name = $("#full_name").val();
            var ppo_no = $("#ppo_no").val();
            var dod = $("#dod").val();
            var death_certificate_path_hidden = $("#death_certificate_path_hidden").val();
            var applicant_name = $("#applicant_name").val();
            var relationship = $("#relationship").val();
            var employment_status = $('input[name="employment_status"]:checked').val();
            var particular_of_employment = $("#particular_of_employment").val();
            var pension_status = $('input[name="pension_status"]:checked').val();
            var particular_of_pension = $("#particular_of_pension").val();
            var atpost = $("#atpost").val();
            var postoffice = $("#postoffice").val();
            var pincode = $("#pincode").val();
            var country = $("#country").val();
            var state = $("#state").val();
            var district = $("#district").val();
            var saving_bank_ac_no = $("#saving_bank_ac_no").val();
            var bank_name = $("#bank_name").val();
            var branch_name_address = $("#branch_name_address").val();
            var ifsc_code = $("#ifsc_code").val();
            var micr_code = $("#micr_code").val();
            // var basic_pay = $("#basic_pay").val();
            var name_of_the_unit = $("#name_of_the_unit").val();
            var if_yes = $('input[name="if_yes"]:checked').val();
            var civil_service = $("#civil_service").val();
            var gratuity_recieved = $("#gratuity_recieved").val();
            var addmissible = $('input[name="addmissible"]:checked').val();
            var addmissble_value = $("#addmissble_value").val();
            var addmissible_family = $("#addmissible_family").val();
            var addmissible_family_name = $("#addmissible_family_name").val();

            $('.page-loader').addClass('d-flex');
            $.post('{{ route("save_as_draft_nominee_family_pensioner_form") }}',{
                "_token": "{{ csrf_token() }}",
                "full_name":full_name,
                "ppo_no":ppo_no,
                "dod":dod,
                "death_certificate_path_hidden":death_certificate_path_hidden,
                "applicant_name":applicant_name,
                "relationship":relationship,
                "employment_status":employment_status,
                "particular_of_employment":particular_of_employment,
                "pension_status":pension_status,
                "particular_of_pension":particular_of_pension,
                "atpost":atpost,
                "postoffice":postoffice,
                "pincode":pincode,
                "country":country,
                "state":state,
                "district":district,
                "saving_bank_ac_no":saving_bank_ac_no,
                "bank_name":bank_name,
                "branch_name_address":branch_name_address,
                "ifsc_code":ifsc_code,
                "micr_code":micr_code,
                // "basic_pay":basic_pay,
                "name_of_the_unit":name_of_the_unit,
                "gratuity_recieved":gratuity_recieved,
                "civil_service":civil_service,
                "if_yes":if_yes,
                "addmissble_value":addmissble_value,
                "addmissible_family":addmissible_family,
                "addmissible":addmissible,
                "addmissible_family_name":addmissible_family_name,
            },function(response){
                $('.page-loader').removeClass('d-flex');
                if(response.status == 'success'){
                    location.reload();
                }
            });
        });
        // for employment status
        $('.particular_of_employment').hide();
        $('input[name="employment_status"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 1) {
                $('.particular_of_employment').show();
                $('#particular_of_employment-error').show();
            } else {
                $('.particular_of_employment').hide();
                $('.particular_of_employment').removeClass('d-inline');
                $('#particular_of_employment-error').hide();
            }
        });
        // for pension status
        $('.particular_of_pension').hide();
        $('input[name="pension_status"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 1) {
                $('.particular_of_pension').show();
                $('#particular_of_pension-error').show();
            } else {
                $('.particular_of_pension').hide();
                $('.particular_of_pension').removeClass('d-inline');
                $('#particular_of_pension-error').hide();
            }
        });
        // for Particulars of previous civil service/gratuity received.
        $('.civil_service').hide();
        $('.gratuity_recieved').hide();

        $('input[name="if_yes"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 1) {
                $('.civil_service').show();
                $('.gratuity_recieved').show();
                $('#civil_service-error').show();
                $('#gratuity_recieved-error').show();
            } else {
                $('.civil_service').hide();
                $('.gratuity_recieved').hide();
                $('.civil_service').removeClass('d-inline');
                $('.gratuity_recieved').removeClass('d-inline');
                $('#civil_service-error').hide();
                $('#gratuity_recieved-error').hide();
            }
        });
        $('#addmissble_value').hide();
        $('.admissible_label').hide();
        $('.addmissible_family').hide();
        $('input[name="addmissible"]').click(function() {
            if ($(this).is(':checked') && $(this).val() == 1) {
                $('.admissible_label').show();
                $('#addmissble_value').show();
                $('.addmissible_family').show();
                $('#addmissble_value-error').show();
                
            } else {
                $('.admissible_label').hide();
                $('#addmissble_value').hide();
                $('.addmissible_family').hide();
                $('#addmissble_value').removeClass('d-inline');
                $('.addmissible_family').removeClass('d-flex');
                $('#addmissble_value-error').hide();
            }
        });

        $('#addmissible_family_name').keyup(function () { 
          this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $("#bank_name").on('change', function(){
            $('.page-loader').addClass('d-flex');
            var bank_id = $(this).val();
            
            $.post("{{ route('nominee_get_branch') }}",{
                "_token": "{{ csrf_token() }}",
                bank_id:bank_id
            },function(response){
                $('.page-loader').removeClass('d-flex');
                $("#branch_name_address").html(response);
                $("#ifsc_code").val('');
                $("#micr_code").val('');
            });
        });

        $("#branch_name_address").on('change',function(){
            var bank_branch_id = $(this).val();
            $('.page-loader').addClass('d-flex');
            $.post("{{ route('nominee_get_branch_details') }}",{
                "_token": "{{ csrf_token() }}",
                bank_branch_id:bank_branch_id
            },function(response){
                $('.page-loader').removeClass('d-flex');
                var obj = JSON.parse(response);
                $("#ifsc_code").val(obj.ifsc_code);
                $("#micr_code").val(obj.micr_code);
            });
        });

        $("#family_pensioner_form").validate({
            onkeyup: false,
            rules: {
                full_name: {
                    required: true,
                    minlength: 4,
                    maxlength: 50
                },
                ppo_no: {
                    required: true,
                },
                dod: {
                    required: true,
                },
                applicant_name:{
                    required: true,
                    minlength: 4,
                    maxlength: 50
                },
                relationship: {
                    required: true
                },
                particular_of_employment: {
                    required: {depends:function(element){
                            $(this).val($.trim($(this).val()));
                            if($('input[name="employment_status"]').val() == 1){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                    minlength: 5,
                    maxlength: 50
                },
                particular_of_pension: {
                    required: {depends:function(element){
                            $(this).val($.trim($(this).val()));
                            if($('input[name="pension_status"]').val() == 1){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                    minlength: 5,
                    maxlength: 50
                },
                atpost: {
                    required: {depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true; 
                        }
                    },
                    addressReg: true,
                    minlength: 4,
                    maxlength: 100
                },
                postoffice: {
                    required: {depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true; 
                        }
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
                bank_name: {
                    required: true,
                },
                branch_name_address: {
                    required: true,
                },
                name_of_the_unit: {
                    required: true,
                },
                civil_service: {
                    required: {depends:function(element){
                        $(this).val($.trim($(this).val()));
                            if($('input[name="if_yes"]').val() == 1){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                    minlength: 5,
                    maxlength: 50
                },
                gratuity_recieved: {
                    required: {depends:function(element){
                            if($('input[name="if_yes"]').val() == 1){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                    onlyDecimal: true
                },
                addmissble_value:{                    
                    required: {depends:function(element){
                        $(this).val($.trim($(this).val()));
                            if($('input[name="addmissible"]').val() == 1){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                    minlength: 5,
                    maxlength: 50
                },
                addmissible_family: {
                    required: {depends:function(element){
                            if($('input[name="addmissible"]').val() == 1){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                addmissible_family_name: {
                    required: {depends:function(element){
                        $(this).val($.trim($(this).val()));
                            if($('input[name="addmissible"]').val() == 1){
                                return true;
                            }else{
                                return false;
                            }
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
                },
                dod: {
                    required: 'Please select date of death',
                },
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
                saving_bank_ac_no: {
                    required: 'Please enter bank acoount no',
                    minlength: 'Bank account no minimum 9 digits',
                    maxlength: 'Bank account no maximum 18 digits',
                    remote: 'A/C no already exits',
                },
                bank_name: {
                    required: "Please select bank",
                },
                branch_name_address: {
                    required: "Please select branch",
                },
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
                    required: 'Please enter admissble value',
                    minlength: 'Admissble name should be minimum of 5 Chars.',
                    maxlength: 'Admissble name should be maximum upto 50 Chars.'
                },
                addmissible_family: {
                    required: 'Please select member of his family',
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
                  url:'{{ route("update_nominee_family_pensioner_form") }}',
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
                        location.href = "{{route('save_nominee_family_pensioner_form')}}";
                      }else{
                        // Success
                        //location.reload();
                        location.href = "{{route('nominee_nominee_form')}}";
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
  
        $('#country').change(function(){
            $('.page-loader').addClass('d-flex');
            let cid=$(this).val();
            $('#state').html('<option value="">Select State</option>')
            $.ajax({
                url:'{{ route("get_state_nominee_family_pensioner_form") }}',
                type:'post',
                data:'cid='+cid+'&_token={{csrf_token()}}',
                success:function(result){
                    $('.page-loader').removeClass('d-flex');
                    $('#state').html(result);
                    $('#district').html('<option value="">Select District</option>');
                }
            });
        });
        
        $('#state').change(function(){
            $('.page-loader').addClass('d-flex');
            let sid=$(this).val();
            $.ajax({
                url:'{{ route("get_district_nominee_family_pensioner_form") }}',
                type:'post',
                data:'sid='+sid+'&_token={{csrf_token()}}',
                success:function(result){
                    $('.page-loader').removeClass('d-flex');
                    $('#district').html(result);
                    
                }
            });
        });

        $('#dod').on('change', function(){
            $(this).valid();
        });

        $('#relationship').on('change', function(){
            $(this).valid();
        });

        
    });
    
    </script>
 <script type="text/javascript">
     
    $(document).ready(function() {

        var $uploadCrop,
        rawImg,
        imageId;

        $(document).on('click', '.cancel-close', function(){
            var filename = $(this).closest('#crop_image').find($('#file_name'));
            var val = $(filename).val();
            $('#'+val).parent().find('.file-upload-info').val('');

            $('#'+val).val('');

            $('#upload-demo').croppie('destroy');
        });

        $(document).on('click', '#crop', function() {
            //debugger;
            $('.page-loader').addClass('d-flex');             
            
            $uploadCrop.croppie('result', {
                type: 'canvas',
                format: 'png',
                size: {width: 150, height: 200}
            }).then(function (resp) {
                // var avatar = URL.createObjectURL(resp);
                
                var file_name = $('#file_name').val();
                console.log(file_name);
                $('#death_certificate_path_hidden').val(resp);
                $("#death_certificate").attr('required', false);

                $('#crop_image').modal('hide');
                $('#upload-demo').croppie('destroy');

                setTimeout(function() { 
                    $("#"+file_name+"_img").attr("data-img", resp).removeClass('d-none');
                    $("#"+file_name+"_check").removeClass('d-none');
                    $('.page-loader').removeClass('d-flex');
                    
                }, 2000);
                
            });
        });
    
        $(document).on('change', '.death_certificate', function() {
            //debugger;
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            check_upload_file(this, attr_id);

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val(attr_id);

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 150,
                    height: 200,
                },
                // showZoomer: false,
                enforceBoundary: false,
                enableExif: true
            });

            $('#crop_image').on('shown.bs.modal', function(){
                $uploadCrop.croppie('bind', {
                    url: rawImg
                }).then(function() {
                });
            });
        });


        $('#crop_image').on('hidden.bs.modal', function(){
            var filename = $(this).closest('#crop_image').find($('#file_name'));
            var val = $(filename).val();

            $('#'+val).parent().find('.file-upload-info').val('');

            $('#'+val).val('');

            $('#upload-demo').croppie('destroy');
        });

        /*$(document).on('click', '.file-upload-browse', function() {
            var file = $(this).parent().parent().parent().find('.file-upload-default');
            file.trigger('click');
        });*/
        
        $('#death_certificate').on('change', function() {
            check_upload_file(this, 'death_certificate');

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {                    
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val('death_certificate');

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 150,
                    height: 200,
                },
                // showZoomer: false,
                enforceBoundary: false,
                enableExif: true
            });

            $('#crop_image').on('shown.bs.modal', function(){
                $uploadCrop.croppie('bind', {
                    url: rawImg
                }).then(function() {
                });
            });
        });

    });

    function check_upload_file(ele, id) {
        $(ele).parent().find('.form-control').val($(ele).val().replace(/C:\\fakepath\\/i, ''));

        $("#" + id + "-error").html("");
        
        var val = ele.value;

        if(val.indexOf('.') !== -1) {
            var ext = ele.value.match(/\.(.+)$/)[1];
            var size = ele.files[0].size;
            var file = ele.files[0];

            if(size > 5000000) {
                $("#" + id + "-error").html('File size less than 5MB allowed');
                $("#" + id + "-error").show();
                ele.value = '';
                $(ele).parent().find('.form-control').val('');
            } else {
                switch (ext) {
                    case 'png':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;
                    case 'jpg':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;
                    case 'jpeg':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;
                    /*case 'pdf':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;*/
                    default:
                        $("#" + id + "-error").html('Please upload only jpg, jpeg, png file');
                        $("#" + id + "-error").show();
                        ele.value = '';
                        $(ele).parent().find('.form-control').val('');
                }

                // $('#image').attr('src', $(ele).attr('src'));
                $('#'+id+'_modal_canvas').attr('src', URL.createObjectURL(file));
            }
        } else {
            $("#" + id + "-error").html('Invalid file type');
            $("#" + id + "-error").show();
            ele.value = '';

            $(ele).parent().find('.form-control').val('');
        }
    }
</script>

@endsection