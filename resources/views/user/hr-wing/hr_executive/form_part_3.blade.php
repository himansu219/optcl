@if($application->application_status_id == 33)
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

                    <input type="hidden" class="form-control date_of_joining_value"  name="date_of_joining_value" id="date_of_joining_value" value="{{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('m/d/Y') : ''  }}">
                    
                    <td colspan="3">{{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('d-m-Y') : 'NA'  }}</td>
                </tr>

                <tr>
                    <td>3</td>
                    <th colspan="4">Date of Retirement / Cessation of service.</th>

                    <input type="hidden" class="form-control date_of_retirement_value"  name="date_of_retirement_value" id="date_of_retirement_value" value="{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('m/d/Y') : ''  }}">

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

                    <input type="hidden" name="gross_qualifying_year_value" id="gross_qualifying_year_value" value="{{ !empty($service_form) ? $service_form->gross_years : 0 }}">
                    <input type="hidden" name="gross_qualifying_month_value" id="gross_qualifying_month_value" value="{{ !empty($service_form) ? $service_form->gross_months : 0 }}">
                    <input type="hidden" name="gross_qualifying_days_value" id="gross_qualifying_days_value" value="{{ !empty($service_form) ? $service_form->gross_days : 0 }}">

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
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass  interruption_service_from" name="interruption_service_from" id="interruption_service_from" data-fieldname="interruption_service"  readonly>
                        </div>
                        <label id="interruption_service_from-error" class="error text-danger" for="interruption_service_from"></label>
                    </td>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass interruption_service_to" name="interruption_service_to" id="interruption_service_to" data-fieldname="interruption_service"  readonly>
                        </div>
                        <label id="interruption_service_to-error" class="error text-danger" for="interruption_service_to"></label>
                    </td>
                    <input type="hidden" name="interruption_service_year_value" id="interruption_service_year_value" value="0">
                    <input type="hidden" name="interruption_service_month_value" id="interruption_service_month_value" value="0">
                    <input type="hidden" name="interruption_service_days_value" id="interruption_service_days_value" value="0">

                    <td colspan="1" class="non-qualifying-service-periods-year" id="interruption_service_year">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-month" id="interruption_service_month">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-days" id="interruption_service_days">0</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="1">ii</td>
                    <th colspan="1">Extraordinary leave not qualifying for pension</th>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass  extraordinary_leave_from" name="extraordinary_leave_from" id="extraordinary_leave_from" data-fieldname="extraordinary_leave"  readonly>
                        </div>
                        <label id="extraordinary_leave_from-error" class="error text-danger" for="extraordinary_leave_from"></label>
                    </td>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass extraordinary_leave_to" name="extraordinary_leave_to" id="extraordinary_leave_to" data-fieldname="extraordinary_leave"  readonly>
                        </div>
                        <label id="extraordinary_leave_to-error" class="error text-danger" for="extraordinary_leave_to"></label>
                    </td>

                    <input type="hidden" name="extraordinary_leave_year_value" id="extraordinary_leave_year_value" value="0">
                    <input type="hidden" name="extraordinary_leave_month_value" id="extraordinary_leave_month_value" value="0">
                    <input type="hidden" name="extraordinary_leave_days_value" id="extraordinary_leave_days_value" value="0">

                    <td colspan="1" class="non-qualifying-service-periods-year" id="extraordinary_leave_year">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-month" id="extraordinary_leave_month">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-days" id="extraordinary_leave_days">0</td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="1">iii</td>
                    <th colspan="1">Period of suspension not trated as qualifying service</th>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass period_of_suspension_from" name="period_of_suspension_from" id="period_of_suspension_from" data-fieldname="period_of_suspension"  readonly>
                        </div>
                        <label id="period_of_suspension_from-error" class="error text-danger" for="period_of_suspension_from"></label>
                    </td>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass period_of_suspension_to" name="period_of_suspension_to" id="period_of_suspension_to" data-fieldname="period_of_suspension"  readonly>
                        </div>
                        <label id="period_of_suspension_to-error" class="error text-danger" for="period_of_suspension_to"></label>
                    </td>
                    <input type="hidden" name="period_of_suspension_year_value" id="period_of_suspension_year_value" value="0">
                    <input type="hidden" name="period_of_suspension_month_value" id="period_of_suspension_month_value" value="0">
                    <input type="hidden" name="period_of_suspension_days_value" id="period_of_suspension_days_value" value="0">

                    <td colspan="1" class="non-qualifying-service-periods-year" id="period_of_suspension_year">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-month" id="period_of_suspension_month">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-days" id="period_of_suspension_days">0</td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="1">iv</td>
                    <th colspan="1">Work charged service period not treated as qualifying service</th>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass work_charged_service_from" name="work_charged_service_from" id="work_charged_service_from" data-fieldname="work_charged_service"  readonly>
                        </div>
                        <label id="work_charged_service_from-error" class="error text-danger" for="work_charged_service_from"></label>
                    </td>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass work_charged_service_to" name="work_charged_service_to" id="work_charged_service_to" data-fieldname="work_charged_service"  readonly>
                        </div>
                        <label id="work_charged_service_to-error" class="error text-danger" for="work_charged_service_to"></label>
                    </td>
                    <input type="hidden" name="work_charged_service_year_value" id="work_charged_service_year_value" value="0">
                    <input type="hidden" name="work_charged_service_month_value" id="work_charged_service_month_value" value="0">
                    <input type="hidden" name="work_charged_service_days_value" id="work_charged_service_days_value" value="0">
                    
                    <td colspan="1" class="non-qualifying-service-periods-year" id="work_charged_service_year">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-month" id="work_charged_service_month">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-days" id="work_charged_service_days">0</td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="1">v</td>
                    <th colspan="1">Boy service period if not treated as qualifying service</th>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass boy_service_from" name="boy_service_from" id="boy_service_from" data-fieldname="boy_service"  readonly>
                        </div>
                        <label id="boy_service_from-error" class="error text-danger" for="boy_service_from"></label>
                    </td>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass boy_service_to" name="boy_service_to" id="boy_service_to" data-fieldname="boy_service"  readonly>
                        </div>
                        <label id="boy_service_to-error" class="error text-danger" for="boy_service_to"></label>
                    </td>
                    <input type="hidden" name="boy_service_year_value" id="boy_service_year_value" value="0">
                    <input type="hidden" name="boy_service_month_value" id="boy_service_month_value" value="0">
                    <input type="hidden" name="boy_service_days_value" id="boy_service_days_value" value="0">

                    <td colspan="1" class="non-qualifying-service-periods-year" id="boy_service_year">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-month" id="boy_service_month">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-days" id="boy_service_days">0</td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="1">vi</td>
                    <th colspan="1">Any other service not treated as qualifying service</th>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass any_other_service_from" name="any_other_service_from" id="any_other_service_from" data-fieldname="any_other_service"  readonly>
                        </div>
                        <label id="any_other_service_from-error" class="error text-danger" for="any_other_service_from"></label>
                    </td>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass any_other_service_to" name="any_other_service_to" id="any_other_service_to" data-fieldname="any_other_service"  readonly>
                        </div>
                        <label id="any_other_service_to-error" class="error text-danger" for="any_other_service_to"></label>
                    </td>

                    <input type="hidden" name="any_other_service_year_value" id="any_other_service_year_value" value="0">
                    <input type="hidden" name="any_other_service_month_value" id="any_other_service_month_value" value="0">
                    <input type="hidden" name="any_other_service_days_value" id="any_other_service_days_value" value="0">

                    <td colspan="1" class="non-qualifying-service-periods-year" id="any_other_service_year">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-month" id="any_other_service_month">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-days" id="any_other_service_days">0</td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="1">vii</td>
                    <th colspan="3">Total (i) to (vii)</th>

                    <input type="hidden" name="total_non_qualifying_years" id="total_non_qualifying_years" value="0">
                    <input type="hidden" name="total_non_qualifying_months" id="total_non_qualifying_months" value="0">
                    <input type="hidden" name="total_non_qualifying_days" id="total_non_qualifying_days" value="0">

                    <td colspan="1" class="non-qualifying-service-periods-total-year">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-total-month">0</td>
                    <td colspan="1" class="non-qualifying-service-periods-total-days">0</td>
                </tr>

                <tr>
                    <td>7</td>
                    <th colspan="4">Qualifying Service ( 5 - 6 )</th>
                    
                    <input type="hidden" name="qualifying_service_period_year_value" id="qualifying_service_period_year_value" value="0">
                    <input type="hidden" name="qualifying_service_period_month_value" id="qualifying_service_period_month_value" value="0">
                    <input type="hidden" name="qualifying_service_period_days_value" id="qualifying_service_period_days_value" value="0">

                    <td colspan="1" id="qualifying_service_period_year" >0</td>
                    <td colspan="1" id="qualifying_service_period_month" >0</td>
                    <td colspan="1" id="qualifying_service_period_days" >0</td>
                </tr>

                <tr>
                    <td>8</td>
                    <!-- <td colspan="1">Add</td> -->
                    <th colspan="2">Addition to qualifying service if any.</th>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass addition_of_qualifying_service_from" name="addition_of_qualifying_service_from" id="addition_of_qualifying_service_from" data-fieldname="addition_of_qualifying_service"  readonly>
                        </div>
                        <label id="addition_of_qualifying_service_from-error" class="error text-danger" for="addition_of_qualifying_service_from"></label>
                    </td>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass addition_of_qualifying_service_to" name="addition_of_qualifying_service_to" id="addition_of_qualifying_service_to" data-fieldname="addition_of_qualifying_service"  readonly>
                        </div>
                        <label id="addition_of_qualifying_service_to-error" class="error text-danger" for="addition_of_qualifying_service_to"></label>
                    </td>

                    <input type="hidden" name="addition_of_qualifying_service_year_value" id="addition_of_qualifying_service_year_value" value="0">
                    <input type="hidden" name="addition_of_qualifying_service_month_value" id="addition_of_qualifying_service_month_value" value="0">
                    <input type="hidden" name="addition_of_qualifying_service_days_value" id="addition_of_qualifying_service_days_value" value="0">

                    <td colspan="1" id="addition_of_qualifying_service_year">0</td>
                    <td colspan="1" id="addition_of_qualifying_service_month" >0</td>
                    <td colspan="1" id="addition_of_qualifying_service_days" >0</td>
                </tr>

                <tr>
                    <td>9</td>
                    <th colspan="4">Net Qualifying Service ( 7 + 8 )</th>
                    <!-- <td colspan="4"></td> -->

                    <input type="hidden" name="net_qualifying_service_year_value" id="net_qualifying_service_year_value" value="{{ !empty($service_form) ? $service_form->gross_years : 0 }}">
                    <input type="hidden" name="net_qualifying_service_month_value" id="net_qualifying_service_month_value" value="{{ !empty($service_form) ? $service_form->gross_months : 0 }}">
                    <input type="hidden" name="net_qualifying_service_days_value" id="net_qualifying_service_days_value" value="{{ !empty($service_form) ? $service_form->gross_days : 0 }}">

                    <td colspan="1" id="net_qualifying_service_year">{{ !empty($service_form) ? $service_form->gross_years : 0 }}</td>
                    <td colspan="1" id="net_qualifying_service_month">{{ !empty($service_form) ? $service_form->gross_months : 0 }}</td>
                    <td colspan="1" id="net_qualifying_service_days">{{ !empty($service_form) ? $service_form->gross_days : 0 }}</td>
                </tr>

                @php
                    $last_basic_pay = $proposal->basic_pay_amount_at_retirement;

                    $net_qualifying_years = !empty($service_form->gross_years) ? $service_form->gross_years : 0;
                    $net_qualifying_months = !empty($service_form->gross_months) ? $service_form->gross_months : 0;
                    $net_qualifying_days = !empty($service_form->gross_days) ? $service_form->gross_days : 0;

                    $total_completed_years = 0;
                    $max_completed_years = 0;
                    $service_pension = 0;

                    $calculate_service_pension = App\Libraries\PensinorCalculation::calculate_completed_half_years_with_service_pension($net_qualifying_years, $net_qualifying_months, $net_qualifying_days, $last_basic_pay);

                    $total_completed_years = !empty($calculate_service_pension['total_completed_years']) ? $calculate_service_pension['total_completed_years'] : 0;
                    $service_pension = !empty($calculate_service_pension['service_pension']) ? $calculate_service_pension['service_pension'] : 0;
                    $max_completed_years = !empty($calculate_service_pension['max_completed_years']) ? $calculate_service_pension['max_completed_years'] : 0;
                    

                    $pensioner_dob = $proposal->date_of_birth;
                    $age_years = App\Libraries\Util::get_years_months_days($pensioner_dob, date('Y-m-d'));
                    $age_on_next_birthday = $age_years['years'] + 1;

                    $commutation_ratio = 0;
                    $commutation_percentage = 0;
                    $commutation_amount_of_pension = 0;
                    $commuted_value_of_pension = 0;
                    $residuary_pension = 0;
                    

                    if($proposal->is_commutation_pension_applied == 1) {
                        $commtation_data = DB::table('optcl_commutation_master')->where('age_as_next_birthday', $age_on_next_birthday)->first();

                        $commutation_percentage = $proposal->commutation_percentage;

                        $commutation_amount_of_pension = ($service_pension * $commutation_percentage) / 100;

                        $commutation_ratio = $commtation_data->commutation_ratio;

                        $commuted_value_of_pension = ceil(($commutation_amount_of_pension * $commutation_ratio) * 12);

                        $residuary_pension = $service_pension - $commutation_amount_of_pension;
                    }


                    // DCR Gratutity Calculation
                    $total_da_amount = 0;
                    $dcr_completed_years = 0;
                    $total_dcr_gratuity = 0;

                    $calculate_dcr_gratuity = App\Libraries\PensinorCalculation::calculate_completed_half_years_with_dcr_gratuity($net_qualifying_years, $net_qualifying_months, $net_qualifying_days, $last_basic_pay, $proposal);

                    $dcr_completed_years = !empty($calculate_dcr_gratuity['dcr_completed_years']) ? $calculate_dcr_gratuity['dcr_completed_years'] : 0;
                    $total_dcr_gratuity = !empty($calculate_dcr_gratuity['total_dcr_gratuity']) ? $calculate_dcr_gratuity['total_dcr_gratuity'] : 0;
                    $total_da_amount = !empty($calculate_dcr_gratuity['total_da_amount']) ? $calculate_dcr_gratuity['total_da_amount'] : 0;

                    $get_da_percentage = DB::table('optcl_da_master')->select('id', 'percentage_of_basic_pay')->where('status', 1)->where('deleted', 0)->whereRaw("? BETWEEN start_date AND end_date", array($proposal->date_of_retirement))->first();

                @endphp

                <tr>
                    <td>10</td>
                    <th colspan="4">Total qualifying service for pensionery benefits(Expressed in half years)</th>
                    <input type="hidden" name="total_qualifying_completed_half_years_value" id="total_qualifying_completed_half_years_value" value="{{ $total_completed_years }}">
                    <input type="hidden" name="total_qualifying_max_completed_half_years_value" id="total_qualifying_max_completed_half_years_value" value="{{ $max_completed_years }}">
                    <td colspan="3" id="total_qualifying_completed_half_years">{{ $total_completed_years }} Six-monthly period</td>
                </tr>
                <tr>
                    <td>11</td>
                    <th colspan="4">Emoluments [Last Basic Pay + Grade Pay]</th>
                    <td colspan="3" id="emolument_last_basic_pay">
                        <input type="text" class="form-control" name="emolument_last_basic_pay_value" id="emolument_last_basic_pay_value" placeholder="Enter last basic pay" value="{{ $last_basic_pay }}" required maxlength="7">
                        <label id="emolument_last_basic_pay_value-error" class="error text-danger" for="emolument_last_basic_pay_value"></label>
                    </td>
                </tr>
                <tr>
                    <td>12</td>
                    <th colspan="4">Pension</th>
                    <input type="hidden" name="service_pension_value" id="service_pension_value" value="{{ $service_pension }}">
                    <td colspan="3" id="service_pension">{{ !empty($service_pension) ? number_format($service_pension) . '/-' : 0 }}</td>
                </tr>
                <tr>
                    <td>13</td>
                    <th colspan="4">Date of commencement of Pension</th>

                    <input type="hidden" name="date_of_commencement_pension" id="date_of_commencement_pension" value="{{ \Carbon\Carbon::parse($proposal->date_of_retirement)->addDay()->format('Y-m-d') }}">

                    <td colspan="3">{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->addDay()->format('d-m-Y') : 'NA'  }}</td>
                </tr>
                <tr>
                    <td>14</td>
                    <th colspan="4">Date of acknowledgement for commutation</th>
                    <input type="hidden" name="date_of_acknowlegement_commutation" id="date_of_acknowlegement_commutation" value="{{ \Carbon\Carbon::parse($application->created_at)->format('Y-m-d') }}">
                    <td colspan="3">{{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td>15</td>
                    <th colspan="4">Age as on next Birthday</th>
                    <input type="hidden" name="age_on_next_birthday_value" id="age_on_next_birthday_value" value="{{ $age_on_next_birthday }}">
                    <td colspan="3" id="age_on_next_birthday_value">{{ $age_on_next_birthday }}</td>
                </tr>
                <tr>
                    <td>16</td>
                    <th colspan="4">Commuted factor applicable</th>

                    <input type="hidden" name="commuted_factor_ratio_value" id="commuted_factor_ratio_value" value="{{ !empty($commtation_data->commutation_ratio) ? $commtation_data->commutation_ratio : 0 }}">
                    <td colspan="3" id="commuted_factor_ratio">{{ $commutation_ratio }}</td>
                </tr>
                <tr>
                    <td>17</td>
                    <th colspan="4">Commutation Amount of Pension [{{ $commutation_percentage }}% of Pension]</th>

                    <input type="hidden" name="commuted_percentage_value" id="commuted_percentage_value" value="{{ $commutation_percentage }}">

                    <input type="hidden" name="commuted_amount_pension_value" id="commuted_amount_pension_value" value="{{ $commutation_amount_of_pension }}">
                    <td colspan="3" id="commuted_amount_pension">{{ !empty($commutation_amount_of_pension) ? number_format($commutation_amount_of_pension) . '/-' : 0 }}</td>
                </tr>
                <tr>
                    <td>18</td>
                    <th colspan="4">Commuted Value of Pension 
                        <span id="commutation_value_of_pension_cal">[ {{ $commutation_amount_of_pension }} * {{ $commutation_ratio }} * 12 ]</span>
                    </th>
                    <input type="hidden" name="commuted_value_of_pension_value" id="commuted_value_of_pension_value" value="{{ $commuted_value_of_pension }}">
                    <td colspan="3" id="commuted_value_of_pension">{{ !empty($commuted_value_of_pension) ?  number_format($commuted_value_of_pension) . '/-' : 0 }}</td>
                </tr>
                <tr>
                    <td>19</td>
                    <th colspan="4">Residuary Pension after Commutaion </th>
                    <input type="hidden" name="residuary_pension_commutation_value" id="residuary_pension_commutation_value" value="{{ $residuary_pension }}">
                    <td colspan="3" id="residuary_pension_commutation">{{ !empty($residuary_pension) ? number_format($residuary_pension) . '/-' : 0 }}</td>
                </tr>
                <tr>
                    <td>20</td>
                    <th colspan="4">Amount of Death Cum-Retirement Gratuity (DCRG) </th>
                    <input type="hidden" name="da_percentage_value" id="da_percentage_value" value="{{ !empty($get_da_percentage->percentage_of_basic_pay) ? $get_da_percentage->percentage_of_basic_pay : '0' }}">
                    <input type="hidden" name="total_da_amount_value" id="total_da_amount_value" value="{{ $total_da_amount }}">
                    <input type="hidden" name="death_retirement_dcr_gratuity_value" id="death_retirement_dcr_gratuity_value" value="{{ $total_dcr_gratuity }}">
                    <td colspan="3" id="death_retirement_dcr_gratuity">
                        <span id="total_dcr_gratuity_amount">{{ number_format($total_dcr_gratuity) }}/- </span> (max. Rs. 15 Lakhs)
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
                    <!-- <td colspan="2"></td> -->
                    <th colspan="4">a) Enhanced Family Pension</th>

                    <input type="hidden" name="enhanced_family_pension" id="enhanced_family_pension" value="{{ $service_pension }}">
                    <td colspan="1" id="enhanced_family_pension">{{ number_format($service_pension) }}/-</td>
                    <td colspan="2">Up to 65 Year</td>
                </tr>
                <tr>
                    <td></td>
                    <!-- <td colspan="2"></td> -->
                    <th colspan="4">b) Normal Family Pension</th>
                    <input type="hidden" name="normal_family_pension" id="normal_family_pension" value="{{ $residuary_pension }}">
                    <td colspan="1" id="enhanced_normal_family_pension">{{ number_format($residuary_pension) }}/-</td>
                    <td colspan="2">After 65 Year</td>
                </tr>

                <tr>
                    <td>22</td>
                    <th colspan="2">Life time arrear Pension (if any)</th>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass life_time_arrear_from" name="life_time_arrear_from" id="life_time_arrear_from" data-fieldname="life_time_arrear"  readonly>
                        </div>
                        <label id="life_time_arrear_from-error" class="error text-danger" for="life_time_arrear_from"></label>
                    </td>
                    <td colspan="1">
                        <div id="nominee-datepicker" class="input-group date">
                            <input type="text" class="form-control datepickerClass life_time_arrear_to" name="life_time_arrear_to" id="life_time_arrear_to" data-fieldname="life_time_arrear"  readonly>
                        </div>
                        <label id="life_time_arrear_to-error" class="error text-danger" for="life_time_arrear_to"></label>
                    </td>
                    <td colspan="3">
                        <input type="text" class="form-control" name="life_time_arrear_pension_amount" id="life_time_arrear_pension_amount" value="" placeholder="Enter life time arrear pension amount" style="margin-bottom: 23px;" maxlength="7">
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
                @if($add_recovery->count() > 0)
                    @foreach($add_recovery as $key => $recovery)
                        <tr>
                            <td>{{ $key + 1 }})</td>
                            <td colspan="2">
                                 <input type="hidden" name="add_recovery[{{$key}}][recovery_id]" value="{{ $recovery->id }}" >
                                <input type="text" class="form-control add_recovery_label" name="add_recovery[{{$key}}][label]"  placeholder="Enter Recovery Label" value="{{ $recovery->recovery_label }}" required>
                                <label id="label_{{$key}}-error" class="error error-msg" for="label_{{$key}}"></label>
                            </td>
                            <td colspan="2">
                                <input type="text" class="form-control add_recovery_value" name="add_recovery[{{$key}}][value]" placeholder="Enter Recovery Value" maxlength="10" value="{{ $recovery->recovery_value }}" required>
                                <label id="value_{{$key}}-error" class="error error-msg" for="value_{{$key}}"></label>
                            </td>
                            <td colspan="3">
                                <input type="text" class="form-control add_recovery_remarks" name="add_recovery[{{$key}}][remarks]" placeholder="Enter Recovery Remarks">
                                <label id="remarks_{{$key}}-error" class="error error-msg" for="remarks_{{$key}}"></label>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" id="form_three_submission" class="btn btn-primary mr-2">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
@else
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

                    <input type="hidden" class="form-control date_of_joining_value"  name="date_of_joining_value" id="date_of_joining_value" value="{{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('m/d/Y') : ''  }}">
                    
                    <td colspan="3">{{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('d-m-Y') : 'NA'  }}</td>
                </tr>

                <tr>
                    <td>3</td>
                    <th colspan="4">Date of Retirement / Cessation of service.</th>

                    <input type="hidden" class="form-control date_of_retirement_value"  name="date_of_retirement_value" id="date_of_retirement_value" value="{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('m/d/Y') : ''  }}">

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
                    $last_basic_pay = $proposal->basic_pay_amount_at_retirement;

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
                    

                    if($proposal->is_commutation_pension_applied == 1) {
                        $commtation_data = DB::table('optcl_commutation_master')->where('age_as_next_birthday', $age_on_next_birthday)->first();

                        $commutation_percentage = $proposal->commutation_percentage;
                        $commutation_ratio = $commtation_data->commutation_ratio;
                    }

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
@endif