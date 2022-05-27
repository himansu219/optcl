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
    <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('pension_unit_update_pension_record')}}">Update Pension Record</a></li>
            <li class="breadcrumb-item active" aria-current="page">View</li>
            <li class="breadcrumb-item active" aria-current="page">Additional Pension</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <table class="table table-bordered perticular-table-details-page body-font-size">
            <tr>
                <th width="20%">PPO No.</th>
                <td width="30%">{{ $request_details->ppo_no }}</td>
                <th width="20%">Employee No.</th>
                <td width="30%">{{ $request_details->pensioner_emp_no }}</td>
            </tr>
            <tr>
                <th width="20%">Pensioner Name</th>
                <td width="30%">{{ $request_details->pensioner_name }}</td>
                <th width="20%">Savings Bank A/C No.</th>
                <td width="30%">{{ $request_details->sb_acc_no }}</td>
            </tr>
            <tr>
                <th>Bank</th>
                <td>{{ $request_details->bank_name }}</td>
                <th>Branch</th>
                <td>{{ $request_details->branch_name }}</td>
            </tr>
            <tr>
                <th>IFSC Code</th>
                <td>{{ $request_details->ifsc_code }}</td>
                <th>NOC From Previous Bank</th>
                <td>{{ $request_details->noc_from_pre_bank == 1 ? 'Yes':'No' }}</td>
            </tr> 
            <tr>
                <th>NOC Document</th>
                <td><a href="{{ url('/') }}/public/{{ $request_details->noc_document }}" target="_blank"><i class="fa fa-paperclip"> </i> Attachment</a></td>
                <th></th>
                <td></td>
            </tr>                                      
          </table>
        </div>
    </div>
</div>


@endsection