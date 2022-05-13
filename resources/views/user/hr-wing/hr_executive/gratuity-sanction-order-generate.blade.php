@extends('user.layout.layout')
@section('section_content')
<style type="text/css">
    .sanction-order-to{
        margin-top: -31px;
        margin-left: 33px;
    }
    .left-pad {
        padding-left: 15px;
    }
    #sanction-order-submit {
        font-size: 15px;
    }
    .tbl-mrg {
        margin-left: 15px;
    }
</style>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <!-- <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{-- route('user_dashboard') --}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{-- route('dealing_applications') --}}">View Applications</a></li>
                    <li class="breadcrumb-item active" aria-current="page" >Application Details</li>
                </ol>
            </nav> -->

                <div class="card">
                    <div class="card-body">
                        <form id="sanction-order-submit" method="post" autocomplete="off" action="{{ route('hr_executive_gratuity_sanction_order_submit') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="application_id" value="{{ $application->id }}">

                            <img src="{{url('public')}}/images/logo_1.png" alt="image" class="brand_logo_1"/>
                            <h4 class="card-title align_center mb-2">ODISHA POWER TRANSMISSION CORPORATION LTD.</h4>
                            <h5 class="card-description align_center mb-1">(A Govt. of Odisha Undertaking)</h5>
                            <h5 class="card-description align_center mb-1">Gridco Pension Trust Fund</h5>
                            <p class="card-description text-center mb-1">Regd. Off – Janpath, Bhubaneswar – 751022</p>
                            <p class="card-description text-center mb-1">Telephone: (6074) 2540051 (EPABX)</p>

                            <hr>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-center"><u>GRATUITY SANCTION ORDER</u></h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-9">
                                    <label><b>Application No:</b> {{ $application->application_no }}</label>
                                </div>
                                <div class="col-md-3">
                                    <label class="float-right"><b>Date:</b> {{ date('d/m/Y') }}</label>
                                </div>
                            </div>
                            <br>
                            @php
                                $amount_in_words = App\Libraries\Util::getAmountInWords($service_form_three->amount_of_dcrg);
                            @endphp
                            <div class="row">
                                <div class="col-md-12">
                                    <span>
                                        1. Sanction is hereby accorded for payment of Gratuity amounting to a sum of Rs
                                        {{ $service_form_three->amount_of_dcrg }}/- Rupees ({{ trim($amount_in_words) }}) ONLY in favour of Sri/Smt/Miss {{ $proposal->employee_name }}, retired {{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }}, of
                                        {{ $proposal->office_last_served }} less recoveries detailed in para – 2 below.
                                    </span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <span>
                                        2. The following recoveries should be affected from the payment of gratuity authorised above.
                                    </span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sl No.</th>
                                                <th>Particulars of Recoveries</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recoveries as $key=>$recovery)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $recovery->recovery_label }}</td>
                                                <td>{{ $recovery->recovery_value }}/-</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <span>
                                        The Net Gratuity Payable Rs {{ $service_form_three->amount_of_dcrg }}/- Rupees ({{ $amount_in_words }}) ONLY
                                    </span>
                                </div>
                            </div>
                            <br>

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
                            <div class="row">
                                <div class="col-md-12">
                                    <span>
                                        3. The net amount shall be credited to his pension A/C No. {{ !empty($proposal->savings_bank_account_no) ? $proposal->savings_bank_account_no : 'NA'  }} maintained at {{ $bankName }}, {{ $branchName }}, IFSC Code: {{ $ifscCode }}, MICR Code: {{ $micrCode }}
                                    </span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-10"></div>
                                <div class="col-md-2">
                                    <span>Yours Faithfully</span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                    General Manager (Finance), Funds OPTCL, Bhubaneswar
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" id="sanction_submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')

<script type="text/javascript">
    $(document).ready(function() {

        $("#sanction-order-submit").validate({
            rules: {
                sanction_order_to_name: {
                    required: true,
                },
                sanction_faithfully: {
                    required: true,
                },
            },
            messages: {
                sanction_order_to_name: {
                    required: 'Please enter correct value',
                },
                sanction_faithfully: {
                    required: 'Please enter correct value',
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
                $(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });

    });
</script>
@endsection