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
            <form class="forms-sample" id="filter_applications" method="post" action="{{ route('filter_applicants') }}" autocomplete="off">
                @csrf
                <div class="row">
                  <div class="col-md-3">
                    <label>Applicant Name</label>
                    <input type="text" name="applicant_name" class="form-control" id="applicant_name" value="{{ !empty($request->applicant_name) ? $request->applicant_name : '' }}">
                  </div>
                  <div class="col-md-3">
                    <label>Employee Code</label>
                    <input type="text" name="employee_code" class="form-control" id="employee_code" maxlength="5" value="{{ !empty($request->employee_code) ? $request->employee_code : '' }}">
                  </div>
                  <div class="col-md-3">
                    <label>Aadhaar No.</label>
                    <input type="text" name="aadhaar_no" class="form-control" id="aadhaar_no" maxlength="12" value="{{ !empty($request->aadhaar_no) ? $request->aadhaar_no : '' }}">
                  </div>
                  <div class="col-md-3">
                    <label>Mobile No.</label>
                    <input type="text" name="mobile_no" class="form-control" id="mobile_no" maxlength="10" value="{{ !empty($request->mobile_no) ? $request->mobile_no : '' }}">
                  </div>
                                        
                </div><br>
                <button type="submit" id="filters" class="btn btn-success">Filter</button>
                <a href="{{ route('filter_applicants') }}" class="btn btn-warning">Reset</a>
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
                      <th class="sortStyle">Applicant Name</th>
                      <th class="sortStyle">Employee Code</th>
                      <th class="sortStyle">Aadhaar No</th>
                      <th class="sortStyle">Mobile No</th>
                      <th class="sortStyle">Is Notified</th>
                      <th class="sortStyle">Created At</th>
                      <th class="sortStyle">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($data->count() > 0)
                      @foreach($data as $key => $application)
                        <tr>
                          <td>{{ $data->firstItem() + $key }}</td>
                          <td>{{ $application->first_name }}</td>
                          <td>{{ $application->employee_code }}</td>
                          <td>{{ $application->aadhaar_no }}</td>
                          <td>{{ $application->mobile }}</td>
                          <td>{{ $application->is_notified ? "Yes":"No" }}</td>
                          <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y  h:i A') }}</td>
                          <td>
                            <a href="{{ route('notify_applicant', array($application->id)) }}"><i class="fa fa-bell-o"></i></a>
                            <a href="{{ route('edit_applicant', array($application->id)) }}"><i class="fa fa-edit"></i></a>
                            <a class="deleteUser" name="{{$application->id}}" href="#"><i class="fa fa-trash"></i></a>
                          </td>
                        </tr>
                      @endforeach
                    @else
                    <tr><td colspan="8" align="center">No Data Found</td></tr>    
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
      var employee_code = $('#employee_code').val();
      var applicant_name = $('#applicant_name').val();
      var aadhaar_no = $('#aadhaar_no').val();
      var mobile_no = $('#mobile_no').val();

      if(applicant_name == '' && employee_code == '' && aadhaar_no == '' && mobile_no == '') {
        swal('', 'Please select at least one filter', 'error');
        return false;
      }
    });

    $(".deleteUser").on("click",function () {
      var userID = $(this).attr('name');
      //alert(userID);
      swal( {
        title: "Are you sure",
        text: "Want to delete this ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, delete it!',
        closeOnConfirm: true,
        closeOnCancel: true,
         })
          .then((value) => { 
            if(value){ 
                var url = '{{ route("delete_applicant", ":id") }}';
                url = url.replace(':id', userID);
                location.href = url;
          //   $.post(base_url+"member/delete/",{ pageID:idVal }); 
          //   swal({ title: "Data deleted successfully.", type: "success" })
          // .then(function(){
          //       location.reload();
          //   });
            
           } 
          });
      });


  });
</script>
@endsection