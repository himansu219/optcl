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
                <th width="20%">Name of Pensioner</th>
                <td width="30%">{{ $request_details->pensioner_name }}</td>
                <th width="20%">Date of Birth</th>
                <td width="30%">{{ date('d/m/Y',strtotime($request_details->dob)) }}</td>
            </tr>
            <tr>
                <th>Effective Date</th>
                <td>{{ date('d/m/Y',strtotime($request_details->effective_date)) }}</td>
                <th>Additional Rate</th>
                <td>{{ number_format($request_details->additional_rate, 2) }}</td>
            </tr>                                        
          </table>
        </div>
    </div>
</div>


@endsection
@section('page-script')

@endsection