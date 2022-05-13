@if(in_array($application->application_status_id, [24, 25, 26, 36, 37, 38, 42, 43, 44]))
<div class="card">
    <div class="card-header" role="tab" id="headingNine">
        <h6 class="mb-0">
        <a class="collapsed" data-toggle="collapse" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine">Calculation Sheet</a>
        </h6>
    </div>
    <div id="collapseNine" class="collapse" role="tabpanel" aria-labelledby="headingNine" data-parent="#accordion">
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
                    <td>{{ !empty($service_form_three->total_non_qualifying_years) ? $service_form_three->total_non_qualifying_years : 0 }}</td>
                    <td>{{ !empty($service_form_three->total_non_qualifying_months) ? $service_form_three->total_non_qualifying_months : 0 }}</td>
                    <td>{{ !empty($service_form_three->total_non_qualifying_days) ? $service_form_three->total_non_qualifying_days : 0 }}</td>
                </tr>

                <tr>
                    <th>Net Qualifying Service: </th>
                    <td>{{ !empty($service_form_three->total_net_qualifying_years) ? $service_form_three->total_net_qualifying_years : 0 }}</td>
                    <td>{{ !empty($service_form_three->total_net_qualifying_months) ? $service_form_three->total_net_qualifying_months : 0 }}</td>
                    <td>{{ !empty($service_form_three->total_net_qualifying_days) ? $service_form_three->total_net_qualifying_days : 0 }}</td>
                </tr>

                <tr>
                    <th>Last Pay Drawn:</th>
                    <td>Rs {{ !empty($service_form_three->emolument_last_basic_pay) ? number_format($service_form_three->emolument_last_basic_pay, 2) . '/-' : number_format($proposal->basic_pay_amount_at_retirement) . '/-'  }}</td>
                    <td>(Basic: {{ !empty($service_form_three->emolument_last_basic_pay) ? $service_form_three->emolument_last_basic_pay : $proposal->basic_pay_amount_at_retirement }} GP:0) </td>
                    <td>+ DA: Rs {{ number_format($total_da_amount,2) }}/-</td>
                </tr>
            </table>
            <hr>
            <h4>Service Pension Due</h4>
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

            <hr>
            <h4>Commutation of Pension with/ without Medical Certificate</h4>
            <ol class="list-unstyled">
                <li>Commutation Applied On: {{ \Carbon\Carbon::parse($application->created_at)->format('d/m/Y') }}</li>

                <li>
                @if(!empty($commutation_value['pension_admissible']) && $commutation_value['pension_admissible'])
                    Pension Admissionable: Rs. {{number_format($commutation_value['pension_admissible'], 2)}}</li>
                @endif
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
                <li>
                @if(!empty($commutation_value['commutation_ratio']))
                    Commutation Ratio: {{$commutation_value['commutation_ratio']}}
                @endif
                </li>
            </ol>
            <h5>Commutation Pension</h5>
            <ol class="list-unstyled">
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

                <li id="commuted_html_value_three">
                    @if(!empty($commutation_three_value['rounded_as_worked_out']))
                    <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" title="{{!empty($commutation_three_value['rule_description']) ? $commutation_three_value['rule_description'] : '' }}"></i> -->
                    Reduced pension per month: {{number_format($commutation_three_value['pension_admissible'], 2)}} -  {{number_format($commutation_three_value['commuted_value'], 2)}} = <strong> Rs. {{number_format($commutation_three_value['reduced_pension_per_month'], 2)}}/-</strong>
                    @endif
                </li>
            </ol>

            <hr>
            <h4>Family Pension Due</h4>

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

            <hr>
            <h4>D.C.R. Gratuity</h4>
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
        </div>
    </div>
</div>
@endif

