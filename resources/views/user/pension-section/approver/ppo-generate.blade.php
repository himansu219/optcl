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
    input[type=text] {
      /*width: 100%;
      padding: 12px 20px;
      margin: 8px 0;*/
      box-sizing: border-box;
      border: none;
      border-bottom: 2px solid #d5dcec ;
    }
    .point-section {
        margin-left: 15px;
    }
    .point-section-number {
        margin-left: 32px;
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
                        <form id="sanction-order-submit" method="post" autocomplete="off" action="{{ route('approver_ppo_order_submit') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="application_id" value="{{ $application->id }}">
                            <input type="hidden" name="ppo_number" value="{{ $generated_ppo_number }}">

                            <img src="{{url('public')}}/images/logo_1.png" alt="image" class="brand_logo_1"/>
                            @if(!empty($document->attached_recent_passport))
                                <img src="{{url('public')}}/{{$document->attached_recent_passport}}" alt="image" class="brand_logo_2"/>
                            @else
                                <img src="{{url('public')}}/images/profile.png" alt="image" class="brand_logo_2"/>
                            @endif
                            <h4 class="card-title align_center mb-2">ଓଡିଶା ବିଦ୍ୟୁତ ଶକ୍ତି ସଂଚାରଣ ନିଗମ ଲି8.</h4>
                            <h4 class="card-title align_center mb-2">ODISHA POWER TRANSMISSION CORPORATION LTD.</h4>
                            <h5 class="card-description align_center mb-1">(A Govt. of Odisha Undertaking)</h5>
                            <h5 class="card-description align_center mb-1">Gridco Pension Trust Fund</h5>
                            <p class="card-description text-center mb-1">Regd. Off – Janpath, Bhubaneswar – 751022</p>
                            <p class="card-description text-center mb-1">Telephone: (6074) 2540051 (EPABX)</p>
                            <p class="card-description text-center mb-1">Website: <a href="http://optcl.co.in/">http://optcl.co.in/</a></p>
                            <p class="card-description text-center mb-1">CIN: U41020R2004SGC007553</p>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-center"><u>PENSION PAYMENT ORDER (PPO) NO: {{ $generated_ppo_number }}</u></h4>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Sanction is hereby accorded for payment of the following pensionary benefits in favour if 
                                    <b>Sri/Smt/Miss {{ $proposal->employee_name }}</b> retired <b>{{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }},
                                    {{ $proposal->office_last_served }}</b>
                                    </label>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12 point-section">
                                    <label>A) 1. Amount of monthly Pension Rs <b>{{ $service_form_three->service_pension }}/-</b> (Rupees <b>{{ App\Libraries\Util::getAmountInWords($service_form_three->service_pension) }}</b>) ONLY with effect from <b>{{ !empty($proposal->date_of_retirement) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : '' }}</b> till his death.</label>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12 point-section-number">
                                   <label>2. Amount of pension commuted Rs <b>{{ $service_form_three->commuted_amount_of_pension }}/-</b> (Rupees <b>{{ App\Libraries\Util::getAmountInWords($service_form_three->commuted_amount_of_pension) }}</b>) ONLY and the commuted value of pension is Rs <b>{{ $service_form_three->commuted_value_of_pension }}/-</b>.</label>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12 point-section-number">
                                   <label>3. Amount of reduced Pension @ Rs <b>{{ $service_form_three->residuary_pension_commutation }}/-</b> (Rupees <b>{{ App\Libraries\Util::getAmountInWords($service_form_three->residuary_pension_commutation) }}</b>) ONLY.</label>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12 point-section">
                                   <label>B) The commuted portion of pension of Rs <b>{{ $service_form_three->commuted_amount_of_pension }}/-</b> may be restored after 15 years from the date of payment of reduced pension.</label>
                                </div>
                            </div>

                            <br>
                           <div class="row">
                                @php
                                    $next_date = \Carbon\Carbon::parse($nomineeDetails->date_of_birth)->addYears(65)->format('d/m/Y');
                                    $amount_after_65_years = $service_form_three->service_pension / 2;
                                @endphp
                                <div class="col-md-12 point-section">
                                   <label>C) In the event of death of <b>Sri/Smt/Miss {{ $proposal->employee_name }}</b> before attaining the age of 65 years, Family Pension of Rs <b>{{ $service_form_three->service_pension }}/-</b> per month shall be payable to <b>Sri/Smt./Miss {{ !empty($nomineeDetails) ? $nomineeDetails->nominee_name : '' }} {{ !empty($nomineeDetails) ? $nomineeDetails->relation_name : '' }}</b> of pensioner from the day following the death of pensioner up to <b>{{ $next_date }}</b> and thereafter @ Rs <b>{{ $amount_after_65_years }}/-</b> till re-marriage OR death of family pensioner whichever is earlier.</label>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12 point-section">
                                   <label>D) The Temporary Increase (T.I) on pension shall be payable as applicable from time to time.</label>
                                </div>
                            </div>

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

                            <br>
                            <div class="row">
                                <div class="col-md-12 point-section">
                                   <label>E) The aforesaid amounts shall be credited to the Savings Bank Account No. <b>{{ !empty($proposal->savings_bank_account_no) ? $proposal->savings_bank_account_no : 'NA'  }}</b> which is to be deemed as the pension account of <b>Sri/Smt/Miss {{ $proposal->employee_name }}</b> in the <b>{{ $bankName }}, {{ $branchName }}</b>, IFSC Code: <b>{{ $ifscCode }}</b>, MICR Code: <b>{{ $micrCode }}</b>.</label>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12 ">
                                   <label><b>Sri/Smt/Miss {{ $proposal->employee_name }}</b> retired <b>{{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }}, {{ $proposal->office_last_served }}</b> for information and necessary action. He / She is advised to submit the life certificate / non-employment / non-marriage certificate to the concerned DDO by 20th November every year.</label>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-9">
                                    <span>Date: {{ date('d/m/Y') }} </span>
                                </div>
                                <div class="col-md-3">
                                    <span>Yours Faithfully</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <b>General Manager (Finance), Funds OPTCL, Bhubaneswar</b>
                                </div>
                            </div>

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

    });
</script>
@endsection