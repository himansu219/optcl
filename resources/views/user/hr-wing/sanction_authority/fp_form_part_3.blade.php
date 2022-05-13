@if(!empty($service_form_three))
    <div class="card">
        <div class="card-header" role="tab" id="headingEight">
            <h6 class="mb-0">
            <a class="collapsed" data-toggle="collapse" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">Part - III</a>
            </h6>
        </div>
        <div id="collapseEight" class="collapse" role="tabpanel" aria-labelledby="headingEight" data-parent="#accordion">
            <div class="card-body">
                <h5 class="text-center">(To be completed by the HR Pension Cell)</h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" class="fsize text-center">FINAL</th>
                            <th colspan="4" class="fsize text-center">PROVISIONAL</th>
                            <th colspan="3" class="fsize text-center">REVISED</th>
                        </tr>
                    </thead>
                </table>
                <br>
                <table class="table table-bordered">
                    <tr>
                        <td>1</td>
                        <th colspan="4">Date of Birth Christian era.</th>
                        <td colspan="3">{{ (!empty($proposal->date_of_birth)) ? \Carbon\Carbon::parse($proposal->date_of_birth)->format('d-m-Y') : 'NA'  }}</td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <th colspan="4">Date of Beginning Of Service.</th>
                        
                        <td colspan="3">{{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('d-m-Y') : 'NA'  }}</td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <th colspan="4">Date of Retirement / Cessation of service.</th>

                        <td colspan="3">{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d-m-Y') : 'NA'  }}</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <th colspan="4">Date of Death.</th>
                        <td colspan="3">NA</td>
                    </tr>
                    <tr>
                        <td rowspan="2">5</td>
                        <th colspan="4">Period of gross qualifying services</th>
                        <th colspan="1">Year</th>
                        <th colspan="1">Month</th>
                        <th colspan="1">Days</th>
                    </tr>
                    <tr>
                        <!-- <td rowspan="1">5</td> -->
                        <td colspan="4"></td>
                        <td colspan="1" class="gross_qualifying_year">{{ !empty($service_form) ? $service_form->gross_years : 0 }}</td>
                        <td colspan="1" class="gross_qualifying_month">{{ !empty($service_form) ? $service_form->gross_months : 0 }}</td>
                        <td colspan="1" class="gross_qualifying_days">{{ !empty($service_form) ? $service_form->gross_days : 0 }}</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <th colspan="1">Less</th>
                        <th colspan="1">Period of non-qualifying service</th>
                        <th colspan="1">From</th>
                        <th colspan="1">To</th>
                        <td colspan="1"></td>
                        <td colspan="1"></td>
                        <td colspan="1"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="1">i</td>
                        <th colspan="1">Interruption in service condoned</th>
                        
                        <td colspan="1">{{ !empty($service_form_three->interruption_service_from) ? \Carbon\Carbon::parse($service_form_three->interruption_service_from)->format('d-m-Y') : 'NA' }}</td>
                        
                        <td colspan="1">{{ !empty($service_form_three->interruption_service_to) ? \Carbon\Carbon::parse($service_form_three->interruption_service_to)->format('d-m-Y') : 'NA' }}</td>
                        
                        <td colspan="1" class="non-qualifying-service-periods-year" id="interruption_service_year">
                            {{ !empty($service_form_three->interruption_service_years) ? $service_form_three->interruption_service_years : 0 }}    
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-month" id="interruption_service_month">{{ !empty($service_form_three->interruption_service_months) ? $service_form_three->interruption_service_months : 0 }}</td>
                        <td colspan="1" class="non-qualifying-service-periods-days" id="interruption_service_days">{{ !empty($service_form_three->interruption_service_days) ? $service_form_three->interruption_service_days : 0 }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="1">ii</td>
                        <th colspan="1">Extraordinary leave not qualifying for pension</th>
                        <td colspan="1">{{ !empty($service_form_three->extraordinary_leave_from) ? \Carbon\Carbon::parse($service_form_three->extraordinary_leave_from)->format('d-m-Y') : 'NA' }}</td>
                        
                        <td colspan="1">{{ !empty($service_form_three->extraordinary_leave_to) ? \Carbon\Carbon::parse($service_form_three->extraordinary_leave_to)->format('d-m-Y') : 'NA' }}</td>

                        <td colspan="1" class="non-qualifying-service-periods-year" id="extraordinary_leave_year">
                            {{ !empty($service_form_three->interruption_service_years) ? $service_form_three->interruption_service_years : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-month" id="extraordinary_leave_month">
                            {{ !empty($service_form_three->interruption_service_years) ? $service_form_three->interruption_service_years : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-days" id="extraordinary_leave_days">
                            {{ !empty($service_form_three->interruption_service_years) ? $service_form_three->interruption_service_years : 0 }}
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td colspan="1">iii</td>
                        <th colspan="1">Period of suspension not trated as qualifying service</th>
                        <td colspan="1">{{ !empty($service_form_three->period_of_suspension_from) ? \Carbon\Carbon::parse($service_form_three->period_of_suspension_from)->format('d-m-Y') : 'NA' }}</td>
                        
                        <td colspan="1">{{ !empty($service_form_three->period_of_suspension_to) ? \Carbon\Carbon::parse($service_form_three->period_of_suspension_to)->format('d-m-Y') : 'NA' }}</td>

                        <td colspan="1" class="non-qualifying-service-periods-year" id="period_of_suspension_year">
                            {{ !empty($service_form_three->period_of_suspension_years) ? $service_form_three->period_of_suspension_years : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-month" id="period_of_suspension_month">
                            {{ !empty($service_form_three->period_of_suspension_months) ? $service_form_three->period_of_suspension_months : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-days" id="period_of_suspension_days">
                            {{ !empty($service_form_three->period_of_suspension_days) ? $service_form_three->period_of_suspension_days : 0 }}
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td colspan="1">iv</td>
                        <th colspan="1">Work charged service period not treated as qualifying service</th>

                        <td colspan="1">{{ !empty($service_form_three->work_charged_service_from) ? \Carbon\Carbon::parse($service_form_three->work_charged_service_from)->format('d-m-Y') : 'NA' }}</td>
                        
                        <td colspan="1">{{ !empty($service_form_three->work_charged_service_to) ? \Carbon\Carbon::parse($service_form_three->work_charged_service_to)->format('d-m-Y') : 'NA' }}</td>
                        
                        <td colspan="1" class="non-qualifying-service-periods-year" id="work_charged_service_year">
                            {{ !empty($service_form_three->work_charged_service_years) ? $service_form_three->work_charged_service_years : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-month" id="work_charged_service_month">
                            {{ !empty($service_form_three->work_charged_service_months) ? $service_form_three->work_charged_service_months : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-days" id="work_charged_service_days">
                            {{ !empty($service_form_three->work_charged_service_days) ? $service_form_three->work_charged_service_days : 0 }}
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td colspan="1">v</td>
                        <th colspan="1">Boy service period if not treated as qualifying service</th>

                        <td colspan="1">{{ !empty($service_form_three->boy_service_from) ? \Carbon\Carbon::parse($service_form_three->boy_service_from)->format('d-m-Y') : 'NA' }}</td>
                        
                        <td colspan="1">{{ !empty($service_form_three->boy_service_to) ? \Carbon\Carbon::parse($service_form_three->boy_service_to)->format('d-m-Y') : 'NA' }}</td>

                        <td colspan="1" class="non-qualifying-service-periods-year" id="boy_service_year">
                            {{ !empty($service_form_three->boy_service_years) ? $service_form_three->boy_service_years : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-month" id="boy_service_month">
                            {{ !empty($service_form_three->boy_service_months) ? $service_form_three->boy_service_months : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-days" id="boy_service_days">
                            {{ !empty($service_form_three->boy_service_days) ? $service_form_three->boy_service_days : 0 }}
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td colspan="1">vi</td>
                        <th colspan="1">Any other service not treated as qualifying service</th>
                        <td colspan="1">{{ !empty($service_form_three->any_other_service_from) ? \Carbon\Carbon::parse($service_form_three->any_other_service_from)->format('d-m-Y') : 'NA' }}</td>
                        
                        <td colspan="1">{{ !empty($service_form_three->any_other_service_to) ? \Carbon\Carbon::parse($service_form_three->any_other_service_to)->format('d-m-Y') : 'NA' }}</td>

                        <td colspan="1" class="non-qualifying-service-periods-year" id="any_other_service_year">
                            {{ !empty($service_form_three->any_other_service_years) ? $service_form_three->any_other_service_years : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-month" id="any_other_service_month">
                            {{ !empty($service_form_three->any_other_service_months) ? $service_form_three->any_other_service_months : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-days" id="any_other_service_days">
                            {{ !empty($service_form_three->any_other_service_days) ? $service_form_three->any_other_service_days : 0 }}
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td colspan="1">vii</td>
                        <th colspan="3">Total (i) to (vii)</th>

                        <td colspan="1" class="non-qualifying-service-periods-total-year">
                            {{ !empty($service_form_three->total_non_qualifying_years) ? $service_form_three->total_non_qualifying_years : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-total-month">
                            {{ !empty($service_form_three->total_non_qualifying_months) ? $service_form_three->total_non_qualifying_months : 0 }}
                        </td>
                        <td colspan="1" class="non-qualifying-service-periods-total-days">
                            {{ !empty($service_form_three->total_non_qualifying_days) ? $service_form_three->total_non_qualifying_days : 0 }}
                        </td>
                    </tr>

                    <tr>
                        <td>7</td>
                        <th colspan="4">Qualifying Service ( 5 - 6 )</th>

                        <td colspan="1" id="qualifying_service_period_year" >
                            {{ !empty($service_form_three->total_qualifying_years) ? $service_form_three->total_qualifying_years : 0 }}
                        </td>
                        <td colspan="1" id="qualifying_service_period_month" >
                            {{ !empty($service_form_three->total_qualifying_months) ? $service_form_three->total_qualifying_months : 0 }}
                        </td>
                        <td colspan="1" id="qualifying_service_period_days" >
                            {{ !empty($service_form_three->total_qualifying_days) ? $service_form_three->total_qualifying_days : 0 }}
                        </td>
                    </tr>

                    <tr>
                        <td>8</td>
                        <th colspan="2">Addition to qualifying service if any.</th>

                        <td colspan="1">{{ !empty($service_form_three->addition_of_qualifying_service_from) ? \Carbon\Carbon::parse($service_form_three->addition_of_qualifying_service_from)->format('d-m-Y') : 'NA' }}</td>
                        
                        <td colspan="1">{{ !empty($service_form_three->addition_of_qualifying_service_to) ? \Carbon\Carbon::parse($service_form_three->addition_of_qualifying_service_to)->format('d-m-Y') : 'NA' }}</td>
                        <td colspan="1" id="addition_of_qualifying_service_year">
                            {{ !empty($service_form_three->total_addition_qualifying_years) ? $service_form_three->total_addition_qualifying_years : 0 }}
                        </td>
                        <td colspan="1" id="addition_of_qualifying_service_month">
                            {{ !empty($service_form_three->total_addition_qualifying_months) ? $service_form_three->total_addition_qualifying_months : 0 }}
                        </td>
                        <td colspan="1" id="addition_of_qualifying_service_days">
                            {{ !empty($service_form_three->total_addition_qualifying_days) ? $service_form_three->total_addition_qualifying_days : 0 }}
                        </td>
                    </tr>

                    <tr>
                        <td>9</td>
                        <th colspan="4">Net Qualifying Service ( 7 + 8 )</th>
                        <td colspan="1" id="net_qualifying_service_year">
                            {{ !empty($service_form_three->total_net_qualifying_years) ? $service_form_three->total_net_qualifying_years : 0 }}
                        </td>
                        <td colspan="1" id="net_qualifying_service_month">
                            {{ !empty($service_form_three->total_net_qualifying_months) ? $service_form_three->total_net_qualifying_months : 0 }}
                        </td>
                        <td colspan="1" id="net_qualifying_service_days">
                            {{ !empty($service_form_three->total_net_qualifying_days) ? $service_form_three->total_net_qualifying_days : 0 }}
                        </td>
                    </tr>

                    @php
                        $last_basic_pay = $proposal->basic_pay_amount_at_retirement ?? '0';

                        $net_qualifying_years = !empty($service_form->gross_years) ? $service_form->gross_years : 0;
                        $net_qualifying_months = !empty($service_form->gross_months) ? $service_form->gross_months : 0;
                        $net_qualifying_days = !empty($service_form->gross_days) ? $service_form->gross_days : 0;

                        $total_completed_years = 0;
                        $max_completed_years = 0;
                        $service_pension = 0;
                        
                        $age_on_next_birthday = $service_form_three->age_on_next_birthday;

                        $commutation_ratio = 0;
                        $commutation_percentage = 0;
                        $commutation_amount_of_pension = 0;
                        $commuted_value_of_pension = 0;
                        $residuary_pension = 0;

                        /*if($proposal->is_commutation_pension_applied == 1) {
                            $commtation_data = DB::table('optcl_commutation_master')->where('age_as_next_birthday', $age_on_next_birthday)->first();

                            $commutation_percentage = $proposal->commutation_percentage;
                            $commutation_ratio = $commtation_data->commutation_ratio;
                        }*/

                        // DCR Gratutity Calculation
                        $total_da_amount = 0;
                        $dcr_completed_years = 0;
                        $total_dcr_gratuity = 0;
                    @endphp

                    <tr>
                        <td>10</td>
                        <th colspan="4">Total qualifying service for pensionery benefits(Expressed in half years)</th>
                        <td colspan="3" id="total_qualifying_completed_half_years">
                            {{ !empty($service_form_three->total_qualifying_half_years) ? $service_form_three->total_qualifying_half_years : 0 }} Six-monthly period
                        </td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <th colspan="4">Emoluments [Last Basic Pay + Grade Pay]</th>
                        <td colspan="3" id="emolument_last_basic_pay">
                            {{ !empty($service_form_three->emolument_last_basic_pay) ? number_format($service_form_three->emolument_last_basic_pay) . '/-' : 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <th colspan="4">Pension</th>
                        <td colspan="3" id="service_pension">
                            {{ !empty($service_form_three->service_pension) ? number_format($service_form_three->service_pension) . '/-' : 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td>13</td>
                        <th colspan="4">Date of commencement of Pension</th>
                        <td colspan="3">
                            {{ (!empty($service_form_three->date_of_commencement_pension)) ? \Carbon\Carbon::parse($service_form_three->date_of_commencement_pension)->format('d-m-Y') : 'NA'  }}
                        </td>
                    </tr>
                    <tr>
                        <td>14</td>
                        <th colspan="4">Date of acknowledgement for commutation</th>
                        <td colspan="3">
                            {{ (!empty($service_form_three->date_of_acknowlegement_commutation)) ? \Carbon\Carbon::parse($service_form_three->date_of_acknowlegement_commutation)->format('d-m-Y') : 'NA'  }}
                        </td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <th colspan="4">Age as on next Birthday</th>
                        <td colspan="3" id="age_on_next_birthday_value">
                            {{ !empty($service_form_three->age_on_next_birthday) ? $service_form_three->age_on_next_birthday : 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td>16</td>
                        <th colspan="4">Commuted factor applicable</th>

                        <td colspan="3" id="commuted_factor_ratio">{{ $commutation_ratio }}</td>
                    </tr>
                    <tr>
                        <td>17</td>
                        <th colspan="4">Commutation Amount of Pension [{{ $commutation_percentage }}% of Pension]</th>

                        <td colspan="3" id="commuted_amount_pension">
                            {{ !empty($service_form_three->commuted_amount_of_pension) ? number_format($service_form_three->commuted_amount_of_pension) : 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td>18</td>
                        <th colspan="4">Commuted Value of Pension 
                            <span id="commutation_value_of_pension_cal">[ {{ $service_form_three->commuted_amount_of_pension }} * {{ $commutation_ratio }} * 12 ]</span>
                        </th>

                        <td colspan="3" id="commuted_value_of_pension">
                            {{ !empty($service_form_three->commuted_value_of_pension) ? number_format($service_form_three->commuted_value_of_pension) : 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td>19</td>
                        <th colspan="4">Residuary Pension after Commutaion </th>
                        <td colspan="3" id="residuary_pension_commutation">
                            {{ !empty($service_form_three->residuary_pension_commutation) ? number_format($service_form_three->residuary_pension_commutation) : 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td>20</td>
                        <th colspan="4">Amount of Death Cum-Retirement Gratuity (DCRG) </th>

                        <td colspan="3" id="death_retirement_dcr_gratuity">
                            <span id="total_dcr_gratuity_amount">
                                {{ !empty($service_form_three->amount_of_dcrg) ? number_format($service_form_three->amount_of_dcrg) . '/-' : 0 }}
                            </span> (max. Rs. 15 Lakhs)
                        </td>
                    </tr>

                    <tr >
                        <td>21</td>
                        <th colspan="4">Family Pension</th>
                        <td colspan="1">Amount</td>
                        <td colspan="2">Upto</td>
                    </tr>
                    <tr>
                        <td></td>
                        <th colspan="4">a) Enhanced Family Pension</th>
                        <td colspan="1" id="enhanced_family_pension">
                            {{ !empty($service_form_three->enhanced_family_pension) ? number_format($service_form_three->enhanced_family_pension) . '/-' : 0 }}
                        </td>
                        <td colspan="2">Up to 65 Year</td>
                    </tr>
                    <tr>
                        <td></td>
                        <!-- <td colspan="2"></td> -->
                        <th colspan="4">b) Normal Family Pension</th>
      
                        <td colspan="1" id="enhanced_normal_family_pension">
                            {{ !empty($service_form_three->normal_family_pension) ? number_format( $service_form_three->normal_family_pension) . '/-' : 0 }}
                        </td>
                        <td colspan="2">After 65 Year</td>
                    </tr>

                    <tr>
                        <td>22</td>
                        <th colspan="2">Life time arrear Pension (if any)</th>

                        <td colspan="1">{{ !empty($service_form_three->life_time_arrear_from) ? \Carbon\Carbon::parse($service_form_three->life_time_arrear_from)->format('d-m-Y') : 'NA' }}</td>
                        
                        <td colspan="1">{{ !empty($service_form_three->life_time_arrear_to) ? \Carbon\Carbon::parse($service_form_three->life_time_arrear_to)->format('d-m-Y') : 'NA' }}</td>

                        <td colspan="3">
                            {{ !empty($service_form_three->life_time_arrear_amount) ? number_format($service_form_three->life_time_arrear_amount) . '/-' : 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td>23</td>
                        <th colspan="7" class="text-center">Outstanding dues for recovery/withheld from DCRG and Pensionery benefit.</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2"><strong>Particulars</strong></td>
                        <td colspan="2"><strong>Amount</strong></td>
                        <td colspan="3"><strong>Remarks</strong> (Rate of interest to be charged & effective date if any may be specified)</td>
                    </tr>
                    @php
                        $add_recovery = DB::table('optcl_employee_pension_service_form_three_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $service_form_three->application_id)->get();
                    @endphp

                    @if($add_recovery->count() > 0)
                        @foreach($add_recovery as $key => $recovery)
                            <tr>
                                <td>{{ $key + 1 }})</td>
                                <td colspan="2">{{ $recovery->recovery_label }}</td>
                                <td colspan="2">{{ $recovery->recovery_value }}</td>
                                <td colspan="3">{{ $recovery->recovery_remarks }}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
@endif