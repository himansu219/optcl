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
                <th width="20%">Name of Prev. Pension Unit</th>
                <td width="30%">{{ $request_details->pre_name }}</td>
            </tr>
            <tr>
                <th>Name of New Pension Unit</th>
                <td>{{ $request_details->new_name }}</td>
                <th>Letter No. for Above Changes</th>
                <td><a href="{{ url('/') }}/public/{{ $request_details->ucruo_letter_no_above_changes }}" target="_blank"><i class="fa fa-paperclip"> </i> Attachment</a></td>
            </tr>
            <tr>
                <th>Date for above Changes</th>
                <td>{{ date('d/m/Y',strtotime($request_details->ucruo_date_for_above_changes)) }}</td>
                <th></th>
                <td></td>
            </tr>                                       
          </table>
        </div>
    </div>
</div>


@endsection