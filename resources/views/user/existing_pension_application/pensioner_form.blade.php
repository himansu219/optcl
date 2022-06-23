@extends('user.layout.layout')

@section('section_content')
<style type="text/css">
    #upload-demo{
        width: 450px;
        height: 300px;
        padding-bottom:25px;
    }
</style>
<div class="content-wrapper">
    <nav aria-label="breadcrumb" role="navigation" class="bg-white">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{route('existing_pension_list')}}">Existing Pensioner</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
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
                        
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                                <div class="media">
                                    <div class="media-body">
                                        
                                        <h6 class="text-center-normal font-weight-bold mb-25">PARTICULARS OF EX-EMPLOYEE</h6>
                                        <hr>
                                        <br />
                                
                                        <form class="forms-sample" autocomplete="off" id="pension_form" action="" method="post" enctype="multipart/form-data">
                                           @csrf
                                              <div class="row form_1_">                                              
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Pensioner Type<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="pesioner_type" name="pesioner_type">
                                                            <option value="">Select Pensioner Type</option>
                                                            @foreach($pension_type as $pension_type_value)
                                                                <option value="{{$pension_type_value->id}}">{{$pension_type_value->pension_type}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="pesioner_type-error" class="error text-danger" for="pesioner_type"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">PPO No<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control ppo_number_format" id="old_ppo_no" name="old_ppo_no"  placeholder="Enter PPO No" minlength="11" maxlength="11">
                                                        <label id="old_ppo_no-error" class="error text-danger" for="old_ppo_no"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Attach PPO File<span class="text-danger">*</span></label>
                                                        <input type="file" name="attached_ppo_certificate" id="attached_ppo_certificate" class="file-upload-default">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload PPO File">
                                                            <div class="input-group-append">
                                                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                            </div>
                                                        </div>
                                                        <label id="attached_ppo_certificate-error" class="error mt-2 text-danger" for="attached_ppo_certificate"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">Pensioner Name (In Block Letter)<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control alpha" id="pensioner_name" name="pensioner_name" placeholder="Pensioner Name" minlength="4" maxlength="50">
                                                        <label id="pensioner_name-error" class="error text-danger" for="pensioner_name"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">Mobile No</label>
                                                        <input type="text" class="form-control only_number" id="mobile_number" name="mobile_number" placeholder="Mobile Number" minlength="10" maxlength="10">
                                                        <label id="mobile_number-error" class="error text-danger" for="mobile_number"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">Aadhaar No.</label>
                                                        <input type="text" class="form-control only_number" id="aadhaar_number" name="aadhaar_number" placeholder="Aadhaar No." minlength="12" maxlength="12">
                                                        <label id="aadhaar_number-error" class="error text-danger" for="aadhaar_number"></label>
                                                    </div>
                                                </div>    
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">Employee Code</label>
                                                        <input type="text" class="form-control only_number" id="employee_code" name="employee_code" placeholder="Employee Code" minlength="5" maxlength="5">
                                                        <label id="employee_code-error" class="error text-danger" for="employee_code"></label>
                                                    </div>
                                                </div>  
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">PAN<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control anns" id="employee_pan" name="employee_pan" placeholder="PAN" minlength="10" maxlength="10">
                                                        <label id="employee_pan-error" class="error text-danger" for="employee_pan"></label>
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
                                                    <input type="hidden" name="age_year" id="age_year">
                                                    <input type="hidden" name="age_month" id="age_month">
                                                    <input type="hidden" name="age_days" id="age_days">
                                                    <div class="form-group">                                                        
                                                        <label class="">Date of Birth<span class="text-danger">*</span> <span id="age_dob"></span></label>
                                                        <div id="inline-datepicker" class="input-group date ">
                                                            <input type="text" class="form-control" id="dob" name="dob" placeholder="Date of Birth" readonly value="{{ isset($employee_master->date_of_birth) ? date('d/m/Y',strtotime($employee_master->date_of_birth)): ''}}">
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="dob-error" class="error text-danger" for="dob"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="">Date of Retirement<span class="text-danger">*</span></label>
                                                        <div id="datepicker-popup" class="input-group date">
                                                            <input type="text" class="form-control" readonly id="dor" name="dor"  placeholder="Date of Retirement">
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="dor-error" class="error text-danger " for="dor"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="date_of_death_div">
                                                    <div class="form-group">
                                                        <label class="">Date of Death<span class="text-danger">*</span></label>
                                                        <div id="" class="input-group date datepicker-upto-current">
                                                            <input type="text" class="form-control" readonly id="date_of_death" name="date_of_death"  placeholder="Date of Death">
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="date_of_death-error" class="error text-danger " for="date_of_death"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">Basic Pension Amount<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control amount_type" id="basic_pension_amount" name="basic_pension_amount" placeholder="Basic Pension Amount">
                                                        <label id="basic_pension_amount-error" class="error text-danger" for="basic_pension_amount"></label>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="">Basic Pension Effective Date<span class="text-danger">*</span></label>
                                                        <div id="" class="input-group">
                                                            <input type="text" class="form-control amount_type" readonly id="basic_pension_effective_date" placeholder="Basic Pension Effective Date" name="basic_pension_effective_date">
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="basic_pension_effective_date-error" class="error text-danger " for="basic_pension_effective_date"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Additional Pension Amount<span class="text-danger">*</span> <span id="additional_pension_percentage"></span></label>
                                                        <input type="text" class="form-control" id="additional_pension_amount" name="additional_pension_amount" placeholder="Additional Pension Amount" readonly>
                                                        <label id="additional_pension_amount-error" class="error text-danger" for="additional_pension_amount"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6" id="enhanced_pension_amount_div">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Enhanced Pension Amount<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="enhanced_pension_amount" name="enhanced_pension_amount" placeholder="Enhanced Pension Amount" readonly>
                                                        <label id="enhanced_pension_amount-error" class="error text-danger" for="enhanced_pension_amount"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6" id="enhanced_pension_end_date_div">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">End Date<span class="text-danger">*</span></label>
                                                        <div id="" class="input-group date datepicker-default">
                                                            <input type="text" class="form-control" readonly id="enhanced_pension_end_date" name="enhanced_pension_end_date" placeholder="End Date">
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="enhanced_pension_end_date-error" class="error text-danger" for="enhanced_pension_end_date"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6" id="normal_pension_amount_div">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Normal Pension Amount<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="normal_pension_amount" name="normal_pension_amount" placeholder="Normal Pension Amount" readonly>
                                                        <label id="normal_pension_amount-error" class="error text-danger" for="normal_pension_amount"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6" id="normal_pension_effective_date_div">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Effective Date<span class="text-danger">*</span></label>
                                                        <div id="" class="input-group date datepicker-default">
                                                            <input type="text" class="form-control" readonly id="normal_pension_effective_date" name="normal_pension_effective_date">
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="normal_pension_effective_date-error" class="error text-danger" for="normal_pension_effective_date"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Category<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="ti_category_id" name="ti_category_id">
                                                            <option value="">Select Category</option>
                                                            @foreach($category_ti as $category_ti_data)
                                                                <option value="{{$category_ti_data->id}}">{{$category_ti_data->category_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="ti_category_id-error" class="error text-danger" for="ti_category_id"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">TI Amount (Percentage)<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="ti_amount" name="ti_amount" 
                                                        placeholder="TI Amount" readonly>
                                                        <input type="hidden" id="hidden_ti_amount" name="hidden_ti_amount">
                                                        <input type="hidden" id="hidden_ti_percentage" name="hidden_ti_percentage">
                                                        <label id="ti_amount-error" class="error text-danger" for="ti_amount"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
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
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Name Address of the Branch<span class="text-danger">*</span> </label>
                                                        <select class="js-example-basic-single" style="width:100%" id="branch_name_address" name="branch_name_address">
                                                            <option value="">Select Branch</option>
                                                        </select>
                                                        <label id="branch_name_address-error" class="error mt-2 text-danger" for="branch_name_address"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">IFSC Code<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" placeholder="IFSC Code" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">MICR Code</label>
                                                        <input type="text" class="form-control" id="micr_code" name="micr_code" placeholder="MICR Code" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Savings Bank A/C No. (Single or Joint Account with Spouse)<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="saving_bank_ac_no" maxlength="18" name="saving_bank_ac_no" placeholder="Savings Bank A/C No." value="{{ isset($personal_details->savings_bank_account_no) ? $personal_details->savings_bank_account_no: ''}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="tax_type_div">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Tax Type<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="tax_type" name="tax_type">
                                                            <option value="">Select Tax Type</option>
                                                            @foreach($tax_master_list as $tax_master_value)
                                                                <option value="{{ $tax_master_value->id }}">{{ $tax_master_value->type_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="tax_type-error" class="error text-danger" for="tax_type"></label>
                                                    </div>
                                                </div>

                                            </div>
                                            <h6 class="text-center-normal form-middle-heading" id="fam_title_h6">Family Pensioner Details</h6>
                                            <i id="family_hr_line"></i>
                                            <div class="row">
                                                <div class="col-md-6" id="relation_type_div">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Relation Type<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="relation_type" name="relation_type">
                                                            <option value="">Select Relation Type</option>
                                                            @foreach($relations as $relation)
                                                                <option value="{{$relation->id}}">{{$relation->relation_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label id="relation_type-error" class="error text-danger" for="relation_type"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6" id="current_status_div">
                                                    <div class="form-group">
                                                        <label for="exampleInputPassword4">Current Status<span class="text-danger">*</span></label>
                                                        <select class="js-example-basic-single form-control" id="current_status" name="current_status">
                                                            <option value="">Select Current Status</option>
                                                        </select>
                                                        <label id="current_status-error" class="error text-danger" for="current_status"></label>
                                                    </div>
                                                </div>                                                
                                                <div class="col-md-6" id="rel_cur_status_end_date_div">
                                                    <div class="form-group">
                                                        <label class="">End Date<span class="text-danger">*</span></label>
                                                        <div id="" class="input-group date datepicker-from-current">
                                                            <input type="text" class="form-control" id="rel_cur_status_end_date" name="rel_cur_status_end_date" placeholder="Family Pensioner End Date" readonly>
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="rel_cur_status_end_date-error" class="error text-danger" for="rel_cur_status_end_date"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="fam_pen_name_div">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Family Pensioner Name<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control alpha" id="nominee_name" name="nominee_name" placeholder="Family Pensioner Name">
                                                        <label id="nominee_name-error" class="error text-danger" for="nominee_name"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="fam_pen_mob_div">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Family Pensioner Mobile No.<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="nominee_mob_no" name="nominee_mob_no" placeholder="Family Pensioner Mobile No." maxlength="10">
                                                        <label id="nominee_mob_no-error" class="error text-danger" for="nominee_mob_no"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="fam_pen_aadhar_div">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Family Pensioner Aadhaar No.<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="nominee_aadhar_no" name="nominee_aadhar_no" placeholder="Family Pensioner Aadhaar No." maxlength="12">
                                                        <label id="nominee_aadhar_no-error" class="error text-danger" for="nominee_aadhar_no"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="fam_pen_dob_div">
                                                    <div class="form-group">
                                                        <label class="">Family Pensioner Date of Birth<span class="text-danger">*</span></label>
                                                        <div id="" class="input-group date datepicker-upto-current">
                                                            <input type="text" class="form-control" id="nominee_dob" name="nominee_dob" placeholder="Family Pensioner Date of Birth" readonly>
                                                            <span class="input-group-addon input-group-append">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="nominee_dob-error" class="error text-danger" for="nominee_dob"></label>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <h6 class="text-center-normal form-middle-heading">Gross Pension</h6>
                                            <div class="row">
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="">Gross Pension Amount<span class="text-danger">*</span></label>
                                                        <div id="" class="input-group">
                                                            <input type="text" class="form-control" id="gross_pension" name="gross_pension" placeholder="Gross Pension Amount" readonly>
                                                        </div>
                                                        <label id="gross_pension-error" class="error text-danger" for="gross_pension"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <h6 class="text-center-normal form-middle-heading">Commutation</h6>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-bordered" id="commutation_table_id">
                                                        <thead>
                                                            <tr>
                                                                <th>Commutation</th>
                                                                <th>End Date</th>
                                                                <th width="5%">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control commutation_amount_class amount_type" id="commutation_amount_1" name="commutation_amount[]" placeholder="Commutation Amount">
                                                                    <label id="commutation_amount_1-error" class="error text-danger" for="commutation_amount_1"></label>
                                                                </td>
                                                                <td>
                                                                    <div id="" class="input-group date datepicker-from-current">
                                                                        <input type="text" class="form-control commutation_amount_end_date_class" id="commutation_amount_end_date_1" name="commutation_amount_end_date[]" placeholder="Commutation End Date" readonly>
                                                                        <span class="input-group-addon input-group-append">
                                                                            <span class="mdi mdi-calendar input-group-text"></span>
                                                                        </span>
                                                                    </div>
                                                                    <label id="commutation_amount_end_date_1-error" class="error text-danger" for="commutation_amount_end_date_1"></label>
                                                                </td>
                                                                <td>
                                                                <a class="commutation-add" href="javascript:void(0)" id="commutation_add_button"><i class="icon-plus"></i></a>
                                                                    <!-- <button type="button" class="btn btn-primary btn-next mr-2" id="commutation_add_button" ></button> -->
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <h6 class="text-center-normal form-middle-heading">Total Income</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="">Total Income Amount<span class="text-danger">*</span></label>
                                                        <div id="" class="input-group">
                                                            <input type="text" class="form-control" id="total_income_amount" name="total_income_amount" placeholder="Total Income Amount" readonly>
                                                        </div>
                                                        <label id="total_income_amount-error" class="error text-danger" for="total_income_amount"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="commutation_count" id="commutation_count" value="1">
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <button type="submit" name="submit" class="btn btn-primary btn-next mr-2" id="submit_form_1" >Submit</button>
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
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <input type="hidden" id="file_name" name="file_name" value="">
                    <!-- <img id="image"> -->
                    <div id="upload-demo" class="center-block"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>    
</div>

 @endsection
 @section('page-script')

  <script type="text/javascript">
    $(document).ready(function() {
        $('.datepickerClass').datepicker({
            autoclose: true,
        });

        $.validator.addMethod("onlyNumber", function (value, element) {
            return this.optional(element) || /^[0-9\s-]*$/.test(value);
        }, "Please use only numbers.");

        // for block letter in name field
        $("#pensioner_name").on("keyup",function(){
            this.value = this.value.toUpperCase();
        }); 
        
       

       // Append area
       $('#commutation_add_button').on('click',function(){
            var commutation_count = $("#commutation_count").val();            
            if(commutation_count < 3){
                //console.log($('#pension_form').valid());
                if($('#pension_form').valid()) {
                    // Updating the row count
                    var totRow = parseFloat(commutation_count)+1;
                    $("#commutation_count").val(totRow); 
                    $('#commutation_table_id').append('<tr><td>'+
                        '<input type="text" class="form-control commutation_amount_class amount_type" id="commutation_amount_'+totRow+'" name="commutation_amount['+totRow+']" placeholder="Commutation Amount">'+
                        '<label id="commutation_amount_'+totRow+'-error" class="error text-danger" for="commutation_amount_'+totRow+'"></label>'+
                        '</td>'+
                        '<td>'+
                            '<div id="" class="input-group date datepicker-from-current">'+
                                '<input type="text" class="form-control commutation_amount_end_date_class" id="commutation_amount_end_date_'+totRow+'" name="commutation_amount_end_date['+totRow+']" placeholder="Commutation End Date" readonly>'+
                                '<span class="input-group-addon input-group-append">'+
                                    '<span class="mdi mdi-calendar input-group-text"></span>'+
                                '</span>'+
                            '</div>'+
                            '<label id="commutation_amount_end_date_'+totRow+'-error" class="error text-danger" for="commutation_amount_end_date_'+totRow+'"></label>'+
                        '</td>'+
                        '<td>'+
                            '<a class="commutation-add commutation_remove_button" href="javascript:void(0)" id="commutation_add_button"><i class="icon-trash"></i></a>'+
                        '</td>'+
                    '</tr>');
                    var endDate = new Date();
                    $('.datepicker-from-current').datepicker({
                      enableOnReadonly: true,
                      todayHighlight: true,
                      autoclose: true,
                      format: 'dd/mm/yyyy',
                      startDate: endDate,
                    });
                    validate_commutation();
                }
            }            
       });

        function validate_commutation(){
            $('.commutation_amount_class').each(function() {
                $(this).rules("add", {
                    required: true,
                    amount_only:true,
                    messages: {
                        required: "Please enter commutation amount",
                    }
                });
            });

            $('.commutation_amount_end_date_class').each(function() {
                $(this).rules("add", {
                    required: true,
                    //commutation_end_date: true,
                    messages: {
                        required: "Please select commutation end date",
                    }
                });
            });
        }

        /* $.validator.addMethod("commutation_end_date", function (value, element) {
            console.log(value);
            //return this.optional(element) || /^\d{1,8}(?:\.\d{1,2})?$/.test(value);
        }, "Please enter in amount format"); */

        $("#commutation_table_id").on('click','.commutation_remove_button',function(){
            var commutation_count = $("#commutation_count").val();
            $("#commutation_count").val(parseFloat(commutation_count)-1);
            $(this).closest('tr').remove();
        });

        function calculate_gross_amount(){
            //$("#submit_form_1").attr('disabled',true);
            var pesioner_type = $("#pesioner_type").val();
            var basic_pension_amount = $("#basic_pension_amount").val();
            var gross_amount = 0;
            var additional_pension_amount = $("#additional_pension_amount").val();
            var hidden_ti_amount = $("#hidden_ti_amount").val();
            if(pesioner_type == 1){
                //console.log('one');
                // Service Pension
                if(basic_pension_amount !="" && additional_pension_amount !="" && hidden_ti_amount !=""){
                    gross_amount = parseFloat(basic_pension_amount)+parseFloat(additional_pension_amount)+parseFloat(hidden_ti_amount);
                }else{
                    gross_amount = 0
                }                
            } else if(pesioner_type == 2){
                // Family Pension
                var dor = $('#dor').val();
                var dob = $("#dob").val();
                var date_of_death = $("#date_of_death").val();

                $.post("{{ route('get_family_pension_pension_amount_details') }}",{
                    "_token": "{{ csrf_token() }}",
                    dob:dob,
                    dor:dor,
                    date_of_death:date_of_death,
                    basic_pension_amount:basic_pension_amount,
                },function(response){
                    $('.page-loader').removeClass('d-flex');
                    console.log(response);
                    var obj = JSON.parse(response);
                    enhanced_pension_amount = obj.enhanced_pension_amount;
                    normal_pension_amount = obj.normal_pension_amount;
                    $("#enhanced_pension_amount").val(enhanced_pension_amount);
                    $("#normal_pension_amount").val(normal_pension_amount);
                });
                var calculation_pension_amount = 0;
                if(basic_pension_amount !="" && additional_pension_amount !="" && hidden_ti_amount !=""){
                    if(enhanced_pension_amount > 0){
                        calculation_pension_amount = enhanced_pension_amount;
                    }else{
                        calculation_pension_amount = normal_pension_amount;
                    }

                    gross_amount = parseFloat(calculation_pension_amount)+parseFloat(additional_pension_amount)+parseFloat(hidden_ti_amount);
                }else{
                    //console.log('three');
                    gross_amount = 0
                }
            }else {
                // In case of blank value
                gross_amount = 0;
            }
            $("#gross_pension").val(gross_amount);
            total_income_calculation();
            //$("#submit_form_1").attr('disabled',false);
        }
        $('body').on('keyup', '.commutation_amount_class', function(){
            total_income_calculation();
        });

        function total_income_calculation(){
            var total_commutation = 0;
            var comm_value = [];
            $('.commutation_amount_class').each(function(){
                //alert('123');
                //console.log($(this).val());
                var comm_data = $(this).val();
                //comm_value.push(comm_data);
                total_commutation += parseFloat(comm_data);
            });
            var gross_sal = $("#gross_pension").val();
            var besic_amount_percentage = (gross_sal/100)*40;
            if(besic_amount_percentage > 0 && total_commutation > besic_amount_percentage){
                swal("Commutation value should be less than or equal to 40% of basic amount");
                $('.commutation_amount_class').val('');
                $("#total_income_amount").val('');
            }else{
                var gross_sal_value = isNaN(gross_sal) ? 0 : gross_sal;
                var total_income_value = (gross_sal_value - total_commutation)*12;
                //console.log(total_data);
                $("#total_income_amount").val(total_income_value);
            }
            console.log('test');
        }

        /* var total_data = 0;
        $(this).each(function(){
            //alert('123');
            //console.log($(this).val());
            var comm_data = $(this).val();
            //comm_value.push(comm_data);
            total_data = parseFloat(total_data) + parseFloat(comm_data);
            $("#total_income_amount").val(total_data);
        }); */
        
        

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

        $("#relation_type").on('change', function(){
            $('.page-loader').addClass('d-flex');
            var relation_type = $(this).val();
            $.post("{{ route('existing_pensioner_get_relation_type') }}",{
                "_token": "{{ csrf_token() }}",
                relation_type:relation_type
            },function(response){
                $('.page-loader').removeClass('d-flex');
                $("#current_status").html(response);
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

        $("#relation_type_div").hide();
        $("#current_status_div").hide();
        $("#closing_date_div").hide();
        $("#date_of_death_div").hide();
        $("#attached_date_of_death_certificate_div").hide();

        $("#enhanced_pension_amount_div").hide();
        $("#enhanced_pension_end_date_div").hide();
        $("#normal_pension_amount_div").hide();
        $("#normal_pension_effective_date_div").hide();
        $("#fam_title_h6").hide();
        $("#family_hr_line").hide();
        $("#fam_pen_name_div").hide();
        $("#fam_pen_mob_div").hide();
        $("#fam_pen_aadhar_div").hide();
        $("#fam_pen_dob_div").hide();
        $("#rel_cur_status_end_date_div").hide();

        $("#pesioner_type").on('change', function(){
            var pesioner_type = $(this).val();
            if(pesioner_type == "2"){
                $("#relation_type_div").show();
                $("#current_status_div").show();
                $("#closing_date_div").show();
                $("#date_of_death_div").show();
                $("#attached_date_of_death_certificate_div").show();
                // -------------------
                $("#enhanced_pension_amount_div").show();
                $("#enhanced_pension_end_date_div").show();
                $("#normal_pension_amount_div").show();
                $("#normal_pension_effective_date_div").show();
                $("#fam_title_h6").show();
                $("#family_hr_line").show();
                $("#fam_pen_name_div").show();
                $("#fam_pen_mob_div").show();
                $("#fam_pen_aadhar_div").show();
                $("#fam_pen_dob_div").show();
                $("#tax_type_div").hide();
                $("#tax_type").val('');

                //$("#rel_cur_status_end_date_div").show();
                // Clear Data
                $("#gross_pension").val("");
                $("#dob").val("");
                $("#age_year").val("");
                $("#age_month").val("");
                $("#age_days").val("");
                $("#age_dob").html("");
                $("#dor").val("");
                $("#date_of_death").val("");
                $("#basic_pension_amount").val("");
                $("#basic_pension_effective_date").val("");
                $("#additional_pension_amount").val("");
                $("#enhanced_pension_amount").val("");
                $("#enhanced_pension_end_date").val("");
                $("#normal_pension_amount").val("");
                $("#normal_pension_effective_date").val("");
                $("#ti_category_id").val("");
                $("#ti_amount").val("");
                $("#hidden_ti_amount").val("");
                $("#hidden_ti_percentage").val("");
            }else{                
                $("#relation_type_div").hide();
                $("#current_status_div").hide();
                $("#closing_date_div").hide();
                $("#date_of_death_div").hide();
                $("#attached_date_of_death_certificate_div").hide();
                $("#tax_type_div").show();
                // -------------------
                $("#enhanced_pension_amount_div").hide();
                $("#enhanced_pension_end_date_div").hide();
                $("#normal_pension_amount_div").hide();
                $("#normal_pension_effective_date_div").hide();
                $("#fam_title_h6").hide();
                $("#family_hr_line").hide();
                $("#fam_pen_name_div").hide();
                $("#fam_pen_mob_div").hide();
                $("#fam_pen_aadhar_div").hide();
                $("#fam_pen_dob_div").hide();
                //$("#rel_cur_status_end_date_div").hide();
                $("#gross_pension").val("");
                // Clear Data
                $("#gross_pension").val("");
                $("#dob").val("");
                $("#age_year").val("");
                $("#age_month").val("");
                $("#age_days").val("");
                $("#age_dob").html("");
                $("#dor").val("");
                $("#date_of_death").val("");
                $("#basic_pension_amount").val("");
                $("#basic_pension_effective_date").val("");
                $("#additional_pension_amount").val("");
                $("#enhanced_pension_amount").val("");
                $("#enhanced_pension_end_date").val("");
                $("#normal_pension_amount").val("");
                $("#normal_pension_effective_date").val("");
                $("#ti_category_id").val("");
                $("#ti_amount").val("");
                $("#hidden_ti_amount").val("");
                $("#hidden_ti_percentage").val("");
            }
            $(".js-example-basic-single").select2();
        });

        $("#current_status").on("change", function(){
            if($(this).val() == 9){
                $("#rel_cur_status_end_date_div").show();
            }else{
                $("#rel_cur_status_end_date_div").hide();
            }
        });

        function tiCalculationResult(){
            var ti_category_id = $("#ti_category_id").val();
            var basic_amount = $("#basic_pension_amount").val();
            var additional_pension_amount = $("#additional_pension_amount").val();
            var enhanced_pension_amount = $("#enhanced_pension_amount").val();
            var enhanced_pension_end_date = $("#enhanced_pension_end_date").val();
            var normal_pension_amount = $("#normal_pension_amount").val();
            var normal_pension_effective_date = $("#normal_pension_effective_date").val();
            var pesioner_type = $("#pesioner_type").val();
            var age_year = $("#age_year").val();
            var age_month = $("#age_month").val();
            var age_days = $("#age_days").val();
            if(basic_amount != ""){
                $('.page-loader').addClass('d-flex');
                $.post('{{ route("category_ta_percentage_amount") }}',{
                    "_token": "{{ csrf_token() }}",
                    "ti_category_id": ti_category_id,
                    "basic_amount": basic_amount,
                    "additional_pension_amount": additional_pension_amount,
                    "enhanced_pension_amount": enhanced_pension_amount,
                    "enhanced_pension_end_date": enhanced_pension_end_date,
                    "normal_pension_amount": normal_pension_amount,
                    "normal_pension_effective_date": normal_pension_effective_date,
                    "pesioner_type": pesioner_type,
                    "age_year": age_year,
                    "age_month": age_month,
                    "age_days": age_days,
                },function(response){
                    $('.page-loader').removeClass('d-flex');
                    var resObj = JSON.parse(response);
                    $("#ti_amount").val(resObj.display_value);
                    $("#hidden_ti_amount").val(resObj.da_amount);
                    $("#hidden_ti_percentage").val(resObj.da_percentage); 
                });
            }
            setTimeout(calculate_gross_amount, 3000);
            setTimeout(tiAmtVal, 1500);
        }

        $("#ti_category_id").on('change', function(){  
            setTimeout(tiCalculationResult, 1500);
        });

        function tiAmtVal(){
            $('#ti_amount').valid();
        }

        $("#basic_pension_amount").on('change', function(){            
            var basic_amount = $(this).val();
            var ti_category_id = $("#ti_category_id").val();
            if(ti_category_id != "" && basic_amount != ""){
                $('.page-loader').addClass('d-flex');
                $.post('{{ route("category_ta_percentage_amount") }}',{
                    "_token": "{{ csrf_token() }}",
                    "ti_category_id":ti_category_id,
                    "basic_amount":basic_amount,
                },function(response){
                    $('.page-loader').removeClass('d-flex');
                    var resObj = JSON.parse(response);
                    $("#ti_amount").val(resObj.display_value);
                    $("#hidden_ti_amount").val(resObj.da_amount);
                    $("#hidden_ti_percentage").val(resObj.da_percentage);
                });
            }else{
                $("#ti_amount").val('');
            }
            setTimeout(calculate_gross_amount, 3000);
            setTimeout(tiAmtVal, 1500);
            total_income_calculation();
        });

        var $uploadCrop,
        rawImg,
        imageId;

        $('#crop_image').on('hidden.bs.modal', function(){
            var filename = $(this).closest('#crop_image').find($('#file_name'));
            var val = $(filename).val();

            $('#'+val).parent().find('.file-upload-info').val('');

            $('#'+val).val('');

            $('#upload-demo').croppie('destroy');
        });


        $('#attached_ppo_certificate').on('change', function() {
            check_upload_file(this, 'attached_ppo_certificate');
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
                        /*case 'png':
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
                            break;*/
                        case 'pdf':
                            $("#" + id + "-error").html('');
                            $("#" + id + "-error").hide();
                            break;
                        default:
                            $("#" + id + "-error").html('Please upload only pdf file');
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

        $(document).on('click', '#crop', function() {
            $('.page-loader').addClass('d-flex'); 
            $('#crop_image').modal('hide');
            $uploadCrop.croppie('result', {
                type: 'blob',
                format: 'jpeg',
                size: {width: 150, height: 200}
            }).then(function (resp) {

                var formData = new FormData();
                var employee_id = $('#employee_id').val();
                var edit = $('#edit').val();
                var file_name = $('#file_name').val();

                var avatar = URL.createObjectURL(resp);

                formData.append(file_name, resp, 'avatar.jpg');
                formData.append('employee_id', employee_id);
                formData.append('edit', edit);
                formData.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('save_existing_application') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false, // NEEDED, DON'T OMIT THIS
                    success:function(response) {
                       // $('#'+file_name).val('');
                        $('.page-loader').removeClass('d-flex');
                        $("#hidden_ppo_file").val(response);                        
                    },
                    error:function(response) {

                    }
                });
            });
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
            $.post('{{ route("existing_pensioner_form_save_as_draft") }}',{
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
        
        $(".js-example-basic-single").on('change', function() {
            $(this).valid();
        });

        $("#doj").on('change', function() {
            $(this).valid();
            //$('#dor').valid();
        });

        $("#dor").on('change', function() {
            $(this).valid();  
            var dor_date = $(this).val();
            if(dor_date != null && dor_date != ""){
                var selectedData = dor_date.split('/');
                var newDate = selectedData[1] + '/' + selectedData[0] +'/' + selectedData[2]; 
                var rDate = new Date(newDate);
                rDate.setDate(rDate.getDate() + 1);
                var dd = rDate.getDate();
                var mm = rDate.getMonth()+1;
                var yyyy = rDate.getFullYear();
                if (dd < 10) { dd = '0' + dd; }
                if (mm < 10) { mm = '0' + mm; }
                var todayDate = dd +"/"+ mm +"/"+yyyy;
                $("#basic_pension_effective_date").val(todayDate);
                setTimeout(basicPensionEffectiveDateVal, 1500);
            }

        });

        function basicPensionEffectiveDateVal() {
            $("#basic_pension_effective_date").valid();           
        }

        $("#dob").on('change', function() {
            $(this).valid();
            $('#doj').val('');
            $('#dor').val('');
            $('#basic_pension_effective_date').val('');
            var dob = $(this).val();            
            $.post("{{ route('get_age_additional_pension') }}",{
                "_token": "{{ csrf_token() }}",
                dob:dob
            },function(response){
                $('.page-loader').removeClass('d-flex');
                console.log(response);
                var obj = JSON.parse(response);
                $("#age_dob").html("(Age - "+ obj.years+ " Years " +obj.months+ " Months " +obj.days+ " Days)");
                $("#age_year").val(obj.years);
                $("#age_month").val(obj.months);
                $("#age_days").val(obj.days);

                var basic_amount = $("#basic_pension_amount").val();
                var additional_pension_amount = $("#additional_pension_amount").val();
                $.post("{{ route('get_additional_pension_amount') }}",{
                    "_token": "{{ csrf_token() }}",
                    basic_amount:basic_amount,
                    year_value:obj.years,
                    month_value:obj.months,
                    day_value:obj.days,
                },function(response){
                    $('.page-loader').removeClass('d-flex');
                    var obj = JSON.parse(response);
                    $("#additional_pension_amount").val(obj.increment_value);
                    $("#additional_pension_percentage").html(obj.increment_percentage);
                });
                
            });
            setTimeout(calculate_gross_amount, 3000);
            setTimeout(addPenVal, 1500);
        });

        function addPenVal(){
            $('#additional_pension_amount').valid();
        }

        $("#basic_pension_amount").on("change", function(){
            var basic_amount = $(this).val();
            var year_value = $("#age_year").val();
            var month_value = $("#age_month").val();
            var day_value = $("#age_days").val();
            $.post("{{ route('get_additional_pension_amount') }}",{
                "_token": "{{ csrf_token() }}",
                basic_amount:basic_amount,
                year_value:year_value,
                month_value:month_value,
                day_value:day_value,
            },function(response){
                $('.page-loader').removeClass('d-flex');
                var obj = JSON.parse(response);
                $("#additional_pension_amount").val(obj.increment_value);
                $("#additional_pension_percentage").html(obj.increment_percentage);
            });
            setTimeout(calculate_gross_amount, 3000);
        });
        

        $.validator.addMethod("amount_only", function (value, element) {
            return this.optional(element) || /^\d{1,8}(?:\.\d{1,2})?$/.test(value);
        }, "Please enter in amount format");

        /* $.validator.addMethod("ppo_format", function (value, element) {
            return this.optional(element) || /^[A-Z0-9]+(\/[A-Z0-9]+)*$/.test(value);
        }, "Please enter valid PPO no"); */

        $.validator.addMethod("ppo_format", function (value, element) {
           return this.optional(element) || /[0-9]{4}\b[\/]{1}[0-9]{1}\b[\/]{1}[0-9]{4}\b/i.test(value); 
        }, "Please enter valid PPO no");

        $("#employee_pan").on("keyup",function(){
            this.value = this.value.toUpperCase();
        });

        $.validator.addMethod("panNo", function (value, element) {
            return this.optional(element) || /[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(value);
        }, "Invalid PAN no");

        $("#pension_form").validate({
            onkeyup:false,
            rules: {
                "tax_type": {
                    required: {
                        depends:function(){
                            if($('#pesioner_type').val() == 2){
                                return false;
                            }else{
                                return true;
                            }
                        }
                    },
                },
                "pesioner_type": {
                    required: true,
                },
                "old_ppo_no": {
                    required: true,
                    ppo_format: true,
                },
                "attached_ppo_certificate":{
                    required: true,
                },
                "pensioner_name":{
                    required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                },
                "mobile_number":{
                    required: false,
                },
                "aadhaar_number":{
                    required: false,
                    onlyNumber: true,
                    minlength: 12,
                    maxlength: 12,
                    remote:{
                        url:'{{ route("validate_aadhar_number") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            }
                        }
                    },
                },
                "employee_pan":{
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
                "employee_code":{
                    required: false,
                },
                "gender":{
                    required: true,
                },
                "designation":{
                    required: true,
                },
                "dob":{
                    required: true,
                },
                "dor":{
                    required: true,
                },
                "date_of_death":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },                
                "enhanced_pension_amount":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },              
                "enhanced_pension_end_date":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },             
                "normal_pension_amount":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },            
                "normal_pension_effective_date":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },          
                "relation_type":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },          
                "current_status":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },     
                "rel_cur_status_end_date":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            var relation_type = $("#relation_type").val();
                            var current_status = $("#current_status").val();
                            if(pesioner_type == "2" && relation_type == 2 && current_status == 9){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                "basic_pension_amount":{
                    required: true,
                    amount_only: true,
                },
                "basic_pension_effective_date":{
                    required: true,
                },
                "additional_pension_amount":{
                    required: true,
                },
                "ti_category_id":{
                    required: true,
                },
                "ti_amount":{
                    required: true,
                },
                "bank_name":{
                    required: true,
                },
                "branch_name_address":{
                    required: true,
                },
                "ifsc_code":{
                    required: true,
                },
                "micr_code":{
                    required: false,
                },
                "saving_bank_ac_no":{
                    required: true,
                },
                "nominee_name":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                "nominee_mob_no":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                "nominee_aadhar_no":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                "nominee_dob":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == "2"){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                "gross_pension":{
                    required: true,
                },
                "commutation_amount[]":{
                    required: true,
                    amount_only:true
                },
                "commutation_amount_end_date[]":{
                    required: true,
                }, 
                "total_income_amount":{
                    required: true,
                },              
            },
            messages: {
                "tax_type": {
                    required: 'Please select tax type',
                },
                "pesioner_type": {                    
                    required: 'Please select pensioner type',
                },
                "old_ppo_no": {
                    required: 'Please enter PPO no',
                    minlength: 'Please enter at least 11 characters'
                },
                "attached_ppo_certificate":{
                    required: 'Please upload PPO file',
                },
                "pensioner_name":{
                    required: 'Please enter pensioner name',
                    minlength: 'Please enter at least 4 characters'
                },
                "mobile_number":{
                    minlength: 'Please enter at least 10 digits',
                },
                "aadhaar_number":{
                    minlength: 'Please enter at least 12 digits',
                    remote: 'Aadhaar No already exists',
                },
                "employee_pan":{
                    required: 'Please enter PAN',
                    remote: 'PAN no already exits',
                },
                "employee_code":{
                    minlength: 'Please enter at least 5 digits',
                },
                "gender":{
                    required: 'Please select gender',
                },
                "designation":{
                    required: 'Please select designation',
                },
                "dob":{
                    required: 'Please select date of birth',
                },
                "dor":{
                    required: 'Please select date of retiremet',
                },
                "date_of_death":{
                    required: 'Please select date of death',
                },                
                "enhanced_pension_amount":{
                    required: 'Please enter enhanced pension amount',
                },              
                "enhanced_pension_end_date":{
                    required: 'Please enter enhanced pension end date',
                },             
                "normal_pension_amount":{
                    required: 'Please enter normal pension amount',
                },            
                "normal_pension_effective_date":{
                    required: 'Please enter normal pension effective date',
                },          
                "relation_type":{
                    required: 'Please select relation type',
                },          
                "current_status":{
                    required: 'Please select current status',
                },     
                "rel_cur_status_end_date":{
                    required: 'Please select end date',
                },
                "basic_pension_amount":{
                    required: 'Please enter basic pension amount',
                },
                "basic_pension_effective_date":{
                    required: 'Please select basic pension effective date',
                },
                "additional_pension_amount":{
                    required: 'Please enter additional pension amount',
                },
                "ti_category_id":{
                    required: 'Please select category',
                },
                "ti_amount":{
                    required: 'Please check category/basic pension amount',
                },
                "bank_name":{
                    required: 'Please select bank',
                },
                "branch_name_address":{
                    required: 'Please select branch',
                },
                "ifsc_code":{
                    required: 'Please select bank and branch',
                },
                "micr_code":{
                    required: 'Please select bank and branch',
                },
                "saving_bank_ac_no":{
                    required: 'Please enter saving account no',
                },
                "nominee_name":{
                    required: 'Please enter nominee name',
                },
                "nominee_mob_no":{
                    required: 'Please enter mobile number',
                    minlength: 'Please enter at least 10 digits',
                },
                "nominee_aadhar_no":{
                    required: 'Please enter aadhar no',
                },
                "nominee_dob":{
                    required: 'Please select nominee date of birth',
                },
                "gross_pension":{
                    required: 'Please enter gross pension',
                },    
                "commutation_amount[]":{
                    required: 'Please enter commutation amount',
                },
                "commutation_amount_end_date[]":{
                    required: 'Please select commutation end date',
                },
                "total_income_amount":{
                    required: 'Please enter total income amount',
                },       
            },
            submitHandler: function(form, event) {
                $('.page-loader').addClass('d-flex'); 
                event.preventDefault();
                var formData = new FormData(form);
                //$("#logid").prop('disabled',true);
                $.ajax({
                    type:'POST',
                    url:'{{ route("existing_pensioner_form_submission") }}',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                    //console.log(response);
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
                        location.href = "{{route('existing_pension_list')}}";
                      }
                    }
                });             
            },
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                //$(element).parent().addClass('has-success');
                $(element).addClass('form-control-danger');
            }
        });
    });

    
    
    </script>


@endsection