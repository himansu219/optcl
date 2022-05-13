@extends('user.layout.layout')
@section('section_content')

<div class="content-wrapper">
  @if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
  <div class="row">
    <div class="col-12 grid-margin">
       <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
          <li class="breadcrumb-item" ><a href="{{-- route('user_dashboard') --}}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">View Applications</li>
        </ol>
      </nav>
      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Filters</h4>
            <form class="forms-sample" id="filter_applications" method="post" action="{{ route('pension_unit_applications') }}" autocomplete="off">
                @csrf
                <div class="row">
                  <div class="col-md-4">
                    <label>Application No.</label>
                    <input type="text" name="application_no" class="form-control" id="application_no" value="{{ !empty($request->application_no) ? $request->application_no : '' }}" >
                  </div>

                  <div class="col-md-4">
                    <label>Employee Code</label>
                    <input type="text" name="employee_code" class="form-control" id="employee_code" value="{{ !empty($request->employee_code) ? $request->employee_code : '' }}">
                  </div>

                  <div class="col-md-4">
                    <label>PPO No.</label>
                    <input type="text" name="employee_aadhaar_no" class="form-control" id="employee_aadhaar_no" value="{{ !empty($request->employee_aadhaar_no) ? $request->employee_aadhaar_no : '' }}">
                  </div>
                                        
                </div><br>
                <button type="submit" id="filters" class="btn btn-success">Filter</button>
            </form>
          </div>
      </div>

      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Applications List</h4>
            <div class="row">
              <div class="table-sorter-wrapper col-lg-12 table-responsive">
                <table id="sampleTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Sl No.</th>
                      <th class="sortStyle">Application No.</th>
                      <th class="sortStyle">Created At</th>
                      <th class="sortStyle">Status</th>
                      <th class="sortStyle">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($applications->count() > 0)
                      @foreach($applications as $key => $application)
                        <tr>
                          <td>{{ $applications->firstItem() + $key }}</td>
                          <td>{{ $application->application_no }}</td>
                          <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y  h:i A') }}</td>
                          <td>{{ $application->status_name }}</td>
                          <td>
                            <a  href="{{ route('pension_unit_application_details', array($application->id)) }}"><i class="fa fa-eye"></i></a>
                          </td>
                        </tr>
                      @endforeach
                    @else
                    <tr><td colspan="5" align="center">No Data Found</td></tr>    
                    @endif
                  </tbody>
                </table>
                @if ($applications->lastPage() > 1)
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
                @endif
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

        if(application_no == '' && employee_code == '' && employee_aadhaar_no == '') {
          swal('', 'Please select at least one filter', 'error');
          return false;
        }
      });
    });
  </script>
@endsection