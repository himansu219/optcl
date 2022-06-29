@extends('user.layout.layout')

@section('section_content')

<div class="content-wrapper">
  <nav aria-label="breadcrumb" role="navigation" class="bg-white">
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
            <form class="forms-sample" id="beneficiary_bill_generation" method="post" action="" autocomplete="off">
                @csrf
                <div class="row">
                  <div class="col-md-3">
                    <label>Years</label>
                    <select class="js-example-basic-single form-control" id="year_id" name="year_id">
                        <option value="">Select Year</option>
                        @for($i = 2022; $i < date('Y')+10; $i++)
                        <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                    <label id="year_id-error" class="error mt-2 text-danger" for="year_id"></label>
                  </div>
                  <div class="col-md-3">
                    <label>Months</label>
                    <select class="js-example-basic-single form-control" id="month_id" name="month_id">
                        <option value="">Select Month</option>
                        @for($m = 1; $m <= 12; $m++) 
                          <option value="{{$m}}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                        @endfor
                    </select>
                    <label id="month_id-error" class="error mt-2 text-danger" for="month_id"></label>
                  </div>                                       
                </div>
                <div class="row mt-4">
                  <div class="col-md-12">
                    <button type="submit" id="filters" class="btn btn-success">Filter</button>
                    <a href="{{ route('billing_history') }}" class="btn btn-warning">Reset</a>
                  </div>                  
                </div>
                
            </form>
          </div>
      </div>

      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Application List</h4>
                            
            <div class="row">
              <div class="table-sorter-wrapper col-lg-12 table-responsive">
                <table id="sampleTable" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Bill No.</th>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Created at</th>
                        <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($applications->count() > 0)
                      @foreach($applications as $key => $application)
                        <tr>
                          <td>{{ $applications->firstItem() + $key }}</td>
                          <td>{{ $application->bill_no }}</td>
                          <td>{{ $application->year_value }}</td>
                          <td>{{ date('F', mktime(0, 0, 0, $application->month_value, 10)) }}</td>
                          <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y  h:i A') }}</td>
                          <td class="text-center">
                            <a href="{{url('storage/app/'.$application->download_path)}}" title="Bill Download" download><i class="fa fa-download"></i></a>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr><td colspan="7" align="center">No Data Found</td></tr>    
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

<!-- Application Assignments -->
<div class="modal fade" id="application_assignments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <form method="post" id="application_assignments_form" accept-charset="utf-8" action="{{route('generate_bill_sheet')}}">
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

      /* $('#generate_bill').on('click', function(){
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
            var arrVal = [];
            $(".application_ids:checked").each(function( index ) {
              arrVal.push($( this ).attr('value')) ;
            });
            $('#application_id_list').val(arrVal);
          }     
      }); */

      


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

      $('#beneficiary_bill_generation').validate({
          rules: {
            year_id: {
                  required: true,
              },
              month_id: {
                  required: true,
              },
          },
          messages: {
              year_id: {
                  required: "Please select year",
              },
              month_id: {
                  required: "Please select month",
              },
            },
          submitHandler: function(form, event) { 
                  event.preventDefault();
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