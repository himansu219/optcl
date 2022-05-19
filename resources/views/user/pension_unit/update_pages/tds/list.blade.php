@extends('user.layout.layout')
@section('section_content')
<style>
  #income_property_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #other_income_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #lic_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #nsc_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ppf_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ety_d_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ety_dd_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  .mdi-pencil{
    font-size:20px;
    color:rgb(74, 172, 74);
  }
  .mdi-pencil:hover{
    color:rgb(0, 100, 0);
  }
  .mdi-delete{
    font-size:20px;
    color:rgb(225, 83, 83);
  }
  .mdi-delete:hover{
    color:rgb(191, 0, 0);
  }
  #sampleTable{
      border: 1px solid rgb(233, 233, 233);
  }
  .addClassbtn {
        margin-left:900px;
    }
  
</style>
<div class="content-wrapper">
    <nav aria-label="breadcrumb" role="navigation" class="bg-white">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item">Update Pension Record</li>
        <li class="breadcrumb-item">TDS Information</li>
      </ol>
    </nav> 
    @if(Session::has('error'))
          <div class="alert alert-danger">{{ Session::get('error') }}</div>
      @endif
      @if(Session::has('success'))
          <div class="alert alert-success">{{ Session::get('success') }}</div>
      @endif
    <div class="row">
        <div class="col-md-12 grid-margin">


        <div class="card">
          <div class="card-body">
              <h4 class="card-title">Filter</h4>
              <form class="forms-sample" id="filter_applications" method="post" action="" autocomplete="off">
                  @csrf
                  <div class="row">
                    <div class="col-md-3">
                      <label>Change Data Type</label>
                      <select class="js-example-basic-single form-control" id="change_data_type" name="change_data_type">
                          <option value="">Select Status</option>
                          
                      </select>
                    </div>

                    <div class="col-md-3">
                      <label>CR Number</label>
                      <input type="text" name="cr_number" class="form-control" id="cr_number" value="{{ !empty($request->cr_number) ? $request->cr_number : '' }}" maxlength="11">
                    </div>             
                  </div><br>
                  <button type="submit" id="filters" class="btn btn-success">Filter</button>
                  <a href="{{ route('pension_unit_update_pension_record') }}" class="btn btn-warning">Reset</a>
              </form>
            </div>
        </div>

        <div class="card">
          <div class="card-body">
              <h4 class="card-title">Application List                
                  <a href="{{ route('pension_unit_tds_information_form_page') }}" class="btn btn-success float-right">Add</a>                 
              </h4>
              <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                  <table id="sortable-table-1" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Sl No.</th>
                        <th>CR Number</th>
                        <th>Pensioner Name</th>
                        <th>PPO No</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @if($applications->count() > 0)
                      @foreach($applications as $key => $application)
                        <tr>
                          <td>{{ $applications->firstItem() + $key }}</td>
                          <td>{{ $application->cr_number }}</td>
                          <td>{{ $application->pensioner_name }}</td>
                          <td>{{ $application->my_ppo_no }}</td>
                          <td>{{ $application->status_name }}</td>
                          <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y  h:i A') }}</td>
                          <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item text-black" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="left: -21px;">
                                        <a href="{{ route('pension_unit_tds_information_edit_form_page', [$application->id]) }}" class="dropdown-item edit_desig"><i class="fa fa-pencil-square-o"></i>Edit</a>
                                        <a href="{{ route('pension_unit_tds_information_view_page', [$application->id]) }}" class="dropdown-item delete_desig"><i class="fa fa-eye"></i>View Details</a>
                                    </div>
                                </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr><td colspan="6" align="center">No Data Found</td></tr>    
                    @endif
                      
                    </tbody>
                  </table>                  
                </div>
              </div>
          </div>
        </div>

            
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
              


 @endsection
 @section('page-script')

 @endsection