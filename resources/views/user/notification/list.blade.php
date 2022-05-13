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
          <li class="breadcrumb-item" ><a href="{{ url('/') }}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Notifications</li>
        </ol>
      </nav>

      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Notification List</h4>
            <div class="row">
              <div class="table-sorter-wrapper col-lg-12 table-responsive">
                <table id="sampleTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th width="8%">Sl No.</th>
                      <th class="sortStyle">Message</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($notifications->count() > 0)
                      @foreach($notifications as $key => $notification)
                        <tr>
                          <td>{{ $notifications->firstItem() + $key }}</td>
                          <td>{!! $notification->status_message !!}</td>
                        </tr>
                      @endforeach
                    @else
                    <tr><td colspan="3" align="center">No Data Found</td></tr>    
                    @endif
                  </tbody>
                </table>

                <nav aria-label="..." class="float-right mt-2">
                    <ul class="pagination">
                        <li class="{{ ($notifications->currentPage() == 1) ? ' disabled' : '' }} page-item">
                            <a class="page-link" href="{{ $notifications->url(1) }}">Previous</a>
                        </li>
                        @for ($i = 1; $i <= $notifications->lastPage(); $i++)
                            <li class="{{ ($notifications->currentPage() == $i) ? ' active' : '' }}  page-item">
                                <a class="page-link" href="{{ $notifications->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="{{ ($notifications->currentPage() == $notifications->lastPage()) ? ' disabled' : '' }} page-item">
                            <a class="page-link" href="{{ $notifications->url($notifications->currentPage()+1) }}" >Next</a>
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

        if(application_no == '' && employee_code == '' && employee_aadhaar_no == '') {
          swal('', 'Please select at least one filter', 'error');
          return false;
        }
      });
    });
  </script>
@endsection