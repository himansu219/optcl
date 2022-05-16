@extends('user.layout.layout')

@section('section_content')

<div class="content-wrapper">
  <nav aria-label="breadcrumb" role="navigation">
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Process Bill</li>
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
            <h4 class="card-title">Filter</h4>
            <form class="forms-sample" id="filter_applications" method="post" action="" autocomplete="off">
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
                        
                    </select>
                  </div>                                        
                </div>
                <div class="row mt-4">
                  <div class="col-md-12">
                    <button type="submit" id="filters" class="btn btn-success">Filter</button>
                    <a href="{{ route('billing_officer_approval_list_list') }}" class="btn btn-warning">Reset</a>
                  </div>                  
                </div>
                
            </form>
          </div>
      </div>

      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Application List
            <a href="{{route('billing_officer_arrears_add')}}" class="btn btn-success float-right" id="generate_bill">Add Arrear</a> 
            </h4>

            <div class="row">
              <div class="table-sorter-wrapper col-lg-12 table-responsive">
                <table id="sampleTable" class="table table-striped">
                  <thead>
                    <tr>
                    <th>Sl No.</th>
                      <th>Pensioner Type</th>
                      <th>Application Type</th>
                      <th>PPO No</th>
                      <th>Status</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                   
                  </tbody>
                </table>
                
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

      $("#all_application_ids").change(function(){  //"select all" change 
          $(".application_ids").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
      });

      //".checkbox" change 
      $('.application_ids').change(function(){ 
        //uncheck "select all", if one of the listed checkbox item is unchecked
          if(false == $(this).prop("checked")){ //if this item is unchecked
              $("#all_application_ids").prop('checked', false); //change "select all" checked status to false
          }
        //check "select all" if all checkbox items are checked
        if ($('.application_ids:checked').length == $('.application_ids').length ){
          $("#all_application_ids").prop('checked', true);
        }
      });

    });



  </script>
@endsection