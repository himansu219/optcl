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
    			<div class="card">
    				<div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <h4 class="card-title">Pensioner Details</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="25%">Pensioner Name</th>
                                        <td>{{ $applicationDetails->pensioner_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>PPO No.</th>
                                        <td>{{ $applicationDetails->new_ppo_no }}</td>
                                    </tr>
                                        <th>Application Type</th>
                                        <td>{{ $applicationDetails->pension_type }}</td>
                                    </tr>                             
                                    
                                </table>
                            </div> 
                            <div class="col-md-8">
                                <h4 class="card-title">Net Pension Calculation</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Basic Amount</th>
                                        <td>{{ $net_pension_details->basic_amount > 0 ? number_format($net_pension_details->basic_amount, 2) : 0 }}</td>
                                        <th>Additional Pension</th>
                                        <td>{{ $net_pension_details->additional_amount > 0 ? number_format($net_pension_details->additional_amount, 2) : 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>TI Amount</th>
                                        <td>{{ $net_pension_details->ti_amount > 0 ? number_format($net_pension_details->ti_amount, 2) : 0 }}</td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                        <th>Gross Pension</th>
                                        <td colspan="3">
                                        <span id="gross_pension_show">{{ $net_pension_details->gross_pension_amount > 0 ? number_format($net_pension_details->gross_pension_amount, 2) : 0 }}</span>
                                        <span class="text-info small">(<strong>Gross Pension = </strong> Besic Pension + Additional Pension + TI Amount)</span>
                                        </td>
                                    </tr>                             
                                    <tr>
                                        <th colspan="4" class="text-center">Commutation</th>
                                    </tr>
                                    @foreach($net_pension_commutation_list as $key => $net_pension_commutation_data)
                                    <tr>
                                        <th>{{ date('d-m-Y', strtotime($net_pension_commutation_data->comm_date)) }}</th>
                                        <td colspan="3">{{ number_format($net_pension_commutation_data->comm_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <th>Trust Recovery</th>
                                        <td>{{ $net_pension_details->trust_recovery_amount }}</td>
                                        <th>Other Recovery</th>
                                        <td>{{ $net_pension_details->other_recovery_amount > 0 ? number_format($net_pension_details->other_recovery_amount, 2) : 0 }}</td>
                                    </tr>
                                    <tr>
                                        <!-- <th>Tax Calculation</th> -->
                                        <td colspan="4">
                                            <table width="100%">
                                                @foreach($net_pension_tax_list as $key => $taxData)                                                    
                                                    <tr>
                                                        <th width="75%">
                                                            @if(!empty($taxData->tax_slab_from) && !empty($taxData->tax_slab_to))
                                                            {{ "Upto ".number_format($taxData->tax_slab_from)."/- to ".number_format($taxData->tax_slab_to)."/-@".$taxData->tax_slab_per."%" }}
                                                            @elseif(empty($taxData->tax_slab_from) && !empty($taxData->tax_slab_to))
                                                            {{ "Upto ".number_format($taxData->tax_slab_to)."/-" }}
                                                            @else
                                                            {{ "Above ".number_format($taxData->tax_slab_from)."/-@".$taxData->tax_slab_per."%" }}
                                                            @endif
                                                        </th>
                                                        <td>{{ $taxData->tax_slab_amount > 0 ? number_format($taxData->tax_slab_amount, 2) : 0 }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th>Total Tax</th>
                                                    <td>{{ number_format($net_pension_details->tot_tax_amount, 2) }}</td>
                                                </tr>                                                
                                                <tr>
                                                    <th>Less: Rebate U/S 87 A(Applicable if Net Income Below 5 Lakh. The maximum rebate that can be availed under section 87A is Rs. 12,500. It means that if the total tax payable is less than or equal to RS. 12,500, full tax </th>
                                                    <td>{{ $net_pension_details->rebate_amount }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Health & Education Cess @ {{ $net_pension_details->health_education_percentage }}%</th>
                                                    <td>{{ $net_pension_details->health_education_amount }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Tax Payable</th>
                                                    <td>{{ $net_pension_details->tot_tax_payable_anually }}</td>
                                                </tr>
                                                <tr>
                                                    <th>TDS Deducted per month</th>
                                                    <td>{{ $net_pension_details->tds_amount }}</td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>TDS</th>
                                        <td>{{ $net_pension_details->tds_amount }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <th>Net Pension</th>
                                        <td colspan="3">
                                        <input type="hidden" name="net_pension" id="net_pension">
                                        <span id="net_pension_show">{{ $net_pension_details->net_pension_amount }}</span>
                                        </td>
                                    </tr>
                                </table>
                                <span class="text-info small"><strong>Net Pension = </strong> Gross Pension - Commutation - TDS - Trust Recovery - Other Recovery</span>
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
                                    location.href = "{{route('billing_officer_list')}}";
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