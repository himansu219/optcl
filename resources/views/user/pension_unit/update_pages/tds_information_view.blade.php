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
            <li class="breadcrumb-item active" aria-current="page">Additional Family Pensioner after Death of SP/FP</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <table class="table table-bordered perticular-table-details-page body-font-size">
            <tr>
                <th width="20%">PPO No.</th>
                <td width="30%">{{ $revision_basic_pension_details->ppo_no }}</td>
                <th width="20%">Pension Employee No.</th>
                <td width="30%">{{ $revision_basic_pension_details->pension_emp_no }}</td>
            </tr>
            <tr>
                <th width="20%">DOD of SP/ FP</th>
                <td width="30%">{{ $revision_basic_pension_details->pensioner_name }}</td>
                <th width="20%">Name of Family Pensioner</th>
                <td width="30%">{{ $revision_basic_pension_details->pensioner_basic_amount }}</td>
            </tr>
            <tr>
                <th>End Date of Enhanced Family Pension</th>
                <td>{{ $revision_basic_pension_details->oo_no }}</td>
                <th>Savings Bank A/C No.</th>
                <td>{{ $revision_basic_pension_details->oo_no_date }}</td>
            </tr>                                   
          </table>
        </div>
    </div>
</div>


@endsection
@section('page-script')

@endsection