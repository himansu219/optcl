@extends('user.layout.layout')

@section('section_content')

<div class="content-wrapper">
  <nav aria-label="breadcrumb" role="navigation">
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page"><a href="{{route('billing_officer_approval_list_list')}}">Monthly Changed Data</a></li>
          <li class="breadcrumb-item active" aria-current="page">Approved Application List</li>
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
                        @foreach($statuslist as $statuslist_value)
                            <option value="{{$statuslist_value->id}}" {{ old('app_status_id') == $statuslist_value->id ? "selected" : "" }}>{{$statuslist_value->status_name}}</option>
                        @endforeach
                    </select>
                  </div>
                                        
                </div><br>
                <button type="submit" id="filters" class="btn btn-success">Filter</button>
                <a href="{{ route('billing_officer_application_history') }}" class="btn btn-warning">Reset</a>
            </form>
          </div>
      </div>

      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Approved Application List</h4>

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
                    @if($applications->count() > 0)
                      @foreach($applications as $key => $application)
                        <tr>
                          <td>{{ $applications->firstItem() + $key }}</td>
                          <td>{{ $application->type_name }}</td>
                          <td>{{ $application->pension_type }}</td>
                          <td>{{ $application->new_ppo_no }}</td>
                          <td>{{ $application->status_name }}</td>
                          <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y  h:i A') }}</td>
                          <td>
                            @if($application->appliation_type == '2')
                              <a href="{{route('get_existing_pensioner_details', array($application->application_id))}}"><i class="fa fa-eye"></i></a>
                            @else
                              @if($application->pensioner_type == '1')
                                <!-- Service Pensioner -->
                                <a href="{{route('billing_officer_sp_approved_application_view', array($application->application_id))}}"><i class="fa fa-eye"></i></a>
                              @else
                                <!-- Family Pensioner -->
                                <a href="{{route('billing_officer_fp_approved_application_view', array($application->application_id))}}"><i class="fa fa-eye"></i></a>
                              @endif
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr><td colspan="7" align="center">No Data Found</td></tr>    
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

<!-- Application Assignments -->
<div class="modal fade" id="application_assignments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <form method="post" id="application_assignments_form" accept-charset="utf-8" action="{{route('monthly_changed_data_multiple_forward_to_billing_officer')}}">
        @csrf
        <input type="hidden" name="application_id_list" id="application_id_list">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Forward To Billing Officer </h5>
                    <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remark<span class="text-danger">*</span></label>
                                <textarea name="remarks" id="remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6" required maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="proposalReturned" class="btn btn-raised btn-success">Submit</button>
                </div>
            </div>
        </div>
    </form>        
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

      $('#assigned_to').on('click', function(){
          var id_selected = $(".application_ids:checked").length;
          if(id_selected < 1){
            swal({
              title: "Alert",
              text: "Please select atleast on application",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            });
          }else{
            $("#application_assignments").modal('show');
            //alert($(".application_ids:checked").val());
            /*var checked = $(".application_ids");
            checked.each(function(i){
              alert(checked[i].val());
            });*/
            var arrVal = [];
            $(".application_ids:checked").each(function( index ) {
              arrVal.push($( this ).attr('value')) ;
            });
            $('#application_id_list').val(arrVal);
          }     
      });


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

      $("#application_assignments_form").validate({
            rules: {
                dealing_assistant_list: {
                    required: true,
                },
            },
            messages: {
                dealing_assistant_list: {
                    required: 'Please select dealing assistant',
                },
              },
            submitHandler: function(form, event) { 
                    event.preventDefault();
                    //var formData = new FormData(form);
                    $('#application_assignments').modal('hide');
                    form.submit();
            },
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });


    });



  </script>
@endsection