@extends('user.layout.layout')

@section('section_content')

<style type="text/css">
    .select-drop {
        width: 100%;
    }
    .minus-btn {
        position: absolute; 
        right: 42px; 
        padding: 0;
        height: 25px;
        width: 26px; 
        /*border-radius: 50%; */
        margin-top: -6px;
    }
    .error {
        color: #DB504A !important;
    }
    .error-msg {
        display: none;
    }
    .spouse_type, .is_legal, .physically_handicapped, .is_second_spouse {
        display: none;
    }
    .nominee_row {
        margin-bottom: 30px;
    }
    #addNominee, .new_nominee, #saveNominee {
        float: right;
    }
    #upload-demo{
        width: 450px;
        height: 300px;
        padding-bottom:25px;
    }
    .circle-icon {
        color: green;
    }
    .img-icon {
        margin-top: 14px;
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
                                <a class="nav-link" id="profile-tab" href="{{ route('family_pensioner_personal_resubmit_page') }}">2. FAMILY PENSIONER</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active-tab" id="nominee-tab" href="">3. NOMINEES</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" href="">4. LIST OF DOCUMENTS</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="">
                                <div class="media-body">
                                    <h4 class="mt-0 text-center">DETAILS OF FAMILY MEMBERS & NOMINATION </h4>
                                    <p class="text-center">Details of family as on the date of application:- (In case of having 2nd Spouse, the proof of death of first spouse and their children may be specified and enclosed)</p>
                                    <br />
                                    <form action="{{ route('family_pensioner_nominee_details_resubmission') }}" method="POST" class="forms-sample" enctype="multipart/form-data" id="nominee-details" autocomplete="off">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="hidden" name="nominee_preference_ids" id="nominee_preference_ids" value="{{ $nominee_preference_ids }}">
                                        <input type="hidden" name="nominee_preference_change_ids" id="nominee_preference_change_ids">
                                        <div id="nominee_list">                                            
                                            @foreach($nominee_list as $key=>$nominee_details)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4 class="card-title">Nominee Details</h4>          
                                                                <div class="row nominee_row">
                                                                    @if(!empty($nominee_details))
                                                                        <input type="hidden" name="nominee[{{$key}}][nominee_id]" data-key="{{$key}}" id="nominee_id_{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->id : '' }}" >
                                                                    @endif

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(46,$nominee_details->id);
                                                                            @endphp
                                                                            <label for="exampleInputEmail3">Full Name of the Family Member<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(46,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            <input type="text" class="form-control nominee_name" name="nominee[{{$key}}][name]" data-key="{{$key}}" id="name_{{$key}}" placeholder="Enter Name here." minlength="4" maxlength="50" value="{{ !empty($nominee_details) ? $nominee_details->nominee_name : '' }}" @if(!$fieldStatus) readonly @endif>
                                                                            <label id="name_{{$key}}-error" class="error error-msg" for="name_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(47,$nominee_details->id);
                                                                            @endphp
                                                                            <label for="exampleInputEmail3">Mobile No.<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(47,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            <input type="text" class="form-control mobile_no" id="mobile_no_{{$key}}" placeholder="Enter Mobile no here." name="nominee[{{$key}}][mobile_no]" data-key="{{$key}}" minlength="10" maxlength="10" value="{{ !empty($nominee_details) ? $nominee_details->mobile_no : '' }}" @if(!$fieldStatus) readonly @endif>
                                                                            <label id="mobile_no_{{$key}}-error" class="error error-msg" for="mobile_no_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(48,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Date of Birth<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(48,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            <div id="nominee-datepicker" class="input-group date">
                                                                                <input type="text" class="form-control datepickerClass date_of_birth" data-key="{{$key}}" name="nominee[{{$key}}][date_of_birth]" id="date_of_birth_{{$key}}" value="{{ !empty($nominee_details) ? \Carbon\Carbon::parse($nominee_details->date_of_birth)->format('d/m/Y') : '' }}" readonly>
                                                                                <span class="input-group-addon input-group-append border-left">
                                                                                    <span class="mdi mdi-calendar input-group-text"></span>
                                                                                </span>
                                                                            </div>
                                                                            <label id="date_of_birth_{{$key}}-error" class="error error-msg" for="date_of_birth_{{$key}}"></label>            
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(49,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Proof of Date of Birth<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(49,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            <input type="hidden" name="nominee[{{$key}}][dob_attachment_path_hidden]" id="dob_attachment_path_{{$key}}_hidden" class="dob_attachment_path_hidden" data-key="{{$key}}">

                                                                            <input type="file" name="nominee[{{$key}}][dob_attachment_path]" id="dob_attachment_path_{{$key}}" class="file-upload-default dob_attachment_path" data-key="{{$key}}" @if(!empty($nominee_details->dob_attachment_path)) data-edit="1" @endif>
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                                <div class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" @if(!$fieldStatus) disabled @endif>Upload</button>
                                                                                </div>
                                                                            </div>
                                                                            <label id="dob_attachment_path_{{$key}}-error" class="error error-msg" for="dob_attachment_path_{{$key}}"></label>

                                                                            @if(!empty($nominee_details->dob_attachment_path))
                                                                                <span class="check-circle" id="dob_attachment_path_{{$key}}_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                                                <span class="document_img_span" id="dob_attachment_path_{{$key}}_img" data-img="{{ asset('public/' . $nominee_details->dob_attachment_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                                                <!-- <img class="document_img" src="{{-- asset('public/' . $nominee_details->dob_attachment_path) --}}" width="60" height="60"> -->
                                                                            @else
                                                                                <span class="check-circle d-none" id="dob_attachment_path_{{$key}}_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                                                <span class="document_img_span d-none" id="dob_attachment_path_{{$key}}_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(50,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Gender<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(50,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            <select class="js-example-basic-single select-drop gender"  data-key="{{$key}}" name="nominee[{{$key}}][gender]" id="gender_{{$key}}">
                                                                                <option value="">Select Gender</option>
                                                                                @foreach($genders as $gender)
                                                                                    <option @if(!empty($nominee_details) && $nominee_details->gender_id == $gender->id) selected @endif  value="{{$gender->id}}" @if(!$fieldStatus && $gender->id != $nominee_details->gender_id) disabled @endif>{{$gender->gender_name}}</option>
                                                                                @endforeach  
                                                                            </select>
                                                                            <label id="gender_{{$key}}-error" class="error error-msg" for="gender_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(51,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Relation with Pensioner<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(51,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            <select class="js-example-basic-single select-drop relation_with_pensioner" name="nominee[{{$key}}][relation_with_pensioner]"  data-key="{{$key}}" id="relation_with_pensioner_{{$key}}">
                                                                                <option value="">Select Relation</option>
                                                                                @foreach($relations as $relation)
                                                                                    <option @if(!empty($nominee_details) && $nominee_details->relationship_id == $relation->id) selected @endif value="{{$relation->id}}" @if(!$fieldStatus && $relation->id != $nominee_details->relationship_id) disabled @endif>{{$relation->relation_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <label id="relation_with_pensioner_{{$key}}-error" class="error error-msg" for="relation_with_pensioner_{{$key}}"></label>
                                                                        </div>

                                                                        <input type="hidden" name="nominee[{{$key}}][is_spouse]" id="is_spouse_{{$key}}">
                                                                    </div>

                                                                    <div class="col-md-6 spouse_type" id="spouse_type_{{$key}}">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(52,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Spouse Type<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(52,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            <select class="js-example-basic-single select-drop is_2nd_spouse" name="nominee[{{$key}}][is_2nd_spouse]"  data-key="{{$key}}" id="is_2nd_spouse_{{$key}}">
                                                                                <option value="">Select Spouse Type</option>
                                                                                <option @if(!empty($nominee_details) && $nominee_details->is_2nd_spouse == 0) selected @endif value="1" @if(!$fieldStatus && $nominee_details->is_2nd_spouse != 0) disabled @endif>1</option>
                                                                                <option @if(!empty($nominee_details) && $nominee_details->is_2nd_spouse == 1) selected @endif value="2" @if(!$fieldStatus && $nominee_details->is_2nd_spouse != 1) disabled @endif>2</option>
                                                                            </select>
                                                                            <label id="is_2nd_spouse_{{$key}}-error" class="error error-msg" for="is_2nd_spouse_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 is_second_spouse" id="is_second_spouse_death_date_{{$key}}">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(53,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Death Date of 1st Spouse<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(53,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            <div id="nominee-datepicker" class="input-group date">
                                                                                <input type="text" class="form-control datepickerClass 1st_spouse_death_date" data-key="{{$key}}" name="nominee[{{$key}}][1st_spouse_death_date]" id="1st_spouse_death_date_{{$key}}" value="{{ !empty($nominee_details) ? \Carbon\Carbon::parse($nominee_details->{'1st_spouse_death_date'})->format('m/d/Y') : '' }}" readonly>
                                                                                <span class="input-group-addon input-group-append border-left">
                                                                                    <span class="mdi mdi-calendar input-group-text"></span>
                                                                                </span>
                                                                            </div>
                                                                            <label id="1st_spouse_death_date_{{$key}}-error" class="error error-msg" for="1st_spouse_death_date_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 is_second_spouse" id="is_second_spouse_death_cert_{{$key}}">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(54,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Death Certificate of 1st Spouse<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(54,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif

                                                                            <input type="hidden" name="nominee[{{$key}}][1st_spouse_death_certificate_path_hidden]" id="1st_spouse_death_certificate_path_{{$key}}_hidden" class="1st_spouse_death_certificate_path_hidden" data-key="{{$key}}">

                                                                            <input type="file" name="nominee[{{$key}}][1st_spouse_death_certificate_path]" id="1st_spouse_death_certificate_path_{{$key}}" class="file-upload-default 1st_spouse_death_certificate_path" data-key="{{$key}}" @if(!empty($nominee_details->{'1st_spouse_death_certificate_path'})) data-edit="1" @endif>
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                                <div class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button"@if(!$fieldStatus) disabled @endif>Upload</button>
                                                                                </div>
                                                                            </div>
                                                                            <label id="1st_spouse_death_certificate_path_{{$key}}-error" class="error error-msg" for="1st_spouse_death_certificate_path_{{$key}}"></label>

                                                                            @if(!empty($nominee_details->{'1st_spouse_death_certificate_path'}))
                                                                                <span class="check-circle" id="1st_spouse_death_certificate_path_{{$key}}_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                                                <span class="document_img_span" id="1st_spouse_death_certificate_path_{{$key}}_img" data-img="{{ asset('public/' . $nominee_details->{'1st_spouse_death_certificate_path'}) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                                                <!-- <img class="document_img" src="{{-- asset('public/' . $nominee_details->{'1st_spouse_death_certificate_path'}) --}}" width="60" height="60"> -->
                                                                            @else
                                                                                <span class="check-circle d-none" id="1st_spouse_death_certificate_path_{{$key}}_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                                                <span class="document_img_span d-none" id="1st_spouse_death_certificate_path_{{$key}}_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(55,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Nominee Preference<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(55,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <select class="js-example-basic-single form-control nominee_preference_id" name="nominee[{{$key}}][nominee_preference_id]"  data-key="{{$key}}" id="nominee_preference_id_{{$key}}">
                                                                                <option value="">Select Nominee Preference</option>
                                                                                @foreach($nominee_prefences as $nominee_prefence)
                                                                                    <option @if(!empty($nominee_details) && $nominee_details->nominee_preference_id == $nominee_prefence->id) selected @endif  @if(!$fieldStatus && $nominee_prefence->id != $nominee_details->nominee_preference_id) disabled @endif value="{{$nominee_prefence->id}}">{{$nominee_prefence->nominee_prefrence}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <label id="nominee_preference_id_{{$key}}-error" class="error error-msg" for="nominee_preference_id_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(56,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Name of the Bank<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(56,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <select class="js-example-basic-single select-drop bank bank_details" id="bank_{{$key}}" name="nominee[{{$key}}][bank]"  data-key="{{$key}}">
                                                                                <option value="">Select Bank</option>
                                                                                @foreach($banks as $bank)
                                                                                    <option @if(!empty($nominee_details) && $nominee_details->bank_id == $bank->id) selected @endif @if(!$fieldStatus && $bank->id != $nominee_details->bank_id) disabled @endif value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <label id="bank_{{$key}}-error" class="error error-msg" for="bank_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    @php
                                                                        if(!empty($nominee_details)) {
                                                                            $bank_branch = DB::table('optcl_bank_branch_master')->where('bank_id', $nominee_details->bank_id)->get();
                                                                        }
                                                                    @endphp

                                                                    @if(!empty($nominee_details))
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(57,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Name Address of the Branch<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(57,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <select class="js-example-basic-single select-drop bank_branch branch" id="branch_{{$key}}" name="nominee[{{$key}}][branch]"  data-key="{{$key}}">
                                                                                <option value="">Select Branch</option>
                                                                                @foreach($bank_branch as $branch)
                                                                                    <option @if(!empty($nominee_details) && $nominee_details->bank_branch_id == $branch->id) selected @endif value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <label id="branch_{{$key}}-error" class="error error-msg" for="branch_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    @php
                                                                        $bank_branch_id = $nominee_details->bank_branch_id;
                                                                        $ifsc_code = DB::table('optcl_bank_branch_master')
                                                                                        ->where('id', $bank_branch_id)
                                                                                        ->value('ifsc_code');
                                                                    @endphp
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="exampleInputEmail3">IFSC Code</label>
                                                                            <input type="text" class="form-control ifsc_code" id="ifsc_code_{{$key}}" placeholder="IFSC Code" name="nominee[{{$key}}][ifsc_code]" data-key="{{$key}}" disabled value="{{$ifsc_code}}">
                                                                            <label id="ifsc_code_{{$key}}-error" class="error error-msg" for="ifsc_code_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    @else
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(57,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Name Address of the Branch<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(57,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <select class="js-example-basic-single select-drop bank_branch branch" id="branch_{{$key}}" name="nominee[{{$key}}][branch]"  data-key="{{$key}}">
                                                                                <option value="">Select Branch</option>
                                                                            </select>
                                                                            <label id="branch_{{$key}}-error" class="error error-msg" for="branch_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="exampleInputEmail3">IFSC Code</label>
                                                                            <input type="text" class="form-control ifsc_code" id="ifsc_code_{{$key}}" placeholder="IFSC Code" name="nominee[{{$key}}][ifsc_code]" data-key="{{$key}}" disabled>
                                                                            <label id="ifsc_code_{{$key}}-error" class="error error-msg" for="ifsc_code_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                    
                                                                    

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(58,$nominee_details->id);
                                                                            @endphp
                                                                            <label for="exampleInputEmail3">Savings Bank A/C No. (Single / Joint A/C with Spouse)<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(58,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            <input type="text" class="form-control savings_bank_account_no" id="savings_bank_account_no_{{$key}}" placeholder=" Enter Saving Bank Account No" name="nominee[{{$key}}][savings_bank_account_no]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->savings_bank_account_no : '' }}" minlength="9" maxlength="18" @if(!$fieldStatus) readonly @endif>
                                                                            <label id="savings_bank_account_no_{{$key}}-error" class="error error-msg" for="savings_bank_account_no_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(59,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Marital Status<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(59,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <select class="js-example-basic-single form-control marital_status" name="nominee[{{$key}}][marital_status]"  data-key="{{$key}}" id="marital_status_{{$key}}">
                                                                                <option value="">Select Status</option>
                                                                                @foreach($mstatus as $mstatusValue)
                                                                                    <option @if(!empty($nominee_details) && $nominee_details->marital_status_id == $mstatusValue->id) selected @endif @if(!$fieldStatus && $mstatusValue->id != $nominee_details->marital_status_id) disabled @endif value="{{$mstatusValue->id}}">{{$mstatusValue->marital_status_name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <label id="marital_status_{{$key}}-error" class="error error-msg" for="marital_status_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(60,$nominee_details->id);
                                                                            @endphp
                                                                            <label for="exampleInputEmail3">Aadhaar No.<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(60,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <input type="text" class="form-control aadhaar_no" id="aadhaar_no_{{$key}}" placeholder="Enter Aadhaar no here." name="nominee[{{$key}}][aadhaar_no]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->nominee_aadhaar_no : '' }}" minlength="12" maxlength="12" @if(!$fieldStatus) readonly @endif>
                                                                            <label id="aadhaar_no_{{$key}}-error" class="error error-msg" for="aadhaar_no_{{$key}}"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(61,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Employment Status<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(61,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <select class="js-example-basic-single form-control employement_status" name="nominee[{{$key}}][employement_status]"  data-key="{{$key}}" id="employement_status_{{$key}}">
                                                                                <option value="">Select Employment Status</option>
                                                                                <option @if(!empty($nominee_details) && $nominee_details->employement_status == 1) selected @endif @if(!$fieldStatus && 1 != $nominee_details->employement_status) disabled @endif value="1">Employed</option>
                                                                                <option @if(!empty($nominee_details) && $nominee_details->employement_status == 2) selected @endif @if(!$fieldStatus && 2 != $nominee_details->employement_status) disabled @endif value="2">Unemployed</option>
                                                                            </select>
                                                                            <label id="employement_status_{{$key}}-error" class="error error-msg" for="employement_status_{{$key}}"></label>
                                                                        </div>
                                                                    </div>                                            

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(62,$nominee_details->id);
                                                                            @endphp
                                                                            <label for="exampleInputEmail3">Total Income per annum<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(62,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <input type="text" class="form-control total_income_per_annum" id="total_income_per_annum_{{$key}}" placeholder="Enter Total Income per annum" name="nominee[{{$key}}][total_income_per_annum]"  data-key="{{$key}}"  value="{{ !empty($nominee_details) ? $nominee_details->total_income_per_annum : '' }}" @if(!$fieldStatus) readonly @endif>
                                                                            <label id="total_income_per_annum_{{$key}}-error" class="error error-msg" for="total_income_per_annum_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(63,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Physically Handicapped<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(63,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <select class="js-example-basic-single form-control is_physically_handicapped" name="nominee[{{$key}}][is_physically_handicapped]"  data-key="{{$key}}" id="is_physically_handicapped_{{$key}}">
                                                                                <option value="">Select Physically Handicapped</option>
                                                                                <option @if(!empty($nominee_details) && $nominee_details->is_physically_handicapped == 1) selected @endif @if(!$fieldStatus && 1 != $nominee_details->is_physically_handicapped) disabled @endif value="1">Yes</option>
                                                                                <option @if(!empty($nominee_details) && $nominee_details->is_physically_handicapped == 2) selected @endif @if(!$fieldStatus && 2 != $nominee_details->is_physically_handicapped) disabled @endif value="2">No</option>
                                                                            </select>
                                                                            <label id="is_physically_handicapped_{{$key}}-error" class="error error-msg" for="is_physically_handicapped_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 physically_handicapped" id="physically_handicapped_cert_{{$key}}">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(64,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Upload Disability Certificate<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(64,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <input type="hidden" name="nominee[{{$key}}][disability_certificate_path_hidden]" id="disability_certificate_path_{{$key}}_hidden" class="disability_certificate_path_hidden" data-key="{{$key}}">

                                                                            <input type="file" name="nominee[{{$key}}][disability_certificate_path]" id="disability_certificate_path_{{$key}}" class="file-upload-default disability_certificate_path" data-key="{{$key}}" @if(!empty($nominee_details->disability_certificate_path)) data-edit="1" @endif>
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                                <div class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button"@if(!$fieldStatus) disabled @endif>Upload</button>
                                                                                </div>
                                                                            </div>
                                                                            <label id="disability_certificate_path_{{$key}}-error" class="error error-msg" for="disability_certificate_path_{{$key}}"></label>

                                                                            @if(!empty($nominee_details->disability_certificate_path))
                                                                                <span class="check-circle" id="disability_certificate_path_{{$key}}_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                                                <span class="document_img_span" id="disability_certificate_path_{{$key}}_img" data-img="{{ asset('public/' . $nominee_details->disability_certificate_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                                                <!-- <img class="document_img" src="{{ asset('public/' . $nominee_details->disability_certificate_path) }}" width="60" height="60"> -->
                                                                            @else
                                                                                <span class="check-circle d-none" id="disability_certificate_path_{{$key}}_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                                                <span class="document_img_span d-none" id="disability_certificate_path_{{$key}}_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 physically_handicapped" id="physically_handicapped_percentage_{{$key}}">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(65,$nominee_details->id);
                                                                            @endphp
                                                                            <label for="exampleInputEmail3">Disability Percentage<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(65,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <input type="text" class="form-control disability_percentage" id="disability_percentage_{{$key}}" placeholder="Disability Percentage" name="nominee[{{$key}}][disability_percentage]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->disability_percentage : '' }}" @if(!$fieldStatus) readonly @endif>
                                                                            <label id="disability_percentage_{{$key}}-error" class="error error-msg" for="disability_percentage_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(66,$nominee_details->id);
                                                                            @endphp
                                                                            <label for="exampleInputEmail3">Amount / Share payable to Each<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(66,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <input type="text" class="form-control pension_amount_share_percentage" id="pension_amount_share_percentage_{{$key}}" placeholder="Enter Amount / Share payable to Each" name="nominee[{{$key}}][pension_amount_share_percentage]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->pension_amount_share_percentage : '' }}" @if(!$fieldStatus) readonly @endif>
                                                                            <label id="pension_amount_share_percentage_{{$key}}-error" class="error error-msg" for="pension_amount_share_percentage_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(67,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Minor<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(67,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <select class="js-example-basic-single form-control is_minor" name="nominee[{{$key}}][is_minor]"  data-key="{{$key}}" id="is_minor_{{$key}}">
                                                                                <option value="">Select Minor</option>
                                                                                <option @if(!empty($nominee_details) && $nominee_details->is_minor == 1) selected @endif @if(!$fieldStatus && 1 != $nominee_details->is_minor) disabled @endif value="1">Yes</option>
                                                                                <option @if(!empty($nominee_details) && $nominee_details->is_minor == 0) selected @endif @if(!$fieldStatus && 0 != $nominee_details->is_minor) disabled @endif value="0">No</option>
                                                                            </select>
                                                                            <label id="is_minor_{{$key}}-error" class="error error-msg" for="is_minor_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 is_legal" id="is_legal_name_{{$key}}">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(68,$nominee_details->id);
                                                                            @endphp
                                                                            <label for="exampleInputEmail3">Legal Guardian Name<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(68,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <input type="text" class="form-control legal_guardian_name" id="legal_guardian_name_{{$key}}" placeholder="Enter Legal Guardian Name" name="nominee[{{$key}}][legal_guardian_name]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->legal_guardian_name : '' }}" minlength="4" maxlength="50" @if(!$fieldStatus) readonly @endif>
                                                                            <label id="legal_guardian_name_{{$key}}-error" class="error error-msg" for="legal_guardian_name_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 is_legal" id="is_legal_age_{{$key}}">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(69,$nominee_details->id);
                                                                            @endphp
                                                                            <label for="exampleInputEmail3">Legal Guardian Age<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(69,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <input type="text" class="form-control legal_guardian_age" id="legal_guardian_age_{{$key}}" placeholder="Enter Legal Guardian Age" name="nominee[{{$key}}][legal_guardian_age]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->legal_guardian_age : '' }}" @if(!$fieldStatus) readonly @endif>
                                                                            <label id="legal_guardian_age_{{$key}}-error" class="error error-msg" for="legal_guardian_age_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 is_legal" id="is_legal_addr_{{$key}}">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(70,$nominee_details->id);
                                                                            @endphp
                                                                            <label for="exampleInputEmail3">Address of Legal Guardian<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(70,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <!-- <input type="text" class="form-control legal_guardian_addr" id="legal_guardian_addr_{{$key}}" placeholder="Enter Address of Legal Guardian" name="nominee[{{$key}}][legal_guardian_addr]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->legal_guardian_addr : '' }}"> -->

                                                                            <textarea rows="4" class="form-control legal_guardian_addr" id="legal_guardian_addr_{{$key}}" placeholder="Enter Address of Legal Guardian" name="nominee[{{$key}}][legal_guardian_addr]"  data-key="{{$key}}" minlength="10" maxlength="200" @if(!$fieldStatus) readonly @endif>{{ !empty($nominee_details) ? $nominee_details->legal_guardian_addr : '' }}</textarea>

                                                                            <label id="legal_guardian_addr_{{$key}}-error" class="error error-msg" for="legal_guardian_addr_{{$key}}"></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 is_legal" id="is_legal_attch_{{$key}}">
                                                                        <div class="form-group">
                                                                            @php 
                                                                              $fieldStatus = App\Libraries\NomineeUtil::check_field_return_status(71,$nominee_details->id);
                                                                            @endphp
                                                                            <label>Legal Guardian Attachment<span class="text-danger">*</span></label>
                                                                            @php 
                                                                                $return_remark_value = App\Libraries\NomineeUtil::check_field_return_remark_show(71,$nominee_details->id);
                                                                            @endphp
                                                                            @if(!empty($return_remark_value) && $return_remark_value['return_status'] == true)
                                                                            <i class="fa fa-times-circle info-resubmission-remark" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="{{$return_remark_value['return_remark']}}"></i>
                                                                            @endif
                                                                            
                                                                            <input type="hidden" name="nominee[{{$key}}][legal_guardian_attachment_path_hidden]" data-key="{{$key}}" id="legal_guardian_attachment_path_{{$key}}_hidden" class="legal_guardian_attachment_path_hidden">

                                                                            <input type="file" name="nominee[{{$key}}][legal_guardian_attachment_path]" data-key="{{$key}}" id="legal_guardian_attachment_path_{{$key}}" class="file-upload-default legal_guardian_attachment_path" @if(!empty($nominee_details->legal_guardian_attachment_path)) data-edit="1" @endif>
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image" >
                                                                                <div class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button"@if(!$fieldStatus) disabled @endif>Upload</button>
                                                                                </div>
                                                                            </div>
                                                                            <label id="legal_guardian_attachment_path_{{$key}}-error" class="error error-msg" for="legal_guardian_attachment_path_{{$key}}"></label>
                                                                            @if(!empty($nominee_details->legal_guardian_attachment_path))
                                                                                <span class="check-circle" id="legal_guardian_attachment_path_{{$key}}_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                                                <span class="document_img_span" id="legal_guardian_attachment_path_{{$key}}_img" data-img="{{ asset('public/' . $nominee_details->legal_guardian_attachment_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                                                <!-- <img class="document_img" src="{{ asset('public/' . $nominee_details->legal_guardian_attachment_path) }}" width="60" height="60"> -->
                                                                            @else
                                                                                <span class="check-circle d-none" id="legal_guardian_attachment_path_{{$key}}_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                                                                <span class="document_img_span d-none" id="legal_guardian_attachment_path_{{$key}}_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    @if(empty($nominee_details))
                                                                    <button type="button" class="btn btn-danger minus-btn">
                                                                        &times;
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                            <!-- </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a href="{{ route('personal_resubmit_page') }}" class="btn btn-default mr-2 btn-prev">PREVIOUS</a>
                                                    <button type="submit" class="btn btn-primary mr-2 btn-next">NEXT</button>                   
                                                </div>
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

<!-- Custom js for this page-->
<script type="text/javascript">

    $(document).ready(function() {

        var $uploadCrop,
        rawImg,
        imageId;

        $('ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
        });

        

        $('.jsgrid-header-cell.jsgrid-control-field').text('Action');

        $('.sidebar ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
            //$(this).closest('.nav-link').removeClass("step_active").addClass('step_inactive');
        });
        
        $('.date_of_birth').datepicker({
            autoclose: true,
            todayHighlight: true,
            endDate: new Date()
        });

        $('.1st_spouse_death_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            endDate: new Date()
        });

        $(document).on('click', '.file-upload-browse', function() {
            var file = $(this).parent().parent().parent().find('.file-upload-default');
            file.trigger('click');
        });

        $(document).on('change', '.bank_details', function() {
            $('.page-loader').addClass('d-flex');
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');
            $.ajax({
                url:'{{ route("get_bank_branch") }}',
                type:'post',
                data:'sid='+sid+'&_token={{csrf_token()}}',
                success:function(result){
                    $('#branch_' + key).html(result);
                    $('.page-loader').removeClass('d-flex');
                }
            });
        });

        $(document).on('change', '.bank_branch', function() {
            $('.page-loader').addClass('d-flex');
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');
            $.ajax({
                url:'{{ route("get_details_branch") }}',
                type:'post',
                data:'sid='+sid+'&_token={{csrf_token()}}',
                success:function(result){
                    $('#ifsc_code_' + key).val(result.ifsc_code);
                    $('.page-loader').removeClass('d-flex');
                }
            });
        });

        $(document).on('change', '.relation_with_pensioner', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');
            
            if(sid == 1) {
                $('#is_spouse_' + key).val(1);
                $('#spouse_type_' + key).show();
                if ($(".js-example-basic-single").length) {
                    $(".js-example-basic-single").select2();
                }

                validate_relation();
            } else {
                $('#is_spouse_' + key).val('');
                $('#is_2nd_spouse_' + key).val('');
                $('#spouse_type_' + key).hide();
                $('#is_second_spouse_death_date_' + key).hide();
                $('#is_second_spouse_death_cert_' + key).hide();
            }
        });

        $(document).on('change', '.is_2nd_spouse', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid == 2) {
                $('#is_second_spouse_death_date_' + key).show();
                $('#is_second_spouse_death_cert_' + key).show();

                validate_relation_spouse();
                validate_attachment('1st_spouse_death_certificate_path');
            } else {
                $('#is_second_spouse_death_date_' + key).hide();
                $('#is_second_spouse_death_cert_' + key).hide();
            }
        });

        $(document).on('change', '.employement_status', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid == 2) {
                $('#total_income_per_annum_' + key).val(0);
                $('#total_income_per_annum_' + key).attr('readonly', true);
            } else {
                // $('#total_income_per_annum_' + key).val('');
                $('#total_income_per_annum_' + key).attr('readonly', false);
            }
        });

        $(document).on('keyup', '.pension_amount_share_percentage', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid > 100) {
                $('#pension_amount_share_percentage_' + key).val('');
                $('#pension_amount_share_percentage_' + key + '-error').text('Amount / Share payable to each cannot be more than 100').removeClass('error-msg').css('display', 'block');
            }
        });

        $(document).on('keyup', '.disability_percentage', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid > 100) {
                $('#disability_percentage_' + key).val('');
                $('#disability_percentage_' + key + '-error').text('Amount / Share payable to each cannot be more than 100').removeClass('error-msg').css('display', 'block');
            }
        });

        $(document).on('change', '.is_physically_handicapped', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid == 1) {
                $('#physically_handicapped_cert_' + key).show();
                $('#physically_handicapped_percentage_' + key).show();

                validate_physically_handicap();
                validate_attachment('disability_certificate_path');
            } else {
                $('#physically_handicapped_cert_' + key).hide();
                $('#physically_handicapped_percentage_' + key).hide();
            }
        });

        $(document).on('change', '.is_minor', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid == 1) {
                $('#is_legal_name_' + key).show();
                $('#is_legal_age_' + key).show();
                $('#is_legal_addr_' + key).show();
                $('#is_legal_attch_' + key).show();

                validate_minor();
                validate_attachment('legal_guardian_attachment_path');
            } else {
                $('#is_legal_name_' + key).hide();
                $('#is_legal_age_' + key).hide();
                $('#is_legal_addr_' + key).hide();
                $('#is_legal_attch_' + key).hide();
            }
        });

        $(document).on('click', '.minus-btn', function() {
            $(this).closest('.row').remove();

            $('#addNominee').removeClass('d-none');
            $('#saveNominee').addClass('d-none');
            $('.btn-next').attr('disabled', false);
        });

        $(document).on('change', '.nominee_preference_id', function() {
            let nominee_preference_id = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');
            let nominee_preference_ids = $('#nominee_preference_ids').val();

            let nominee_preference_id_array = nominee_preference_ids.split(',');

            if(nominee_preference_id_array.length != 0 && nominee_preference_id != '') {

                if($.inArray(nominee_preference_id, nominee_preference_id_array) !== -1) {
                    $('#nominee_preference_id_' + key + '-error').text('This value already selected. Please choose another value').removeClass('error-msg').css('display', 'block');
                    $('#nominee_preference_id_' + key).select2().val('').trigger('change');
                } else {

                    if(nominee_preference_id != '') {
                        var elval = [];

                        $('.nominee_preference_id').each(function() {
                            let exist_key = $(this).data('key');

                            if(exist_key != key) {
                                elval.push($(this).val()); 
                            }
                        });

                        if($.inArray(nominee_preference_id, elval) !== -1) {
                           $('#nominee_preference_id_' + key + '-error').text('This value already selected. Please choose another value').removeClass('error-msg').css('display', 'block');
                            //alert('this value already selected. Please choose another value');
                            $('#nominee_preference_id_' + key).select2().val('').trigger('change');
                        } else {
                            $('#nominee_preference_id_' + key + '-error').text('').hide();
                        }
                    }
                }
            }
        });

        $(document).on('click', '#addNominee', function() {

            if($("#nominee-details").valid()) {
                $('.page-loader').addClass('d-flex');
                let nominee_list_len = $('.nominee_row').length;                

                // let nominee_preference_ids = $('#nominee_preference_ids').val();
                let nominee_preference_ids = '';

                $.post("{{ route('add_new_nominee') }}",{
                    "_token": "{{ csrf_token() }}", "key" : nominee_list_len, "nominee_preference_ids" : nominee_preference_ids,
                },function(response) {
                    $("#nominee_list").append(response.html);
                    
                    validate();
                    validate_attachment('dob_attachment_path');

                    $('.date_of_birth').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        endDate: new Date()
                    });

                    $('.1st_spouse_death_date').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        endDate: new Date()
                    });

                    if ($(".js-example-basic-single").length) {
                        $(".js-example-basic-single").select2();
                    }

                    $('#addNominee').addClass('d-none');
                    $('#saveNominee').removeClass('d-none');
                    $('.btn-next').attr('disabled', true);


                    $('.page-loader').removeClass('d-flex');
                });
            }
        });

        $('.savings_bank_account_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.aadhaar_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.legal_guardian_age').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.mobile_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.pension_amount_share_percentage').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.total_income_per_annum').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.disability_percentage').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.nominee_name').keyup(function () { 
            this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $('.legal_guardian_name').keyup(function () { 
            this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $.validator.addMethod("onlyNumber", function (value, element) {
            return this.optional(element) || /^[0-9\s-]*$/.test(value);
        }, "Please use only numbers");    

        $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s,/-]*$/.test(value);
        }, "Please use only letters, numbers and special characters(,/-).");

        $.validator.addMethod("precentage", function (value, element) {
            if(value > 100) {
                return false;
            }
            return true;
        }, "Percentage should not be allowed more than 100");

        $('#nominee-details').validate({
            submitHandler: function (form) {
                $('.page-loader').addClass('d-flex');
                event.preventDefault();
                var formData = new FormData(form);

                $.ajax({
                    type:'POST',
                    url:'{{ route("family_pensioner_nominee_details_resubmission") }}',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('.page-loader').removeClass('d-flex');
                        if(response['error']) {
                            for (i in response['error']) {
                                var element = $('#' + i);
                                var id = response['error'][i]['id'];
                                var eValue = response['error'][i]['eValue'];
                                $("#"+id).show();
                                $("#"+id).html(eValue);
                            }
                        }else if(response['fFormStatus']){
                            location.href = "{{route('nominee_application_view_details')}}";
                        } else {
                            location.href = "{{ route('family_pensioner_nominee_pension_documents') }}";
                            //window.location.reload();
                        }
                    }
                });
            }
        });

        $(document).on('click', '.cancel-close', function(){
            var filename = $(this).closest('#crop_image').find($('#file_name'));
            var val = $(filename).val();
            $('#'+val).parent().find('.file-upload-info').val('');

            $('#'+val).val('');

            $('#upload-demo').croppie('destroy');
        });

        $(document).on('click', '#crop', function() {

            $('.page-loader').addClass('d-flex');             
            
            $uploadCrop.croppie('result', {
                type: 'canvas',
                format: 'png',
                size: {width: 150, height: 200}
            }).then(function (resp) {
                // var avatar = URL.createObjectURL(resp);
                var file_name = $('#file_name').val();

                $('#'+file_name+'_hidden').val(resp);
                $("#"+file_name).attr('required', false);

                $('#crop_image').modal('hide');
                $('#upload-demo').croppie('destroy');

                setTimeout(function() { 
                    $("#"+file_name+"_img").attr("data-img", resp).removeClass('d-none');
                    $("#"+file_name+"_check").removeClass('d-none');
                    $('.page-loader').removeClass('d-flex');
                    
                }, 2000);
                
            });
        });
    
        $(document).on('change', '.dob_attachment_path', function() {
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

        $(document).on('change', '.1st_spouse_death_certificate_path', function() {
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

        $(document).on('change', '.disability_certificate_path', function() {
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

        $(document).on('change', '.legal_guardian_attachment_path', function() {
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

        $(document).on('focusout', '.savings_bank_account_no', function() {
            
            let account_no = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');
            let nominee_id = $('#nominee_id_' + key).val();
            let check = 0;

            $('.savings_bank_account_no').each(function() {
                let exist_account_no = $(this).val();
                let exist_data_key = $(this).data('key');

                if(exist_data_key != key) {
                    if(exist_account_no == account_no) {
                        check = 1;
                        swal("", "Account No. already added", 'error')
                        .then((value) => {
                            $('#savings_bank_account_no_'+key).val('');
                            return false;
                        });
                    }
                }
            });

            if(check == 0) {
                $('.page-loader').addClass('d-flex');
                if(account_no != '') {
                    $.ajax({
                        url:'{{ route("check_account_no") }}',
                        type:'post',
                        data:'account_no='+account_no+'&_token={{csrf_token()}}&nominee_id='+nominee_id,
                        success:function(result) {
                            $('.page-loader').removeClass('d-flex');
                            if(result.status == 'error') {
                                swal("", result.message, 'error')
                                .then((value) => {
                                    $('#savings_bank_account_no_'+key).val('');
                                });
                            }
                        }
                    });
                } else {
                    $('.page-loader').removeClass('d-flex');
                }
            }
        });

        $(document).on('click', '#nominee-next-btn', function() {
            location.href = "{{ route('pension_documents') }}";
        });
    });

    function validate() {
        $('.nominee_name').keyup(function () { 
            this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $('.legal_guardian_name').keyup(function () { 
            this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $('.savings_bank_account_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.aadhaar_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.mobile_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.pension_amount_share_percentage').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.disability_percentage').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.total_income_per_annum').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.legal_guardian_age').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.nominee_name').each(function() {
            $(this).rules("add", {
                required: true,
                minlength: 4,
                maxlength: 50,
                messages: {
                    required: "Please enter nominee name",
                    minlength: 'nominee name minimum 4 characters',
                    maxlength: 'nominee name maximum 50 characters'
                }
            });
        });

        $('.date_of_birth').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select date of birth",
                }
            });
        });

        $('.gender').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select gender",
                }
            });
        });

        $('.relation_with_pensioner').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select relation with pensioner",
                }
            });
        });

        $('.marital_status').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select marital status",
                }
            });
        });

        $('.aadhaar_no').each(function() {
            $(this).rules("add", {
                required: true,
                onlyNumber: true,
                minlength: 12,
                maxlength: 12,
                messages: {
                    required: "Please enter aadhaar no",
                    minlength: 'Aadhaar no must be 12 digits',
                    maxlength: 'Aadhaar no must be 12 digits'
                }
            });
        });


        $('.mobile_no').each(function() {
            $(this).rules("add", {
                required: true,
                onlyNumber: true,
                minlength: 10,
                maxlength: 10,
                messages: {
                    required: "Please enter mobile no",
                    minlength: 'Mobile no must be 10 digits',
                    maxlength: 'Mobile no must be 10 digits'
                }
            });
        });


        $('.savings_bank_account_no').each(function() {
            $(this).rules("add", {
                required: true,
                onlyNumber: true,
                minlength: 9,
                maxlength: 18,
                messages: {
                    required: "Please enter bank account no",
                    minlength: 'Savings Bank account no minimum 9 digits',
                    maxlength: 'Savings Bank account no maximum 18 digits'
                }
            });
        });

        $('.bank').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select bank",
                }
            });
        });

        $('.branch').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select branch",
                }
            });
        });

        $('.total_income_per_annum').each(function() {
            $(this).rules("add", {
                required: true,
                maxlength: 10,
                messages: {
                    required: "Please enter total income per annum",
                    maxlength: "Total income per annum maximum 10 digits"
                }
            });
        });

        $('.nominee_preference_id').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select nominee preference",
                }
            });
        });

        $('.employement_status').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select employement status",
                }
            });
        });

        $('.is_physically_handicapped').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select physically handicapped",
                }
            });
        });

        $('.pension_amount_share_percentage').each(function() {
            $(this).rules("add", {
                required: true,
                onlyNumber: true,
                maxlength: 10,
                messages: {
                    required: "Please enter amount / share payable to each",
                    maxlength: "Amount / share payable maximum 10 digits"
                }
            });
        });

        $('.is_minor').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select minor",
                }
            });
        });
    }

    function validate_relation() {
        $('.is_2nd_spouse').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select spouse type",
                }
            });
        });
    }

    function validate_relation_spouse() {
        $('.1st_spouse_death_date').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select death date of spouse",
                }
            });
        });        
    }

    function validate_minor() {
        $('.legal_guardian_name').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please enter leagal guardian name",
                }
            });
        });

        $('.legal_guardian_age').each(function() {
            $(this).rules("add", {
                required: true,
                maxlength: 2,
                messages: {
                    required: "Please enter leagal guardian age",
                    required: 'Legal guardian age maximum 2 digits',
                }
            });
        });

        $('.legal_guardian_addr').each(function() {
            $(this).rules("add", {
                required: true,
                addressReg: true,
                messages: {
                    required: "Please enter leagal guardian address",
                }
            });
        });
    }

    function validate_physically_handicap() {
        $('.disability_percentage').each(function() {
            $(this).rules("add", {
                required: true,
                onlyNumber: true,
                messages: {
                    required: "Please enter disability percentage",
                }
            });
        });
    }

    function validate_attachment(id) {

        if(id == 'dob_attachment_path') {
            $('.dob_attachment_path').each(function() {

                let edit = $(this).data('edit');

                if(edit == 1) {
                } else {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please select file",
                        }
                    });
                }
            });
        }

        if(id == '1st_spouse_death_certificate_path') {
            $('.1st_spouse_death_certificate_path').each(function() {
                
                let edit = $(this).data('edit');

                if(edit == 1) {
                } else {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please select file",
                        }
                    });
                }
            });
        }

        if(id == 'disability_certificate_path') {
            $('.disability_certificate_path').each(function() {
                let edit = $(this).data('edit');

                if(edit == 1) {
                } else {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please select file",
                        }
                    });
                }
            });
        }

        if(id == 'legal_guardian_attachment_path') {
            $('.legal_guardian_attachment_path').each(function() {
                let edit = $(this).data('edit');

                if(edit == 1) {
                } else {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please select file",
                        }
                    });
                }
            });
        }
    }

    function check_upload_file(ele, id) {
        $(ele).parent().find('.form-control').val($(ele).val().replace(/C:\\fakepath\\/i, ''));

        $("#" + id + "-error").html("");
        
        var val = ele.value;

        if(val.indexOf('.') !== -1) {
            var ext = ele.value.match(/\.(.+)$/)[1];
            var size = ele.files[0].size;

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