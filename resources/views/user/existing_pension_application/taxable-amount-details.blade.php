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
                    <li class="breadcrumb-item"><a href="{{ route('existing_pension_list') }}">Existing Pensioner</a></li>
                    <li class="breadcrumb-item active" aria-current="page" >Taxable Amount</li>
                </ol>
            </nav> 
            <form method="post" action="" id="net_pension_application" autocomplete="off">
                @csrf
                <input type="hidden" name="application_id" value="{{$application_id}}">
                <input type="hidden" name="pensioner_type_id" value="{{ $response['pension_type_id'] }}">
    			<div class="card">
    				<div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <h4 class="card-title">Pensioner Details</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Application Type</th>
                                        <td>{{ $response['application_type_name'] }}</td>
                                        <th>Pensioner Type</th>
                                        <td>{{ $response['pension_type'] }}</td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Pensioner Name</th>
                                        <td>{{ $response['pensioner_name'] }}</td>
                                        <th>PPO No.</th>
                                        <td>{{ $response['ppo_number'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>TI Amount (%)</th>
                                        <td>{{ $response['ti_amount'] ? number_format($response['ti_amount'], 2) : 0 }} ({{ $response['ti_percentage'] }}%)</td>
                                        <th>Basic Amount</th>
                                        <td>{{ $response['basic_amount'] ? number_format($response['basic_amount'], 2) : 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gross Pension Amount</th>
                                        <td colspan="3">{{ $response['gross_pension_amount'] ? number_format($response['gross_pension_amount'], 2) : 0 }}</td>
                                    </tr>                             
                                </table>
                            </div> 
                            <div class="col-md-8">
                                <h4 class="card-title">Taxable Amount</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2">Total Income</th>
                                        <td colspan="2">
                                            <input type="hidden" name="total_income" id="total_income" value="{{ $response['total_income'] }}"> 
                                            <span id="gross_basic_show">{{ number_format($response['total_income'], 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Standard Deduction</th>
                                        <td colspan="2">
                                            <input type="hidden" name="standard_deduction" id="standard_deduction" value="{{ $standard_deduction_amount }}"> 
                                            <span id="gross_basic_show">{{ number_format($standard_deduction_amount, 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">80C (LIC/ PPFA/ HB Principal)<br>
                                            <span class="text-info small"><strong>Max Amount = </strong> 150K</span>
                                        </th>
                                        <td colspan="2">
                                            <input type="text" name="amount_80c" id="amount_80c" class="form-control only_number" maxlength="6"> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">80D (Health Insurance)<br>
                                            <span class="text-info small"><strong>Max Amount = </strong> 50K</span>
                                        </th>
                                        <td colspan="2">
                                            <input type="text" name="amount_80d" id="amount_80d" class="form-control only_number" maxlength="5"> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">
                                            80DD (Dependent Disability)
                                        </th>
                                        <td colspan="2">
                                            <select class="form-control" name="amount_8dd" id="amount_8dd">
                                                <option value="0">Not Applicable</option>
                                                <option value="75000">75K</option>
                                                <option value="125000">125K</option>
                                            </select> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">
                                            80E (Higher Education Interest)<br>
                                            <span class="text-info small"><strong>Max Amount = </strong> 10L</span>
                                        </th>
                                        <td colspan="2">
                                            <input type="text" name="amount_80e" id="amount_80e" class="form-control only_number" maxlength="7"> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">80U (Self Disability)</th>
                                        <td colspan="2">
                                            <select class="form-control" id="amount_80u" name="amount_80u">
                                                <option value="0">Not Applicable</option>
                                                <option value="75000">75K</option>
                                                <option value="125000">125K</option>
                                            </select>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">24B (House Building Interest)</th>
                                        <td colspan="2">
                                            <select class="form-control" id="amount_24b" name="amount_24b">
                                                <option value="0">Not Applicable</option>
                                                <option value="75000">75K</option>
                                                <option value="125000">125K</option>
                                            </select>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Others<br>
                                            <span class="text-info small"><strong>Max Amount = </strong> 10L</span>
                                        </th>
                                        <td colspan="2">
                                            <input type="text" name="others_amount" id="others_amount" class="form-control only_number" maxlength="7">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Taxable Amount</th>
                                        <td colspan="2">
                                            <input type="text" name="taxable_amount" id="taxable_amount" class="form-control only_number" value="{{ number_format($response['total_income'] - $standard_deduction_amount, 2) }}" readonly>
                                        </td>
                                    </tr>
                                </table>
                            </div>                            
                        </div>
                        <div class="row mt-2">
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
            //console.log($("#amount_80d").val());
            //console.log("isnan - "+isNaN($("#amount_80d").val()));
            var total = 0;
            var total_income = isNaN($("#total_income").val()) || $("#total_income").val() =="" ? 0 : $("#total_income").val();
            var standard_deduction = isNaN($("#standard_deduction").val()) || $("#standard_deduction").val() =="" ? 0 : $("#standard_deduction").val();
            var amount_80c = isNaN($("#amount_80c").val()) || $("#amount_80d").val() =="" ? 0 : $("#amount_80c").val();
            var amount_80d = isNaN($("#amount_80d").val()) || $("#amount_80d").val() =="" ? 0 : $("#amount_80d").val();
            var amount_8dd = isNaN($("#amount_8dd").val()) || $("#amount_8dd").val() =="" ? 0 : $("#amount_8dd").val();
            var amount_80e = isNaN($("#amount_80e").val()) || $("#amount_80e").val() =="" ? 0 : $("#amount_80e").val();
            var amount_80u = isNaN($("#amount_80u").val()) || $("#amount_80u").val() =="" ? 0 : $("#amount_80u").val();
            var amount_24b = isNaN($("#amount_24b").val()) || $("#amount_24b").val() =="" ? 0 : $("#amount_24b").val();
            var others_amount = isNaN($("#others_amount").val()) || $("#others_amount").val() =="" ? 0 : $("#others_amount").val();

            total = parseFloat(total_income) - parseFloat(standard_deduction) - parseFloat(amount_80c) - parseFloat(amount_80d) - parseFloat(amount_8dd) - parseFloat(amount_80e) - parseFloat(amount_80u) - parseFloat(amount_24b) - parseFloat(others_amount);
            //console.log(amount_80c+'---'+amount_80d);

            
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

        $.validator.addMethod("amount_only", function (value, element) {
            return this.optional(element) || /^\d{1,8}(?:\.\d{1,2})?$/.test(value);
        }, "Invalid amount format");

        $("#net_pension_application").validate({
            rules: {
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
            },
            messages: {
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
                            url:'{{ route("existing_pensioner_taxable_amount_submission") }}',
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
                                    location.href = "{{route('existing_pension_list')}}";
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