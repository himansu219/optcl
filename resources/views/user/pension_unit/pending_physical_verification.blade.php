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
      <!-- <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
          <li class="breadcrumb-item" ><a href="{{-- route('user_dashboard') --}}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">View Applications</li>
        </ol>
      </nav> -->
      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Filters</h4>
            <form class="forms-sample" id="filter_applications" method="post" autocomplete="off" action="{{ route('filter_pending_physical_verification') }}">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <label>Application No.</label>
                    <input type="text" name="application_no" class="form-control" id="application_no" value="{{ !empty($request->application_no) ? $request->application_no : '' }}" >
                  </div>

                  <div class="col-md-6">
                    <label>Aadhaar No</label>
                    <input type="text" name="aadhaar_no" class="form-control" id="aadhaar_no" value="{{ !empty($request->aadhaar_no) ? $request->aadhaar_no : '' }}">
                  </div>
                                        
                </div><br>
                <button type="submit"  id="filters" class="btn btn-success">Filter</button>
            </form>
          </div>
      </div>
     <div class="card">
        <div class="card-body">
            <h4 class="card-title">Applicant List</h4>
            <div class="row">
              <div class="table-sorter-wrapper col-lg-12 table-responsive">
                <table id="sampleTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Sl No.</th>
                      <th class="sortStyle">Application No</th>
                      <th class="sortStyle">Created At</th>
                      <th class="sortStyle">PPO</th>
                      <th class="sortStyle">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($data->count() > 0)
                      @foreach($data as $key => $application)
                        <tr>
                          <td>{{ $data->firstItem() + $key }}</td>
                          <td>{{ $application->application_no }}</td>
                          <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y  h:i A') }}</td>
                          <td><a href="{{ asset('public/' . $application->ppo_order_file_path) }}" target="_blank"><i class="fa fa-file-pdf-o"></i></a></td>
                          <td>
                            <a  href="{{URL('pension-unit/physical-verification/pending/view/'.$application->id)}}"><i class="fa fa-eye"></i></a>
                            @if($application->pension_type_id == 1)
                            {{-- <a  href="{{ route('dealing_application_details', array($application->id)) }}"><i class="fa fa-eye"></i></a>
                            @else
                            <a  href="{{ route('family_pension_application_details', array($application->id)) }}"><i class="fa fa-eye"></i></a> --}}
                            @endif
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
                          <li class="{{ ($data->currentPage() == 1) ? ' disabled' : '' }} page-item">
                              <a class="page-link" href="{{ $data->url(1) }}">Previous</a>
                          </li>
                          @for ($i = 1; $i <= $data->lastPage(); $i++)
                              <li class="{{ ($data->currentPage() == $i) ? ' active' : '' }}  page-item">
                                  <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                              </li>
                          @endfor
                          <li class="{{ ($data->currentPage() == $data->lastPage()) ? ' disabled' : '' }} page-item">
                              <a class="page-link" href="{{ $data->url($data->currentPage()+1) }}" >Next</a>
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
      var aadhaar_no = $('#aadhaar_no').val();

      if(application_no == '' && aadhaar_no == '') {
        swal('', 'Please select at least one filter', 'error');
        return false;
      }
    });
  });
</script>
@endsection