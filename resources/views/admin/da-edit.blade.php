@extends('user.layout.layout')

@section('container')
 <div class="content-wrapper">
                    <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item "><a href="{{route('da_details')}}">DA Master</a></li>
                        <li class="breadcrumb-item "><a href="{{route('da_add')}}">Add DA</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit DA</li>
                      </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit DA</h4>
                <form class="forms-sample" id="da_form" method="post" action="{{URL('da_update/'.$result['0']->id)}}">
                      @csrf
                        <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Start Date <span class="span-red">*</span></label>
                              <div id="datepicker-joining" class="input-group date datepicker ">
                                <input type="text" class="form-control" autocomplete="off" id="start_date" name="start_date" value="{{ \Carbon\Carbon::parse($result['0']->start_date)->format('d-m-Y') }}">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                              </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>End Date <span class="span-red">*</span></label>
                              <div id="datepicker-joining" class="input-group date datepicker ">
                                <input type="text" class="form-control" autocomplete="off" id="end_date" name="end_date" value="{{ \Carbon\Carbon::parse($result['0']->end_date)->format('d-m-Y') }}">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                              </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="exampleInputEmail3">% Of Basic Pay <span class="span-red">*</span></label>
                              <input type="text" class="form-control" id="basic_pay" name="basic_pay" placeholder="Enter % of basic pay" autocomplete="off" value="{{$result['0']->percentage_of_basic_pay}}" >
                          </div>
                        </div>

                        </div>
                         
                         <button type="submit" class="btn btn-success mr-2">Update</button>
                  </form>
                </div>
              </div>
   </div> 


  @endsection
  @section('page-script')

  
<script type="text/javascript">
  $(document).ready(function() {
    //$('.datepickerClass').datepicker(); 
    $('#start_date').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
      //endDate: new Date()
    });

    $('#end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
        //endDate: new Date()
    });
    $.validator.addMethod("basicPay", function (value, element) {
              return this.optional(element) || /^[0-9\s.-]*$/.test(value);
          }, "Please use only numbers");
    $("#da_form").validate({
        rules: {
          start_date: {
            required: true 
          },
          end_date: {
            required: true,
          },
          basic_pay: {
            required: true,
            minlength: 1,
            maxlength: 9,
            basicPay:true
            }

         
        },
        messages: {
          start_date: {
            required: 'Please enter start date'
          },
          end_date: {
            required: 'Please enter end date'
          },
          basic_pay: {                    
            required: 'Please enter basic pay',
            minlength: 'Basic pay minimum 1 numbers',
            maxlength: 'Basic pay maximum 9 numbers'
            }
        },
                errorPlacement: function(label, element) {
            label.addClass('mt-2 text-danger');
            label.insertAfter(element);
        },
        highlight: function(element, errorClass) {
            $(element).parent().addClass('has-success')
            $(element).addClass('form-control-danger')
        }
    });
  }); 



</script>
@endsection