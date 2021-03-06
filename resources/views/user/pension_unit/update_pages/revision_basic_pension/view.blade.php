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
        <ol class="breadcrumb">
          <li class="breadcrumb-item" ><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item" >Update Pension Record</li>
          <li class="breadcrumb-item" ><a href="{{ route('pension_unit_revision_basic_pension') }}">Revision of Basic Pension</a></li>
          <li class="breadcrumb-item active" aria-current="page">View</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <table class="table table-bordered perticular-table-details-page body-font-size bg-white">
            <tr>
                <th width="20%">PPO No.</th>
                <td width="30%">{{ $request_details->ppo_no }}</td>
                <th width="20%">Pension Employee No.</th>
                <td width="30%">{{ $request_details->pensioner_emp_no }}</td>
            </tr>
            <tr>
                <th width="20%">Pensioner Name</th>
                <td width="30%">{{ $request_details->pensioner_name }}</td>
                <th width="20%">Revised Basic Amount</th>
                <td width="30%">{{ $request_details->pensioner_basic_amount ? number_format($request_details->pensioner_basic_amount, 2) : 0 }}</td>
            </tr>
            <tr>
                <th>O.O No.</th>
                <td>{{ $request_details->oo_no }}</td>
                <th>O.O No. Date</th>
                <td>{{ $request_details->oo_no_date }}</td>
            </tr>                                          
          </table>
        </div>
    </div>
</div>


@endsection