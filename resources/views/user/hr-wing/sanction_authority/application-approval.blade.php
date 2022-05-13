@extends('user.layout.layout')
@section('section_content')
<style type="text/css">
	.document_img {
	    width: 70px !important; 
	    height: 70px !important; 
	    border-radius: 0 !important;
	}
	.widt-50 {
		width: 50%
	}
    .marg-left {
        margin-left: 0px;
    }
    .marg-left-col {
        margin-left: 20px;
    }
    .error {
        /*margin-top: 10px;*/
    }
    .del-recovery-btn{
        margin-top: 20px;
    }
    .fsize {
        font-size: 13px !important;
    }
    .service-table {
        margin-bottom: 10px;
        margin-top: 10px;
    }
    .fa-check {
        color: green !important;
    }
    .fa-times {
        color: #DB504A !important;
    }
    .mrgtop {
        margin-top: 10px;
    }
    #form_is_checked {
        margin-left: 0px;
    }
    .service_period_duly, .service_period_absence {
        display: none;
    }
    .radio-margleft{
        margin-left: 5px;
    }
</style>
<div class="content-wrapper">
    @if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
	<div class="row">
		<div class="col-12 grid-margin">
            <!-- <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{-- route('user_dashboard') --}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{-- route('dealing_applications') --}}">View Applications</a></li>
                    <li class="breadcrumb-item active" aria-current="page" >Application Details</li>
                </ol>
            </nav> -->
            @if($application->application_status_id == 35)
            <form id="application-form" method="post" action="{{ route('submit_application_approval') }}" autocomplete="off">
            @elseif($application->application_status_id == 2)
            <form id="application-recovery-form" method="post" action="{{ route('applications_store_recovery') }}" autocomplete="off">
            @elseif($application->application_status_id == 12)
            <form id="application-form-2" method="post" autocomplete="off" action="{{ route('service_pension_form_submission') }}">
            @else
            <form>
            @endif
                @csrf

    			<div class="card">
    				<div class="card-body">
    					<h4 class="card-title">Application Details
                            @if($application->application_status_id == 35)
                                <!-- <button type="button" id="return-btn" class="btn btn-danger float-right ml-2">Return</button>
                                <button type="button" id="approve-btn" class="btn btn-success float-right">Approve</button> -->
                            @endif
                            @php
                                $is_application_assigned = DB::table('optcl_application_user_assignments')
                                        ->where('application_id', $application->id)
                                        ->first();
                            @endphp
                            @if(empty($is_application_assigned))
                                <a href="javascript:void(0)" class="btn btn-success float-right " id="assigned_to">Assigned To</a>
                            @endif   
                        </h4>
    					<div class="accordion" id="accordion" role="tablist">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered mt-3">
                                        <tr>
                                            <th width="18%">Application No. :</th>
                                            <td width="15%">{{  $application->application_no }}</td>
                                            <th width="12%">Status :</th>
                                            <td width="20%">{{  $application->status_name }}</td>
                                            <th width="15%">Created At :</th>
                                            <!-- <td>{{-- \Carbon\Carbon::parse($application->created_at)->format('d-m-Y') --}}</td> -->
                                            <td>{{ date("d-m-Y h:i A", strtotime($application->created_at)) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

    						<div class="card">
    							<div class="card-header" role="tab" id="headingOne">
    								<h6 class="mb-0">
    									<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Pension Form</a>
    								</h6>
    							</div>
                                <input type="hidden" name="application_id" id="application_id" value="{{ $application->id }}">
                                <input type="hidden" name="application_status" id="application_status">
    							<div id="collapseOne" class="collapse @if($application->application_status_id == 1) show @endif" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                                    @php
                                        $form1_id = 1;
                                    @endphp
    								<div class="card-body">
    									<table class="table table-bordered">
    										<tr>
    											<th>Employee No/Code :</th>
    											<td>{{ (!empty($proposal->employee_code)) ? $proposal->employee_code : ''  }}</td>
    											<td></td>
    										</tr>
    										<tr>
    											<th>Aadhaar No :</th>
    											<td>{{ (!empty($proposal->aadhaar_no)) ? $proposal->aadhaar_no : '' }}</td>
    											<td></td>
    										</tr>
    										<tr>
    											<th>Name :</th>
    											<td>{{ (!empty($proposal->employee_name)) ? $proposal->employee_name : ''  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 3);
                                                    @endphp

                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="employee_name" value="1" data-fieldid="3" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="employee_name" value="2" data-fieldid="3" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="employee_name-error" class="error text-danger" for="employee_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>

    										<tr>
    											<th>Designation :</th>
    											<td>{{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 4);
                                                    @endphp

                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="designation_name" value="1" data-fieldid="4" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="designation_name" value="2" data-fieldid="4" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="designation_name-error" class="error text-danger" for="designation_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>

    										<tr>
    											<th>Father's Name :</th>
    											<td>{{ (!empty($proposal->father_name)) ? $proposal->father_name : ''  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 5);
                                                    @endphp

                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="father_name" value="1" data-fieldid="5" required 
                                                                    @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="father_name" value="2" data-fieldid="5" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="father_name-error" class="error text-danger" for="father_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>

    										<tr>
    											<th>Gender :</th>
    											<td>{{ $proposal->gender_name ? $proposal->gender_name : 'NA' }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 6);
                                                    @endphp

                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="gender_name" value="1" data-fieldid="6" required 
                                                                    @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="gender_name" value="2" data-fieldid="6" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="gender_name-error" class="error text-danger" for="gender_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>

    										<tr>
    											<th>Marital Status :</th>
    											<td>{{ $proposal->marital_status_name ? $proposal->marital_status_name : 'NA' }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 7);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="marital_status_name" value="1" data-fieldid="7" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="marital_status_name" value="2" data-fieldid="7" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="marital_status_name-error" class="error text-danger" for="marital_status_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>

    										<tr>
    											<th>Religion :</th>
    											<td>{{ $proposal->religion_name ? $proposal->religion_name : 'NA' }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 8);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="religion_name" value="1" data-fieldid="8" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="religion_name" value="2" data-fieldid="8" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="religion_name-error" class="error text-danger" for="religion_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>

    										<tr>
    											<th>PF A/C Type :</th>
    											<td>{{ $proposal->account_type ? $proposal->account_type : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 9);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="account_type" value="1" data-fieldid="9" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="account_type" value="2" data-fieldid="9" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="account_type-error" class="error text-danger" for="account_type"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>

    										<tr>
    											<th>PF A/C No. :</th>
    											<td>{{ $proposal->pf_account_no ? $proposal->pf_account_no : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 79);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="pf_account_no" value="1" data-fieldid="79" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="pf_account_no" value="2" data-fieldid="79" 
                                                                    @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="pf_account_no-error" class="error text-danger" for="pf_account_no"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>

    										<tr>
    											<th>Name of the Office :</th>
    											<td>{{ $proposal->office_last_served ? $proposal->office_last_served : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 10);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="office_last_served" value="1" data-fieldid="10" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="office_last_served" value="2" data-fieldid="10" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="office_last_served-error" class="error text-danger" for="office_last_served"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>

                                            <tr>
                                                <th>Date of Birth :</th>
                                                <td>{{ (!empty($proposal->date_of_birth)) ? \Carbon\Carbon::parse($proposal->date_of_birth)->format('d-m-Y') : 'NA'  }}</td>
                                                <td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 11);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
                                                    <div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="date_of_birth" value="1" data-fieldid="11" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="date_of_birth" value="2" data-fieldid="11" 
                                                                    @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="date_of_birth-error" class="error text-danger" for="date_of_birth"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>

    										<tr>
    											<th>Date of Joining Service :</th>
    											<td>{{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('d-m-Y') : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 12);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="date_of_joining" value="1" data-fieldid="12" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="date_of_joining" value="2" data-fieldid="12" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="date_of_joining-error" class="error text-danger" for="date_of_joining"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>

    										<tr>
    											<th>Date of Retirement :</th>
    											<td>{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d-m-Y') : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form1_id, 13);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="date_of_retirement" value="1" data-fieldid="13" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="date_of_retirement" value="2" data-fieldid="13" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="date_of_retirement-error" class="error text-danger" for="date_of_retirement"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    									</table>
    								</div>
    							</div>
    						</div>

    						<div class="card">
    							<div class="card-header" role="tab" id="headingTwo">
    								<h6 class="mb-0">
    									<a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Personal Details</a>
    								</h6>
    							</div>
    							<div id="collapseTwo" class="collapse @if($application->application_status_id == 1) show @endif" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                                    @php
                                        $form2_id = 2;
                                    @endphp
    								<div class="card-body">
    									<table class="table table-bordered">
    										<tr>
    											<th colspan="4">Permanent Address</th>
    										</tr>
    										<tr>
    											<th>At :</th>
    											<td>{{ $proposal->permanent_addr_at ? $proposal->permanent_addr_at : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 14);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="permanent_addr_at" value="1" data-fieldid="14" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="permanent_addr_at" value="2" data-fieldid="14" 
                                                                    @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="permanent_addr_at-error" class="error text-danger" for="permanent_addr_at"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Post :</th>
    											<td>{{ $proposal->permanent_addr_post ? $proposal->permanent_addr_post : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 15);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="permanent_addr_post" value="1" data-fieldid="15" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="permanent_addr_post" value="2" data-fieldid="15" 
                                                                    @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="permanent_addr_post-error" class="error text-danger" for="permanent_addr_post"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Pin :</th>
    											<td>{{ $proposal->permanent_addr_pincode ? $proposal->permanent_addr_pincode : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 16);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="permanent_addr_pincode" value="1" data-fieldid="16" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="permanent_addr_pincode" value="2" data-fieldid="16" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="permanent_addr_pincode-error" class="error text-danger" for="permanent_addr_pincode"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Country :</th>
    											<td>{{ $proposal->cName ? $proposal->cName : ''  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 17);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="cName" value="1" data-fieldid="17" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="cName" value="2" data-fieldid="17" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="cName-error" class="error text-danger" for="cName"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>State :</th>
    											<td>{{ $proposal->state_name ? $proposal->state_name : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 18);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="state_name" value="1" data-fieldid="18" required 
                                                                    @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="state_name" value="2" data-fieldid="18" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="state_name-error" class="error text-danger" for="state_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>District :</th>
    											<td>{{ $proposal->district_name ? $proposal->district_name : ''  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 19);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="district_name" value="1" data-fieldid="19" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="district_name" value="2" data-fieldid="19" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="district_name-error" class="error text-danger" for="district_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th colspan="4">Present Address</th>
    										</tr>
    										<tr>
    											<th>At :</th>
    											<td>{{ $proposal->present_addr_at ? $proposal->present_addr_at : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 20);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="present_addr_at" value="1" data-fieldid="20" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="present_addr_at" value="2" data-fieldid="20" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="present_addr_at-error" class="error text-danger" for="present_addr_at"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Post :</th>
    											<td>{{ $proposal->present_addr_post ? $proposal->present_addr_post : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 21);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="present_addr_post" value="1" data-fieldid="21" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="present_addr_post" value="2" data-fieldid="21" 
                                                                    @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="present_addr_post-error" class="error text-danger" for="present_addr_post"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Pin :</th>
    											<td>{{ $proposal->present_addr_pincode ? $proposal->present_addr_pincode : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 22);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="present_addr_pincode" value="1" data-fieldid="22" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="present_addr_pincode" value="2" data-fieldid="22" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="present_addr_pincode-error" class="error text-danger" for="present_addr_pincode"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Country :</th>
    											<td>{{ $proposal->country_name ? $proposal->country_name : ''  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 23);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="country_name" value="1" data-fieldid="23" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="country_name" value="2" data-fieldid="23" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="country_name-error" class="error text-danger" for="country_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>State :</th>
    											<td>{{ $proposal->sName ? $proposal->sName : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 24);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="sName" value="1" data-fieldid="24" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="sName" value="2" data-fieldid="24" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="sName-error" class="error text-danger" for="sName"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>District :</th>
    											<td>{{ $proposal->dName ? $proposal->dName : ''  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 25);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="dName" value="1" data-fieldid="25" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="dName" value="2" data-fieldid="25" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="dName-error" class="error text-danger" for="dName"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Telephone No with STD Code :</th>
    											<td>{{ $proposal->telephone_std_code ? $proposal->telephone_std_code : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 26);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="telephone_std_code" value="1" data-fieldid="26" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="telephone_std_code" value="2" data-fieldid="26" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="telephone_std_code-error" class="error text-danger" for="telephone_std_code"></label>
                                                     @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Mobile No :</th>
    											<td>{{ $proposal->mobile_no ? $proposal->mobile_no : ''  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 27);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="mobile_no" value="1" data-fieldid="27" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="mobile_no" value="2" data-fieldid="27" 
                                                                    @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="mobile_no-error" class="error text-danger" for="mobile_no"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Email Address :</th>
    											<td>{{ $proposal->email_address ? $proposal->email_address : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 28);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="email_address" value="1" data-fieldid="28" required  @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="email_address" value="2" data-fieldid="28" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="email_address-error" class="error text-danger" for="email_address"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>PAN No :</th>
    											<td>{{ $proposal->pan_no ? $proposal->pan_no : ''  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 29);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="pan_no" value="1" data-fieldid="29" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="pan_no" value="2" data-fieldid="29" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="pan_no-error" class="error text-danger" for="pan_no"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										@php
    											$bank_branch_id = $proposal->bank_branch_id;
    											$bankDetaills = DB::table('optcl_bank_branch_master as bbm')
    															->join('optcl_bank_master as b','b.id','=','bbm.bank_id')
    															->select('b.bank_name','bbm.branch_name','bbm.ifsc_code','bbm.micr_code')
    															->where('bbm.status', 1)
    															->where('bbm.deleted', 0)
    															->where('b.status', 1)
    															->where('b.deleted', 0)
    															->first();
    											if($bankDetaills){
    												$bankName = $bankDetaills->bank_name;
    												$branchName = $bankDetaills->branch_name;
    												$ifscCode = $bankDetaills->ifsc_code;
    												$micrCode = $bankDetaills->micr_code;
    											}else{
    												$bankName = 'NA';
    												$branchName = 'NA';
    												$ifscCode = 'NA';
    												$micrCode = 'NA';
    											}

    										@endphp
    										<tr>
    											<th>Savings Bank A/C No.(Single or Joint Account with Spouse) :</th>
    											<td>{{ $proposal->savings_bank_account_no ? $proposal->savings_bank_account_no : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 30);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="savings_bank_account_no" value="1" data-fieldid="30" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="savings_bank_account_no" value="2" data-fieldid="30" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="savings_bank_account_no-error" class="error text-danger" for="savings_bank_account_no"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Name of the Bank :</th>
    											<td>{{ $bankName }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 31);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="bankName" value="1" data-fieldid="31" required 
                                                                    @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="bankName" value="2" data-fieldid="31" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="bankName-error" class="error text-danger" for="bankName"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Name Address of the Branch :</th>
    											<td>{{ $branchName }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 32);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="branchName" value="1" data-fieldid="32" required 
                                                                    @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="branchName" value="2" data-fieldid="32" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="branchName-error" class="error text-danger" for="branchName"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>IFSC Code :</th>
    											<td>{{ $ifscCode }}</td>
    											<td></td>
    										</tr>
    										<tr>
    											<th>MICR Code :</th>
    											<td>{{ $micrCode }}</td>
    											<td></td>
    										</tr>
    										<tr>
    											<th>Amount of Basic Pay at the time of Retirement :</th>
                                                <td>{{ !empty($proposal->basic_pay_amount_at_retirement) ? number_format($proposal->basic_pay_amount_at_retirement, 2) : 'NA' }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 34);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="basic_pay_amount_at_retirement" value="1" data-fieldid="34" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="basic_pay_amount_at_retirement" value="2" data-fieldid="34" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="basic_pay_amount_at_retirement-error" class="error text-danger" for="basic_pay_amount_at_retirement"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Name of the Unit (where life certificate & income tax declaration to be submitted) :</th>
    											<td>{{ $proposal->office_last_served ? $proposal->office_last_served : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 35);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="office_last_unit" value="1" data-fieldid="35" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="office_last_unit" value="2" data-fieldid="35" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="office_last_unit-error" class="error text-danger" for="office_last_unit"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Particulars of previous civil service if any and amount and nature of any pension or gratuity received :</th>
    											<td>{{ $proposal->is_civil_service_amount_received == 1 ? 'Yes' : 'No'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 36);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="is_civil_service_amount_received" value="1" data-fieldid="36" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="is_civil_service_amount_received" value="2" data-fieldid="36" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="is_civil_service_amount_received-error" class="error text-danger" for="is_civil_service_amount_received"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										@if($proposal->is_civil_service_amount_received == 1)
    										<tr>
    											<th>Particulars of previous civil service :</th>
    											<td>{{ $proposal->civil_service_name ? $proposal->civil_service_name : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 37);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="civil_service_name" value="1" data-fieldid="37" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="civil_service_name" value="2" data-fieldid="37" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="civil_service_name-error" class="error text-danger" for="civil_service_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Amount and nature of any pension or gratuity received :</th>
                                                <td>{{ !empty($proposal->civil_service_received_amount) ? number_format($proposal->civil_service_received_amount, 2) : 'NA' }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 38);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="civil_service_received_amount" value="1" data-fieldid="38" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="civil_service_received_amount" value="2" data-fieldid="38" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="civil_service_received_amount-error" class="error text-danger" for="civil_service_received_amount"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										@endif

    										<tr>
    											<th>Particulars of family pension if any Received / admissible from any other source to the retired employee and any members of his family : </th>
    											<td>{{ $proposal->is_family_pension_received_by_family_members == 1 ? 'Yes' : 'No'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 39);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="is_family_pension_received_by_family_members" value="1" data-fieldid="39" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="is_family_pension_received_by_family_members" value="2" data-fieldid="39" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="is_family_pension_received_by_family_members-error" class="error text-danger" for="is_family_pension_received_by_family_members"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										@if($proposal->is_family_pension_received_by_family_members == 1)
    										<tr>
    											<th>Enter admissible form any other source to the retired employee :</th>
    											<td>{{ $proposal->admission_source_of_family_pension ? $proposal->admission_source_of_family_pension : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 40);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="admission_source_of_family_pension" value="1" data-fieldid="40" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="admission_source_of_family_pension" value="2" data-fieldid="40" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="admission_source_of_family_pension-error" class="error text-danger" for="admission_source_of_family_pension"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Members of his family :</th>
    											<td>{{ $proposal->relation_name ? $proposal->relation_name : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 41);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="relation_name" value="1" data-fieldid="41" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="relation_name" value="2" data-fieldid="41" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="relation_name-error" class="error text-danger" for="relation_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										<tr>
    											<th>Name of member :</th>
    											<td>{{ $proposal->family_member_name ? $proposal->family_member_name : 'NA'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 42);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="family_member_name" value="1" data-fieldid="42" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="family_member_name" value="2" data-fieldid="42" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="family_member_name-error" class="error text-danger" for="family_member_name"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										@endif
    										<tr>
    											<th class="widt-50">Whether Commutation of pension to be made & percentage to be specified (not applicable for applicants for family pension) : </th>
    											<td>{{ $proposal->is_commutation_pension_applied == 1 ? 'Yes' : 'No'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 43);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="is_commutation_pension_applied" value="1" data-fieldid="43" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="is_commutation_pension_applied" value="2" data-fieldid="43" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="is_commutation_pension_applied-error" class="error text-danger" for="is_commutation_pension_applied"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										@if($proposal->is_commutation_pension_applied == 1)
    										<tr>
    											<th>Percentage Value :</th>
    											<td>{{ ($proposal->commutation_percentage ? $proposal->commutation_percentage : 'NA').'%'  }}</td>
    											<td>
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form2_id, 44);
                                                    @endphp
                                                    @if(!$field_status['form_submit'])
    												<div class="row marg-left">
                                                        <div class="col-sm-4">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkApprove" name="commutation_percentage" value="1" data-fieldid="44" required @if($field_status['status_id'] == 1) checked @endif>
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 marg-left-col">
                                                            <div class="form-radio">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input checkfield" name="commutation_percentage" value="2" data-fieldid="44" @if($field_status['status_id'] == 2) checked @endif>
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label id="commutation_percentage-error" class="error text-danger" for="commutation_percentage"></label>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @else
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i> 
                                                        </div>
                                                    @endif
    											</td>
    										</tr>
    										@endif
    									</table>
    								</div>
    							</div>
    						</div>

    						<div class="card">
    							<div class="card-header" role="tab" id="headingThree">
    								<h6 class="mb-0">
    									<a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Nominee Details</a>
    								</h6>
    							</div>
                                @php
                                    $form3_id = 3;
                                @endphp
    							<div id="collapseThree" class="collapse @if($application->application_status_id == 1) show @endif" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
    								<div class="card-body">
    									<table class="table table-bordered">
    										@foreach($employee_nominees as $key=>$employee_nominee)
    											<tr>
    												<th colspan="4" class="text-center">Nominee</th>
    											</tr>
    											<tr>
                                                    
    												<th>Full Name of the Family Member</th>
    												<td>{{ (!empty($employee_nominee->nominee_name)) ? $employee_nominee->nominee_name : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 46, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_name_{{$employee_nominee->id}}" value="1" data-fieldid="46" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_name_{{$employee_nominee->id}}" value="2" data-fieldid="46" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_name_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											<tr>
    												<th>Mobile No</th>
    												<td>{{ (!empty($employee_nominee->mobile_no)) ? $employee_nominee->mobile_no : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 47, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_mobile_no_{{$employee_nominee->id}}" value="1" data-fieldid="47" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_mobile_no_{{$employee_nominee->id}}" value="2" data-fieldid="47" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_mobile_no_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_mobile_no_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											<tr>
    												<th>Date of Birth</th>
    												<td>{{ (!empty($employee_nominee->date_of_birth)) ? \Carbon\Carbon::parse($employee_nominee->date_of_birth)->format('d-m-Y') : 'NA'  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 48, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_date_of_birth_{{$employee_nominee->id}}" value="1" data-fieldid="48" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_date_of_birth_{{$employee_nominee->id}}" value="2" data-fieldid="48" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_date_of_birth_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_date_of_birth_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											@if($employee_nominee->dob_attachment_path)
    											<tr>
    												<th>Proof of Date of Birth</th>
    												<td> <img class="document_img" src="{{ asset('public/' . $employee_nominee->dob_attachment_path) }}"> </td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 49, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="dob_attachment_path_{{$employee_nominee->id}}" value="1" data-fieldid="49" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="dob_attachment_path_{{$employee_nominee->id}}" value="2" data-fieldid="49" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="dob_attachment_path_{{$employee_nominee->id}}-error" class="error text-danger" for="dob_attachment_path_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											@endif
    											<tr>
    												<th>Gender</th>
    												<td>{{ (!empty($employee_nominee->gender_name)) ? $employee_nominee->gender_name : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 50, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_gender_name_{{$employee_nominee->id}}" value="1" data-fieldid="50" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_gender_name_{{$employee_nominee->id}}" value="2" data-fieldid="50" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_gender_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_gender_name_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											<tr>
    												<th>Relation with Pensioner</th>
    												<td>{{ (!empty($employee_nominee->relation_name)) ? $employee_nominee->relation_name : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 51, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_relation_name_{{$employee_nominee->id}}" value="1" data-fieldid="51" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_relation_name_{{$employee_nominee->id}}" value="2" data-fieldid="51" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_relation_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_relation_name_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											@if(!empty($employee_nominee) && $employee_nominee->is_spouse == 1)
    												<!-- <tr>
    													<th>Spouse Type</th>
    													<td>
    														@if($employee_nominee->is_2nd_spouse == 1)
    															'Yes'
    														@else
    															'No'
    														@endif
    													</td>
    													<td>
    														<a href="javascript:;">Approve</a>
    														<a href="javascript:;">Return</a>
    													</td>
    												</tr> -->
    												@if(!empty($employee_nominee) && $employee_nominee->is_2nd_spouse == 1)
    												<tr>
    													<th>1st Spouse Death Date</th>
    													<td>{{ (!empty($employee_nominee->{'1st_spouse_death_date'})) ? \Carbon\Carbon::parse($employee_nominee->{'1st_spouse_death_date'})->format('d-m-Y') : ''  }}</td>
    													<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 53, $employee_nominee->id);
                                                            @endphp
                                                            @if(!$field_status['form_submit'])
    														<div class="row marg-left">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input checkApproveNominee" name="spouse_death_date_{{$employee_nominee->id}}" value="1" data-fieldid="53" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                            Approve
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 marg-left-col">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input checkfield" name="spouse_death_date_{{$employee_nominee->id}}" value="2" data-fieldid="53" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                            Reject
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <label id="spouse_death_date_{{$employee_nominee->id}}-error" class="error text-danger" for="spouse_death_date_{{$employee_nominee->id}}"></label>
                                                            @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                                <div class="preview"> 
                                                                    <i class="fa fa-check"></i> 
                                                                </div>
                                                            @else
                                                                <div class="preview"> 
                                                                    <i class="fa fa-times"></i> 
                                                                </div>
                                                            @endif
    													</td>
    												</tr>
    												<tr>
    													<th>1st Spouse Death Certificate</th>
    													<td><img class="document_img" src="{{ asset('public/' . $employee_nominee->{'1st_spouse_death_certificate_path'}) }}"></td>
    													<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 54, $employee_nominee->id);
                                                            @endphp
                                                            @if(!$field_status['form_submit'])
    														<div class="row marg-left">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input checkApproveNominee" name="spouse_death_certificate_path_{{$employee_nominee->id}}" value="1" data-fieldid="54" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                            Approve
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 marg-left-col">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input checkfield" name="spouse_death_certificate_path_{{$employee_nominee->id}}" value="2" data-fieldid="54" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                            Reject
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <label id="spouse_death_certificate_path_{{$employee_nominee->id}}-error" class="error text-danger" for="spouse_death_certificate_path_{{$employee_nominee->id}}"></label>
                                                            @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                                <div class="preview"> 
                                                                    <i class="fa fa-check"></i> 
                                                                </div>
                                                            @else
                                                                <div class="preview"> 
                                                                    <i class="fa fa-times"></i> 
                                                                </div>
                                                            @endif
    													</td>
    												</tr>
    												@endif
    											@endif
    											<tr>
    												<th>Nominee Preference</th>
    												<td>{{ (!empty($employee_nominee->nominee_prefrence)) ? $employee_nominee->nominee_prefrence : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 55, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_prefrence_{{$employee_nominee->id}}" value="1" data-fieldid="55" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_prefrence_{{$employee_nominee->id}}" value="2" data-fieldid="55" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_prefrence_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_prefrence_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>

    											<tr>
    												<th>Name of the Bank</th>
    												<td>{{ (!empty($employee_nominee->bank_name)) ? $employee_nominee->bank_name : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 56, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_bank_name_{{$employee_nominee->id}}" value="1" data-fieldid="56" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_bank_name_{{$employee_nominee->id}}" value="2" data-fieldid="56" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_bank_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_bank_name_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>

    											<tr>
    												<th>Name Address of the Branch</th>
    												<td>{{ (!empty($employee_nominee->branch_name)) ? $employee_nominee->branch_name : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 57, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_branch_name_{{$employee_nominee->id}}" value="1" data-fieldid="57" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_branch_name_{{$employee_nominee->id}}" value="2" data-fieldid="57" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_branch_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_branch_name_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>

    											<tr>
    												<th>IFSC Code</th>
    												<td>{{ (!empty($employee_nominee->ifsc_code)) ? $employee_nominee->ifsc_code : ''  }}</td>
    												<td>
    													<!-- <div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApprove" name="field_action" value="1" data-fieldid="46" data-nomineeid="{{ $employee_nominee->id }}">
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="field_action" value="2" data-fieldid="46" data-nomineeid="{{ $employee_nominee->id }}">
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div> -->
    												</td>
    											</tr>
    											<tr>
    												<th>Savings Bank A/C No. (Single / Joint A/C with Spouse)</th>
    												<td>{{ (!empty($employee_nominee->savings_bank_account_no)) ? $employee_nominee->savings_bank_account_no : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 58, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_savings_bank_account_no_{{$employee_nominee->id}}" value="1" data-fieldid="58" data-nomineeid="{{ $employee_nominee->id }}" required 
                                                                        @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_savings_bank_account_no_{{$employee_nominee->id}}" value="2" data-fieldid="58" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_savings_bank_account_no_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_savings_bank_account_no_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>

    											<tr>
    												<th>Marital Status</th>
    												<td>{{ (!empty($employee_nominee->marital_status_name)) ? $employee_nominee->marital_status_name : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 59, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_marital_status_name_{{$employee_nominee->id}}" value="1" data-fieldid="59" data-nomineeid="{{ $employee_nominee->id }}" required 
                                                                        @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_marital_status_name_{{$employee_nominee->id}}" value="2" data-fieldid="59" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_marital_status_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_marital_status_name_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>

    											<tr>
    												<th>Aadhaar No.</th>
    												<td>{{ (!empty($employee_nominee->nominee_aadhaar_no)) ? $employee_nominee->nominee_aadhaar_no : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 60, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="nominee_aadhaar_no_{{$employee_nominee->id}}" value="1" data-fieldid="60" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="nominee_aadhaar_no_{{$employee_nominee->id}}" value="2" data-fieldid="60" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="nominee_aadhaar_no_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_aadhaar_no_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>

    											<tr>
    												<th>Employment Status</th>
    												<td>
    													@if($employee_nominee->employement_status == 1)
    														Employeed
    													@elseif($employee_nominee->employement_status == 2)
    														Unemployeed
    													@else
    														NA
    													@endif
    												</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 61, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="employement_status_{{$employee_nominee->id}}" value="1" data-fieldid="61" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="employement_status_{{$employee_nominee->id}}" value="2" data-fieldid="61" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="employement_status_{{$employee_nominee->id}}-error" class="error text-danger" for="employement_status_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>

    											<tr>
    												<th>Total Income per annum</th>
    												<td>{{ !empty($employee_nominee->total_income_per_annum) ? number_format($employee_nominee->total_income_per_annum, 2) : 0  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 62, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="total_income_per_annum_{{$employee_nominee->id}}" value="1" data-fieldid="62" data-nomineeid="{{ $employee_nominee->id }}" required 
                                                                        @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="total_income_per_annum_{{$employee_nominee->id}}" value="2" data-fieldid="62" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="total_income_per_annum_{{$employee_nominee->id}}-error" class="error text-danger" for="total_income_per_annum_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>

    											<tr>
    												<th>Physically Handicapped</th>
    												<td>
    													@if($employee_nominee->is_physically_handicapped == 1)
    														Yes
    													@elseif($employee_nominee->is_physically_handicapped == 2)
    														No
    													@else
    														NA
    													@endif
    												</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 63, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="is_physically_handicapped_{{$employee_nominee->id}}" value="1" data-fieldid="63" data-nomineeid="{{ $employee_nominee->id }}" required 
                                                                        @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="is_physically_handicapped_{{$employee_nominee->id}}" value="2" data-fieldid="63" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="is_physically_handicapped_{{$employee_nominee->id}}-error" class="error text-danger" for="is_physically_handicapped_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>

    											@if($employee_nominee->is_physically_handicapped == 1)
    											<tr>
    												<th>Disability Certificate</th>
    												<td><img class="document_img" src="{{ asset('public/' . $employee_nominee->disability_certificate_path) }}"></td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 64, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="disability_certificate_path_{{$employee_nominee->id}}" value="1" data-fieldid="64" data-nomineeid="{{ $employee_nominee->id }}" required 
                                                                        @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="disability_certificate_path_{{$employee_nominee->id}}" value="2" data-fieldid="64" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="disability_certificate_path_{{$employee_nominee->id}}-error" class="error text-danger" for="disability_certificate_path_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											<tr>
    												<th>Disability Percentage</th>
    												<td>{{ (!empty($employee_nominee->disability_percentage)) ? $employee_nominee->disability_percentage : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 65, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="disability_percentage_{{$employee_nominee->id}}" value="1" data-fieldid="65" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="disability_percentage_{{$employee_nominee->id}}" value="2" data-fieldid="65" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="disability_percentage_{{$employee_nominee->id}}-error" class="error text-danger" for="disability_percentage_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											@endif
    											<tr>
    												<th>Amount / Share payable to Each</th>
    												<td>{{ (!empty($employee_nominee->pension_amount_share_percentage)) ? $employee_nominee->pension_amount_share_percentage : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 66, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="pension_amount_share_percentage_{{$employee_nominee->id}}" value="1" data-fieldid="66" data-nomineeid="{{ $employee_nominee->id }}" required 
                                                                        @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="pension_amount_share_percentage_{{$employee_nominee->id}}" value="2" data-fieldid="66" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="pension_amount_share_percentage_{{$employee_nominee->id}}-error" class="error text-danger" for="pension_amount_share_percentage_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>

    											<tr>
    												<th>Minor</th>
    												<td>
    													@if($employee_nominee->is_minor == 1)
    														Yes
    													@elseif($employee_nominee->is_minor == 0)
    														No
    													@else
    														NA
    													@endif
    												</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 67, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="is_minor_{{$employee_nominee->id}}" value="1" data-fieldid="67" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="is_minor_{{$employee_nominee->id}}" value="2" data-fieldid="67" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="is_minor_{{$employee_nominee->id}}-error" class="error text-danger" for="is_minor_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											@if($employee_nominee->is_minor == 1)
    											<tr>
    												<th>Legal Guardian Name</th>
    												<td>{{ (!empty($employee_nominee->legal_guardian_name)) ? $employee_nominee->legal_guardian_name : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 68, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="legal_guardian_name_{{$employee_nominee->id}}" value="1" data-fieldid="68" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="legal_guardian_name_{{$employee_nominee->id}}" value="2" data-fieldid="68" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="legal_guardian_name_{{$employee_nominee->id}}-error" class="error text-danger" for="legal_guardian_name_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											<tr>
    												<th>Legal Guardian Age</th>
    												<td>{{ (!empty($employee_nominee->legal_guardian_age)) ? $employee_nominee->legal_guardian_age : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 69, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="legal_guardian_age_{{$employee_nominee->id}}" value="1" data-fieldid="69" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="legal_guardian_age_{{$employee_nominee->id}}" value="2" data-fieldid="69" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="legal_guardian_age_{{$employee_nominee->id}}-error" class="error text-danger" for="legal_guardian_age_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											<tr>
    												<th>Legal Guardian Address</th>
    												<td>{{ (!empty($employee_nominee->legal_guardian_addr)) ? $employee_nominee->legal_guardian_addr : ''  }}</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 70, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="legal_guardian_addr_{{$employee_nominee->id}}" value="1" data-fieldid="70" data-nomineeid="{{ $employee_nominee->id }}" required @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="legal_guardian_addr_{{$employee_nominee->id}}" value="2" data-fieldid="70" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="legal_guardian_addr_{{$employee_nominee->id}}-error" class="error text-danger" for="legal_guardian_addr_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											<tr>
    												<th>Legal Guardian Attachment</th>
    												<td><img class="document_img" src="{{ asset('public/' . $employee_nominee->legal_guardian_attachment_path) }}"></td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, $form3_id, 71, $employee_nominee->id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApproveNominee" name="legal_guardian_attachment_path_{{$employee_nominee->id}}" value="1" data-fieldid="71" data-nomineeid="{{ $employee_nominee->id }}" required 
                                                                        @if($field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="legal_guardian_attachment_path_{{$employee_nominee->id}}" value="2" data-fieldid="71" data-nomineeid="{{ $employee_nominee->id }}" @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="legal_guardian_attachment_path_{{$employee_nominee->id}}-error" class="error text-danger" for="legal_guardian_attachment_path_{{$employee_nominee->id}}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											@endif
    										@endforeach
    									</table>
    								</div>
    							</div>
    						</div>

    						<div class="card">
    							<div class="card-header" role="tab" id="headingFour">
    								<h6 class="mb-0">
    									<a class="collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="false" aria-controls="collapseThree">List of Documents</a>
    								</h6>
    							</div>
    							<div id="collapseFour" class="collapse @if($application->application_status_id == 1) show @endif" role="tabpanel" aria-labelledby="headingFour" data-parent="#accordion">
    								<div class="card-body">
    									<table class="table table-bordered">
    										@if(!empty($employee_documents))
    											@foreach($employee_documents as $employee_document)
    											<tr>
    												<th class="widt-50">{{ $employee_document->document_name }}</th>
    												<td>
    													<img class="document_img" src="{{ asset('public/' . $employee_document->document_attachment_path ) }}">
    												</td>
    												<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRSanctionAuthority($application->id, 4, $employee_document->field_id);
                                                        @endphp
                                                        @if(!$field_status['form_submit'])
    													<div class="row marg-left">
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkApprove" name="field_action_{{ $employee_document->field_id }}" value="1" data-fieldid="{{ $employee_document->field_id }}" required @if(!empty($field_status['status_id']) && $field_status['status_id'] == 1) checked @endif>
                                                                        Approve
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 marg-left-col">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input checkfield" name="field_action_{{ $employee_document->field_id }}" value="2" data-fieldid="{{ $employee_document->field_id }}" @if(!empty($field_status['status_id']) && $field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="field_action_{{ $employee_document->field_id }}-error" class="error text-danger" for="field_action_{{ $employee_document->field_id }}"></label>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @else
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i> 
                                                            </div>
                                                        @endif
    												</td>
    											</tr>
    											@endforeach
    										@endif
    									</table>
    								</div>
    							</div>
    						</div>

                            <div class="card">
                                <div class="card-header" role="tab" id="headingFive">
                                    <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseThree">Status History</a>
                                    </h6>
                                </div>
                                <div id="collapseFive" class="collapse" role="tabpanel" aria-labelledby="headingFive" data-parent="#accordion">
                                    <div class="card-body">
                                        <ul class="bullet-line-list">

                                        @if(!empty($statusHistory))
                                            @foreach($statusHistory as $status_data)
                                                <li>
                                                    <h6>{{ $status_data->status_name }}</h6>
                                                    @if($status_data->remarks)
                                                        <p class="mb-0">{{$status_data->remarks}}</p>
                                                    @endif
                                                    <p class="text-muted">
                                                       <i class="mdi mdi-clock"></i>
                                                       {{ date('d M Y h:i A', strtotime($status_data->created_at)) }}
                                                    </p>
                                                </li>
                                            @endforeach
                                        @endif

                                        </ul>
                                    </div>
                                </div>
                            </div>
    					</div>
    				</div>
    			</div>
            </form>
		</div>
	</div>
</div>


<!-- Overall return remark -->
<div class="modal fade" id="main_application_remark" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <form action="" method="post" id="return_remark_form" accept-charset="utf-8">
        @csrf
        <input type="hidden" name="application_id" id="reject_application_id" value="{{ $application->id }}">
        <input type="hidden" name="nominee_id" id="reject_nomminee_id" value="">
        <input type="hidden" name="field_id" id="field_id" value="">
        <input type="hidden" name="status_id" id="status_id" value="2">
        <input type="hidden" name="field_name" id="field_name" value="">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reject</h5>
                    <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remark<span class="text-danger">*</span></label>
                                <textarea name="remarks" id="remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6" required maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="proposalReturned" class="btn btn-raised btn-success">Submit</button>
                </div>
            </div>
        </div>
    </form>        
</div>


<div class="modal fade" id="application_remark" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <form action="{{ route('submit_application_approval') }}" method="post" id="application_return_remark" accept-charset="utf-8">
        @csrf
        <input type="hidden" name="application_id" id="return_application_id" value="{{ $application->id }}">
        <input type="hidden" name="application_status" id="return_application_status">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Return</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remark<span class="text-danger">*</span></label>
                                <textarea name="remarks" id="remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6" required maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="proposalReturned" class="btn btn-raised btn-success">Submit</button>
                </div>
            </div>
        </div>
    </form>        
</div>

<div class="modal fade" id="img_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <img id="img-show" width="450" height="250">
            </div>
        </div>
    </div>
</div>

<!-- Application Assignments -->
<div class="modal fade" id="application_assignments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <form method="post" id="application_assignments_form" accept-charset="utf-8" action="{{route('hr_sanction_authority_application_assignment')}}">
        @csrf
        <input type="hidden" name="assigned_application_id" id="assigned_application_id" value="{{ $application->id }}">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assigned To</h5>
                    <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @php
                            $dealing_assistants = DB::table('optcl_users')->where('user_type', 4)->where('system_user_role', 2)->where('designation_id', 5)->get();
                        @endphp
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Dealing Assistant<span class="text-danger">*</span></label>
                                <select class="form-control" name="dealing_assistant_list" id="dealing_assistant_list">
                                    <option value="">Select Dealing Assistant</option>
                                    @foreach($dealing_assistants as $dealing_assistants)
                                        <option value="{{$dealing_assistants->id}}">{{$dealing_assistants->first_name." ".$dealing_assistants->last_name}}</option>
                                    @endforeach  
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-raised btn-success">Submit</button>
                </div>
            </div>
        </div>
    </form>        
</div>

@endsection

@section('page-script')

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('change', '.checkApprove', function() {
            var field_id = $(this).data('fieldid');
            var status_id = this.value;
            var application_id = $('#application_id').val();

            if(field_id != '') {
                $.ajax({
                    url:'{{ route("hr_applications_approval") }}',
                    type:'post',
                    data:'field_id='+field_id+'&_token={{csrf_token()}}&status_id='+status_id+'&application_id='+application_id,
                    success:function(result) {
                    }
                });
            }
        });

        $(document).on('change', '.checkApproveNominee', function() {
            var field_id = $(this).data('fieldid');
            var status_id = this.value;
            var application_id = $('#application_id').val();
            var nominee_id = $(this).data('nomineeid');

            if(field_id != '') {
                $.ajax({
                    url:'{{ route("hr_applications_approval") }}',
                    type:'post',
                    data:'field_id='+field_id+'&_token={{csrf_token()}}&status_id='+status_id+'&application_id='+application_id+'&nominee_id='+nominee_id,
                    success:function(result) {
                    }
                });
            }
        });

        $(document).on('change', '.checkfield', function() {
            var field_id = $(this).data('fieldid');
            var status_id = this.value;
            var application_id = $('#application_id').val();
            var nomminee_id = $(this).data('nomineeid');
            var field_name = $(this).attr('name');
            
            $('#reject_nomminee_id').val(nomminee_id);
            $('#reject_application_id').val(application_id);
            $('#field_id').val(field_id);
            $('#remarks').val('');
            $('#field_name').val(field_name);

            $('#main_application_remark').modal('show');
            $('#main_application_remark').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $("#main_application_remark").on("hidden.bs.modal", function () {
            var field_name = $(this).find('#field_name').val();
            $('input[name='+field_name+']').prop('checked', false);
        });

        $("#return_remark_form").validate({
            rules: {
                remarks: {
                    required: true,
                },
            },
            messages: {
                remarks: {
                    required: 'Please enter remark',
                },
              },
            submitHandler: function(form, event) { 
                    event.preventDefault();
                    var formData = new FormData(form);
                    $('#main_application_remark').modal('hide');
                    
                    $.ajax({
                        type:'POST',
                        url:'{{ route("submit_application_approval") }}',
                        data: formData,
                        dataType: 'JSON',
                        processData: false,
                        contentType: false,
                        success: function(response) {

                        }
                    });
              },
              errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
              },
              highlight: function(element, errorClass) {
                $(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
              }
        });

        // Final approval submission
        $('#approve-btn').on('click', function() {

            if($('#application-form').valid()) {

                var checked = 0;

                $('.checkfield').each(function() {
                    if($(this).prop("checked")) {
                        checked = 1;
                    }
                });

                if(checked == 0) {
                    $('#application_status').val(1);
                    $('#application-form').submit();
                } else {
                    // $('#approve-btn').hide();
                    swal("", "You can not approve the form. Because you have reject some fields", "error");    
                }
            } else {
                swal("", "Please select all fields", "error");
            }
        });

        // Final returned submission
        $('#return-btn').on('click', function() {

            if($('#application-form').valid()) {

                var checked = 0;

                $('.checkApprove').each(function() {
                    if($(this).prop("checked")) {
                        checked = 1;
                    }
                });

                if(checked == 0) {
                    var application_id = $('#application_id').val();
                    $('#return_application_status').val(0);
                    $('#return_application_id').val(application_id);

                    $('#application_remark').modal('show');
                    $('#application_remark').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    swal("", "You can not returned the form. Please reject some fields to returned the application", "error");
                }
            } else {
                swal("", "Please select all fields", "error");
            }
        });

        $("#application_return_remark").validate({
            rules: {
                remarks: {
                    required: true,
                },
            },
            messages: {
                remarks: {
                    required: 'Please enter remark',
                },
              },
            submitHandler: function(form, event) { 
                    event.preventDefault();
                    //var formData = new FormData(form);
                    $('#application_remark').modal('hide');
                    form.submit();
            },
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });

        $('.document_img').on('click', function() {
            var src = $(this).attr('src');

            $('#img-show').attr('src', src);
            $('#img_show').modal('show');
        });

        $('#assigned_to').on('click', function(){
            $("#application_assignments").modal('show');            
        });

        $("#application_assignments_form").validate({
            rules: {
                dealing_assistant_list: {
                    required: true,
                },
            },
            messages: {
                dealing_assistant_list: {
                    required: 'Please select dealing assistant',
                },
              },
            submitHandler: function(form, event) { 
                    event.preventDefault();
                    //var formData = new FormData(form);
                    $('#application_assignments').modal('hide');
                    form.submit();
            },
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });

    });
</script>

@endsection