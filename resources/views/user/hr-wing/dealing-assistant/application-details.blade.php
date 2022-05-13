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
    /*.service_period_duly, .service_period_absence {
        display: none;
    }*/
    .radio-margleft{
        margin-left: 5px;
    }
    ul, ol, dl {
        padding-left: 1rem;
        font-size: 1rem;
    }
    #sanction-order-generate {
        margin-top: -9px;
    }
</style>

<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin">
            <!-- <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{-- route('user_dashboard') --}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{-- route('dealing_applications') --}}">View Applications</a></li>
                    <li class="breadcrumb-item active" aria-current="page" >Application Details</li>
                </ol>
            </nav> -->
            @if(in_array($application->application_status_id, [20,28]))
            <form id="application-form" method="post" action="{{ route('hr_applications_submission') }}" autocomplete="off">
            @elseif($application->application_status_id == 7)
            <form id="application-recovery-form" method="post" action="{{ route('hr_applications_store_recovery') }}" autocomplete="off">
            @elseif($application->application_status_id == 21)
            <form id="application-form-2" method="post" autocomplete="off" action="{{ route('hr_service_pension_form_submission') }}">
            @elseif($application->application_status_id == 22)
            <form id="application-form-3" method="post" autocomplete="off" action="{{ route('hr_service_pension_form_part_three_submission') }}">
            @else
            <form>
            @endif
                @csrf

    			<div class="card">
    				<div class="card-body">
    					<h4 class="card-title">Application Details
                            <!-- <a href="javascript:;" class="btn btn-danger float-right marg-left-col">Returned</a> -->
                            <!-- <a href="javascript:;" class="btn btn-success float-right">Approve</a> -->
                            @if(in_array($application->application_status_id, [20,28]))
                                <button type="button" id="return-btn" class="btn btn-danger float-right marg-left-col">Return</button>
                                <button type="button" id="approve-btn" class="btn btn-success float-right">Approve</button>
                            @endif

                            @if(in_array($application->application_status_id, [40]))
                                <button type="button" id="resubmit-btn" class="btn btn-success float-right marg-left-col">Resubmit</button>
                            @endif

                            @if(in_array($application->application_status_id, [23]))
                            <a href="{{route('calculate_pensionar_benefits', $application->id)}}" class="btn btn-success float-right">Calculation Pension</a>
                            @endif

                        </h4>
    					<div class="accordion" id="accordion" role="tablist">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered mt-3">
                                        <tr>
                                            <th width="18%">Application No. :</th>
                                            <td width="15%">{{ $application->application_no }}</td>
                                            <th width="12%">Status :</th>
                                            <td width="20%">{{ $application->status_name }}</td>
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

                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 3);
                                                            var_dump($field_status);
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
                                                                        <input type="radio" class="form-check-input checkfield" name="employee_name" value="2" data-fieldid="3" 
                                                                        @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <label id="employee_name-error" class="error text-danger" for="employee_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>

    										<tr>
    											<th>Designation :</th>
    											<td>{{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 4);
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
                                                        <!-- <label id="designation_name-error" class="error text-danger" for="designation_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>

    										<tr>
    											<th>Father's Name :</th>
    											<td>{{ (!empty($proposal->father_name)) ? $proposal->father_name : ''  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 5);
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
                                                        <!-- <label id="father_name-error" class="error text-danger" for="father_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>

    										<tr>
    											<th>Gender :</th>
    											<td>{{ $proposal->gender_name ? $proposal->gender_name : 'NA' }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 6);
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
                                                        <!-- <label id="gender_name-error" class="error text-danger" for="gender_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>

    										<tr>
    											<th>Marital Status :</th>
    											<td>{{ $proposal->marital_status_name ? $proposal->marital_status_name : 'NA' }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 7);
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
                                                        <!-- <label id="marital_status_name-error" class="error text-danger" for="marital_status_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>

    										<tr>
    											<th>Religion :</th>
    											<td>{{ $proposal->religion_name ? $proposal->religion_name : 'NA' }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 8);
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
                                                        <!-- <label id="religion_name-error" class="error text-danger" for="religion_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>

    										<tr>
    											<th>PF A/C Type :</th>
    											<td>{{ $proposal->account_type ? $proposal->account_type : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 9);
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
                                                        <!-- <label id="account_type-error" class="error text-danger" for="account_type"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>

    										<tr>
    											<th>PF A/C No. :</th>
    											<td>{{ $proposal->pf_account_no ? $proposal->pf_account_no : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 79);
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
                                                        <!-- <label id="pf_account_no-error" class="error text-danger" for="pf_account_no"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>

    										<tr>
    											<th>Name of the Office :</th>
    											<td>{{ $proposal->office_last_served ? $proposal->office_last_served : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 10);
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
                                                        <!-- <label id="office_last_served-error" class="error text-danger" for="office_last_served"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>

                                            <tr>
                                                <th>Date of Birth :</th>
                                                <td>{{ (!empty($proposal->date_of_birth)) ? \Carbon\Carbon::parse($proposal->date_of_birth)->format('d-m-Y') : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
                                                    <td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 11);
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
                                                        <!-- <label id="date_of_birth-error" class="error text-danger" for="date_of_birth"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>

    										<tr>
    											<th>Date of Joining Service :</th>
    											<td>{{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('d-m-Y') : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 12);
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
                                                        <!-- <label id="date_of_joining-error" class="error text-danger" for="date_of_joining"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>

    										<tr>
    											<th>Date of Retirement :</th>
    											<td>{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d-m-Y') : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form1_id, 13);
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
                                                        <!-- <label id="date_of_retirement-error" class="error text-danger" for="date_of_retirement"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
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
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 14);
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
                                                        <!-- <label id="permanent_addr_at-error" class="error text-danger" for="permanent_addr_at"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Post :</th>
    											<td>{{ $proposal->permanent_addr_post ? $proposal->permanent_addr_post : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 15);
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
                                                        <!-- <label id="permanent_addr_post-error" class="error text-danger" for="permanent_addr_post"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Pin :</th>
    											<td>{{ $proposal->permanent_addr_pincode ? $proposal->permanent_addr_pincode : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 16);
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
                                                        <!-- <label id="permanent_addr_pincode-error" class="error text-danger" for="permanent_addr_pincode"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Country :</th>
    											<td>{{ $proposal->cName ? $proposal->cName : ''  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 17);
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
                                                        <!-- <label id="cName-error" class="error text-danger" for="cName"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>State :</th>
    											<td>{{ $proposal->state_name ? $proposal->state_name : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 18);
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
                                                        <!-- <label id="state_name-error" class="error text-danger" for="state_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>District :</th>
    											<td>{{ $proposal->district_name ? $proposal->district_name : ''  }}</td>
    											@if(!in_array($application->application_status_id, [1]))
                                                    <td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 19);
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
                                                        <!-- <label id="district_name-error" class="error text-danger" for="district_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th colspan="4">Present Address</th>
    										</tr>
    										<tr>
    											<th>At :</th>
    											<td>{{ $proposal->present_addr_at ? $proposal->present_addr_at : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 20);
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
                                                        <!-- <label id="present_addr_at-error" class="error text-danger" for="present_addr_at"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Post :</th>
    											<td>{{ $proposal->present_addr_post ? $proposal->present_addr_post : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 21);
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
                                                        <!-- <label id="present_addr_post-error" class="error text-danger" for="present_addr_post"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Pin :</th>
    											<td>{{ $proposal->present_addr_pincode ? $proposal->present_addr_pincode : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 22);
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
                                                        <!-- <label id="present_addr_pincode-error" class="error text-danger" for="present_addr_pincode"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Country :</th>
    											<td>{{ $proposal->country_name ? $proposal->country_name : ''  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 23);
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
                                                        <!-- <label id="country_name-error" class="error text-danger" for="country_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>State :</th>
    											<td>{{ $proposal->sName ? $proposal->sName : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 24);
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
                                                        <!-- <label id="sName-error" class="error text-danger" for="sName"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>District :</th>
    											<td>{{ $proposal->dName ? $proposal->dName : ''  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 25);
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
                                                        <!-- <label id="dName-error" class="error text-danger" for="dName"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Telephone No with STD Code :</th>
    											<td>{{ $proposal->telephone_std_code ? $proposal->telephone_std_code : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 26);
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
                                                        <!-- <label id="telephone_std_code-error" class="error text-danger" for="telephone_std_code"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Mobile No :</th>
    											<td>{{ $proposal->mobile_no ? $proposal->mobile_no : ''  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 27);
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
                                                        <!-- <label id="mobile_no-error" class="error text-danger" for="mobile_no"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Email Address :</th>
    											<td>{{ $proposal->email_address ? $proposal->email_address : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 28);
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
                                                        <!-- <label id="email_address-error" class="error text-danger" for="email_address"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>PAN No :</th>
    											<td>{{ $proposal->pan_no ? $proposal->pan_no : ''  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 29);
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
                                                        <!-- <label id="pan_no-error" class="error text-danger" for="pan_no"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										@php
    											$bank_branch_id = $proposal->bank_branch_id;
    											$bankDetaills = DB::table('optcl_bank_branch_master as bbm')
    															->join('optcl_bank_master as b','b.id','=','bbm.bank_id')
    															->select('b.bank_name','bbm.branch_name','bbm.ifsc_code','bbm.micr_code')
    															->where('bbm.status', 1)
    															->where('bbm.deleted', 0)
                                                                ->where('bbm.id', $bank_branch_id)
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
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 30);
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
                                                        <!-- <label id="savings_bank_account_no-error" class="error text-danger" for="savings_bank_account_no"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Name of the Bank :</th>
    											<td>{{ $bankName }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 31);
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
                                                        <!-- <label id="bankName-error" class="error text-danger" for="bankName"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Name Address of the Branch :</th>
    											<td>{{ $branchName }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 32);
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
                                                        <!-- <label id="branchName-error" class="error text-danger" for="branchName"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
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
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 34);
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
                                                        <!-- <label id="basic_pay_amount_at_retirement-error" class="error text-danger" for="basic_pay_amount_at_retirement"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Name of the Unit (where life certificate & income tax declaration to be submitted) :</th>
    											<td>{{ $proposal->office_last_served ? $proposal->office_last_served : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 35);
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
                                                        <!-- <label id="office_last_unit-error" class="error text-danger" for="office_last_unit"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Particulars of previous civil service if any and amount and nature of any pension or gratuity received :</th>
    											<td>{{ $proposal->is_civil_service_amount_received == 1 ? 'Yes' : 'No'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 36);
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
                                                        <!-- <label id="is_civil_service_amount_received-error" class="error text-danger" for="is_civil_service_amount_received"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										@if($proposal->is_civil_service_amount_received == 1)
    										<tr>
    											<th>Particulars of previous civil service :</th>
    											<td>{{ $proposal->civil_service_name ? $proposal->civil_service_name : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 37);
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
                                                        <!-- <label id="civil_service_name-error" class="error text-danger" for="civil_service_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Amount and nature of any pension or gratuity received :</th>
                                                <td>{{ !empty($proposal->civil_service_received_amount) ? number_format($proposal->civil_service_received_amount, 2) : 'NA' }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 38);
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
                                                        <!-- <label id="civil_service_received_amount-error" class="error text-danger" for="civil_service_received_amount"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										@endif

    										<tr>
    											<th>Particulars of family pension if any Received / admissible from any other source to the retired employee and any members of his family : </th>
    											<td>{{ $proposal->is_family_pension_received_by_family_members == 1 ? 'Yes' : 'No'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 39);
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
                                                                        <input type="radio" class="form-check-input checkfield" name="is_family_pension_received_by_family_members" value="2" data-fieldid="39" 
                                                                        @if($field_status['status_id'] == 2) checked @endif>
                                                                        Reject
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <label id="is_family_pension_received_by_family_members-error" class="error text-danger" for="is_family_pension_received_by_family_members"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										@if($proposal->is_family_pension_received_by_family_members == 1)
    										<tr>
    											<th>Enter admissible form any other source to the retired employee :</th>
    											<td>{{ $proposal->admission_source_of_family_pension ? $proposal->admission_source_of_family_pension : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 40);
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
                                                        <!-- <label id="admission_source_of_family_pension-error" class="error text-danger" for="admission_source_of_family_pension"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Members of his family :</th>
    											<td>{{ $proposal->relation_name ? $proposal->relation_name : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 41);
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
                                                        <!-- <label id="relation_name-error" class="error text-danger" for="relation_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										<tr>
    											<th>Name of member :</th>
    											<td>{{ $proposal->family_member_name ? $proposal->family_member_name : 'NA'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 42);
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
                                                        <!-- <label id="family_member_name-error" class="error text-danger" for="family_member_name"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										@endif
    										<tr>
    											<th class="widt-50">Whether Commutation of pension to be made & percentage to be specified (not applicable for applicants for family pension) : </th>
    											<td>{{ $proposal->is_commutation_pension_applied == 1 ? 'Yes' : 'No'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 43);
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
                                                        <!-- <label id="is_commutation_pension_applied-error" class="error text-danger" for="is_commutation_pension_applied"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
    										</tr>
    										@if($proposal->is_commutation_pension_applied == 1)
    										<tr>
    											<th>Percentage Value :</th>
    											<td>{{ ($proposal->commutation_percentage ? $proposal->commutation_percentage : 'NA').'%'  }}</td>
                                                @if(!in_array($application->application_status_id, [1]))
        											<td>
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form2_id, 44);
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
                                                        <!-- <label id="commutation_percentage-error" class="error text-danger" for="commutation_percentage"></label> -->
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
                                                @else
                                                    <td></td>
                                                @endif
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
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 46, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_name_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											<tr>
    												<th>Mobile No</th>
    												<td>{{ (!empty($employee_nominee->mobile_no)) ? $employee_nominee->mobile_no : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 47, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_mobile_no_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_mobile_no_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											<tr>
    												<th>Date of Birth</th>
    												<td>{{ (!empty($employee_nominee->date_of_birth)) ? \Carbon\Carbon::parse($employee_nominee->date_of_birth)->format('d-m-Y') : 'NA'  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 48, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_date_of_birth_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_date_of_birth_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>

    											@if($employee_nominee->dob_attachment_path)
    											<tr>
    												<th>Proof of Date of Birth</th>
    												<td> <img class="document_img" src="{{ asset('public/' . $employee_nominee->dob_attachment_path) }}"> </td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 49, $employee_nominee->id);
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
                                                            <!-- <label id="dob_attachment_path_{{$employee_nominee->id}}-error" class="error text-danger" for="dob_attachment_path_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											@endif
    											<tr>
    												<th>Gender</th>
    												<td>{{ (!empty($employee_nominee->gender_name)) ? $employee_nominee->gender_name : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 50, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_gender_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_gender_name_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											<tr>
    												<th>Relation with Pensioner</th>
    												<td>{{ (!empty($employee_nominee->relation_name)) ? $employee_nominee->relation_name : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 51, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_relation_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_relation_name_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											@if(!empty($employee_nominee) && $employee_nominee->is_spouse == 1)
    												@if(!empty($employee_nominee) && $employee_nominee->is_2nd_spouse == 1)
    												<tr>
    													<th>1st Spouse Death Date</th>
    													<td>{{ (!empty($employee_nominee->{'1st_spouse_death_date'})) ? \Carbon\Carbon::parse($employee_nominee->{'1st_spouse_death_date'})->format('d-m-Y') : ''  }}</td>
                                                        @if(!in_array($application->application_status_id, [1]))
        													<td>
                                                                @php
                                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 53, $employee_nominee->id);
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
                                                                <!-- <label id="spouse_death_date_{{$employee_nominee->id}}-error" class="error text-danger" for="spouse_death_date_{{$employee_nominee->id}}"></label> -->
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
                                                        @else
                                                            <td></td>
                                                        @endif
    												</tr>
    												<tr>
    													<th>1st Spouse Death Certificate</th>
    													<td><img class="document_img" src="{{ asset('public/' . $employee_nominee->{'1st_spouse_death_certificate_path'}) }}"></td>
                                                        @if(!in_array($application->application_status_id, [1]))
        													<td>
                                                                @php
                                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 54, $employee_nominee->id);
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
                                                                                <input type="radio" class="form-check-input checkfield" name="spouse_death_certificate_path_{{$employee_nominee->id}}" value="2" data-fieldid="54" data-nomineeid="{{ $employee_nominee->id }}" 
                                                                                @if($field_status['status_id'] == 2) checked @endif>
                                                                                Reject
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- <label id="spouse_death_certificate_path_{{$employee_nominee->id}}-error" class="error text-danger" for="spouse_death_certificate_path_{{$employee_nominee->id}}"></label> -->
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
                                                        @else
                                                            <td></td>
                                                        @endif
    												</tr>
    												@endif
    											@endif
    											<tr>
    												<th>Nominee Preference</th>
    												<td>{{ (!empty($employee_nominee->nominee_prefrence)) ? $employee_nominee->nominee_prefrence : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 55, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_prefrence_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_prefrence_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>

    											<tr>
    												<th>Name of the Bank</th>
    												<td>{{ (!empty($employee_nominee->bank_name)) ? $employee_nominee->bank_name : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 56, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_bank_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_bank_name_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>

    											<tr>
    												<th>Name Address of the Branch</th>
    												<td>{{ (!empty($employee_nominee->branch_name)) ? $employee_nominee->branch_name : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 57, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_branch_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_branch_name_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>

    											<tr>
    												<th>IFSC Code</th>
    												<td>{{ (!empty($employee_nominee->ifsc_code)) ? $employee_nominee->ifsc_code : ''  }}</td>
    												<td></td>
    											</tr>
    											<tr>
    												<th>Savings Bank A/C No. (Single / Joint A/C with Spouse)</th>
    												<td>{{ (!empty($employee_nominee->savings_bank_account_no)) ? $employee_nominee->savings_bank_account_no : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 58, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_savings_bank_account_no_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_savings_bank_account_no_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>

    											<tr>
    												<th>Marital Status</th>
    												<td>{{ (!empty($employee_nominee->marital_status_name)) ? $employee_nominee->marital_status_name : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 59, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_marital_status_name_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_marital_status_name_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>

    											<tr>
    												<th>Aadhaar No.</th>
    												<td>{{ (!empty($employee_nominee->nominee_aadhaar_no)) ? $employee_nominee->nominee_aadhaar_no : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 60, $employee_nominee->id);
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
                                                            <!-- <label id="nominee_aadhaar_no_{{$employee_nominee->id}}-error" class="error text-danger" for="nominee_aadhaar_no_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
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
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 61, $employee_nominee->id);
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
                                                            <!-- <label id="employement_status_{{$employee_nominee->id}}-error" class="error text-danger" for="employement_status_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>

    											<tr>
    												<th>Total Income per annum</th>
    												<td>{{ !empty($employee_nominee->total_income_per_annum) ? number_format($employee_nominee->total_income_per_annum, 2) : 0  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 62, $employee_nominee->id);
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
                                                            <!-- <label id="total_income_per_annum_{{$employee_nominee->id}}-error" class="error text-danger" for="total_income_per_annum_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
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
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 63, $employee_nominee->id);
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
                                                            <!-- <label id="is_physically_handicapped_{{$employee_nominee->id}}-error" class="error text-danger" for="is_physically_handicapped_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>

    											@if($employee_nominee->is_physically_handicapped == 1)
    											<tr>
    												<th>Disability Certificate</th>
    												<td><img class="document_img" src="{{ asset('public/' . $employee_nominee->disability_certificate_path) }}"></td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 64, $employee_nominee->id);
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
                                                            <!-- <label id="disability_certificate_path_{{$employee_nominee->id}}-error" class="error text-danger" for="disability_certificate_path_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											<tr>
    												<th>Disability Percentage</th>
    												<td>{{ (!empty($employee_nominee->disability_percentage)) ? $employee_nominee->disability_percentage : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 65, $employee_nominee->id);
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
                                                            <!-- <label id="disability_percentage_{{$employee_nominee->id}}-error" class="error text-danger" for="disability_percentage_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											@endif
    											<tr>
    												<th>Amount / Share payable to Each</th>
    												<td>{{ (!empty($employee_nominee->pension_amount_share_percentage)) ? $employee_nominee->pension_amount_share_percentage : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 66, $employee_nominee->id);
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
                                                            <!-- <label id="pension_amount_share_percentage_{{$employee_nominee->id}}-error" class="error text-danger" for="pension_amount_share_percentage_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
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
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 67, $employee_nominee->id);
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
                                                            <!-- <label id="is_minor_{{$employee_nominee->id}}-error" class="error text-danger" for="is_minor_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>

    											@if($employee_nominee->is_minor == 1)
    											<tr>
    												<th>Legal Guardian Name</th>
    												<td>{{ (!empty($employee_nominee->legal_guardian_name)) ? $employee_nominee->legal_guardian_name : ''  }}</td>

                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 68, $employee_nominee->id);
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
                                                            <!-- <label id="legal_guardian_name_{{$employee_nominee->id}}-error" class="error text-danger" for="legal_guardian_name_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											<tr>
    												<th>Legal Guardian Age</th>
    												<td>{{ (!empty($employee_nominee->legal_guardian_age)) ? $employee_nominee->legal_guardian_age : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 69, $employee_nominee->id);
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
                                                            <!-- <label id="legal_guardian_age_{{$employee_nominee->id}}-error" class="error text-danger" for="legal_guardian_age_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											<tr>
    												<th>Legal Guardian Address</th>
    												<td>{{ (!empty($employee_nominee->legal_guardian_addr)) ? $employee_nominee->legal_guardian_addr : ''  }}</td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 70, $employee_nominee->id);
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
                                                            <!-- <label id="legal_guardian_addr_{{$employee_nominee->id}}-error" class="error text-danger" for="legal_guardian_addr_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											<tr>
    												<th>Legal Guardian Attachment</th>
    												<td><img class="document_img" src="{{ asset('public/' . $employee_nominee->legal_guardian_attachment_path) }}"></td>
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, $form3_id, 71, $employee_nominee->id);
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
                                                            <!-- <label id="legal_guardian_attachment_path_{{$employee_nominee->id}}-error" class="error text-danger" for="legal_guardian_attachment_path_{{$employee_nominee->id}}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
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
                                                    @if(!in_array($application->application_status_id, [1]))
        												<td>
                                                            @php
                                                                $field_status = App\Libraries\Util::checkApproveRejectStatusHRDealing($application->id, 4, $employee_document->field_id);
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
                                                            <!-- <label id="field_action_{{ $employee_document->field_id }}-error" class="error text-danger" for="field_action_{{ $employee_document->field_id }}"></label> -->
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
                                                    @else
                                                        <td></td>
                                                    @endif
    											</tr>
    											@endforeach
    										@endif
    									</table>
    								</div>
    							</div>
    						</div>

                            @if($application->application_status_id == 7)
                            <div class="card">
                                <div class="card-header" role="tab" id="headingSix">
                                    <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">Edit Recovery</a>
                                    </h6>
                                </div>
                                <div id="collapseSix" class="collapse @if($application->application_status_id == 7) show @endif" role="tabpanel" aria-labelledby="headingSix" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- <button type="button" id="addRecovery" class="btn btn-success mr-2 float-right">+ Add</button> -->
                                            </div>
                                        </div>
                                        <div class="more-recovery">
                                            @foreach($add_recovery as $key=>$recoveryDetails)
                                            <input type="hidden" name="add_recovery[{{$key}}][id_value]" value="{{$recoveryDetails->id}}">
                                            <div class="row recovery-len">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Recovery Label<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control add_recovery_label" name="add_recovery[{{$key}}][label]" value="{{$recoveryDetails->recovery_label}}"  placeholder="Enter Recovery Label" required>
                                                        <label id="label_{{$key}}-error" class="error error-msg" for="label_{{$key}}"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Recovery Value<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control add_recovery_value only_number" name="add_recovery[{{$key}}][value]" value="{{$recoveryDetails->recovery_value}}" placeholder="Enter Recovery Value" required>
                                                        <label id="value_{{$key}}-error" class="error error-msg" for="value_{{$key}}"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" id="store-recovery" class="btn btn-primary mr-2">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                                @if($add_recovery->count() > 0)
                                    <div class="card">
                                        <div class="card-header" role="tab" id="headingSix">
                                            <h6 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">Recovery Details</a>
                                            </h6>
                                        </div>
                                        <div id="collapseSix" class="collapse @if($application->application_status_id == 2) show @endif" role="tabpanel" aria-labelledby="headingSix" data-parent="#accordion">
                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    @foreach($add_recovery as $recovery)
                                                    <tr>
                                                        <th>{{ $recovery->recovery_label }}</th>
                                                        <td>{{ $recovery->recovery_value  }}</td>
                                                    </tr>
                                                    @endforeach

                                                    @if(!empty($application->recovery_attachment))
                                                        <tr>
                                                            <th>Recovery Attachment</th>
                                                            <td>
                                                                <span class="document_pdf_span" data-pdf="{{ asset('public/' . $application->recovery_attachment) }}"><i class="fa fa-file-pdf-o"></i></span>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if($application->application_status_id == 21)
                            <div class="card">
                                <div class="card-header" role="tab" id="headingSeven">
                                    <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">Part - II</a>
                                    </h6>
                                </div>
                                <input type="hidden" name="service_form_id" value="{{$service_form->id}}">
                                <div id="collapseSeven" class="collapse @if($application->application_status_id == 12) show @endif" role="tabpanel" aria-labelledby="headingSeven" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Date of Birth of Employee<span class="text-danger">*</span></label>
                                                    <div id="nominee-datepicker" class="input-group date">
                                                        <input type="text" class="form-control datepickerClass form_date_of_birth" name="form_date_of_birth" id="form_date_of_birth" value="{{ (!empty($proposal->date_of_birth)) ? \Carbon\Carbon::parse($proposal->date_of_birth)->format('d/m/Y') : ''  }}" disabled>
                                                        <span class="input-group-addon input-group-append border-left">
                                                            <span class="mdi mdi-calendar input-group-text"></span>
                                                        </span>
                                                    </div>
                                                    <label id="form_date_of_birth-error" class="error text-danger" for="form_date_of_birth"></label>            
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Date of Joining<span class="text-danger">*</span></label>
                                                    <div id="nominee-datepicker" class="input-group date">
                                                        <input type="text" class="form-control datepickerClass form_date_of_joining"  name="form_date_of_joining" id="form_date_of_joining" value="{{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('d/m/Y') : ''  }}" disabled>
                                                        <span class="input-group-addon input-group-append border-left">
                                                            <span class="mdi mdi-calendar input-group-text"></span>
                                                        </span>
                                                    </div>
                                                    <label id="form_date_of_joining-error" class="error text-danger" for="form_date_of_joining"></label>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Date of Retirement<span class="text-danger">*</span></label>
                                                    <div id="nominee-datepicker" class="input-group date">
                                                        <input type="text" class="form-control datepickerClass form_date_of_retirement"  name="form_date_of_retirement" id="form_date_of_retirement" value="{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : ''  }}" disabled>
                                                        <span class="input-group-addon input-group-append border-left">
                                                            <span class="mdi mdi-calendar input-group-text"></span>
                                                        </span>
                                                    </div>
                                                    <label id="form_date_of_retirement-error" class="error text-danger" for="form_date_of_retirement"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5 class="text-center">Service Rendered in various offices/posts</h5>
                                        <hr>
                                        <!-- <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" id="add-service-rendered" class="btn btn-success mr-2 float-right">+ Add </button>
                                            </div>
                                        </div> -->
                                        @php
                                            $organisation_details = DB::table('optcl_employee_pension_service_offices')->where('application_id', $application->id)->where('service_pension_form_id', $service_form->id)->get();
                                        @endphp
                                        <div class="row service-table">
                                            <div class="col-md-12">
                                                <table class="table table-bordered">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th rowspan="2" class="fsize">Organisation</th>
                                                            <th rowspan="2" class="fsize">Name Of Office</th>
                                                            <th rowspan="2" class="fsize">Post Held</th>
                                                            <th colspan="2" class="fsize">Period</th>
                                                            <th colspan="3" class="fsize">Period of Service</th>
                                                            <!-- <th rowspan="3" class="fsize">Action</th> -->
                                                        </tr>
                                                        <tr>
                                                            <th class="fsize">From</th>
                                                            <th class="fsize">To</th>
                                                            <th class="fsize">Years</th>
                                                            <th class="fsize">Months</th>
                                                            <th class="fsize">Days</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="service_rendered_offices">
                                                        @foreach($organisation_details as $key=>$partii)
                                                        <tr class="service_rendered_len">
                                                            <td>
                                                                 <input type="hidden" name="service_form[{{$key}}][form_part_ii_id]" id="form_organisation_{{$key}}" data-key="{{$key}}" value="{{$partii->id}}">
                                                                <input type="text" class="form-control form_organisation" name="service_form[{{$key}}][form_organisation]" id="form_organisation_{{$key}}" data-key="{{$key}}" placeholder="" value="{{$partii->organisation_name}}">
                                                                <label id="form_organisation_{{$key}}-error" class="error text-danger" for="form_organisation_{{$key}}"></label>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form_name_of_office" name="service_form[{{$key}}][form_name_of_office]" id="form_name_of_office_{{$key}}" placeholder=""  value="{{$partii->name_of_office}}"data-key="{{$key}}">
                                                                <label id="form_name_of_office_{{$key}}-error" class="error text-danger" for="form_name_of_office_{{$key}}"></label>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form_post_held" name="service_form[{{$key}}][form_post_held]" id="form_post_held_{{$key}}" placeholder=""  value="{{$partii->post_held}}" data-key="{{$key}}">
                                                                <label id="form_post_held_{{$key}}-error" class="error text-danger" for="form_post_held_{{$key}}"></label>
                                                            </td>
                                                            <td>
                                                                <div id="nominee-datepicker" class="input-group date">
                                                                    <input type="text" class="form-control datepickerClass form_period_from" name="service_form[{{$key}}][form_period_from]" id="form_period_from_{{$key}}" value="{{date('d/m/Y', strtotime($partii->service_period_from))}}" data-key="{{$key}}" readonly>
                                                                </div>
                                                                <label id="form_period_from_{{$key}}-error" class="error text-danger" for="form_period_from_{{$key}}"></label>
                                                            </td>
                                                            <td>
                                                                <div id="nominee-datepicker" class="input-group date">
                                                                    <input type="text" class="form-control datepickerClass form_period_to" name="service_form[{{$key}}][form_period_to]" id="form_period_to_{{$key}}" value="{{date('d/m/Y', strtotime($partii->service_period_to))}}" data-key="{{$key}}" readonly>
                                                                </div>
                                                                <label id="form_period_to_{{$key}}-error" class="error text-danger" for="form_period_to_{{$key}}"></label>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" class="service_years" name="service_form[{{$key}}][total_service_years]" id="total_service_years_{{$key}}" value="{{$partii->total_service_years}}">
                                                               <span class="years_{{$key}}">{{$partii->total_service_years}}</span> 
                                                            </td>
                                                            <td>
                                                                <input type="hidden" class="service_months" name="service_form[{{$key}}][total_service_months]" id="total_service_months_{{$key}}" value="{{$partii->total_service_months}}">
                                                               <span class="months_{{$key}}">{{$partii->total_service_months}}</span> 
                                                            </td>
                                                            <td>
                                                                <input type="hidden" class="service_days" name="service_form[{{$key}}][total_service_days]" id="total_service_days_{{$key}}" value="{{$partii->total_service_days}}">
                                                               <span class="days_{{$key}}">{{$partii->total_service_days}}</span> 
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="5" class="text-right">Gross Qualifying Period</th>
                                                            <input type="hidden" name="gross_years" id="gross_years" value="{{$service_form->gross_years}}">
                                                            <input type="hidden" name="gross_months" id="gross_months" value="{{$service_form->gross_months}}">
                                                            <input type="hidden" name="gross_days" id="gross_days" value="{{$service_form->gross_days}}">
                                                            <td class="gross_years">{{$service_form->gross_years}}</td>
                                                            <td class="gross_months">{{$service_form->gross_months}}</td>
                                                            <td class="gross_days">{{$service_form->gross_days}}</td>
                                                            <!-- <td></td> -->
                                                        </tr>
                                                        <tr>
                                                            <th colspan="1">Non-Qualifying Period</th>
                                                            <input type="hidden" name="non_qualifying_years" id="non_qualifying_years" value="{{$service_form->non_qualifying_years}}">
                                                            <input type="hidden" name="non_qualifying_months" id="non_qualifying_months" value="{{$service_form->non_qualifying_months}}">
                                                            <input type="hidden" name="non_qualifying_days" id="non_qualifying_days" value="{{$service_form->non_qualifying_days}}">

                                                            <td colspan="2">
                                                                <div id="non-qualifying-from" class="input-group date datepicker">
                                                                    <input type="text" class="form-control datepickerClass non-qualifying-from" name="non_qualifying_period_from" id="non-qualifying-period-from" value="{{!empty($service_form->non_qualifying_period_from) ? date('d/m/Y', strtotime($service_form->non_qualifying_period_from)) : ''}}" readonly>
                                                                </div>
                                                            </td>

                                                            <td colspan="2">
                                                                <div id="non-qualifying-to" class="input-group date datepicker">
                                                                    <input type="text" class="form-control datepickerClass non-qualifying-to" name="non_qualifying_period_to" id="non-qualifying-period-to" value="{{!empty($service_form->non_qualifying_period_to) ? date('d/m/Y', strtotime($service_form->non_qualifying_period_to)) : ''}}" readonly>
                                                                </div>
                                                            </td>

                                                            <td class="non_qualifying_years">{{$service_form->non_qualifying_years}}</td>
                                                            <td class="non_qualifying_months">{{$service_form->non_qualifying_months}}</td>
                                                            <td class="non_qualifying_days">{{$service_form->non_qualifying_days}}</td>
                                                            <!-- <td></td> -->
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="text-right">Net Qualifying Period</th>
                                                            <input type="hidden" name="net_qualifying_years" id="net_qualifying_years" value="{{$service_form->net_qualifying_years}}">
                                                            <input type="hidden" name="net_qualifying_months" id="net_qualifying_months" value="{{$service_form->net_qualifying_months}}">
                                                            <input type="hidden" name="net_qualifying_days" id="net_qualifying_days" value="{{$service_form->net_qualifying_days}}">
                                                            <td class="net_years">{{$service_form->net_qualifying_years}}</td>
                                                            <td class="net_months">{{$service_form->net_qualifying_months}}</td>
                                                            <td class="net_days">{{$service_form->net_qualifying_days}}</td>
                                                            <!-- <td></td> -->
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Whether the entire the service period is duly covered by service verficiation Certificate. (Specify the periods not verified)<span class="text-danger">*</span></label>
                                            <div class="col-sm-6 mrgtop">
                                                <div class="row radio-margleft">
                                                    <div class="col-sm-2">
                                                        <div class="form-radio">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input form_service_period_duly" name="form_service_period_duly" value="1" @if($service_form->is_service_period_duly == 1) checked @endif>
                                                                Yes
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-radio">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input form_service_period_duly" name="form_service_period_duly" value="0" @if($service_form->is_service_period_duly == 0) checked @endif>
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label id="form_service_period_duly-error" class="error text-danger" for="form_service_period_duly"></label>
                                            </div>
                                        </div>
                                        @php
                                            if($service_form->is_service_period_duly == 1){
                                                $dulyClass = '';
                                            }else{
                                                $dulyClass = 'd-none';
                                            }
                                        @endphp
                                        <div class="service_period_duly {{$dulyClass}}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Period From<span class="text-danger">*</span></label>
                                                        <div id="nominee-datepicker" class="input-group date">
                                                            <input type="text" class="form-control datepickerClass form_service_period_duly_from"  name="form_service_period_duly_from" id="form_service_period_duly_from" value="{{!empty($service_form->service_period_duly_from) ? date('d/m/Y', strtotime($service_form->service_period_duly_from)) : ''}}" readonly>
                                                            <span class="input-group-addon input-group-append border-left">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="form_service_period_duly_from-error" class="error text-danger" for="form_service_period_duly_from"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Period To<span class="text-danger">*</span></label>
                                                        <div id="nominee-datepicker" class="input-group date">
                                                            <input type="text" class="form-control datepickerClass form_service_period_duly_to"  name="form_service_period_duly_to" id="form_service_period_duly_to" value="{{!empty($service_form->service_period_duly_to) ? date('d/m/Y', strtotime($service_form->service_period_duly_to)):''}}" readonly>
                                                            <span class="input-group-addon input-group-append border-left">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="form_service_period_duly_to-error" class="error text-danger" for="form_service_period_duly_to"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Whether all the periods of absence including participation in strike, etc. have been regularised by grant  of leave or otherwise.(Specify the periods)  <span class="text-danger">*</span></label>
                                            <div class="col-sm-6 mrgtop">
                                                <div class="row radio-margleft">
                                                    <div class="col-sm-2">
                                                        <div class="form-radio">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input form_service_period_absence" name="form_service_period_absence" value="1" @if($service_form->is_period_of_absence == 1) checked @endif>
                                                                Yes
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-radio">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input form_service_period_absence" name="form_service_period_absence" value="0" @if($service_form->is_period_of_absence == 0) checked @endif>
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label id="form_service_period_absence-error" class="error text-danger" for="form_service_period_absence"></label>
                                            </div>
                                        </div>
                                        @php
                                            if($service_form->is_period_of_absence == 0){
                                                $absenseClass = '';
                                            }else{
                                                $absenseClass = 'd-none';
                                            }
                                        @endphp
                                        <div class="service_period_absence {{$absenseClass}}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Period From<span class="text-danger">*</span></label>
                                                        <div id="nominee-datepicker" class="input-group date">
                                                            <input type="text" class="form-control datepickerClass form_service_period_absence_from"  name="form_service_period_absence_from" id="form_service_period_absence_from" value="{{!empty($service_form->service_period_absence_from) ? date('d/m/Y', strtotime($service_form->service_period_absence_from)) : ''}}" readonly>
                                                            <span class="input-group-addon input-group-append border-left">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="form_service_period_absence_from-error" class="error text-danger" for="form_service_period_absence_from"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Period To<span class="text-danger">*</span></label>
                                                        <div id="nominee-datepicker" class="input-group date">
                                                            <input type="text" class="form-control datepickerClass form_service_period_absence_to"  name="form_service_period_absence_to" id="form_service_period_absence_to" value="{{!empty($service_form->service_period_absence_to) ? date('d/m/Y', strtotime($service_form->service_period_absence_to)) : ''}}" readonly>
                                                            <span class="input-group-addon input-group-append border-left">
                                                                <span class="mdi mdi-calendar input-group-text"></span>
                                                            </span>
                                                        </div>
                                                        <label id="form_service_period_absence_to-error" class="error text-danger" for="form_service_period_absence_to"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Status of departmental or judicial proceedings instituted/contemplated Or to be instituted against the employee if any <span class="text-danger">*</span></label>
                                            <div class="col-sm-6 mrgtop">
                                                <div class="row radio-margleft">
                                                    <div class="col-sm-2">
                                                        <div class="form-radio">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" name="form_status_of_departmental_judicial" value="1" @if($service_form->is_departmental_or_judicial == 1) checked @endif>
                                                                Yes
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-radio">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" name="form_status_of_departmental_judicial" value="0" @if($service_form->is_departmental_or_judicial == 0) checked @endif>
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label id="form_status_of_departmental_judicial-error" class="error text-danger" for="form_status_of_departmental_judicial"></label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Pay Band / Scale of Pay<span class="text-danger">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" name="form_scale_of_pay" class="form-control alpha_numeric" value="{{$service_form->scale_of_pay}}">
                                                <label id="form_scale_of_pay-error" class="error text-danger" for="form_scale_of_pay"></label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Last Basic Pay as on the date of retirement/cessation of service<span class="text-danger">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" name="form_last_basic_pay" class="form-control" value="{{ !empty($service_form->last_basic_pay) ? number_format($service_form->last_basic_pay, 2) : '' }}">
                                                <label id="form_last_basic_pay-error" class="error text-danger" for="form_last_basic_pay"></label>
                                            </div>
                                        </div>

                                        <hr>
                                        <h5 class="text-center">NO DEMAND CERTIFICATE</h5>
                                        <hr>

                                        <div class="form-group row">
                                            <!-- <div class="col-sm-1"></div> -->
                                            <div class="col-sm-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" id="form_is_checked" name="form_is_checked" @if($service_form->is_no_demand_certificate == 1) checked @endif>
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        There is nothing outstanding against <b>Sri/ Smt/ Miss {{ $proposal->employee_name }}, {{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }}</b> retired on <b>{{ $proposal->date_of_retirement }}</b> of <b>{{ $proposal->office_last_served }}</b> so far as the GRIDCO/OPTCL is concerned / except (specify)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            if($service_form->is_no_demand_certificate == 1){
                                                $ondemandClass = 'd-none';
                                            }else{
                                                $ondemandClass = '';
                                            }
                                        @endphp
                                        <div class="no-demand {{$ondemandClass}}">
                                            <hr>
                                            <h5 class="text-center">OR</h5>
                                            <hr>

                                            <div class="row">                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label for="staticEmail" class="col-form-label">Reason for non issue of No Demand Certificate</label>
                                                        <input type="text" name="form_reason_no_demand_certificate" class="form-control alpha_numeric" value="{{$service_form->reason_of_no_demand_certificate}}">
                                                        <label id="form_reason_no_demand_certificate-error" class="error text-danger" for="form_reason_no_demand_certificate"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="staticEmail" class="col-form-label">Recommended for provisional Pension</label>
                                                        <div class="row ml-2 mt-2">
                                                            <div class="col-md-2">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="form_provisional_pension" value="1" @if($service_form->is_no_demand_certificate == 1 && $service_form->is_recommended_provisional_pension == 1) checked @endif>
                                                                        Yes
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input mt-2" name="form_provisional_pension" value="0" @if($service_form->is_no_demand_certificate == 0 && $service_form->is_recommended_provisional_pension == 0) checked @endif>
                                                                        No
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label id="form_provisional_pension-error" class="error text-danger mt-3" for="form_provisional_pension"></label>
                                                    </div>                                                    
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" id="store-form-2" class="btn btn-primary mr-2">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else 
                                @if(!empty($service_form))
                                    <div class="card">
                                        <div class="card-header" role="tab" id="headingSeven">
                                            <h6 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">Part - II</a>
                                            </h6>
                                        </div>
                                        <div id="collapseSeven" class="collapse @if($application->application_status_id == 12) show @endif" role="tabpanel" aria-labelledby="headingSeven" data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Date of Birth of Employee: </label> {{ (!empty($proposal->date_of_birth)) ? \Carbon\Carbon::parse($proposal->date_of_birth)->format('d/m/Y') : ''  }}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Date of Joining: </label> {{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('d/m/Y') : ''  }}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Date of Retirement: </label> {{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : ''  }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <h5 class="text-center">Service Rendered in various offices/posts</h5>
                                                <hr>
                                                @php
                                                    $service_form_offices = DB::table('optcl_employee_pension_service_offices')->where('application_id', $application->id)->where('service_pension_form_id', $service_form->id)->get();

                                                @endphp
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-bordered">
                                                            <thead class="text-center">
                                                                <tr>
                                                                    <th rowspan="2" class="fsize">Organisation</th>
                                                                    <th rowspan="2" class="fsize">Name Of Office</th>
                                                                    <th rowspan="2" class="fsize">Post Held</th>
                                                                    <th colspan="2" class="fsize">Period</th>
                                                                    <th colspan="3" class="fsize">Period of Service</th>
                                                                    <!-- <th rowspan="3" class="fsize">Action</th> -->
                                                                </tr>
                                                                <tr>
                                                                    <th class="fsize">From</th>
                                                                    <th class="fsize">To</th>
                                                                    <th class="fsize">Years</th>
                                                                    <th class="fsize">Months</th>
                                                                    <th class="fsize">Days</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if($service_form_offices->count() > 0)
                                                                @foreach($service_form_offices as $service_form_office)
                                                                <tr class="service_rendered_len">
                                                                    <td>{{ $service_form_office->organisation_name }}</td>
                                                                    
                                                                    <td>{{ $service_form_office->name_of_office }}</td>
                                                                    
                                                                    <td>{{ $service_form_office->post_held }}</td>
                                                                    
                                                                    <td>{{ \Carbon\Carbon::parse($service_form_office->service_period_from)->format('d/m/Y') }}</td>
                                                                    
                                                                    <td>{{ \Carbon\Carbon::parse($service_form_office->service_period_to)->format('d/m/Y') }}</td>
                                                                    
                                                                    <td>{{ $service_form_office->total_service_years }}</td>
                                                                    
                                                                    <td>{{ $service_form_office->total_service_months }}</td>
                                                                    
                                                                    <td>{{ $service_form_office->total_service_days }}</td>
                                                                </tr>
                                                                @endforeach
                                                                @endif
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="5" class="text-right">Gross Qualifying Period</th>
                                                                    <td class="gross_years">{{ $service_form->gross_years }}</td>
                                                                    <td class="gross_months">{{ $service_form->gross_months }}</td>
                                                                    <td class="gross_days">{{ $service_form->gross_days }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="1">Non-Qualifying Period</th>
                                                                    <td colspan="2">
                                                                        {{ !empty($service_form->non_qualifying_period_from) ? \Carbon\Carbon::parse($service_form->non_qualifying_period_from)->format('d/m/Y') : '' }}
                                                                    </td>

                                                                    <td colspan="2">
                                                                        {{ !empty($service_form->non_qualifying_period_to) ? \Carbon\Carbon::parse($service_form->non_qualifying_period_to)->format('d/m/Y') : '' }}
                                                                    </td>

                                                                    <td class="non_qualifying_years">{{ $service_form->non_qualifying_years }}</td>
                                                                    <td class="non_qualifying_months">{{ $service_form->non_qualifying_months }}</td>
                                                                    <td class="non_qualifying_days">{{ $service_form->non_qualifying_days }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="5" class="text-right">Net Qualifying Period</th>
                                                                    <td class="net_years">{{ $service_form->net_qualifying_years }}</td>
                                                                    <td class="net_months">{{ $service_form->net_qualifying_months }}</td>
                                                                    <td class="net_days">{{ $service_form->net_qualifying_days }}</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="staticEmail" class="col-sm-6 col-form-label">Whether the entire the service period is duly covered by service verficiation Certificate. (Specify the periods not verified)</label>
                                                    <div class="col-sm-6 mrgtop">
                                                        @if($service_form->is_service_period_duly == 1)
                                                            Yes
                                                        @else
                                                            No
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($service_form->is_service_period_duly == 1)
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Period From :</label> {{ !empty($service_form->service_period_duly_from) ? \Carbon\Carbon::parse($service_form->service_period_duly_from)->format('d/m/Y') : '' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Period To :</label> {{ !empty($service_form->service_period_duly_to) ? \Carbon\Carbon::parse($service_form->service_period_duly_to)->format('d/m/Y') : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="form-group row">
                                                    <label for="staticEmail" class="col-sm-6 col-form-label">Whether all the periods of absence including participation in strike, etc. have been regularised by grant  of leave or otherwise.(Specify the periods) </label>
                                                    <div class="col-sm-6 mrgtop">
                                                        @if($service_form->is_period_of_absence == 1)
                                                            Yes
                                                        @else
                                                            No
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($service_form->is_period_of_absence == 0)
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Period From :</label> {{ !empty($service_form->service_period_absence_from) ? \Carbon\Carbon::parse($service_form->service_period_absence_from)->format('d/m/Y') : '' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Period To :</label> {{ !empty($service_form->service_period_absence_to) ? \Carbon\Carbon::parse($service_form->service_period_absence_to)->format('d/m/Y') : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="form-group row">
                                                    <label for="staticEmail" class="col-sm-6 col-form-label">Status of departmental or judicial proceedings instituted/contemplated Or to be instituted against the employee if any </label>
                                                    <div class="col-sm-6 mrgtop">
                                                        @if($service_form->is_departmental_or_judicial == 1)
                                                            Yes
                                                        @else
                                                            No
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="staticEmail" class="col-sm-6 col-form-label">Pay Band / Scale of Pay</label>
                                                    <div class="col-sm-6">
                                                        {{ $service_form->scale_of_pay }}
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="staticEmail" class="col-sm-6 col-form-label">Last Basic Pay as on the date of retirement/cessation of service</label>
                                                    <div class="col-sm-6">
                                                        {{ $service_form->last_basic_pay }}
                                                    </div>
                                                </div>

                                                <hr>
                                                <h5 class="text-center">NO DEMAND CERTIFICATE</h5>
                                                <hr>

                                                @if($service_form->is_no_demand_certificate == 1)
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <label class="form-check-label">
                                                                There is nothing outstanding against <b>Sri/ Smt/ Miss {{ $proposal->employee_name }}, {{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }}</b> retired on <b>{{ $proposal->date_of_retirement }}</b> of <b>{{ $proposal->office_last_served }}</b> so far as the GRIDCO/OPTCL is concerned / except (specify)
                                                        </label>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="no-demand">
                                                   
                                                    <div class="row">                                                
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                            <label for="staticEmail" class="col-form-label">Reason for non issue of No Demand Certificate</label>
                                                            <div class="row ml-0">{{$service_form->reason_of_no_demand_certificate}}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="staticEmail" class="col-form-label">Recommended for provisional Pension</label>
                                                                <div class="row ml-0">
                                                                    @if($service_form->is_no_demand_certificate == 0 && $service_form->is_recommended_provisional_pension == 1) Yes @else No @endif
                                                                </div>
                                                            </div>                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @include('user.hr-wing.dealing-assistant.form_part_3')

                            @include('user.hr-wing.dealing-assistant.calculation_sheet')

                            @if(in_array($application->application_status_id, [25, 26]))
                            <div class="card">
                                <div class="card-header" role="tab" id="headingTen">
                                    <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseTen" aria-expanded="false" aria-controls="collapseTen">Sanction Order</a>
                                    </h6>
                                </div>
                                <div id="collapseTen" class="collapse" role="tabpanel" aria-labelledby="headingTen" data-parent="#accordion">
                                    <div class="card-body">
                                        <a target="_blank" href="{{ asset('public/uploads/sanction_order/sanction_order_' . $application->application_no . '.pdf') }}">Sanction Order PDF</a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(in_array($application->application_status_id, [26]))
                            <div class="card">
                                <div class="card-header" role="tab" id="headingEleven">
                                    <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">Gratuity Sanction Order</a>
                                    </h6>
                                </div>
                                <div id="collapseEleven" class="collapse" role="tabpanel" aria-labelledby="headingEleven" data-parent="#accordion">
                                    <div class="card-body">
                                        <a target="_blank" href="{{ asset('public/uploads/gratuity_sanction_order/gratuity_sanction_order_' . $application->application_no . '.pdf') }}">Gratuity Sanction Order PDF</a>
                                    </div>
                                </div>
                            </div>
                            @endif

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
                    <button type="button" id="remove-modal" class="close modal-close" data-dismiss="modal">&times;</button>
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
    <form action="{{ route('hr_applications_submission') }}" method="post" id="application_return_remark" accept-charset="utf-8">
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


<!-- Resubmit details -->
<div class="modal fade" id="application_resubmit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <form action="{{ route('hr_dealing_assistant_application_resubmission') }}" method="post" id="application_resubmit_remark" accept-charset="utf-8">
        @csrf
        <input type="hidden" name="application_id" id="return_application_id" value="{{ $application->id }}">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Resubmit</h5>
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

@endsection

@section('page-script')

<script type="text/javascript">
    var year_month_day_url = "{{ route('get_year_month_day') }}";
    
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

        $('#remove-modal').on('click', function() {
            var field_name = $(this).closest('#main_application_remark').find('#field_name').val();
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
                        url:'{{ route("hr_applications_approval") }}',
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

                var checked = 1;

                $('.checkApprove').each(function() {
                    if($(this).prop("checked")) {
                        //checked = 1;
                    } else {
                        checked = 0;
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

        $('#resubmit-btn').on('click', function() {
            $('#application_resubmit').modal('show');
            $('#application_resubmit').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $("#application_resubmit_remark").validate({
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
                    $('#application_resubmit').modal('hide');
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

        $('#application-form').validate({
            ignore: false,
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.text('Please select appropriate option');
                element.closest('td').append(label);
            },
        });
    });
</script>

<!-- Form Part II -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#form_date_of_birth').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: new Date()
        });

        $('#form_date_of_joining').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: new Date()
        });

        $('#form_date_of_retirement').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: new Date()
        });

        $('#form_service_period_duly_from').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: $('#form_date_of_retirement').val(),
            startDate: $('#form_date_of_joining').val()
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#form_service_period_duly_to').datepicker('setStartDate', minDate);
        });

        $('#form_service_period_duly_to').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: $('#form_date_of_retirement').val(),
            startDate: $('#form_date_of_joining').val()
        });

        $('#form_service_period_absence_from').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: $('#form_date_of_retirement').val(),
            startDate: $('#form_date_of_joining').val()
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#form_service_period_absence_to').datepicker('setStartDate', minDate);
        });

        $('#form_service_period_absence_to').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: $('#form_date_of_retirement').val(),
            startDate: $('#form_date_of_joining').val()
        });

        $('#form_period_from_0').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: $('#form_date_of_retirement').val(),
            startDate: $('#form_date_of_joining').val()
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#form_period_to_0').datepicker('setStartDate', minDate);
        });

        $('#form_period_to_0').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: $('#form_date_of_retirement').val(),
            startDate: $('#form_date_of_joining').val()
        });

        $('#non-qualifying-period-from').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: $('#form_date_of_retirement').val(),
            startDate: $('#form_date_of_joining').val()
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#non-qualifying-period-to').datepicker('setStartDate', minDate);
        });

        $('#non-qualifying-period-to').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: $('#form_date_of_retirement').val(),
            startDate: $('#form_date_of_joining').val()
        });

        $(document).on('change', '.form_period_to', function() {
            let period_to = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            let period_from = $('#form_period_from_' + key).val();

            if(period_from != '') {
                $('.page-loader').addClass('d-flex');
                $.ajax({
                    url:'{{ route("get_year_month_day") }}',
                    type:'post',
                    data:'from='+period_from+'&to='+period_to+'&_token={{csrf_token()}}',
                    success:function(result) {

                        $('.years_'+key).text(result.years);
                        $('.months_'+key).text(result.months);
                        $('.days_'+key).text(result.days);

                        $('#total_service_years_'+key).val(result.years);
                        $('#total_service_months_'+key).val(result.months);
                        $('#total_service_days_'+key).val(result.days);

                        var years = 0;
                        var months = 0;
                        var days = 0;

                        $('.service_years').each(function() {
                            if($(this).val() != '') {
                                years = parseInt(years) + parseInt($(this).val());
                            }
                        });

                        $('.service_months').each(function() {
                            if($(this).val() != '') {
                                months = parseInt(months) + parseInt($(this).val());
                            }
                        });

                        $('.service_days').each(function() {
                            if($(this).val() != '') {
                                days = parseInt(days) + parseInt($(this).val());
                            }
                        });

                        $('.gross_years').text(years);
                        $('.gross_months').text(months);
                        $('.gross_days').text(days);

                        $('#gross_years').val(years);
                        $('#gross_months').val(months);
                        $('#gross_days').val(days);

                        var non_qualifying_years = $('.non_qualifying_years').text();
                        var non_qualifying_months = $('.non_qualifying_months').text();
                        var non_qualifying_days = $('.non_qualifying_days').text();

                        if(non_qualifying_years != '' && non_qualifying_months != '' && non_qualifying_days != '') {
                            var net_years = Math.abs(parseInt(years) - parseInt(non_qualifying_years));
                            var net_months = Math.abs(parseInt(months) - parseInt(non_qualifying_months));
                            var net_days = Math.abs(parseInt(days) - parseInt(non_qualifying_days));

                            $('.net_years').text(net_years);
                            $('.net_months').text(net_months);
                            $('.net_days').text(net_days);

                            $('#net_qualifying_years').val(net_years);
                            $('#net_qualifying_months').val(net_months);
                            $('#net_qualifying_days').val(net_days);
                        }

                        $('.page-loader').removeClass('d-flex');
                    }
                });
            } else {
                $(this).val('');
                swal('', 'Please select period from date first', 'error');
            }
        });

        $(document).on('change', '.form_period_from', function() {
            let period_from = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            let period_to = $('#form_period_to_' + key).val();

            if(period_to != '') {
                $('.page-loader').addClass('d-flex');
                $.ajax({
                    url:'{{ route("get_year_month_day") }}',
                    type:'post',
                    data:'from='+period_from+'&to='+period_to+'&_token={{csrf_token()}}',
                    success:function(result) {
                        $('.years_'+key).text(result.years);
                        $('.months_'+key).text(result.months);
                        $('.days_'+key).text(result.days);

                        $('#total_service_years_'+key).val(result.years);
                        $('#total_service_months_'+key).val(result.months);
                        $('#total_service_days_'+key).val(result.days);

                        var years = 0;
                        var months = 0;
                        var days = 0;

                        $('.service_years').each(function() {
                            if($(this).val() != '') {
                                years = parseInt(years) + parseInt($(this).val());
                            }
                        });

                        $('.service_months').each(function() {
                            if($(this).val() != '') {
                                months = parseInt(months) + parseInt($(this).val());
                            }
                        });

                        $('.service_days').each(function() {
                            if($(this).val() != '') {
                                days = parseInt(days) + parseInt($(this).val());
                            }
                        });

                        $('.gross_years').text(years);
                        $('.gross_months').text(months);
                        $('.gross_days').text(days);

                        $('#gross_years').val(years);
                        $('#gross_months').val(months);
                        $('#gross_days').val(days);


                        var non_qualifying_years = $('.non_qualifying_years').text();
                        var non_qualifying_months = $('.non_qualifying_months').text();
                        var non_qualifying_days = $('.non_qualifying_days').text();

                        if(non_qualifying_years != '' && non_qualifying_months != '' && non_qualifying_days != '') {
                            var net_years = Math.abs(parseInt(years) - parseInt(non_qualifying_years));
                            var net_months = Math.abs(parseInt(months) - parseInt(non_qualifying_months));
                            var net_days = Math.abs(parseInt(days) - parseInt(non_qualifying_days));

                            $('.net_years').text(net_years);
                            $('.net_months').text(net_months);
                            $('.net_days').text(net_days);

                            $('#net_qualifying_years').val(net_years);
                            $('#net_qualifying_months').val(net_months);
                            $('#net_qualifying_days').val(net_days);
                        }

                        $('.page-loader').removeClass('d-flex');
                    }
                });
            }
        });

        $(document).on('click', '#add-service-rendered', function() {
            
            if($('#application-form-2').valid()) {
                let service_rendered_len = $('.service_rendered_len').length;

                let html = '<tr class="service_rendered_len">'+
                                '<td>'+
                                    '<input type="text" class="form-control form_organisation" name="service_form['+service_rendered_len+'][form_organisation]" id="form_organisation_'+service_rendered_len+'" placeholder="" data-key="'+ service_rendered_len +'">'+
                                    '<label id="form_organisation_'+service_rendered_len+'-error" class="error text-danger" for="form_organisation_'+service_rendered_len+'"></label>'+
                                '</td>'+
                                '<td>'+
                                    '<input type="text" class="form-control form_name_of_office" name="service_form['+service_rendered_len+'][form_name_of_office]" id="form_name_of_office_'+service_rendered_len+'" placeholder="" data-key="'+ service_rendered_len +'">'+
                                    '<label id="form_name_of_office_'+service_rendered_len+'-error" class="error text-danger" for="form_name_of_office_'+service_rendered_len+'"></label>'+
                                '</td>'+
                                '<td>'+
                                    '<input type="text" class="form-control form_post_held" name="service_form['+service_rendered_len+'][form_post_held]" id="form_post_held_'+service_rendered_len+'" placeholder="" data-key="'+ service_rendered_len +'">'+
                                    '<label id="form_post_held_'+service_rendered_len+'-error" class="error text-danger" for="form_post_held_'+service_rendered_len+'"></label>'+
                                '</td>'+
                                '<td>'+
                                    '<div id="nominee-datepicker" class="input-group date datepicker">'+
                                        '<input type="text" class="form-control datepickerClass form_period_from" name="service_form['+service_rendered_len+'][form_period_from]" id="form_period_from_'+service_rendered_len+'" data-key="'+ service_rendered_len +'">'+
                                    '</div>'+
                                    '<label id="form_period_from_'+service_rendered_len+'-error" class="error text-danger" for="form_period_from_'+service_rendered_len+'"></label>'+
                                '</td>'+
                                '<td>'+
                                    '<div id="nominee-datepicker" class="input-group date datepicker">'+
                                        '<input type="text" class="form-control datepickerClass form_period_to" name="service_form['+service_rendered_len+'][form_period_to]" id="form_period_to_'+service_rendered_len+'" data-key="'+ service_rendered_len +'">'+
                                    '</div>'+
                                    '<label id="form_period_to_'+service_rendered_len+'-error" class="error text-danger" for="form_period_to_'+service_rendered_len+'"></label>'+
                                '</td>'+
                                '<td>'+
                                    '<input type="hidden" class="service_years" name="service_form['+service_rendered_len+'][total_service_years]" id="total_service_years_'+service_rendered_len+'">'+
                                   '<span class="years_'+service_rendered_len+'"></span> '+
                                '</td>'+
                                '<td>'+
                                    '<input type="hidden" class="service_months" name="service_form['+service_rendered_len+'][total_service_months]" id="total_service_months_'+service_rendered_len+'">'+
                                   '<span class="months_'+service_rendered_len+'"></span> '+
                                '</td>'+
                                '<td>'+
                                    '<input type="hidden" class="service_days" name="service_form['+service_rendered_len+'][total_service_days]" id="total_service_days_'+service_rendered_len+'">'+
                                   '<span class="days_'+service_rendered_len+'"></span> '+
                                '</td>'+
                                '<td><a id="del-service-rendered" class="btn btn-danger del-service-btn"><i class="fa fa-trash-o"></i></a></td>'+
                            '</tr>';

                $('.service_rendered_offices').append(html);

                $('#form_period_from_' + service_rendered_len).datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    format: 'dd/mm/yyyy',
                    endDate: new Date()
                }).on('changeDate', function (selected) {
                    var minDate = new Date(selected.date.valueOf());
                    $('#form_period_to_' + service_rendered_len).datepicker('setStartDate', minDate);
                });;

                $('#form_period_to_' + service_rendered_len).datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    format: 'dd/mm/yyyy',
                    endDate: new Date()
                });

                validate_form_2();
            }
        });

        $(document).on('click', '.del-service-btn', function() {
            $(this).closest('tr.service_rendered_len').remove();

            var years = 0;
            var months = 0;
            var days = 0;

            var non_qualifying_years = $('.non_qualifying_years').text();
            var non_qualifying_months = $('.non_qualifying_months').text();
            var non_qualifying_days = $('.non_qualifying_days').text();

            $('.service_years').each(function() {
                if($(this).val() != '') {
                    years = parseInt(years) + parseInt($(this).val());
                }
            });

            $('.service_months').each(function() {
                if($(this).val() != '') {
                    months = parseInt(months) + parseInt($(this).val());
                }
            });

            $('.service_days').each(function() {
                if($(this).val() != '') {
                    days = parseInt(days) + parseInt($(this).val());
                }
            });

            $('.gross_years').text(years);
            $('.gross_months').text(months);
            $('.gross_days').text(days);

            $('#gross_years').val(years);
            $('#gross_months').val(months);
            $('#gross_days').val(days);

            var net_years = parseInt(years) - parseInt(non_qualifying_years);
            var net_months = parseInt(months) - parseInt(non_qualifying_months);
            var net_days = parseInt(days) - parseInt(non_qualifying_days);

            $('.net_years').text(net_years);
            $('.net_months').text(net_months);
            $('.net_days').text(net_days);

        });

        $('#application-form-2').validate({
            ignore: false,
            rules: {
                "service_form[0][form_organisation]": {
                    required: true,
                },
                "service_form[0][form_name_of_office]": {
                    required: true,
                },
                "service_form[0][form_post_held]": {
                    required: true,
                },
                "service_form[0][form_period_from]": {
                    required: true,
                },
                "service_form[0][form_period_to]": {
                    required: true,
                },
                "form_service_period_duly": {
                    required: true,
                },
                "form_service_period_absence": {
                    required: true,
                },
                "form_status_of_departmental_judicial": {
                    required: true,
                },
                "form_scale_of_pay": {
                    required: true,
                },
                "form_last_basic_pay": {
                    required: true,
                },                
                "form_reason_no_demand_certificate": {
                    required: {depends:function(){
                            $(this).val($.trim($(this).val()));
                            if($('#form_is_checked').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }                            
                        }
                    },
                },
                "form_provisional_pension": {
                    required: {depends:function(){
                            if($('#form_is_checked').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }                            
                        }
                    },
                },
            },
            messages: {
               "service_form[0][form_organisation]": {
                    required: "Please enter organisation name",
                },
                "service_form[0][form_name_of_office]": {
                    required: "Please enter name of office",
                },
                "service_form[0][form_post_held]": {
                    required: "Please enter post held",
                },
                "service_form[0][form_period_from]": {
                    required: "Please select form date",
                },
                "service_form[0][form_period_to]": {
                    required: "Please select to date",
                },
                "form_service_period_duly": {
                    required: "Please select the field",
                },
                "form_service_period_absence": {
                    required: "Please select the field",
                },
                "form_status_of_departmental_judicial": {
                    required: "Please select the field",
                },
                "form_scale_of_pay": {
                    required: "Please enter scale of pay",
                },
                "form_last_basic_pay": {
                    required: "Please enter last basic pay",
                },
                "form_reason_no_demand_certificate": {
                    required: "Please enter reson for non issue of no demand certificate",
                },
                "form_provisional_pension": {
                    required: "Please select recommended for provisional pension",
                },
                "form_reason_no_demand_certificate": {
                    required: "Please enter reson for non issue of no demand certificate",
                },
                "form_provisional_pension": {
                    required: "Please select recommended for provisional pension",
                },
              },
            submitHandler: function(form, event) { 
                    event.preventDefault();
                    form.submit();
            },
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                //$(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });

        $(document).on('change', '#form_is_checked', function() {

            if($(this).prop('checked')) {
                //$('.no-demand').hide();
                $('.no-demand').addClass('d-none');
            } else {
                //$('.no-demand').show();
                $('.no-demand').removeClass('d-none');
            }
        });

        $(document).on('change', '.form_service_period_duly', function() {
            var form_service_period_duly = $(this).val();

            if(form_service_period_duly == 1) {
                //$('.service_period_duly').show();
                $('.service_period_duly').removeClass('d-none');
                validate_duly();
            } else {
                //$('.service_period_duly').hide();
                $('.service_period_duly').addClass('d-none');
            }
        });

        $(document).on('change', '.form_service_period_absence', function() {
            var form_service_period_absence = $(this).val();

            if(form_service_period_absence == 0) {
                //$('.service_period_absence').show();
                $('.service_period_absence').removeClass('d-none');
                validate_absence();
            } else {
                //$('.service_period_absence').hide();
                $('.service_period_absence').addClass('d-none');
            }
        });

        $(document).on('change', '.non-qualifying-to', function() {
            let period_to = $(this).val();

            let period_from = $('#non-qualifying-period-from').val();

            if(period_from != '') {
                $('.page-loader').addClass('d-flex');
                $.ajax({
                    url:'{{ route("get_year_month_day") }}',
                    type:'post',
                    data:'from='+period_from+'&to='+period_to+'&_token={{csrf_token()}}',
                    success:function(result) {

                        $('.non_qualifying_years').text(result.years);
                        $('.non_qualifying_months').text(result.months);
                        $('.non_qualifying_days').text(result.days);

                        $('#non_qualifying_years').val(result.years);
                        $('#non_qualifying_months').val(result.months);
                        $('#non_qualifying_days').val(result.days);

                        var years = 0;
                        var months = 0;
                        var days = 0;

                        $('.service_years').each(function() {
                            if($(this).val() != '') {
                                years = parseInt(years) + parseInt($(this).val());
                            }
                        });

                        $('.service_months').each(function() {
                            if($(this).val() != '') {
                                months = parseInt(months) + parseInt($(this).val());
                            }
                        });

                        $('.service_days').each(function() {
                            if($(this).val() != '') {
                                days = parseInt(days) + parseInt($(this).val());
                            }
                        });

                        var net_years = Math.abs(parseInt(years) - parseInt(result.years));
                        var net_months = Math.abs(parseInt(months) - parseInt(result.months));
                        var net_days = Math.abs(parseInt(days) - parseInt(result.days));

                        $('.net_years').text(net_years);
                        $('.net_months').text(net_months);
                        $('.net_days').text(net_days);

                        $('#net_qualifying_years').val(net_years);
                        $('#net_qualifying_months').val(net_months);
                        $('#net_qualifying_days').val(net_days);

                        $('.page-loader').removeClass('d-flex');
                    }
                });
            } else {
                $(this).val('');
                swal('', 'Please select period from date first', 'error');
            }
        });

        $(document).on('change', '.non-qualifying-from', function() {
            let period_from = $(this).val();

            $('#non-qualifying-period-to').val('');
            // Gross field Value
            var day = $('#gross_days').val();
            var month = $('#gross_months').val();
            var year = $('#gross_years').val();
            // Hidden Field Value
            $('#non_qualifying_years').val('0');
            $('#non_qualifying_months').val('0');
            $('#non_qualifying_days').val('0');
            // td Value
            $('.non_qualifying_years').text('0');
            $('.non_qualifying_months').text('0');
            $('.non_qualifying_days').text('0');
            // Net qualifing period
            $('#net_qualifying_years').val(year);
            $('#net_qualifying_months').val(month);
            $('#net_qualifying_days').val(day);

            $('.net_days').text(day);
            $('.net_months').text(month);
            $('.net_years').text(year);

            let period_to = $('#non-qualifying-period-to').val();

            if(period_to != '') {
                $('.page-loader').addClass('d-flex');
                $.ajax({
                    url:'{{ route("get_year_month_day") }}',
                    type:'post',
                    data:'from='+period_from+'&to='+period_to+'&_token={{csrf_token()}}',
                    success:function(result) {
                        $('.non_qualifying_years').text(result.years);
                        $('.non_qualifying_months').text(result.months);
                        $('.non_qualifying_days').text(result.days);

                        $('#non_qualifying_years').val(result.years);
                        $('#non_qualifying_months').val(result.months);
                        $('#non_qualifying_days').val(result.days);

                        var years = 0;
                        var months = 0;
                        var days = 0;

                        $('.service_years').each(function() {
                            if($(this).val() != '') {
                                years = parseInt(years) + parseInt($(this).val());
                            }
                        });

                        $('.service_months').each(function() {
                            if($(this).val() != '') {
                                months = parseInt(months) + parseInt($(this).val());
                            }
                        });

                        $('.service_days').each(function() {
                            if($(this).val() != '') {
                                days = parseInt(days) + parseInt($(this).val());
                            }
                        });

                        var net_years = Math.abs(parseInt(years) - parseInt(result.years));
                        var net_months = Math.abs(parseInt(months) - parseInt(result.months));
                        var net_days = Math.abs(parseInt(days) - parseInt(result.days));

                        $('.net_years').text(net_years);
                        $('.net_months').text(net_months);
                        $('.net_days').text(net_days);

                        $('#net_qualifying_years').val(net_years);
                        $('#net_qualifying_months').val(net_months);
                        $('#net_qualifying_days').val(net_days);

                        $('.page-loader').removeClass('d-flex');
                    }
                });
            }
        });
    });

    function validate_form_2() {
        $('.form_organisation').each(function() {
            $(this).rules("add", {
                required: {depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true; }
                    },
                messages: {
                    required: "Please enter organisation name",
                }
            });
        });

        $('.form_name_of_office').each(function() {
            $(this).rules("add", {
                required: {depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true; }
                    },
                messages: {
                    required: "Please enter name of office",
                }
            });
        });

        $('.form_post_held').each(function() {
            $(this).rules("add", {
                required: {depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true; }
                    },
                messages: {
                    required: "Please enter post held",
                }
            });
        });

        $('.form_period_from').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select from date",
                }
            });
        });

        $('.form_period_to').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select to date",
                }
            });
        });
    }

    function validate_duly() {
        $('.form_service_period_duly_from').each(function() {
            $(this).rules("add", {
                required: {depends:function(){
                        if($('input[name="form_service_period_duly"]').val() == 1){
                            return false;
                        }else{
                            return true;
                        }                            
                    }
                },
                messages: {
                    required: "Please select period from date",
                }
            });
        });

        $('.form_service_period_duly_to').each(function() {
            $(this).rules("add", {
                required: {depends:function(){
                        if($('input[name="form_service_period_duly"]').val() == 1){
                            return false;
                        }else{
                            return true;
                        }                            
                    }
                },
                messages: {
                    required: "Please select period to date",
                }
            });
        });
    }

    function validate_absence() {
        $('.form_service_period_absence_from').each(function() {
            $(this).rules("add", {
                required: {depends:function(){
                        if($('input[name="form_service_period_absence"]').val() == 1){
                            return false;
                        }else{
                            return true;
                        }                            
                    }
                },
                messages: {
                    required: "Please select period from date",
                }
            });
        });

        $('.form_service_period_absence_to').each(function() {
            $(this).rules("add", {
                required: {depends:function(){
                        if($('input[name="form_service_period_absence"]').val() == 1){
                            return false;
                        }else{
                            return true;
                        }                            
                    }
                },
                messages: {
                    required: "Please select period to date",
                }
            });
        });
    }
</script>

<script src="{{url('public')}}/js/custom/form_part.js"></script>
@endsection