<div class="row nominee_row">
    @if(!empty($nominee_details))
        <input type="hidden" name="nominee[{{$key}}][nominee_id]" data-key="{{$key}}" id="nominee_id_{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->id : '' }}" >
    @endif

    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleInputEmail3">Full Name of the Family Member<span class="text-danger">*</span></label>
            <input type="text" class="form-control nominee_name" name="nominee[{{$key}}][name]" data-key="{{$key}}" id="name_{{$key}}" placeholder="Enter Name here." minlength="4" maxlength="50" value="{{ !empty($nominee_details) ? $nominee_details->nominee_name : '' }}">
            <label id="name_{{$key}}-error" class="error error-msg text-danger" for="name_{{$key}}"></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleInputEmail3">Mobile No.<span class="text-danger">*</span></label>
            <input type="text" class="form-control mobile_no" id="mobile_no_{{$key}}" placeholder="Enter Mobile no here." name="nominee[{{$key}}][mobile_no]" data-key="{{$key}}" minlength="10" maxlength="10" value="{{ !empty($nominee_details) ? $nominee_details->mobile_no : '' }}">
            <label id="mobile_no_{{$key}}-error" class="error error-msg text-danger" for="mobile_no_{{$key}}"></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Date of Birth<span class="text-danger">*</span></label>
            <div id="nominee-datepicker" class="input-group date">
                <input type="text" class="form-control datepickerClass date_of_birth" data-key="{{$key}}" name="nominee[{{$key}}][date_of_birth]" id="date_of_birth_{{$key}}" value="{{ !empty($nominee_details) ? \Carbon\Carbon::parse($nominee_details->date_of_birth)->format('m/d/Y') : '' }}" readonly>
                <span class="input-group-addon input-group-append border-left">
                    <span class="mdi mdi-calendar input-group-text"></span>
                </span>
            </div>
            <label id="date_of_birth_{{$key}}-error" class="error error-msg text-danger" for="date_of_birth_{{$key}}"></label>            
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Proof of Date of Birth<span class="text-danger">*</span></label>
            <input type="hidden" name="nominee[{{$key}}][dob_attachment_path_hidden]" id="dob_attachment_path_{{$key}}_hidden" class=" dob_attachment_path_hidden" data-key="{{$key}}">

            <input type="file" name="nominee[{{$key}}][dob_attachment_path]" id="dob_attachment_path_{{$key}}" class="file-upload-default dob_attachment_path" data-key="{{$key}}" @if(!empty($nominee_details->dob_attachment_path)) data-edit="1" @endif>
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <div class="input-group-append">
                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                </div>
            </div>
            <label id="dob_attachment_path_{{$key}}-error" class="error error-msg text-danger" for="dob_attachment_path_{{$key}}"></label>

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
            <label>Gender<span class="text-danger">*</span></label>
            <select class="js-example-basic-single select-drop gender"  data-key="{{$key}}" name="nominee[{{$key}}][gender]" id="gender_{{$key}}">
                <option value="">Select Gender</option>
                @foreach($genders as $gender)
                    <option @if(!empty($nominee_details) && $nominee_details->gender_id == $gender->id) selected @endif  value="{{$gender->id}}">{{$gender->gender_name}}</option>
                @endforeach  
            </select>
            <label id="gender_{{$key}}-error" class="error error-msg text-danger" for="gender_{{$key}}"></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Relation with Pensioner<span class="text-danger">*</span></label>
            <select class="js-example-basic-single select-drop relation_with_pensioner" name="nominee[{{$key}}][relation_with_pensioner]"  data-key="{{$key}}" id="relation_with_pensioner_{{$key}}">
                <option value="">Select Relation</option>
                @foreach($relations as $relation)
                    <option @if(!empty($nominee_details) && $nominee_details->relationship_id == $relation->id) selected @endif value="{{$relation->id}}">{{$relation->relation_name}}</option>
                @endforeach
            </select>
            <label id="relation_with_pensioner_{{$key}}-error" class="error error-msg text-danger" for="relation_with_pensioner_{{$key}}"></label>
        </div>

        <input type="hidden" name="nominee[{{$key}}][is_spouse]" id="is_spouse_{{$key}}">
    </div>

    <div class="col-md-6 spouse_type" id="spouse_type_{{$key}}">
        <div class="form-group">
            <label>Spouse Type<span class="text-danger">*</span></label>
            <select class="js-example-basic-single select-drop is_2nd_spouse" name="nominee[{{$key}}][is_2nd_spouse]"  data-key="{{$key}}" id="is_2nd_spouse_{{$key}}">
                <option value="">Select Spouse Type</option>
                <option @if(!empty($nominee_details) && $nominee_details->is_2nd_spouse == 0) selected @endif value="1">1</option>
                <option @if(!empty($nominee_details) && $nominee_details->is_2nd_spouse == 1) selected @endif value="2">2</option>
            </select>
            <label id="is_2nd_spouse_{{$key}}-error" class="error error-msg text-danger" for="is_2nd_spouse_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6 is_second_spouse" id="is_second_spouse_death_date_{{$key}}">
        <div class="form-group">
            <label>Death Date of 1st Spouse<span class="text-danger">*</span></label>
            <div id="nominee-datepicker" class="input-group date">
                <input type="text" class="form-control datepickerClass 1st_spouse_death_date" data-key="{{$key}}" name="nominee[{{$key}}][1st_spouse_death_date]" id="1st_spouse_death_date_{{$key}}" value="{{ !empty($nominee_details) ? \Carbon\Carbon::parse($nominee_details->{'1st_spouse_death_date'})->format('m/d/Y') : '' }}" readonly>
                <span class="input-group-addon input-group-append border-left">
                    <span class="mdi mdi-calendar input-group-text"></span>
                </span>
            </div>
            <label id="1st_spouse_death_date_{{$key}}-error" class="error error-msg text-danger" for="1st_spouse_death_date_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6 is_second_spouse" id="is_second_spouse_death_cert_{{$key}}">
        <div class="form-group">
            <label>Death Certificate of 1st Spouse<span class="text-danger">*</span></label>
            <input type="hidden" name="nominee[{{$key}}][1st_spouse_death_certificate_path_hidden]" id="1st_spouse_death_certificate_path_{{$key}}_hidden" class="1st_spouse_death_certificate_path_hidden" data-key="{{$key}}">

            <input type="file" name="nominee[{{$key}}][1st_spouse_death_certificate_path]" id="1st_spouse_death_certificate_path_{{$key}}" class="file-upload-default 1st_spouse_death_certificate_path" data-key="{{$key}}" @if(!empty($nominee_details->{'1st_spouse_death_certificate_path'})) data-edit="1" @endif>
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <div class="input-group-append">
                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                </div>
            </div>
            <label id="1st_spouse_death_certificate_path_{{$key}}-error" class="error error-msg text-danger" for="1st_spouse_death_certificate_path_{{$key}}"></label>

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
            <label>Nominee Preference<span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control nominee_preference_id" name="nominee[{{$key}}][nominee_preference_id]"  data-key="{{$key}}" id="nominee_preference_id_{{$key}}">
                <option value="">Select Nominee Preference</option>
                @foreach($nominee_prefences as $nominee_prefence)
                    <option @if(!empty($nominee_details) && $nominee_details->nominee_preference_id == $nominee_prefence->id) selected @endif value="{{$nominee_prefence->id}}">{{$nominee_prefence->nominee_prefrence}}</option>
                @endforeach
            </select>
            <label id="nominee_preference_id_{{$key}}-error" class="error error-msg text-danger" for="nominee_preference_id_{{$key}}"></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Name of the Bank<span class="text-danger">*</span></label>
            <select class="js-example-basic-single select-drop bank bank_details" id="bank_{{$key}}" name="nominee[{{$key}}][bank]"  data-key="{{$key}}">
                <option value="">Select Bank</option>
                @foreach($banks as $bank)
                    <option @if(!empty($nominee_details) && $nominee_details->bank_id == $bank->id) selected @endif value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                @endforeach
            </select>
            <label id="bank_{{$key}}-error" class="error error-msg text-danger" for="bank_{{$key}}"></label>
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
            <label>Name of the Bank Branch<span class="text-danger">*</span></label>
            <select class="js-example-basic-single select-drop bank_branch branch" id="branch_{{$key}}" name="nominee[{{$key}}][branch]"  data-key="{{$key}}">
                <option value="">Select Branch</option>
                @foreach($bank_branch as $branch)
                    <option @if(!empty($nominee_details) && $nominee_details->bank_branch_id == $branch->id) selected @endif value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                @endforeach
            </select>
            <label id="branch_{{$key}}-error" class="error error-msg text-danger" for="branch_{{$key}}"></label>
        </div>
    </div>
    @else
    <div class="col-md-6">
        <div class="form-group">
            <label>Name of the Bank Branch<span class="text-danger">*</span></label>
            <select class="js-example-basic-single select-drop bank_branch branch" id="branch_{{$key}}" name="nominee[{{$key}}][branch]"  data-key="{{$key}}">
                <option value="">Select Branch</option>
            </select>
            <label id="branch_{{$key}}-error" class="error error-msg text-danger" for="branch_{{$key}}"></label>
        </div>
    </div>
    @endif
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleInputEmail3">IFSC Code</label>
            <input type="text" class="form-control ifsc_code" id="ifsc_code_{{$key}}" placeholder="IFSC Code" name="nominee[{{$key}}][ifsc_code]" data-key="{{$key}}" disabled>
            <label id="ifsc_code_{{$key}}-error" class="error error-msg text-danger" for="ifsc_code_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleInputEmail3">Savings Bank A/C No. (Single / Joint A/C with Spouse)<span class="text-danger">*</span></label>
            <input type="text" class="form-control savings_bank_account_no" id="savings_bank_account_no_{{$key}}" placeholder=" Enter Saving Bank Account No" name="nominee[{{$key}}][savings_bank_account_no]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->savings_bank_account_no : '' }}" minlength="9" maxlength="18">
            <label id="savings_bank_account_no_{{$key}}-error" class="error error-msg text-danger" for="savings_bank_account_no_{{$key}}"></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Marital Status<span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control marital_status" name="nominee[{{$key}}][marital_status]"  data-key="{{$key}}" id="marital_status_{{$key}}">
                <option value="">Select Status</option>
                @foreach($mstatus as $mstatusValue)
                    <option @if(!empty($nominee_details) && $nominee_details->marital_status_id == $mstatusValue->id) selected @endif value="{{$mstatusValue->id}}">{{$mstatusValue->marital_status_name}}</option>
                @endforeach
            </select>
            <label id="marital_status_{{$key}}-error" class="error error-msg text-danger" for="marital_status_{{$key}}"></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleInputEmail3">Aadhaar No.<span class="text-danger">*</span></label>
            <input type="text" class="form-control aadhaar_no" id="aadhaar_no_{{$key}}" placeholder="Enter Aadhaar no here." name="nominee[{{$key}}][aadhaar_no]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->nominee_aadhaar_no : '' }}" minlength="12" maxlength="12">
            <label id="aadhaar_no_{{$key}}-error" class="error error-msg text-danger" for="aadhaar_no_{{$key}}"></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Employment Status<span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control employement_status" name="nominee[{{$key}}][employement_status]"  data-key="{{$key}}" id="employement_status_{{$key}}">
                <option value="">Select Employment Status</option>
                <option @if(!empty($nominee_details) && $nominee_details->employement_status == 1) selected @endif value="1">Employed</option>
                <option @if(!empty($nominee_details) && $nominee_details->employement_status == 2) selected @endif value="2">Unemployed</option>
            </select>
            <label id="employement_status_{{$key}}-error" class="error error-msg text-danger" for="employement_status_{{$key}}"></label>
        </div>
    </div>                                            

    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleInputEmail3">Total Income per annum<span class="text-danger">*</span></label>
            <input type="text" class="form-control total_income_per_annum" id="total_income_per_annum_{{$key}}" placeholder="Enter Total Income per annum" name="nominee[{{$key}}][total_income_per_annum]"  data-key="{{$key}}"  value="{{ !empty($nominee_details) ? $nominee_details->total_income_per_annum : '' }}" maxlength="10">
            <label id="total_income_per_annum_{{$key}}-error" class="error error-msg text-danger" for="total_income_per_annum_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Physically Handicapped<span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control is_physically_handicapped" name="nominee[{{$key}}][is_physically_handicapped]"  data-key="{{$key}}" id="is_physically_handicapped_{{$key}}">
                <option value="">Select Physically Handicapped</option>
                <option @if(!empty($nominee_details) && $nominee_details->is_physically_handicapped == 1) selected @endif  value="1">Yes</option>
                <option @if(!empty($nominee_details) && $nominee_details->is_physically_handicapped == 2) selected @endif value="2">No</option>
            </select>
            <label id="is_physically_handicapped_{{$key}}-error" class="error error-msg text-danger" for="is_physically_handicapped_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6 physically_handicapped" id="physically_handicapped_cert_{{$key}}">
        <div class="form-group">
            <label>Upload Disability Certificate<span class="text-danger">*</span></label>
            <input type="hidden" name="nominee[{{$key}}][disability_certificate_path_hidden]" id="disability_certificate_path_{{$key}}_hidden" class="disability_certificate_path_hidden" data-key="{{$key}}">

            <input type="file" name="nominee[{{$key}}][disability_certificate_path]" id="disability_certificate_path_{{$key}}" class="file-upload-default disability_certificate_path" data-key="{{$key}}" @if(!empty($nominee_details->disability_certificate_path)) data-edit="1" @endif>
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <div class="input-group-append">
                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                </div>
            </div>
            <label id="disability_certificate_path_{{$key}}-error" class="error error-msg text-danger" for="disability_certificate_path_{{$key}}"></label>

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
            <label for="exampleInputEmail3">Disability Percentage<span class="text-danger">*</span></label>
            <input type="text" class="form-control disability_percentage" id="disability_percentage_{{$key}}" placeholder="Disability Percentage" name="nominee[{{$key}}][disability_percentage]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->disability_percentage : '' }}">
            <label id="disability_percentage_{{$key}}-error" class="error error-msg text-danger" for="disability_percentage_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleInputEmail3">Amount / Share payable to Each<span class="text-danger">*</span></label>
            <input type="text" class="form-control pension_amount_share_percentage" id="pension_amount_share_percentage_{{$key}}" placeholder="Enter Amount / Share payable to Each" name="nominee[{{$key}}][pension_amount_share_percentage]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->pension_amount_share_percentage : '' }}" maxlength="3">
            <label id="pension_amount_share_percentage_{{$key}}-error" class="error error-msg text-danger" for="pension_amount_share_percentage_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Minor<span class="text-danger">*</span></label>
            <select class="js-example-basic-single form-control is_minor" name="nominee[{{$key}}][is_minor]"  data-key="{{$key}}" id="is_minor_{{$key}}">
                <option value="">Select Minor</option>
                <option @if(!empty($nominee_details) && $nominee_details->is_minor == 1) selected @endif value="1">Yes</option>
                <option @if(!empty($nominee_details) && $nominee_details->is_minor == 0) selected @endif value="0">No</option>
            </select>
            <label id="is_minor_{{$key}}-error" class="error error-msg text-danger" for="is_minor_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6 is_legal" id="is_legal_name_{{$key}}">
        <div class="form-group">
            <label for="exampleInputEmail3">Legal Guardian Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control legal_guardian_name" id="legal_guardian_name_{{$key}}" placeholder="Enter Legal Guardian Name" name="nominee[{{$key}}][legal_guardian_name]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->legal_guardian_name : '' }}" minlength="4" maxlength="50">
            <label id="legal_guardian_name_{{$key}}-error" class="error error-msg text-danger" for="legal_guardian_name_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6 is_legal" id="is_legal_age_{{$key}}">
        <div class="form-group">
            <label for="exampleInputEmail3">Legal Guardian Age<span class="text-danger">*</span></label>
            <input type="text" class="form-control legal_guardian_age" id="legal_guardian_age_{{$key}}" placeholder="Enter Legal Guardian Age" name="nominee[{{$key}}][legal_guardian_age]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->legal_guardian_age : '' }}" maxlength="2">
            <label id="legal_guardian_age_{{$key}}-error" class="error error-msg text-danger" for="legal_guardian_age_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6 is_legal" id="is_legal_addr_{{$key}}">
        <div class="form-group">
            <label for="exampleInputEmail3">Address of Legal Guardian<span class="text-danger">*</span></label>
            <!-- <input type="text" class="form-control legal_guardian_addr" id="legal_guardian_addr_{{$key}}" placeholder="Enter Address of Legal Guardian" name="nominee[{{$key}}][legal_guardian_addr]"  data-key="{{$key}}" value="{{ !empty($nominee_details) ? $nominee_details->legal_guardian_addr : '' }}"> -->

            <textarea rows="4" class="form-control legal_guardian_addr" id="legal_guardian_addr_{{$key}}" placeholder="Enter Address of Legal Guardian" name="nominee[{{$key}}][legal_guardian_addr]"  data-key="{{$key}}" minlength="10" maxlength="200">{{ !empty($nominee_details) ? $nominee_details->legal_guardian_addr : '' }}</textarea>

            <label id="legal_guardian_addr_{{$key}}-error" class="error error-msg text-danger" for="legal_guardian_addr_{{$key}}"></label>
        </div>
    </div>

    <div class="col-md-6 is_legal" id="is_legal_attch_{{$key}}">
        <div class="form-group">
            <label>Legal Guardian Attachment<span class="text-danger">*</span></label>
            <input type="hidden" name="nominee[{{$key}}][legal_guardian_attachment_path_hidden]" data-key="{{$key}}" id="legal_guardian_attachment_path_{{$key}}_hidden" class="legal_guardian_attachment_path_hidden">

            <input type="file" name="nominee[{{$key}}][legal_guardian_attachment_path]" data-key="{{$key}}" id="legal_guardian_attachment_path_{{$key}}" class="file-upload-default legal_guardian_attachment_path" @if(!empty($nominee_details->legal_guardian_attachment_path)) data-edit="1" @endif>
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image" >
                <div class="input-group-append">
                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                </div>
            </div>
            <label id="legal_guardian_attachment_path_{{$key}}-error" class="error error-msg text-danger" for="legal_guardian_attachment_path_{{$key}}"></label>
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