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
            <nav aria-label="breadcrumb" role="navigation" class="bg-white">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Update Pension Record</li>
                    <li class="breadcrumb-item"><a href="{{ route('pension_unit_tds_information_list_page') }}">TDS Information</a></li>
                    <li class="breadcrumb-item">View</li>
                </ol>
            </nav> 
            <form method="post" action="" id="net_pension_application" autocomplete="off">
                @csrf
                <input type="hidden" name="cr_tds_application_id" id="cr_tds_application_id" value="{{ $request_details->id }}">
                <input type="hidden" name="application_id" id="application_id" value="{{ $request_details->application_id }}">
                <input type="hidden" name="pensioner_type_id" id="pensioner_type_id" value="{{ $request_details->application_type }}">
                <input type="hidden" name="application_type_id" id="application_type_id" value="{{ $request_details->pensioner_type }}">
    			<div class="card">
    				<div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <h4 class="card-title">Pensioner Details</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>PPO No.</th>
                                        <td>
                                            <input type="test" class="form-control ppo_number_format" maxlength="12" name="tds_info_ppo_no" id="tds_info_ppo_no" value="{{ $request_details->ppo_no }}">
                                            <label id="tds_info_ppo_no-error" class="error text-danger" for="tds_info_ppo_no" ></label>
                                        </td>
                                    </tr>
                                </table>
                            </div> 
                            <div class="col-md-8">
                                <h4 class="card-title">Taxable Amount</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2">Total Income</th>
                                        <td colspan="2">
                                            <input type="text" class="form-control amount_type" readonly value="{{ $request_details->total_income > 0 ? number_format($request_details->total_income, 2) : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">80C (LIC/ PPFA/ HB Principal)<br>
                                            <span class="text-info small"><strong>Max Amount = </strong> 150K</span>
                                        </th>
                                        <td colspan="2">
                                            <input type="text" readonly class="form-control only_number" maxlength="6" value="{{ $request_details->amount_80c > 0 ? number_format($request_details->amount_80c, 2) : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">80D (Health Insurance)<br>
                                            <span class="text-info small"><strong>Max Amount = </strong> 50K</span>
                                        </th>
                                        <td colspan="2">
                                            <input type="text" readonly class="form-control only_number" maxlength="5" value="{{ $request_details->amount_80d > 0 ? number_format($request_details->amount_80d, 2) : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">
                                            80DD (Dependent Disability)
                                        </th>
                                        <td colspan="2">
                                            <input type="text" readonly class="form-control only_number" maxlength="5" value="{{ $request_details->amount_8dd > 0 ? number_format($request_details->amount_8dd, 2) : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">
                                            80E (Higher Education Interest)<br>
                                            <span class="text-info small"><strong>Max Amount = </strong> 10L</span>
                                        </th>
                                        <td colspan="2">
                                            <input type="text" readonly class="form-control only_number" maxlength="7" value="{{ $request_details->amount_80e > 0 ? number_format($request_details->amount_80e, 2) : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">80U (Self Disability)</th>
                                        <td colspan="2">
                                            <input type="text" readonly class="form-control only_number" maxlength="7" value="{{ $request_details->amount_80u > 0 ? number_format($request_details->amount_80u, 2) : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">24B (House Building Interest)</th>
                                        <td colspan="2">
                                            <input type="text" readonly class="form-control only_number" maxlength="7" value="{{ $request_details->amount_24b > 0 ? number_format($request_details->amount_24b, 2) : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Others<br>
                                            <span class="text-info small"><strong>Max Amount = </strong> 10L</span>
                                        </th>
                                        <td colspan="2">
                                            <input type="text" readonly class="form-control only_number" maxlength="7" value="{{ $request_details->others_amount > 0 ? number_format($request_details->others_amount, 2) : 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Taxable Amount</th>
                                        <td colspan="2">
                                            <input type="text" readonly class="form-control only_number" readonly value="{{ $request_details->taxable_amount > 0 ? number_format($request_details->taxable_amount, 2) : 0 }}" >
                                        </td>
                                    </tr>
                                </table>
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
            //console.log($("#amount_80d").val());
            //console.log("isnan - "+isNaN($("#amount_80d").val()));
            var total = 0;
            var total_income = isNaN($("#tds_info_total_income").val()) || $("#tds_info_total_income").val() =="" ? 0 : $("#tds_info_total_income").val();
            //var standard_deduction = isNaN($("#standard_deduction").val()) || $("#standard_deduction").val() =="" ? 0 : $("#standard_deduction").val();
            var amount_80c = isNaN($("#amount_80c").val()) || $("#amount_80d").val() =="" ? 0 : $("#amount_80c").val();
            var amount_80d = isNaN($("#amount_80d").val()) || $("#amount_80d").val() =="" ? 0 : $("#amount_80d").val();
            var amount_8dd = isNaN($("#amount_8dd").val()) || $("#amount_8dd").val() =="" ? 0 : $("#amount_8dd").val();
            var amount_80e = isNaN($("#amount_80e").val()) || $("#amount_80e").val() =="" ? 0 : $("#amount_80e").val();
            var amount_80u = isNaN($("#amount_80u").val()) || $("#amount_80u").val() =="" ? 0 : $("#amount_80u").val();
            var amount_24b = isNaN($("#amount_24b").val()) || $("#amount_24b").val() =="" ? 0 : $("#amount_24b").val();
            var others_amount = isNaN($("#others_amount").val()) || $("#others_amount").val() =="" ? 0 : $("#others_amount").val();

            total = parseFloat(total_income) - parseFloat(amount_80c) - parseFloat(amount_80d) - parseFloat(amount_8dd) - parseFloat(amount_80e) - parseFloat(amount_80u) - parseFloat(amount_24b) - parseFloat(others_amount);
            console.log(total_income+'-'+amount_80d+'-'+amount_8dd)+'-'+amount_80e+'-'+amount_80u+'-'+amount_24b+'-'+others_amount;

            $("#taxable_amount").val((total < 0 ? 0 : total).toFixed(2));
        }
        $('#amount_80c').on('keyup', function() {
            calculate_pension();
        });

        $('#amount_80d').on('keyup', function() {
            calculate_pension();
        });

        $('#amount_8dd').on('change', function() {
            calculate_pension();
        });

        $('#amount_80e').on('keyup', function() {
            calculate_pension();
        });

        $('#amount_80u').on('change', function() {
            calculate_pension();
        });

        $('#amount_24b').on('change', function() {
            calculate_pension();
        });

        $('#others_amount').on('keyup', function() {
            calculate_pension();
        });

        // Total Income Calculation
        $("#tds_info_ppo_no").on('change', function(){
            var tds_info_ppo_no = $(this).val();
            //alert(tds_info_ppo_no);
            $.post('{{ route("pension_unit_get_data_from_ppo_no") }}',
            {
                "_token": "{{ csrf_token() }}",
                "tds_info_ppo_no": tds_info_ppo_no,
            },function(response){
                if(response.status === 'success'){
                    var amount_value = response.data.total_income_amount;
                    $("#tds_info_total_income").val(amount_value);
                    $("#pensioner_type_id").val(response.data.pensioner_type_id);
                    $("#application_type_id").val(response.data.application_type_id);
                    $("#application_id").val(response.data.application_id);
                    $("#tds_info_ppo_no-error").hide();
                    $("#tds_info_ppo_no-error").html('');
                }else{
                    $("#tds_info_ppo_no").val('');
                    $("#pensioner_type_id").val('');
                    $("#application_type_id").val('');
                    $("#application_id").val('');
                    $("#tds_info_total_income").val('');
                    $("#tds_info_ppo_no-error").show();
                    $("#tds_info_ppo_no-error").html(response.message_value);
                }
                calculate_pension();
            });
        });

        $.validator.addMethod("amount_only", function (value, element) {
            return this.optional(element) || /^\d{1,8}(?:\.\d{1,2})?$/.test(value);
        }, "Invalid amount format");

        $.validator.addMethod("ppo_format", function (value, element) {
            return this.optional(element) || /[0-9]{4}\b[\/]{1}[0-9]{2}\b[\/]{1}[0-9]{4}\b/i.test(value); 
        }, "Please enter valid PPO no");

        $("#net_pension_application").validate({
            rules: {
                'tds_info_ppo_no': {
                    required: true,
                    ppo_format: true,
                },
                'tds_info_total_income': {
                    required: true,
                    amount_only: true,
                },
                'amount_80c': {
                    required: true,
                    amount_only: true,
                    max:150000,
                },
                'amount_80d': {
                    required: true,
                    amount_only: true,
                    max:50000,
                },
                'amount_8dd': {
                    required: true,
                },
                'amount_80e': {
                    required: true,
                    amount_only: true,
                    max:1000000,
                },
                'amount_80u': {
                    required: true,
                },
                'amount_24b': {
                    required: true,
                },
                'others_amount': {
                    required: true,
                    amount_only: true,
                    max:1000000,
                },
                'taxable_amount': {
                    required: true,
                },
                'declaration_status': {
                    required: true,
                }
            },
            messages: {
                'tds_info_ppo_no': {
                    required: 'Please enter PPO no',
                },
                'tds_info_total_income': {
                    required: 'PPO no not found',
                },
                'amount_80c': {
                    required: 'Please enter 80C (LIC/ PPFA/ HB Principal) amount',
                    max:'Amount must be less than 150000',
                },
                'amount_80d': {
                    required: 'Please enter 80D (Health Insurance) amount',
                    max: 'Amount must be less than 50000',
                },
                'amount_8dd': {
                    required: 'Please select 80DD (Dependent Disability) amount',
                },
                'amount_80e': {
                    required: 'Please enter 80E (Higher Education Interest) amount',
                    max:'Amount must be less than 100000',
                },
                'amount_80u': {
                    required: 'Please select 80U (Self Disability) amount',
                },
                'amount_24b': {
                    required: 'Please select 24B (House Building Interest) amount',
                },
                'others_amount': {
                    required: 'Please enter others amount',
                    max:'Amount must be less than 1000000',
                },
                'taxable_amount': {
                    required: 'Please fill all fileds',
                },
                'declaration_status': {
                    required: 'Please check the declaration',
                }
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
                            url:'{{ route("pension_unit_tds_information_edit_submission") }}',
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
                                    location.href = "{{route('pension_unit_tds_information_list_page')}}";
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