@extends('user.layout.layout')

@section('section_content')
<style type="text/css">
    #upload-demo{
        width: 450px;
        height: 300px;
        padding-bottom:25px;
    }
	.body-font-size {
		font-size: 0.8rem;
	}
</style>
<div class="content-wrapper">
    <nav aria-label="breadcrumb" role="navigation" class="bg-white">
        <ol class="breadcrumb"
            <li class="breadcrumb-item" ><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item" >Update Pension Record</li>
            <li class="breadcrumb-item" ><a href="{{ route('pension_unit_additional_family_pensioner') }}">Addition of Pensioner New Pensioner</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Details</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <table class="table table-bordered perticular-table-details-page body-font-size">
            <tr>
                <th width="20%">PPO No.</th>
                <td width="30%">{{ $addl_family_pen_details->ppo_no }}</td>
                <th width="20%">Pension Employee No.</th>
                <td width="30%">{{ $addl_family_pen_details->pension_emp_no }}</td>
            </tr>
            <tr>
                <th width="20%">DOD of SP/ FP</th>
                <td width="30%">{{ $addl_family_pen_details->dod_sp_fp }}</td>
                <th width="20%">Name of Family Pensioner</th>
                <td width="30%">{{ $addl_family_pen_details->name_family_pensioner }}</td>
            </tr>
            <tr>
                <th>End Date of Enhanced Family Pension</th>
                <td>{{ $addl_family_pen_details->end_date_enhan_fam_pension }}</td>
                <th>Savings Bank A/C No.</th>
                <td>{{ $addl_family_pen_details->sb_bank_ac_number }}</td>
            </tr>
            <tr>
                <th>Bank</th>
                <td>{{ $addl_family_pen_details->bank_name }}</td>
                <th>Branch</th>
                <td>{{ $addl_family_pen_details->branch_name }}</td>
            </tr>
            <tr>
                <th>IFSC Code</th>
                <td>{{ $addl_family_pen_details->ifsc_code }}</td>
                <th>NOC From Previous Bank</th>
                <td>{{ $addl_family_pen_details->noc_from_pre_bank ? 'Yes':'No' }}</td>
            </tr> 
            <tr>
                <th>NOC Document</th>
                <td><a href="{{ url('/') }}/public/{{ $addl_family_pen_details->noc_document }}" target="_blank"><i class="fa fa-paperclip"> </i> Attachment</a></td>
                <th></th>
                <td></td>
            </tr> 
                                                      
          </table>
        </div>
    </div>
</div>


@endsection
@section('page-script')

@endsection