@extends('user.layout.layout')

@section('section_content')
<style type="text/css">
    .mrgtop {
        margin-top: 26px;
    }
    .half {
        margin-left: 5px;
        margin-right: 5px;
    }
    .rule-logic, .service_pension_edit_div, .dcr-rule-logic {
        display: none;
    }
    ul, ol, dl {
        padding-left: 1rem;
        font-size: 1rem;
    }
    .onebytwo {
        padding-left: 60px;
    }
    .card .card-body {
        padding: 10px 25px;
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
            <input type="hidden" name="application_id" id="application_id" value="{{ $application->id }}">
            <input type="hidden" name="employee_id" id="employee_id" value="{{ $application->employee_id }}">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title text-center">CALCULATION SHEET OF PENSIONARY BENEFITS</h4>

                    <table class="table table-bordered">
                        <tr>
                           <th>Employee Code :</th>
                           <td>{{ !empty($proposal->employee_code) ? $proposal->employee_code : 'NA'  }}</td>
                           <th>Application No :</th>
                           <td>{{ !empty($application->application_no) ? $application->application_no : 'NA'  }}</td>
                        </tr>
                        <tr>
                           <th>SHRI/SMT:</th>
                           <td>{{ $proposal->employee_name }}</td>
                        </tr>

                        <tr>
                           <th>LAST OFFICE/ESTT:</th>
                           <td>{{ !empty($proposal->office_last_served) ? $proposal->office_last_served : 'NA'  }}</td>
                           <th>LAST UNIT HEAD:</th>
                           <td>{{ !empty($proposal->office_last_served) ? $proposal->office_last_served : 'NA'  }}</td>
                        </tr>

                        <tr>
                           <th>Retired On:</th>
                           <td>{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : 'NA'  }}</td>
                        </tr>

                        <tr>
                           <th>Date of Birth:</th>
                           <td>{{ (!empty($proposal->date_of_birth)) ? \Carbon\Carbon::parse($proposal->date_of_birth)->format('d/m/Y') : 'NA'  }}</td>
                        </tr>

                        <tr>
                           <th>Date of Entry in Service:</th>
                           <td> {{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('d/m/Y') : 'NA'  }}</td>
                        </tr>

                        <tr>
                           <th>Date of Retirement/Death:</th>
                           <td>{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : 'NA'  }}</td>
                        </tr>

                        <tr>
                            <th></th>
                            <th>Years</th>
                            <th>Months</th>
                            <th>Days</th>
                        </tr>

                        @php
                            $age_at_joining = App\Libraries\Util::get_years_months_days($proposal->date_of_birth, $proposal->date_of_joining);

                            $age_at_retirement = App\Libraries\Util::get_years_months_days($proposal->date_of_birth, $proposal->date_of_retirement);
                        @endphp
                        <tr>
                            <th>Age at the time of entry in service: </th>
                            <td>{{ $age_at_joining['years'] }}</td>
                            <td>{{ $age_at_joining['months'] }}</td>
                            <td>{{ $age_at_joining['days'] }}</td>
                        </tr>

                        <tr>
                            <th>Age at the time of death/retirement: </th>
                            <td>{{ $age_at_retirement['years'] }}</td>
                            <td>{{ $age_at_retirement['months'] }}</td>
                            <td>{{ $age_at_retirement['days'] }}</td>
                        </tr>

                        <tr>
                            <th>Gross Qualifying Service: </th>
                            <td>{{ !empty($service_form->gross_years) ? $service_form->gross_years : 0 }}</td>
                            <td>{{ !empty($service_form->gross_months) ? $service_form->gross_months : 0 }}</td>
                            <td>{{ !empty($service_form->gross_days) ? $service_form->gross_days : 0 }}</td>
                        </tr>

                        <tr>
                            <th>Non-Qualifying Service Period: </th>
                            <td>{{ !empty($form_three->total_non_qualifying_years) ? $form_three->total_non_qualifying_years : 0 }}</td>
                            <td>{{ !empty($form_three->total_non_qualifying_months) ? $form_three->total_non_qualifying_months : 0 }}</td>
                            <td>{{ !empty($form_three->total_non_qualifying_days) ? $form_three->total_non_qualifying_days : 0 }}</td>
                        </tr>

                        <tr>
                            <th>Net Qualifying Service: </th>
                            <td>{{ !empty($form_three->total_net_qualifying_years) ? $form_three->total_net_qualifying_years : 0 }}</td>
                            <td>{{ !empty($form_three->total_net_qualifying_months) ? $form_three->total_net_qualifying_months : 0 }}</td>
                            <td>{{ !empty($form_three->total_net_qualifying_days) ? $form_three->total_net_qualifying_days : 0 }}</td>
                        </tr>

                        <tr>
                            <th>Last Pay Drawn:</th>
                            <td>Rs {{ !empty($form_three->emolument_last_basic_pay) ? number_format($form_three->emolument_last_basic_pay, 2) . '/-' : number_format($proposal->basic_pay_amount_at_retirement) . '/-'  }}</td>
                            <td>(Basic: {{ !empty($form_three->emolument_last_basic_pay) ? $form_three->emolument_last_basic_pay : $proposal->basic_pay_amount_at_retirement }} GP:0) </td>
                            <td>+ DA: Rs {{ number_format($total_da_amount,2) }}/-</td>
                        </tr>
                    </table>
				</div>
			</div>

            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mb-0">
                        <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Service Pension Due</a>
                    </h6>
                </div>
                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        
                            <div class="service_pension_due_main @if(!empty($service_pension_due_exist)) d-none @endif">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Rules</label>
                                            <select class="js-example-basic-single select-drop rules form-control mb-2" name="rules" id="service_pension_rule">
                                                <option value="">Select Rule</option>
                                                @foreach($service_pension_masters as $service_pension_master)
                                                    <option @if(!empty($service_pension_due_exist) && $service_pension_due_exist->rule_id == $service_pension_master->id) selected @endif  value="{{$service_pension_master->id}}">{{$service_pension_master->rule_name}}</option>
                                                @endforeach  
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rule-logic">
                                <div class="row">
                                    <!-- <div class="col-md-1"></div> -->
                                    <div class="col-md-4">
                                        <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title"></i>
                                        <span id="info-logic"></span>
                                    </div>
                                    <input type="hidden" name="service_pension_due_value" id="service_pension_due_value" value="">
                                    <input type="hidden" name="service_pension_due_rule_id" id="service_pension_due_rule_id" value="">
                                    <input type="hidden" name="service_pension_due_calculation_type_id" id="service_pension_due_calculation_type_id" value="">
                                    <input type="hidden" name="service_pension_due_last_basic_pay" id="service_pension_due_last_basic_pay" value="">
                                    <input type="hidden" name="service_pension_due_completed_years" id="service_pension_due_completed_years" value="">
                                    <div class="col-md-12 mt-3">
                                        <a href="javascript:;" id="save-service-pension" class="btn btn-success">Save</a>
                                    </div>
                                </div>
                            </div>
                        
                            <ol type="a" class="ol-list @if(empty($service_pension_due_exist)) d-none @endif">
                                <li id="service_pension_due_pension_admissible">Pension Admissible:
                                    ({{ $service_pension_due['last_basic_pay'] }} X {{$service_pension_due['total_completed_years']}}/50 X 1/2) = Rs {{ number_format($service_pension_due['service_pension']) }}/-
                                </li>
                                <li id="service_pension_due_wef">W.E.F. :
                                    {{ !empty($form_three->date_of_commencement_pension) ?
                                        \Carbon\Carbon::parse($form_three->date_of_commencement_pension)->format('d/m/Y') : \Carbon\Carbon::parse($proposal->date_of_retirement)->addDay()->format('d/m/Y')
                                    }}    
                                    <b>till Death</b></li>
                            </ol>
                            @if(!empty($service_pension_due_exist))
                            <div class="row" id="service_pension_due_edit_div">
                                <div class="col-md-2">
                                    <a href="javascript:;" id="service_pension_due_edit_btn" class="btn btn-primary">Edit</a>
                                </div>
                            </div>
                            @endif
                    </div>
                </div>
            </div>
            @php
                //dd($application);
                $commutationDetails = DB::table('optcl_pension_application_form')
                                        ->where('employee_code', $application->employee_code)
                                        ->where('pension_type_id', 1)
                                        ->get();
                if($commutationDetails->count() > 0){
                    $is_commutation_pension_applied = $commutationDetails->first()->is_commutation_received;
                }else{
                    $is_commutation_pension_applied = 0;
                }
                //dd($is_commutation_pension_applied);
            @endphp
            @if($is_commutation_pension_applied == 0)

            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mb-0">
                        <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Commutation of Pension with/ without Medical Certificate</a>
                    </h6>
                </div>
                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <ol class="list-unstyled">
                            <li>Commutation Applied On: {{ \Carbon\Carbon::parse($application->created_at)->format('d/m/Y') }}</li>

                            <li>
                            @if(!empty($commutation_value['pension_admissible']) && $commutation_value['pension_admissible'])
                                Pension Admissionable: Rs. {{number_format($commutation_value['pension_admissible'], 2)}}</li>
                            @endif
                            <li>
                                <input type="hidden" name="commutation_rule_one" id="commutation_rule_one" value="{{!empty($commutation_value['commutation_rule_one']) ? $commutation_value['commutation_rule_one'] : '' }}">
                                <div class="row @if(!empty($commutation_value)) d-none @endif" id="commutation_rule_one_div">
                                    <div class="col-md-4">
                                        <label><strong>Rule</strong></label>
                                        <select class="js-example-basic-single form-control mb-2" id="commutation_pension_id_data" name = "{{$application->employee_id}}_{{$application->id}}"> 
                                            <option value="">Select Rule</option>
                                            @foreach($rule_one_commutation as $list)
                                            <option value="{{$list->id}}" @if(!empty($commutation_value['comm_rule_id']) && $commutation_value['comm_rule_id'] == $list->id) selected @endif>{{$list->rule_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </li>
                            <li id="commuted_html_value">
                                @if(!empty($commutation_value['pension_admissible']) && $commutation_value['pension_admissible'])
                                    <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" title="{{!empty($commutation_value['rule_description']) ? $commutation_value['rule_description'] : '' }}"></i> -->
                                    Commuted Value: Rs. {{number_format($commutation_value['pension_admissible'], 2)}} X {{$commutation_value['commutation_percentage']}}% = Rs. {{number_format($commutation_value['commuted_value'], 2)}}
                                @endif
                            </li>
                            <li id="commuted_html_rounded_value">
                                @if(!empty($commutation_value['rounded_below_value']))
                                    Rounded to below: Rs. {{number_format($commutation_value['rounded_below_value'], 2)}}
                                @endif
                            </li>
                            <li class="d-none" id="save_commutation_rule_one_div">
                                <a href="javascript:void(0)" id="save_commutation_rule_one" class="btn btn-success mt-3 mb-2">Save</a>
                            </li>
                            @php
                                if(count($commutation_value) > 0){
                                    $classValue = '';
                                }else{
                                    $classValue = 'd-none';
                                }
                            @endphp
                            
                            <li class="{{$classValue}}" id="edit_commutation_rule_one_div">
                                <a href="javascript:void(0)" id="edit_commutation_rule_one" class="btn btn-primary mt-3 mb-2">Edit</a>
                            </li>
                            
                            <li>
                            @if(!empty($commutation_value['commutation_ratio']))
                                Commutation Ratio: {{$commutation_value['commutation_ratio']}}
                            @endif
                            </li>
                        </ol>
                        <h5>Commutation Pension</h5>
                        <ol class="list-unstyled">
                            <li>
                                <input type="hidden" name="commutation_rule_two" id="commutation_rule_two" value="{{!empty($commutation_two_value['commutation_rule_two']) ? $commutation_two_value['commutation_rule_two'] : '' }}">

                                <div class="row @if(!empty($commutation_two_value)) d-none @endif" id="commutation_rule_two_div">
                                    <div class="col-md-4">
                                        <label><strong>Rule</strong></label>
                                        <select class="js-example-basic-single form-control mb-2" id="commutation_pension_id_data_two" name = "{{$application->employee_id}}_{{$application->id}}"> 
                                            <option value="">Select Rule</option>
                                            @foreach($rule_two_commutated_value as $list)
                                            <option value="{{$list->id}}"
                                             @if(!empty($commutation_two_value['comm_rule_id_two']) && $commutation_two_value['comm_rule_id_two'] == $list->id) selected @endif>{{$list->rule_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </li>
                            <li id="commuted_html_value_two">
                                @if(!empty($commutation_two_value['commuted_value']))
                                <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" title="{{!empty($commutation_two_value['rule_description']) ? $commutation_two_value['rule_description'] : '' }}"></i> -->
                                As Worked Out Rs. {{number_format($commutation_two_value['commuted_value'], 2)}}/- X {{$commutation_two_value['commutation_ratio']}} X 12 = Rs. {{number_format($commutation_two_value['as_worked_out'], 2)}}
                                @endif
                            </li>
                            <li id="commuted_html_rounded_value_two">
                            @if(!empty($commutation_two_value['rounded_as_worked_out']))
                                Rounded to: Rs. {{number_format($commutation_two_value['rounded_as_worked_out'], 2)}}
                            @endif
                            </li>
                            <li class="d-none" id="save_commutation_rule_two_div">
                                <a href="javascript:void(0)" id="save_commutation_rule_two" class="btn btn-success mt-3 mb-2">Save</a>
                            </li>
                            @php
                                if(count($commutation_two_value) > 0){
                                    $classValue = '';
                                }else{
                                    $classValue = 'd-none';
                                }
                            @endphp                            
                            <li class="{{$classValue}}" id="edit_commutation_rule_two_div">
                                <a href="javascript:void(0)" id="edit_commutation_rule_two" class="btn btn-primary mt-3 mb-2">Edit</a>
                            </li>
                            <li>
                                <input type="hidden" name="commutation_rule_three" id="commutation_rule_three" value="{{!empty($commutation_three_value['commutation_rule_three']) ? $commutation_three_value['commutation_rule_three'] : '' }}">
                                <div class="row @if(!empty($commutation_three_value)) d-none @endif" id="commutation_rule_three_div">
                                    <div class="col-md-4">
                                        <label><strong>Rule</strong></label>
                                        <select class="js-example-basic-single form-control mb-2" id="commutation_pension_id_data_three" name = "{{$application->employee_id}}_{{$application->id}}"> 
                                            <option value="">Select Rule</option>
                                            @foreach($rule_three_reduced_pension_value as $list)
                                            <option value="{{$list->id}}" @if(!empty($commutation_three_value['comm_rule_id_three']) && $commutation_three_value['comm_rule_id_three'] == $list->id) selected @endif >{{$list->rule_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </li>
                            <li id="commuted_html_value_three">
                                @if(!empty($commutation_three_value['rounded_as_worked_out']))
                                <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" title="{{!empty($commutation_three_value['rule_description']) ? $commutation_three_value['rule_description'] : '' }}"></i> -->
                                Reduced pension per month: {{number_format($commutation_three_value['pension_admissible'], 2)}} -  {{number_format($commutation_three_value['commuted_value'], 2)}} = <strong> Rs. {{number_format($commutation_three_value['reduced_pension_per_month'], 2)}}/-</strong>
                                @endif
                            </li>
                            <li class="d-none" id="save_commutation_rule_three_div">
                                <a href="javascript:void(0)" id="save_commutation_rule_three" class="btn btn-success mt-3">Save</a>
                            </li>
                            @php
                                if(count($commutation_three_value) > 0){
                                    $classValue = '';
                                }else{
                                    $classValue = 'd-none';
                                }
                            @endphp                            
                            <li class="{{$classValue}}" id="edit_commutation_rule_three_div">
                                <a href="javascript:void(0)" id="edit_commutation_rule_three" class="btn btn-primary mt-3">Edit</a>
                            </li>
                        </ol>

                    </div>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mb-0">
                        <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Family Pension Due</a>
                    </h6>
                </div>
                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row @if(!empty($family_pension_data)) d-none @endif" id="family_rule_div">
                            <div class="col-md-4">
                                <label>Rule</label>
                                <select class="js-example-basic-single form-control mb-2" id="family_pension_id_data" name = "{{$application->employee_id}}_{{$application->id}}"> 
                                    <option value="">Select Rule</option>
                                    @foreach($rule_family as $list)
                                    <option value="{{$list->id}}" @if(!empty($family_pension_data['rule_id']) && $family_pension_data['rule_id'] == $list->id) selected @endif>{{$list->rule_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <input type="hidden" name="family_pensioner_details" id="family_pensioner_details" value="{{!empty($family_pension_data['family_pensioner_details']) ? $family_pension_data['family_pensioner_details'] : '' }}">
                            <div class="col-md-12">
                                <ol class="list-unstyled" id="family_pension_view">

                                    @if(count($family_pension_data) > 0 && $family_pension_data['rule_id'] == 5)
                                        <li><!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" title="{{--!empty($family_pension_data['rule_description']) ? $family_pension_data['rule_description'] : '' --}}"></i> --> Pension Amount: Rs. {{number_format($family_pension_data['fp_pension_amount'], 2)}}</li>
                                        <li>Rounded To: Rs. {{number_format($family_pension_data['fp_rounded_to'], 2)}}</li>
                                        <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                                        <li>In the event of death of pensioner before attaining the age of 65 years Rs. {{number_format($family_pension_data['fp_pension_admissible'], 2)}} + TI is payable to <strong>{{$family_pension_data['nominee_name']}}</strong> <br>DOB({{date("d/m/Y", strtotime($family_pension_data['nominee_dob']))}}) {{ $family_pension_data['nominee_relation'] }} of pensioner, from day following the day of death of pensioner upto {{$family_pension_data['Last_Full_Pension_Date']}} and <br>thereafter @ Rs. {{number_format($family_pension_data['fp_pension_amount'], 2)}}/- till her death or remarriage whichever is earlier.</li>
                                    @elseif(count($family_pension_data) > 0 && $family_pension_data['rule_id'] == 8)
                                        <li>Pension Amount: Rs. {{number_format($family_pension_data['fp_pension_amount'], 2)}}</li>
                                        <li>Rounded To: Rs. {{number_format($family_pension_data['fp_rounded_to'], 2)}}</li>
                                        <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                                        <li>This Family pension allowed for 10 years from the next day of the date of death ({{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : 'NA'  }}).</li>
                                    @elseif(count($family_pension_data) > 0 && $family_pension_data['rule_id'] == 9)
                                        <li>Pension Amount: Rs. {{number_format($family_pension_data['fp_pension_amount'], 2)}}</li>
                                        <li>Rounded To: Rs. {{number_format($family_pension_data['fp_rounded_to'], 2)}}</li>
                                        <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                                        <li>This Family pension allowed for 7 years or upto 65 years of SP if allived which ever is erlier from the next day of the date of death ({{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : 'NA'  }}).</li>
                                    @elseif(count($family_pension_data) > 0 && $family_pension_data['rule_id'] == 10)
                                        <li>Pension Amount: Rs. {{number_format($family_pension_data['fp_pension_amount'], 2)}}</li>
                                        <li>Rounded To: Rs. {{number_format($family_pension_data['fp_rounded_to'], 2)}}</li>
                                        <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                                        <li>This Family pension allowed till the service pensioner complete the  65 years if allived form the next day of death of SP ({{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : 'NA'  }}).</li>
                                    @elseif(count($family_pension_data) > 0 && $family_pension_data['rule_id'] == 11)
                                        <li>Pension Amount: Rs. {{number_format($family_pension_data['fp_pension_amount'], 2)}}</li>
                                        <li>Rounded To: Rs. {{number_format($family_pension_data['fp_rounded_to'], 2)}}</li>
                                        <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                                        <li>This Family pension allowed till the service pensioner complete the  65 years if allived form the next day of death of FP.</li>
                                    @elseif(count($family_pension_data) > 0 && $family_pension_data['rule_id'] == 13)
                                        <li>Pension Amount: Rs. {{number_format($family_pension_data['fp_pension_amount'], 2)}}</li>
                                        <li>Rounded To: Rs. {{number_format($family_pension_data['fp_rounded_to'], 2)}}</li>
                                        <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                                        <li>Service Pensioner died within in 7 Years of Service.</li>
                                    @endif
                                </ol>
                            </div>
                        </div>

                        <div class="row" id="family_edit_button_div">
                            @if(count($family_pension_data) > 0)
                            <div class="col-md-2">
                                <a href="javascript:void(0)" id="edit_family_pension" class="btn btn-primary">Edit</a>
                            </div>
                            @endif
                        </div>
                        <div class="row" id="family_button_div">
                            <div class="col-md-2">
                                <a href="javascript:void(0)" id="save_family_pension" class="btn btn-success d-none">Save</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mb-0">
                        <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">D.C.R. Gratuity</a>
                    </h6>
                </div>
                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">

                        <div class="service_dcr_gratuity @if(!empty($dcr_gratuity_exist)) d-none @endif">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Rules</label>
                                        <select class="js-example-basic-single select-drop form-control mb-2" name="rules" id="dcr_gratuity_rule">
                                            <option value="">Select Rule</option>
                                            @foreach($service_dcr_gratuity as $dcr_gratuity)
                                                <option @if(!empty($dcr_gratuity_exist) && $dcr_gratuity_exist->rule_id == $dcr_gratuity->id) selected @endif value="{{$dcr_gratuity->id}}">{{$dcr_gratuity->rule_name}}</option>
                                            @endforeach  
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dcr-rule-logic">
                            <div class="row">
                                <!-- <div class="col-md-1"></div> -->
                                <div class="col-md-4">
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="dcr-info-title" title="1/4 X (Last Basic + DA Prevailing at the DOR) X Completed Half Years (Max 66 half years) (To be Rounded up to nearest rupee)"></i>
                                    <span id="dcr-info-logic"></span>
                                </div>

                                <input type="hidden" name="dcr_gratutiy_rule_id" id="dcr_gratutiy_rule_id">
                                <input type="hidden" name="dcr_gratuity_value" id="dcr_gratuity_value" value="">
                                <input type="hidden" name="sdcr_gratuity_last_basic_pay" id="dcr_gratuity_last_basic_pay" value="">
                                <input type="hidden" name="dcr_gratuity_completed_years" id="dcr_gratuity_completed_years" value="">
                                <input type="hidden" name="dcr_gratuity_da_amount" id="dcr_gratuity_da_amount" value="">
                                <input type="hidden" name="dcr_gratutiy_calculation_type_id" id="dcr_gratutiy_calculation_type_id" value="">

                                <div class="col-md-12">
                                    <a href="javascript:;" id="save-dcr-gratuity" class="btn btn-success mt-3">Save</a>
                                </div>
                            </div>
                        </div>

                        <ol type="a" class="dcr-ol-list @if(empty($dcr_gratuity_exist)) d-none @endif">
                            @php
                                $total_gratuity = 0;
                            @endphp
                            @if(!empty($dcr_gratuity_exist) && $dcr_gratuity_exist->rule_id == 7)
                                <li id="dcr_gratuity_logic">({{ $dcr_gratuity_value['last_basic_pay'] }} + {{ $total_da_amount }}) X 1/4 X {{ $dcr_gratuity_value['dcr_completed_years'] }} </li>

                                @if(!empty($recovery_details))
                                    @foreach($recovery_details as $recovery_detail)
                                        <li id="recovery">{{ $recovery_detail->recovery_label }} (Recovery) : {{ $recovery_detail->recovery_value }}</li>
                                        @php
                                            $total_gratuity = $total_gratuity + $recovery_detail->recovery_value;
                                        @endphp
                                    @endforeach
                                @endif

                                <li id="dcr_sub_limitation">(Subject of Limitation Rs. 1500000/-) is Rs. {{ round($dcr_gratuity_value['total_dcr_gratuity'] - $total_gratuity) }}/-</li>

                            @elseif(!empty($dcr_gratuity_exist) && $dcr_gratuity_exist->rule_id == 12)
                                <li id="dcr_gratuity_logic">({{ $dcr_gratuity_value['last_basic_pay'] }} + {{ $total_da_amount }}) X 1/2 X {{ $dcr_gratuity_value['dcr_completed_years'] }} </li>
                                @if(!empty($recovery_details))
                                    @foreach($recovery_details as $recovery_detail)
                                        <li id="recovery">{{ $recovery_detail->recovery_label }} (Recovery) : {{ $recovery_detail->recovery_value }}</li>
                                        @php
                                            $total_gratuity = $total_gratuity + $recovery_detail->recovery_value;
                                        @endphp
                                    @endforeach
                                @endif
                                <li id="dcr_sub_limitation">(Subject of Limitation Rs. 1500000/-) is Rs. {{ round($dcr_gratuity_value['total_dcr_gratuity'] - $total_gratuity) }}/-</li>
                            @endif
                        </ol>

                        @if(!empty($dcr_gratuity_exist))
                        <div class="row" id="dcr_edit_div">
                            <div class="col-md-2">
                                <a href="javascript:;" id="dcr_gratuity_edit_btn" class="btn btn-primary">Edit</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($is_commutation_pension_applied == 0)
                @if(!empty($service_pension_due_exist) && !empty($dcr_gratuity_exist) && count($family_pension_data) > 0 && count($commutation_value) > 0 && count($commutation_two_value) > 0 && count($commutation_three_value) > 0)
                <button type="button" id="calculation-sheet-submit" class="btn btn-primary">Submit</button>
                @endif
            @else 
                @if(!empty($service_pension_due_exist) && !empty($dcr_gratuity_exist) && count($family_pension_data) > 0)
                <button type="button" id="calculation-sheet-submit" class="btn btn-primary">Submit</button>
                @endif
            @endif
		</div>
	</div>
</div>

@endsection

@section('page-script')

<!-- Service Pension Due calculation -->
<script type="text/javascript">
    $(document).ready(function() {

        // Save Service Pension Due
        $(document).on('change', '#service_pension_rule', function() {
            var rule_id = $(this).val();
            var application_id = $('#application_id').val();
            var employee_id = $('#employee_id').val();

            if(rule_id == 1) {
                $('.page-loader').addClass('d-flex'); 
                $('#service_pension_due_rule_id').val(rule_id);

                $.ajax({
                    url:'{{ route("fp_calculate_rules") }}',
                    type:'post',
                    data:'application_id='+application_id+'&_token={{csrf_token()}}&rule_id='+rule_id+'&employee_id='+employee_id,
                    success:function(result){
                        
                        if(result.status == 'success') {
                            var title = result.service_pension_due.rule_desc;
                            var logic = result.service_pension_due.rule_logic;
                            var service_pension_value = result.service_pension_due.service_pension;
                            var last_basic_pay = result.service_pension_due.last_basic_pay;
                            var total_completed_years = result.service_pension_due.total_completed_years;
                            var calculation_type_id = result.service_pension_due.calculation_type_id;

                            $('#info-title').attr('data-original-title', title);
                            $('#info-logic').html(logic);
                            $('#service_pension_due_value').val(service_pension_value);
                            $('#service_pension_due_last_basic_pay').val(last_basic_pay);
                            $('#service_pension_due_completed_years').val(total_completed_years);
                            $('#service_pension_due_calculation_type_id').val(calculation_type_id);

                            $('.page-loader').removeClass('d-flex');
                            $('.rule-logic').show();
                        } else {
                            swal('', result.message, 'error');
                            $('.page-loader').removeClass('d-flex');
                        }
                    }
                });
            } else {
                $('.rule-logic').hide();
                // $('.page-loader').removeClass('d-flex');
            }
        });

        $(document).on('click', '#save-service-pension', function() {
            $('.page-loader').addClass('d-flex'); 
            var rule_id = $('#service_pension_due_rule_id').val();
            var pension_value = $('#service_pension_due_value').val();
            var application_id = $('#application_id').val();
            var employee_id = $('#employee_id').val();
            var last_basic_pay = $('#service_pension_due_last_basic_pay').val();
            var total_completed_years = $('#service_pension_due_completed_years').val();
            var calculation_type_id = $('#service_pension_due_calculation_type_id').val();

            $.ajax({
                url:'{{ route("fp_calculate_service_pension_save") }}',
                type:'post',
                data:'application_id='+application_id+'&_token={{csrf_token()}}&rule_id='+rule_id+'&employee_id='+employee_id+'&pension_value='+pension_value+'&last_basic_pay='+last_basic_pay+'&total_completed_years='+total_completed_years+'&calculation_type_id='+calculation_type_id,
                success:function(result){
                    
                    if(result.status == 'success') {
                        location.reload();
                    } else {
                        swal('', result.message, 'error');
                        $('.page-loader').removeClass('d-flex');
                    }
                }
            });
        });


        // Edit Service Pension Due
        $(document).on('click', '#service_pension_due_edit_btn', function() {
            // $(this).hide();
            $('#service_pension_due_edit_div').addClass('d-none');
            $('.service_pension_due_main').removeClass('d-none');
            $('.ol-list').addClass('d-none');
            $("#service_pension_rule").change();
            $(".js-example-basic-single").select2();
        });
    });
</script>

<!-- DCR Gratuity Calculation -->
<script type="text/javascript">
    $(document).ready(function() {

        // Save Service Pension Due
        $(document).on('change', '#dcr_gratuity_rule', function() {

            var rule_id = $(this).val();
            var application_id = $('#application_id').val();
            var employee_id = $('#employee_id').val();


            if(rule_id == 7 || rule_id == 12) {
                $('.page-loader').addClass('d-flex'); 
                $('#dcr_gratutiy_rule_id').val(rule_id);

                $.ajax({
                    url:'{{ route("fp_calculate_rules") }}',
                    type:'post',
                    data:'application_id='+application_id+'&_token={{csrf_token()}}&rule_id='+rule_id+'&employee_id='+employee_id,
                    success:function(result){
                        
                        if(result.status == 'success') {
                            var title = result.dcr_gratuity.rule_desc;
                            var logic = result.dcr_gratuity.rule_logic;
                            var da_amount = result.dcr_gratuity.total_da_amount;
                            var last_basic_pay = result.dcr_gratuity.last_basic_pay;
                            var dcr_completed_years = result.dcr_gratuity.dcr_completed_years;
                            var total_dcr_gratuity = result.dcr_gratuity.total_dcr_gratuity;
                            var total_gratuity = result.dcr_gratuity.total_gratuity;
                            var calculation_type_id = result.dcr_gratuity.calculation_type_id;
                            
                            $('#dcr-info-title').attr('data-original-title', title);
                            $('#dcr-info-logic').html(logic);

                            $('#dcr_gratuity_value').val(total_gratuity);
                            $('#dcr_gratuity_last_basic_pay').val(last_basic_pay);
                            $('#dcr_gratuity_completed_years').val(dcr_completed_years);
                            $('#dcr_gratuity_da_amount').val(da_amount);
                            $('#dcr_gratutiy_calculation_type_id').val(calculation_type_id);
                            
                            $('.page-loader').removeClass('d-flex');
                            $('.dcr-rule-logic').show();
                        } else {
                            swal('', result.message, 'error');
                            $('.page-loader').removeClass('d-flex');
                        }
                    }
                });
            } else {
                $('.dcr-rule-logic').hide();
            }
        });

        $(document).on('click', '#save-dcr-gratuity', function() {
            $('.page-loader').addClass('d-flex'); 
            var rule_id = $('#dcr_gratuity_rule').val();
            var application_id = $('#application_id').val();
            var employee_id = $('#employee_id').val();

            var total_dcr_gratuity = $('#dcr_gratuity_value').val();
            var last_basic_pay = $('#dcr_gratuity_last_basic_pay').val();
            var dcr_completed_years = $('#dcr_gratuity_completed_years').val();
            var da_amount = $('#dcr_gratuity_da_amount').val();
            var calculation_type_id = $('#dcr_gratutiy_calculation_type_id').val();

            $.ajax({
                url:'{{ route("fp_calculate_dcr_gratuity_save") }}',
                type:'post',
                data:'application_id='+application_id+'&_token={{csrf_token()}}&rule_id='+rule_id+'&employee_id='+employee_id+'&dcr_gratuity_value='+dcr_gratuity_value+'&last_basic_pay='+last_basic_pay+'&total_completed_years='+dcr_completed_years+'&da_amount='+da_amount+'&total_dcr_gratuity='+total_dcr_gratuity+"&calculation_type_id="+calculation_type_id,
                success:function(result){
                    
                    if(result.status == 'success') {
                        location.reload();
                    } else {
                        swal('', result.message, 'error');
                        $('.page-loader').removeClass('d-flex');
                    }
                }
            });
        });

        $(document).on('click', '#dcr_gratuity_edit_btn', function() {
            $('#dcr_edit_div').addClass('d-none');
            $('.service_dcr_gratuity').removeClass('d-none');
            $('.dcr-ol-list').addClass('d-none');
            $("#dcr_gratuity_rule").change();
            $(".js-example-basic-single").select2();
        });
    });
</script>

<!-- Family and Commutation -->
<script type="text/javascript">
    $(document).ready(function(){
        $('#family_pension_id_data').on('change', function(){
            var rule_id = $(this).val();
            var name_value = $(this).attr("name");
            $('.page-loader').addClass('d-flex');

            $.ajax({
                url:'{{ route("fp_get_family_pension_details") }}',
                type:'post',
                data:'rule_id='+rule_id+'&_token={{csrf_token()}}&name_value='+name_value,
                success:function(result){  
                    $('.page-loader').removeClass('d-flex');
                    if(result.view_details){
                        $("#family_pension_view").html(result.view_details);
                        $("#family_pensioner_details").val(result.detail_value);
                        $("#save_family_pension").removeClass('d-none');
                        $("#edit_family_pension").addClass('d-none');
                        $('[data-toggle="tooltip"]').tooltip();
                    }else{
                        $("#family_pension_view").html("");
                    }
                }
            });
        });

        $('#save_family_pension').on('click',function(){
            var transaction_val = $("#family_pensioner_details").val();
            if(transaction_val != ""){
                $('.page-loader').addClass('d-flex');
                $.ajax({
                    url:'{{ route("fp_save_transaction_details") }}',
                    type:'post',
                    data:'transaction_val='+transaction_val+'&_token={{csrf_token()}}',
                    success:function(result){  
                        // $('.page-loader').removeClass('d-flex');
                        if(result.status == 'success') {
                            /*$('#family_rule_div').addClass('d-none');
                            $('#family_button_div').addClass('d-none');*/
                            location.reload();
                        } else {
                            //location.reload();
                        }
                    }
                });
            }
        });

        $("#edit_family_pension").on('click', function(){
            $('#family_rule_div').removeClass('d-none');
            $('#family_button_div').removeClass('d-none');
            $('#family_edit_button_div').addClass('d-none');
            $('#save_family_pension').removeClass('d-none');
            $("#family_pension_id_data").change();
            $(".js-example-basic-single").select2();
        });

        $('#commutation_pension_id_data').on('change', function(){
            var rule_id = $(this).val();
            var name_value = $(this).attr("name");
            $('.page-loader').addClass('d-flex');
            $.ajax({
                url:'{{ route("fp_get_commutation_rule_one") }}',
                type:'post',
                data:'rule_id='+rule_id+'&_token={{csrf_token()}}&name_value='+name_value,
                success:function(result){  
                    console.log(result);
                    $('.page-loader').removeClass('d-flex');
                    if(result.calculation_li_value){
                         $("#commuted_html_value").html(result.calculation_li_value);
                         $("#commuted_html_rounded_value").html(result.calculation_rounded_li_value);
                         $("#save_commutation_rule_one_div").removeClass('d-none');
                         $("#commutation_rule_one").val(result.commuted_rule_one_details);
                         $('[data-toggle="tooltip"]').tooltip();
                    }else{
                        $("#commuted_html_value").html("");
                        $("#commuted_html_rounded_value").html("");
                        $("#save_commutation_rule_one_div").addClass('d-none');
                        $("#commutation_rule_one").val("");
                        // $("#family_pension_view").html("");
                    }
                }
            });
        });

        $('#save_commutation_rule_one').on('click',function(){
            var transaction_val = $("#commutation_rule_one").val();
            if(transaction_val != ""){
                $('.page-loader').addClass('d-flex');
                $.ajax({
                    url:'{{ route("fp_save_transaction_details") }}',
                    type:'post',
                    data:'transaction_val='+transaction_val+'&_token={{csrf_token()}}',
                    success:function(result){  
                        // $('.page-loader').removeClass('d-flex');
                        if(result.status == 'success'){
                            //console.log('qqqq');
                            /*$('#family_rule_div').addClass('d-none');
                            $('#family_button_div').addClass('d-none');*/
                            location.reload();
                        }else{
                            //location.reload();
                        }
                    }
                });
            }
        });

        $("#edit_commutation_rule_one").on('click', function(){
            $('#save_commutation_rule_one_div').removeClass('d-none');
            $('#edit_commutation_rule_one_div').addClass('d-none');
            $('#commutation_rule_one_div').removeClass('d-none');
            $('#commutation_pension_id_data').change();
            $(".js-example-basic-single").select2();
        });
        
        $('#commutation_pension_id_data_two').on('change', function(){
            var rule_id = $(this).val();
            var name_value = $(this).attr("name");
            $('.page-loader').addClass('d-flex');
            $.ajax({
                url:'{{ route("fp_get_commutation_rule_two") }}',
                type:'post',
                data:'rule_id='+rule_id+'&_token={{csrf_token()}}&name_value='+name_value,
                success:function(result){  
                    $('.page-loader').removeClass('d-flex');
                    if(result.calculation_li_value_two){
                         $("#commuted_html_value_two").html(result.calculation_li_value_two);
                         $("#commuted_html_rounded_value_two").html(result.calculation_rounded_li_value_two);
                         $("#save_commutation_rule_two_div").removeClass('d-none');
                         $("#commutation_rule_two").val(result.commuted_rule_two_details);
                         $('[data-toggle="tooltip"]').tooltip();
                    }else{
                        $("#commuted_html_value_two").html("");
                        $("#commuted_html_rounded_value_two").html("");
                        $("#save_commutation_rule_two_div").addClass('d-none');
                        $("#commutation_rule_two").val("");
                        // $("#family_pension_view").html("");
                    }
                }
            });
        });
        
        $('#save_commutation_rule_two').on('click',function(){
            var transaction_val = $("#commutation_rule_two").val();
            if(transaction_val != ""){
                $('.page-loader').addClass('d-flex');
                $.ajax({
                    url:'{{ route("fp_save_transaction_details") }}',
                    type:'post',
                    data:'transaction_val='+transaction_val+'&_token={{csrf_token()}}',
                    success:function(result){  
                        // $('.page-loader').removeClass('d-flex');
                        if(result.status == 'success'){
                            location.reload();
                        }else{
                            //location.reload();
                        }
                    }
                });
            }
        });

        $("#edit_commutation_rule_two").on('click', function(){
            $('#save_commutation_rule_two_div').removeClass('d-none');
            $('#edit_commutation_rule_two_div').addClass('d-none');
            $('#commutation_rule_two_div').removeClass('d-none');
            $('#commutation_pension_id_data_two').change();
            $(".js-example-basic-single").select2();
        });

        $('#commutation_pension_id_data_three').on('change', function(){
            var rule_id = $(this).val();
            var name_value = $(this).attr("name");
            $('.page-loader').addClass('d-flex');
            $.ajax({
                url:'{{ route("fp_get_commutation_rule_three") }}',
                type:'post',
                data:'rule_id='+rule_id+'&_token={{csrf_token()}}&name_value='+name_value,
                success:function(result){  
                    $('.page-loader').removeClass('d-flex');
                    if(result.calculation_li_value_three){
                        $("#commuted_html_value_three").html(result.calculation_li_value_three);
                        $("#save_commutation_rule_three_div").removeClass('d-none');
                        $("#commutation_rule_three").val(result.commuted_rule_three_details);
                        $('[data-toggle="tooltip"]').tooltip();
                    }else{
                        $("#commuted_html_value_three").html("");
                        $("#save_commutation_rule_three_div").addClass('d-none');
                        $("#commutation_rule_three").val("");
                    }
                }
            });
        });

        $('#save_commutation_rule_three').on('click',function(){
            var transaction_val = $("#commutation_rule_three").val();
            if(transaction_val != ""){
                $('.page-loader').addClass('d-flex');
                $.ajax({
                    url:'{{ route("fp_save_transaction_details") }}',
                    type:'post',
                    data:'transaction_val='+transaction_val+'&_token={{csrf_token()}}',
                    success:function(result) {
                        // $('.page-loader').removeClass('d-flex');
                        if(result.status == 'success'){
                            location.reload();
                        }else{
                            //location.reload();
                        }
                    }
                });
            }
        });

        $("#edit_commutation_rule_three").on('click', function(){
            $('#save_commutation_rule_three_div').removeClass('d-none');
            $('#edit_commutation_rule_three_div').addClass('d-none');
            $('#commutation_rule_three_div').removeClass('d-none');
            $('#commutation_pension_id_data_three').change();
            $(".js-example-basic-single").select2();
        });
    });
</script>

<!-- Calculation Sheet Submitted -->
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '#calculation-sheet-submit', function() {
            var application_id = $('#application_id').val();
            var employee_id = $('#employee_id').val();

            $('.page-loader').addClass('d-flex');

            $.ajax({
                url:'{{ route("fp_calculation_sheet_submitted") }}',
                type:'post',
                data:'application_id='+application_id+'&employee_id='+employee_id+'&_token={{csrf_token()}}',
                success:function(result){
                    $('.page-loader').removeClass('d-flex');
                    if(result.status == 'success') {
                        swal({
                            title: '',
                            text: result.message,
                            icon: 'success'
                        }).then(function() {
                            location.href = result.url;
                        });
                    } else {
                        swal({
                            title: '',
                            text: result.message,
                            icon: 'error'
                        }).then(function() {
                            
                        });
                    }
                }
            });
        });
    });
</script>

@endsection