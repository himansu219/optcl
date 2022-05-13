@extends('user.layout.layout')

@section('section_content')

<div class="content-wrapper">
  <nav aria-label="breadcrumb" role="navigation">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Existing Pensioner</li>
    </ol>
  </nav>
  @if(Session::has('error'))
      <div class="alert alert-danger">{{ Session::get('error') }}</div>
  @endif
  @if(Session::has('success'))
      <div class="alert alert-success">{{ Session::get('success') }}</div>
  @endif
  <div class="row">
    <div class="col-12 grid-margin">
      
      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Filters</h4>
            <form class="forms-sample" id="filter_applications" method="post" action="{{ route('existing_pension_list') }}" autocomplete="off">
                @csrf
                <div class="row">
                  <div class="col-md-3">
                    <label>Old/New PPO No.</label>
                    <input type="text" name="application_no" class="form-control" id="application_no" value="{{ !empty($request->application_no) ? $request->application_no : '' }}" >
                  </div>

                  <div class="col-md-3">
                    <label>Employee Code</label>
                    <input type="text" name="employee_code" class="form-control" id="employee_code" value="{{ !empty($request->employee_code) ? $request->employee_code : '' }}" maxlength="5">
                  </div>

                  <div class="col-md-3">
                    <label>Aadhaar/ Mobile No.</label>
                    <input type="text" name="employee_aadhaar_no" class="form-control only_number" maxlength="12" id="employee_aadhaar_no" value="{{ !empty($request->employee_aadhaar_no) ? $request->employee_aadhaar_no : '' }}">
                  </div>

                  <div class="col-md-3">
                    <label>Status</label>
                    <select class="js-example-basic-single form-control" id="app_status_id" name="app_status_id">
                        <option value="">Select Status</option>
                        @foreach($statuslist as $statuslist_value)
                            <option value="{{$statuslist_value->id}}" {{ old('app_status_id') == $statuslist_value->id ? "selected" : "" }}>{{$statuslist_value->status_name}}</option>
                        @endforeach
                    </select>
                  </div>
                                        
                </div><br>
                <button type="submit" id="filters" class="btn btn-success">Filter</button>
                <a href="{{ route('existing_pension_list') }}" class="btn btn-warning">Reset</a>
            </form>
          </div>
      </div>

      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Applications List 
            <a href="{{ route('existing_pensioner_form') }}" class="btn btn-success float-right">Add New</a>
            </h4>
            
            <div class="row">
              <div class="table-sorter-wrapper col-lg-12 table-responsive">
                <table id="sampleTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th width="80">Sl No.</th>
                      <th>Application Type</th>
                      <th>Pensioner Name</th>
                      <th>PPO No (New)</th>
                      <th>Status</th>
                      <th>Created At</th>
                      <th class="sortStyle text-center" width="80">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($applications->count() > 0)
                      @foreach($applications as $key => $application)
                        <tr>
                          <td>{{ $applications->firstItem() + $key }}</td>
                          <td>{{ $application->pension_type }}</td>
                          <td>{{ $application->pensioner_name }}</td>
                          <td>{{ $application->new_ppo_no }}</td>
                          <td>{{ $application->status_name }}</td>
                          <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y  h:i A') }}</td>
                          <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item text-black" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="left: -21px;">
                                      <a href="{{route('get_existing_pensioner_details', array($application->id))}}" class="dropdown-item edit_desig"><i class="fa fa-eye"></i>View Details</a>
                                      @if($application->is_taxable_amount_generated == 0)
                                      <a href="{{route('existing_pensioner_taxable_amount', array($application->id))}}" class="dropdown-item edit_desig"><i class="fa fa-calculator"></i>Taxable Income</a>
                                      @endif
                                    </div>
                                </div>
                            </div>

                          </td>
                        </tr>
                      @endforeach
                    @else
                    <tr><td colspan="5" align="center">No Data Found</td></tr>    
                    @endif
                  </tbody>
                </table>
                  <nav aria-label="..." class="float-right">
                      <ul class="pagination">
                          <li class="{{ ($applications->currentPage() == 1) ? ' disabled' : '' }} page-item">
                              <a class="page-link" href="{{ $applications->url(1) }}">Previous</a>
                          </li>
                          @for ($i = 1; $i <= $applications->lastPage(); $i++)
                              <li class="{{ ($applications->currentPage() == $i) ? ' active' : '' }}  page-item">
                                  <a class="page-link" href="{{ $applications->url($i) }}">{{ $i }}</a>
                              </li>
                          @endfor
                          <li class="{{ ($applications->currentPage() == $applications->lastPage()) ? ' disabled' : '' }} page-item">
                              <a class="page-link" href="{{ $applications->url($applications->currentPage()+1) }}" >Next</a>
                          </li>
                      </ul>
                  </nav>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#filters').on('click', function() {
        var application_no = $('#application_no').val();
        var employee_code = $('#employee_code').val();
        var employee_aadhaar_no = $('#employee_aadhaar_no').val();
        var app_status_id = $('#app_status_id').val();

        if(application_no == '' && employee_code == '' && employee_aadhaar_no == '' && app_status_id == '') {
          swal('', 'Please select at least one filter', 'error');
          return false;
        }
      });
    });
  </script>
@endsection