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
                        <form id="sanction-order-submit" method="post" autocomplete="off" action="{{ route('sanction_order_submit') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="application_id" value="{{ $application->id }}">

                            <img src="{{url('public')}}/images/logo_1.png" alt="image" class="brand_logo_1"/>
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
                                    <h4 class="text-center"><u>SANCTION ORDER</u></h4>
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
                                    <label><b>To,</b></label>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <span>The</span>
                                    <input type="text" name="sanction_order_to_name" class="form-control sanction-order-to input-sm">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">OPTCL Hqrs. Office, Bhubaneswar</div>
                            </div>
                            <br>
                            <div class="row">
                                @if($application->pension_type_id == 1)
                                    <div class="col-md-12">
                                        <label><b>Sub:</b> Sanction of Pension & other pensionary benefits in favour of 
                                        Sri/Smt/Miss {{ $proposal->employee_name }}, {{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }},
                                        {{ $proposal->office_last_served }}
                                        retired on {{ !empty($proposal->date_of_retirement) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : '' }},
                                        {{ $proposal->account_type }} A/C No - {{ $proposal->pf_account_no }}
                                        </label>
                                    </div>
                                @else
                                    <div class="col-md-12">
                                        <label><b>Sub:</b> Sanction of Family Pension & other pensionary benefits in favour of 
                                        Sri/Smt/Miss {{ $proposal->employee_name }}, {{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }},
                                        {{ $proposal->office_last_served }}
                                        retired on {{ !empty($proposal->date_of_retirement) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : '' }},
                                        {{ $proposal->account_type }} A/C No - {{ $proposal->pf_account_no }}
                                        </label>
                                    </div>
                                @endif
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4"><b>Sir/Madam,</b></div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-12">
                                    @if($application->pension_type_id == 1)
                                        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;With reference to the subject cited above, I am to intimate you that the Pensionary benefits is sanctioned in favour of 
                                            Sri/Smt/Miss {{ $proposal->employee_name }}, {{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }},
                                            {{ $proposal->office_last_served }}
                                            retired on {{ !empty($proposal->date_of_retirement) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : '' }}.

                                            The Pensionary benefits shall be paid in his favour w.e.f. {{ !empty($service_form_three->date_of_commencement_pension) ? \Carbon\Carbon::parse($service_form_three->date_of_commencement_pension)->format('d/m/Y') : '' }} as per calculation given below.
                                        </span>
                                    @else
                                        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;With reference to the subject cited above, I am to intimate you that the Pensionary benefits is sanctioned in favour of 
                                            Sri/Smt/Miss {{ $proposal->employee_name }}, {{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }},
                                            {{ $proposal->office_last_served }}
                                            retired on {{ !empty($proposal->date_of_retirement) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') : '' }}.

                                            The Pensionary benefits shall be paid in his favour w.e.f. {{ !empty($service_form_three->date_of_commencement_pension) ? \Carbon\Carbon::parse($service_form_three->date_of_commencement_pension)->format('d/m/Y') : '' }} as per calculation given below.
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL No</th>
                                                <th>Particulars</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>(1)</td>
                                                <td>Pension</td>
                                                <td>Rs {{ $service_form_three->service_pension }}/- + TI</td>
                                            </tr>
                                            <tr>
                                                <td>(2)</td>
                                                <td>D.C.R Gratuity</td>
                                                <td>Rs {{ $service_form_three->amount_of_dcrg }}/-</td>
                                            </tr>
                                            <tr>
                                                <td>(3)</td>
                                                <td>Commutation Pension Value</td>
                                                <td>Rs {{ $service_form_three->commuted_value_of_pension }}/-</td>
                                            </tr>
                                            <tr>
                                                <td>(4)</td>
                                                <td>Residuary Pension</td>
                                                <td>Rs {{ $service_form_three->residuary_pension_commutation }}/- + TI</td>
                                            </tr>
                                            <tr>
                                                <td>(5)</td>
                                                <td>Family Pension</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>i) Up to 65 Years</td>
                                                <td>Rs {{ $service_form_three->enhanced_family_pension }}/- + TI</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>ii) After 65 Years </td>
                                                <td>Rs {{ $service_form_three->normal_family_pension }}/- + TI</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <span>The following documents are enclosed herewith for further necessary action.</span>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="tbl-mrg">
                                        <tr>
                                            <td>1)</td>
                                            <td class="left-pad">New Pension forms (in duplicate) Service book in original in 2 Vols.</td>
                                        </tr>
                                        <tr>
                                            <td>2)</td>
                                            <td class="left-pad">No Dues Certificate</td>
                                        </tr>
                                        <tr>
                                            <td>3)</td>
                                            <td class="left-pad">Last Pay Certificate</td>
                                        </tr>
                                        <tr>
                                            <td>4)</td>
                                            <td class="left-pad">Identification documents Sri/Smt/Miss {{ $proposal->employee_name }}, {{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }} (in duplicate)</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="left-pad">i) Single passport size photograph (3 copies)</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="left-pad">ii) Joint passport size photograph with spouse (3 copies)</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="left-pad">iii) Descriptive Roll Slips</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="left-pad">Specimen Signature</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="left-pad">Left hand thumb and finger impression slips</td>
                                        </tr>
                                        <tr>
                                            <td>5)</td>
                                            <td class="left-pad">History Sheet</td>
                                        </tr>
                                        <tr>
                                            <td>6)</td>
                                            <td class="left-pad">Calculation Sheet</td>
                                        </tr>
                                        <tr>
                                            <td>7)</td>
                                            <td class="left-pad">Photo copy of the 1st page of Bank Pass Book</td>
                                        </tr>
                                        <tr>
                                            <td>8)</td>
                                            <td class="left-pad">Photo copy of Aadhaar & PAN Card</td>
                                        </tr>

                                        @if(!empty($proposal->date_of_joining) && $proposal->date_of_joining <= '1991-03-31' && $proposal->pf_account_type_id == 1)
                                        <tr>
                                            <td>9)</td>
                                            <td class="left-pad">Indemnity Bond</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-12">
                                    <span>Action taken in the matter may please be intimated to this office for reference and record.</span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-10">
                                    <span>Encl: As above</span>
                                </div>
                                <div class="col-md-2">
                                    <span>Yours Faithfully</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                    <input type="text" name="sanction_faithfully" class="form-control">
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
                $('.page-loader').addClass('d-flex');
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