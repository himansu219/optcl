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
            <li class="breadcrumb-item">Update Pension Record</li>
                    <li class="breadcrumb-item"><a href="{{ route('pension_unit_life_certificate_list_page') }}">Life Certificate</a></li>
            <li class="breadcrumb-item active" aria-current="page">View</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <table class="table table-bordered perticular-table-details-page body-font-size">
            <tr>
                <th width="20%">PPO No.</th>
                <td width="30%">{{ $request_details->ppo_number }}</td>
                <th width="20%">Bank Account Number</th>
                <td width="30%">{{ $request_details->bank_account }}</td>
            </tr>
            <tr>
                <th width="20%">Authentication Date</th>
                <td width="30%">{{ $request_details->authentication_date }}</td>
                <th width="20%">Praman ID</th>
                <td width="30%">{{ $request_details->praman_id }}</td>
            </tr>
            <tr>
                <th>Aadhaar Number</th>
                <td>{{ $request_details->aadhar_number }}</td>
                <th>Mobile Number</th>
                <td>{{ $request_details->mobile_number }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $request_details->name }}</td>
                <th>Submitted Type</th>
                <td>{{ $request_details->submit_type }}</td>
            </tr>                                      
          </table>
        </div>
    </div>
</div>


@endsection