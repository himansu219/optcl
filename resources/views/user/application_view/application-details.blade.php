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
    .fa-check {
        color: green !important;
    }
    .fa-times {
        color: #DB504A !important;
    }
</style>
<div class="content-wrapper">
    <nav aria-label="breadcrumb" role="navigation" class="bg-white">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
            @if(\Request::route()->getName() == 'pension_unit_head_sp_history_application_view' )
            <li class="breadcrumb-item"><a href="{{route('monthly_changed_data_list')}}">Monthly Changed Data</a></li>
            @else
            <li class="breadcrumb-item"><a href="{{route('billing_officer_approval_list_list')}}">Monthly Changed Data</a></li>
            @endif
            @if(\Request::route()->getName() == 'pension_unit_head_sp_history_application_view' )
            <li class="breadcrumb-item"><a href="{{route('monthly_changed_data_history')}}">Forward History</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Application Details</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-12 grid-margin">
            <input type="hidden" name="return_remark_value" id="return_remark_value">
            <div class="card">
                <div class="card-body">
                    <form action="" id="changed_request_approval" method="post">
                    <h4 class="card-title">Application Details
                        <!-- <a href="javascript:;" class="btn btn-danger float-right marg-left-col">Returned</a> -->
                        <!-- <a href="javascript:;" class="btn btn-success float-right">Approve</a> -->
                        @if(in_array($application->application_status_id, [19,27]))
                        <!-- <button type="button" id="return-btn" class="btn btn-danger float-right ml-2">Return</button> -->
                        <!-- <button type="button" id="approve-btn" class="btn btn-success float-right">Approve</button> -->
                        @endif

                        @if(!in_array($application->application_status_id, [1,2,3,12]))
                        <!-- <a href="{{--route('calculate_pensionar_benefits', $application->id)--}}" class="btn btn-success float-right mr-2">Calculation Pension</a> -->
                        @endif
                        
                            @csrf
                            <input type="hidden" name="monthly_changed_data_id" value="">
                            <input type="hidden" name="application_type" value="">
                            <input type="hidden" name="pensioner_type" value="">
                            <input type="hidden" name="application_id" value="">
                            <button type="submit" id="approve-btn" class="btn btn-success float-right">Approve</button>                        
                    </h4>
                    </form>
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
                                        <td>{{ date('d-m-Y h:i A', strtotime($application->created_at)) }}</td>
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
                                                <input type="hidden" class="field_value" name="fieldName_3_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 3);
                                                    //dd($field_status);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i> 
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Designation :</th>
                                            <td>{{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_4_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 4);
                                                    //dd($field_status);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Father's Name :</th>
                                            <td>{{ (!empty($proposal->father_name)) ? $proposal->father_name : ''  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_5_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 5);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Gender :</th>
                                            <td>{{ $proposal->gender_name ? $proposal->gender_name : 'NA' }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_6_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 6);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Marital Status :</th>
                                            <td>{{ $proposal->marital_status_name ? $proposal->marital_status_name : 'NA' }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_7_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 7);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Religion :</th>
                                            <td>{{ $proposal->religion_name ? $proposal->religion_name : 'NA' }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_8_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 8);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>PF A/C Type :</th>
                                            <td>{{ $proposal->account_type ? $proposal->account_type : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_9_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 9);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>PF A/C No. :</th>
                                            <td>{{ $proposal->pf_account_no ? $proposal->pf_account_no : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_79_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 79);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Name of the Office :</th>
                                            <td>{{ $proposal->office_last_served ? $proposal->office_last_served : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_10_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 10);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Date of Birth :</th>
                                            <td>{{ (!empty($proposal->date_of_birth)) ? \Carbon\Carbon::parse($proposal->date_of_birth)->format('d-m-Y') : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_11_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 11);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Date of Joining Service :</th>
                                            <td>{{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('d-m-Y') : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_12_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 12);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Date of Retirement :</th>
                                            <td>{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d-m-Y') : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_13_1_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form1_id, 13);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
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
                                                <input type="hidden" class="field_value" name="fieldName_14_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 14);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Post :</th>
                                            <td>{{ $proposal->permanent_addr_post ? $proposal->permanent_addr_post : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_15_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 15);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Pin :</th>
                                            <td>{{ $proposal->permanent_addr_pincode ? $proposal->permanent_addr_pincode : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_16_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 16);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Country :</th>
                                            <td>{{ $proposal->cName ? $proposal->cName : ''  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_17_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 17);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>State :</th>
                                            <td>{{ $proposal->state_name ? $proposal->state_name : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_18_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 18);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>District :</th>
                                            <td>{{ $proposal->district_name ? $proposal->district_name : ''  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_19_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 19);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
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
                                                <input type="hidden" class="field_value" name="fieldName_20_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 20);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Post :</th>
                                            <td>{{ $proposal->present_addr_post ? $proposal->present_addr_post : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_21_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 21);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Pin :</th>
                                            <td>{{ $proposal->present_addr_pincode ? $proposal->present_addr_pincode : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_22_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 22);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Country :</th>
                                            <td>{{ $proposal->country_name ? $proposal->country_name : ''  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_23_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 23);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>State :</th>
                                            <td>{{ $proposal->sName ? $proposal->sName : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_24_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 24);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>District :</th>
                                            <td>{{ $proposal->dName ? $proposal->dName : ''  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_25_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 25);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Telephone No with STD Code :</th>
                                            <td>{{ $proposal->telephone_std_code ? $proposal->telephone_std_code : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_26_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 26);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Mobile No :</th>
                                            <td>{{ $proposal->mobile_no ? $proposal->mobile_no : ''  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_27_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 27);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Email Address :</th>
                                            <td>{{ $proposal->email_address ? $proposal->email_address : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_28_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 28);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>PAN No :</th>
                                            <td>{{ $proposal->pan_no ? $proposal->pan_no : ''  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_29_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 29);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $bank_branch_id = $proposal->bank_branch_id;
                                            $bankDetaills = DB::table('optcl_bank_branch_master as bbm')
                                                            ->join('optcl_bank_master as b','b.id','=','bbm.bank_id')
                                                            ->select('b.bank_name','bbm.branch_name','bbm.ifsc_code','bbm.micr_code')
                                                            ->where('bbm.id', $bank_branch_id)
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
                                                <input type="hidden" class="field_value" name="fieldName_30_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 30);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Name of the Bank :</th>
                                            <td>{{ $bankName }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_31_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 31);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Name Address of the Branch :</th>
                                            <td>{{ $branchName }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_32_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 32);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
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
                                                <input type="hidden" class="field_value" name="fieldName_34_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 34);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Name of the Unit (where life certificate & income tax declaration to be submitted) :</th>
                                            <td>{{ $proposal->office_last_served ? $proposal->office_last_served : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_35_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 35);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Particulars of previous civil service if any and amount and nature of any pension or gratuity received :</th>
                                            <td>{{ $proposal->is_civil_service_amount_received == 1 ? 'Yes' : 'No'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_36_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 36);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @if($proposal->is_civil_service_amount_received == 1)
                                        <tr>
                                            <th>Particulars of previous civil service :</th>
                                            <td>{{ $proposal->civil_service_name ? $proposal->civil_service_name : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_37_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 37);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Amount and nature of any pension or gratuity received :</th>
                                            <td>{{ !empty($proposal->civil_service_received_amount) ? number_format($proposal->civil_service_received_amount, 2) : 'NA' }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_38_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 38);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <th>Particulars of family pension if any Received / admissible from any other source to the retired employee and any members of his family : </th>
                                            <td>{{ $proposal->is_family_pension_received_by_family_members == 1 ? 'Yes' : 'No'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_39_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 39);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @if($proposal->is_family_pension_received_by_family_members == 1)
                                        <tr>
                                            <th>Enter admissible form any other source to the retired employee :</th>
                                            <td>{{ $proposal->admission_source_of_family_pension ? $proposal->admission_source_of_family_pension : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_40_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 40);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Members of his family :</th>
                                            <td>{{ $proposal->relation_name ? $proposal->relation_name : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_41_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 41);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Name of member :</th>
                                            <td>{{ $proposal->family_member_name ? $proposal->family_member_name : 'NA'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_42_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 42);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th class="widt-50">Whether Commutation of pension to be made & percentage to be specified (not applicable for applicants for family pension) : </th>
                                            <td>{{ $proposal->is_commutation_pension_applied == 1 ? 'Yes' : 'No'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_43_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 43);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @if($proposal->is_commutation_pension_applied == 1)
                                        <tr>
                                            <th>Percentage Value :</th>
                                            <td>{{ ($proposal->commutation_percentage ? $proposal->commutation_percentage : 'NA').'%'  }}</td>
                                            <td>
                                                <input type="hidden" class="field_value" name="fieldName_44_2_{{$application->id}}" value="1">
                                                @php
                                                    $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form2_id, 44);
                                                @endphp

                                                @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
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
                                                    <input type="hidden" class="field_value" name="fieldName_46_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 46, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Mobile No</th>
                                                <td>{{ (!empty($employee_nominee->mobile_no)) ? $employee_nominee->mobile_no : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_47_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 47, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Date of Birth</th>
                                                <td>{{ (!empty($employee_nominee->date_of_birth)) ? \Carbon\Carbon::parse($employee_nominee->date_of_birth)->format('d-m-Y') : 'NA'  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_48_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 48, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                                </td>
                                            </tr>
                                            @if($employee_nominee->dob_attachment_path)
                                            <tr>
                                                <th>Proof of Date of Birth</th>
                                                <td> <img class="document_img" src="{{ asset('public/' . $employee_nominee->dob_attachment_path) }}"> </td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_49_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 49, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Gender</th>
                                                <td>{{ (!empty($employee_nominee->gender_name)) ? $employee_nominee->gender_name : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_50_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 50, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Relation with Pensioner</th>
                                                <td>{{ (!empty($employee_nominee->relation_name)) ? $employee_nominee->relation_name : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_51_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 51, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                    <div class="preview"> 
                                                        <i class="fa fa-check"></i> 
                                                    </div>
                                                @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                    <div class="preview"> 
                                                        <i class="fa fa-times"></i>
                                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                    </div>
                                                @else
                                                @endif
                                                </td>
                                            </tr>
                                            @if(!empty($employee_nominee) && $employee_nominee->is_spouse == 1)
                                                @if(!empty($employee_nominee) && $employee_nominee->is_2nd_spouse == 1)
                                                <tr>
                                                    <th>1st Spouse Death Date</th>
                                                    <td>{{ (!empty($employee_nominee->{'1st_spouse_death_date'})) ? \Carbon\Carbon::parse($employee_nominee->{'1st_spouse_death_date'})->format('d-m-Y') : ''  }}</td>
                                                    <td>
                                                        <input type="hidden" class="field_value" name="fieldName_53_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 53, $employee_nominee->id);
                                                        @endphp

                                                        @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i>
                                                                <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                            </div>
                                                        @else
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>1st Spouse Death Certificate</th>
                                                    <td><img class="document_img" src="{{ asset('public/' . $employee_nominee->{'1st_spouse_death_certificate_path'}) }}"></td>
                                                    <td>
                                                        <input type="hidden" class="field_value" name="fieldName_54_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                        @php
                                                            $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 54, $employee_nominee->id);
                                                        @endphp

                                                        @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                            <div class="preview"> 
                                                                <i class="fa fa-check"></i> 
                                                            </div>
                                                        @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                            <div class="preview"> 
                                                                <i class="fa fa-times"></i>
                                                                <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                            </div>
                                                        @else
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                            @endif
                                            <tr>
                                                <th>Nominee Preference</th>
                                                <td>{{ (!empty($employee_nominee->nominee_prefrence)) ? $employee_nominee->nominee_prefrence : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_55_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 55, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Name of the Bank</th>
                                                <td>{{ (!empty($employee_nominee->bank_name)) ? $employee_nominee->bank_name : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_56_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 56, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Name Address of the Branch</th>
                                                <td>{{ (!empty($employee_nominee->branch_name)) ? $employee_nominee->branch_name : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_57_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 57, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>IFSC Code</th>
                                                <td>{{ (!empty($employee_nominee->ifsc_code)) ? $employee_nominee->ifsc_code : ''  }}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <th>Savings Bank A/C No. (Single / Joint A/C with Spouse)</th>
                                                <td>{{ (!empty($employee_nominee->savings_bank_account_no)) ? $employee_nominee->savings_bank_account_no : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_58_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 58, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Marital Status</th>
                                                <td>{{ (!empty($employee_nominee->marital_status_name)) ? $employee_nominee->marital_status_name : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_59_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 59, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Aadhaar No.</th>
                                                <td>{{ (!empty($employee_nominee->nominee_aadhaar_no)) ? $employee_nominee->nominee_aadhaar_no : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_60_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 60, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
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
                                                    <input type="hidden" class="field_value" name="fieldName_61_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 61, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Total Income per annum</th>
                                                <td>{{ !empty($employee_nominee->total_income_per_annum) ? number_format($employee_nominee->total_income_per_annum, 2) : 0  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_62_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 62, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
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
                                                    <input type="hidden" class="field_value" name="fieldName_63_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 63, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>

                                            @if($employee_nominee->is_physically_handicapped == 1)
                                            <tr>
                                                <th>Disability Certificate</th>
                                                <td><img class="document_img" src="{{ asset('public/' . $employee_nominee->disability_certificate_path) }}"></td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_64_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 64, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Disability Percentage</th>
                                                <td>{{ (!empty($employee_nominee->disability_percentage)) ? $employee_nominee->disability_percentage : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_65_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 65, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Amount / Share payable to Each</th>
                                                <td>{{ (!empty($employee_nominee->pension_amount_share_percentage)) ? $employee_nominee->pension_amount_share_percentage : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_66_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 66, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
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
                                                    <input type="hidden" class="field_value" name="fieldName_67_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 67, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($employee_nominee->is_minor == 1)
                                            <tr>
                                                <th>Legal Guardian Name</th>
                                                <td>{{ (!empty($employee_nominee->legal_guardian_name)) ? $employee_nominee->legal_guardian_name : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_68_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 68, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Legal Guardian Age</th>
                                                <td>{{ (!empty($employee_nominee->legal_guardian_age)) ? $employee_nominee->legal_guardian_age : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_69_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 69, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Legal Guardian Address</th>
                                                <td>{{ (!empty($employee_nominee->legal_guardian_addr)) ? $employee_nominee->legal_guardian_addr : ''  }}</td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_70_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 70, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Legal Guardian Attachment</th>
                                                <td><img class="document_img" src="{{ asset('public/' . $employee_nominee->legal_guardian_attachment_path) }}"></td>
                                                <td>
                                                    <input type="hidden" class="field_value" name="fieldName_71_3_{{$application->id}}_{{$employee_nominee->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, $form3_id, 71, $employee_nominee->id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
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
                                                    <input type="hidden" class="field_value" name="fieldName_{{ $employee_document->field_id }}_4_{{$application->id}}" value="1">
                                                    @php
                                                        $field_status = App\Libraries\Util::checkApproveRejectStatusUH($application->id, 4, $employee_document->field_id);
                                                    @endphp

                                                    @if($field_status['form_submit'] && $field_status['status_id'] == 1)
                                                        <div class="preview"> 
                                                            <i class="fa fa-check"></i> 
                                                        </div>
                                                    @elseif($field_status['form_submit'] && $field_status['status_id'] == 2)
                                                        <div class="preview"> 
                                                            <i class="fa fa-times"></i>
                                                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" data-original-title="{{ !empty($field_status['remarks']) ? $field_status['remarks'] : NULL }}" ></i>
                                                        </div>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

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
                                                    <th>Last Pay Certificate</th>
                                                    <td>
                                                        <span class="document_pdf_span" data-title="Last Pay Certificate" data-pdf="{{ asset('public/' . $application->recovery_attachment) }}"><i class="fa fa-file-pdf-o"></i></span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

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
        </div>
    </div>
</div>

<div class="modal fade" id="application_remark" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <form action="{{ route('unit_head_applications_submission') }}" method="post" id="application_return_remark" accept-charset="utf-8">
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

@endsection
@section('page-script')

<script type="text/javascript">
    $(document).ready(function() {

        $('#approve-btn').on('click', function() {
            $('.field_value').val(1);
            $('#application_status').val(1);
            $('#application-form').submit();           
        });

        $('#return-btn').on('click', function() {
            $('.field_value').val(0);
            $('#return_application_status').val(0);
            $('#application_remark').modal('show');
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

                    $('.field_value').val(0);
                    $('#application_status').val(0);
                    var remark_value = $('#remarks').val();
                    $('#return_remark_value').val(remark_value);
                    $('#application_remark').modal('hide');
                    $('#application-form').submit();
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
    });
</script>

@endsection