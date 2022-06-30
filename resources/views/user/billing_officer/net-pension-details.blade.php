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
    .recovery-btn-group {
        margin-top: 31px;
    }
</style>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin">
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('billing_officer_list') }}">Process Bill</a></li>
                    <li class="breadcrumb-item active" aria-current="page" >Net Pension Calculation</li>
                </ol>
            </nav> 
            <form method="post" action="" id="net_pension_application" autocomplete="off">
                @csrf
                <input type="hidden" name="application_type" value="{{ $response['application_type'] }}">
                <input type="hidden" name="pensioner_type" value="{{ $response['pension_type_id'] }}">
                <input type="hidden" name="application_id" value="{{ $response['application_id'] }}">
    			<div class="card">
    				<div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <h4 class="card-title">Pensioner Details</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="25%">Pensioner Name</th>
                                        <td>{{ $response['pensioner_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>PPO No.</th>
                                        <td>{{ $response['ppo_number'] }}</td>
                                    </tr>
                                        <th>Application Type</th>
                                        <td>{{ $response['pension_type'] }}</td>
                                    </tr>                             
                                    
                                </table>
                            </div> 
                            <div class="col-md-8">
                                <h4 class="card-title">Net Pension Calculation</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Basic Amount</th>
                                        <td>
                                        <input type="hidden" name="gross_basic" id="gross_basic" value="{{ $response['basic_amount'] }}"> 
                                        <span id="gross_basic_show">{{ number_format($response['basic_amount'], 2) }}</span>
                                        </td>
                                        <th>Additional Pension</th>
                                        <td>
                                        <input type="hidden" name="additional_pension" id="additional_pension" value="{{ $response['additional_pension_amount'] }}">   
                                        <span id="additional_pension_show">{{ number_format($response['additional_pension_amount'], 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>TI Amount</th>
                                        <td>
                                        <input type="hidden" name="ti_amount" id="ti_amount" value="{{ $response['ti_amount'] }}">
                                        <span id="ti_amount_show">{{ number_format($response['ti_amount'], 2) }}</span>
                                        </td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                        <th>Gross Pension</th>
                                        <td colspan="3">
                                        <input type="hidden" name="gross_pension" id="gross_pension" value="{{ $response['total'] }}">
                                        <span id="gross_pension_show">{{ number_format($response['total'], 2) }}</span>
                                        <span class="text-info small">(<strong>Gross Pension = </strong> Basic Pension + Additional Pension + TI Amount)</span>
                                        </td>
                                    </tr>                             
                                    <tr>
                                        <th colspan="4" class="text-center">Commutation</th>
                                    </tr>
                                    @php
                                        $commutationTotalAmount = 0;
                                    @endphp
                                    @foreach($commutations as $key => $commutation)
                                    @php
                                        $commutationTotalAmount += $commutation->commutation_amount;
                                    @endphp
                                    <tr>
                                        <th>
                                            <input type="hidden" name="commutation_id[]" value="{{ $commutation->id }}">
                                            <input type="hidden" name="commutation_date[]"  class="commutation_date" value="{{ $commutation->commutation_end_date }}">
                                            {{ date('d-m-Y', strtotime($commutation->commutation_end_date)) }}
                                        </th>
                                        <td colspan="3">
                                            <input type="hidden" name="commutation_amount[]"  class="commutation_amount" value="{{ $commutation->commutation_amount }}">
                                            {{ number_format($commutation->commutation_amount, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <th>Trust Recovery</th>
                                        <td>
                                            <input type="text" name="trust_recovery_amount" id="trust_recovery_amount" class="form-control amount_type" maxlength="8">
                                            <label id="trust_recovery_amount-error" class="error text-danger" for="trust_recovery_amount"></label>
                                        </td>
                                        <th>Other Recovery</th>
                                        <td>
                                            <input type="text" name="other_recovery_amount" id="other_recovery_amount" class="form-control amount_type" maxlength="9">
                                            <label id="other_recovery_amount-error" class="error text-danger" for="other_recovery_amount"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- <th>Tax Calculation</th> -->
                                        <td colspan="4">
                                            <table width="100%">
                                                @php 
                                                $base_taxable_amount = $response['taxable_amount'];
                                                $reminder_base_amount = $response['taxable_amount'];
                                                $pending_taxable_amount = 0;
                                                $calculated_amount = 0;
                                                $totalTax = 0;
                                                @endphp
                                                @foreach($taxList as $key => $taxData)
                                                    @php 
                                                        $tax_percentage = $taxData->rate_of_tax;
                                                        $toSalary = $taxData->to_salary;
                                                        $fromSalary = $taxData->from_salary;
                                                        if($reminder_base_amount > $fromSalary){
                                                            if(empty($fromSalary)){
                                                                $taxableAmount = $toSalary - $fromSalary;
                                                                $base_taxable_amount = $base_taxable_amount;
                                                            }else if(empty($toSalary)){
                                                                $taxableAmount = $base_taxable_amount;
                                                                $base_taxable_amount = $base_taxable_amount;
                                                                //$calculated_amount = $toSalary;
                                                            }else if($base_taxable_amount > $toSalary){
                                                                $taxableAmount = ($toSalary - $fromSalary)+1;
                                                                $base_taxable_amount = $base_taxable_amount - $taxableAmount;
                                                                //$calculated_amount = $toSalary;
                                                            }else{
                                                                $taxableAmount = ($toSalary - $fromSalary)+1;
                                                                $base_taxable_amount = $base_taxable_amount - $taxableAmount;
                                                                if($taxableAmount < $base_taxable_amount){
                                                                    $taxableAmount = $taxableAmount;
                                                                }else{                                                           
                                                                    $taxableAmount = $base_taxable_amount;
                                                                }
                                                            }
                                                        }else{
                                                            $taxableAmount = 0;
                                                            $base_taxable_amount = 0;
                                                        }
                                                        $percentageValue = ($taxableAmount/100)*$tax_percentage;
                                                        $totalTax = $totalTax + $percentageValue;
                                                    @endphp
                                                    <tr>
                                                        <th width="75%">
                                                            @if(!empty($taxData->from_salary) && !empty($taxData->to_salary))
                                                            {{ "Upto ".number_format($taxData->from_salary)."/- to ".number_format($taxData->to_salary)."/-@".$taxData->rate_of_tax."%" }}
                                                            @elseif(empty($taxData->from_salary) && !empty($taxData->to_salary))
                                                            {{ "Upto ".number_format($taxData->to_salary)."/-" }}
                                                            @else
                                                            {{ "Above ".number_format($taxData->from_salary)."/-@".$taxData->rate_of_tax."%" }}
                                                            @endif
                                                            <input type="hidden" name="hidden_tax_id[]" value="{{ $taxData->id }}">
                                                            <input type="hidden" name="hidden_tax_from[]" value="{{ $taxData->from_salary }}">
                                                            <input type="hidden" name="hidden_tax_to[]" value="{{ $taxData->to_salary }}">
                                                            <input type="hidden" name="hidden_tax_percentage[]" value="{{ $taxData->rate_of_tax }}">
                                                            <input type="hidden" name="hidden_tax_per_value[]" value="{{ $percentageValue }}">
                                                        </th>
                                                        <td>{{ $percentageValue > 0 ? number_format($percentageValue, 2) : 0 }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th>Total Tax</th>
                                                    <td>{{ $totalTax > 0 ? number_format($totalTax, 2) : 0 }}</td>
                                                </tr>                                                
                                                <tr>
                                                    <th>Less: Rebate U/S 87 A(Applicable if Net Income Below 5 Lakh. The maximum rebate that can be availed under section 87A is Rs. 12,500. It means that if the total tax payable is less than or equal to RS. 12,500, full tax </th>
                                                    <td>
                                                        @php
                                                            if($reminder_base_amount > 500000) {
                                                                $rebet_amount = 0;
                                                            }else{
                                                                $rebet_amount = $tax_calculation_details->rebet_amount;
                                                            }
                                                            echo $rebet_amount != 0 ? number_format($rebet_amount, 2) : 0;
                                                        @endphp
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Health & Education Cess @ 4%</th>
                                                    <td>
                                                        @php 
                                                            $h_e_cess_per = $tax_calculation_details->health_education_cess_per;
                                                            $h_e_cess_per_value = ($totalTax/100) * $h_e_cess_per;
                                                            echo $h_e_cess_per_value > 0 ? number_format($h_e_cess_per_value, 2) : 0;
                                                            $total_tax_payable = $totalTax + $h_e_cess_per_value;
                                                            $ded_val_month = $total_tax_payable / 12;
                                                        @endphp
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Total Tax Payable</th>
                                                    <td>{{ $total_tax_payable > 0 ? number_format($total_tax_payable, 2) : 0 }}</td>
                                                </tr>
                                                <tr>
                                                    <th>TDS Deducted per month</th>
                                                    <td>{{ $ded_val_month > 0 ? number_format($ded_val_month, 2) : 0 }}</td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>TDS</th>
                                        <td>{{ $ded_val_month > 0 ? number_format($ded_val_month, 2) : 0 }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <th>Net Pension</th>
                                        <td colspan="3">
                                        <input type="hidden" name="net_pension" id="net_pension">
                                        <span id="net_pension_show">{{ number_format($response['total'] - $ded_val_month - $commutationTotalAmount, 2) }}</span>
                                        </td>
                                    </tr>
                                </table>
                                <span class="text-info small"><strong>Net Pension = </strong> Gross Pension - Commutation - TDS - Trust Recovery - Other Recovery</span>
                            </div>                            
                        </div>
    					<input type="hidden" name="hidden_total_tax" id="hidden_total_tax" value="{{ $totalTax }}">
                        <input type="hidden" name="hidden_h_e_cess_percentage" id="hidden_h_e_cess_percentage" value="{{ $h_e_cess_per }}">
                        <input type="hidden" name="hidden_h_e_cess_value" id="hidden_h_e_cess_value" value="{{ $h_e_cess_per_value }}">
                        <input type="hidden" name="hidden_total_tax_payable" id="hidden_total_tax_payable" value="{{ $total_tax_payable }}">
                        <input type="hidden" name="hidden_rebet_amount" id="hidden_rebet_amount" value="{{ $rebet_amount }}">
                        <input type="hidden" name="tds_value" id="tds_value" value="{{ $ded_val_month }}">
                        <input type="hidden" name="monthly_changed_data_id" id="monthly_changed_data_id" value="{{ $mcd_details->id }}">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" id="proposalReturned" class="btn btn-raised btn-success">Submit</button>
                            </div>
                        </div>                        
    				</div>
    			</div>
            </form>
		</div>
	</div>
</div>

@endsection

@section('page-script')
<script type="text/javascript">
    $(document).ready(function(){
        // Calculate Net Pension
        function calculate_pension(){
            //debugger;
            var total = 0;
            var gross_basic = $("#gross_basic").val();
            var additional_pension = $("#additional_pension").val();
            var ti_amount = $("#ti_amount").val();
            var trust_recovery_amount = $("#trust_recovery_amount").val();
            if(trust_recovery_amount != ""){
            trust_recovery_amount = trust_recovery_amount;
            }else{
            trust_recovery_amount = 0;
            }
            var other_recovery_amount = $("#other_recovery_amount").val();
            if(other_recovery_amount != ""){
            other_recovery_amount = other_recovery_amount;
            }else{
            other_recovery_amount = 0;
            }
            // TDS
            var tds_value = $("#tds_value").val();
            if(tds_value != ""){
            tds_value = tds_value;
            }else{
            tds_value = 0;
            }
            // Commutation amount Calculation
            var communicationTotalValue = 0;
            $('.commutation_amount').each(function() { 
                communicationTotalValue += parseFloat($(this).val());
            });
            //console.log($communicationTotalValue);
            var hidden_total_tax = $("#hidden_total_tax").val();
            var hidden_h_e_cess_value = $("#hidden_h_e_cess_value").val();
            var hidden_total_tax_payable = $("#hidden_total_tax_payable").val();
            var hidden_ded_val_month = $("#hidden_ded_val_month").val();
            var hidden_rebet_amount = $("#hidden_rebet_amount").val();

            var gross_pension_amount = parseFloat(gross_basic) + parseFloat(additional_pension) + parseFloat(ti_amount);
            total = parseFloat(gross_pension_amount) - parseFloat(communicationTotalValue) - parseFloat(tds_value) - parseFloat(trust_recovery_amount) - parseFloat(other_recovery_amount) - parseFloat(hidden_rebet_amount);
            if(total < 1){
                total = 0;
            }
            //$("#gross_pension").val((gross_pension_amount).toFixed(2));
            //$("#gross_pension_show").html((gross_pension_amount).toFixed(2));
            $("#net_pension").val((total).toFixed(2));
            $("#net_pension_show").html((total).toFixed(2));
        }
        $('#trust_recovery_amount').on('keyup', function() {
            calculate_pension();
        });

        $('#other_recovery_amount').on('keyup', function() {
            calculate_pension();
        });

        $('#tds_value').on('keyup', function() {
            calculate_pension();
        });

        $.validator.addMethod("amount_only", function (value, element) {
            return this.optional(element) || /^\d{1,8}(?:\.\d{1,2})?$/.test(value);
        }, "Invalid amount format");

        $("#net_pension_application").validate({
            rules: {
                trust_recovery_amount: {
                    required: true,
                    amount_only: true,
                },
                other_recovery_amount: {
                    required: true,
                    amount_only: true,
                },
                tds_value: {
                    required: true,
                    amount_only: true,
                },
            },
            messages: {
                trust_recovery_amount: {
                    required: 'Please enter trust recovery amount',
                },
                other_recovery_amount: {
                    required: 'Please enter other recovery amount',
                },
                tds_value: {
                    required: 'Please enter TDS amount',
                },
            },
            submitHandler: function(form, event) { 
                    event.preventDefault();
                    swal({
                    title: "Are you sure?",
                    text: "Do you want to save the change?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                    if (willDelete) {
                        var formData = new FormData(form);
                        $.ajax({
                            type:'POST',
                            url:'{{ route("save_net_amount_details") }}',
                            data: formData,
                            dataType: 'JSON',
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('.page-loader').removeClass('d-flex');
                                if(response['error']){
                                    //$("#logid").prop('disabled',false);
                                    for (i in response['error']) {
                                        var element = $('#' + i);
                                        var id = response['error'][i]['id'];
                                        var eValue = response['error'][i]['eValue'];
                                        console.log(id);
                                        console.log(eValue);
                                        $("#"+id).show();
                                        $("#"+id).html(eValue);
                                    }
                                }else{
                                    // Success
                                    location.href = "{{route('monthly_changed_data_list')}}";
                                }
                            }
                        });
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
    });
</script>

@endsection