@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
      <div class="row">
         <div class="col-12 grid-margin">
                @if(Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
                @if(Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                    <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item "><a href="{{route('ti_details')}}">TI Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add TI</li>
                      </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add TI</h4>
                  <form class="forms-sample" id="ti_form" method="post" action="{{URL('ti_submit')}}">
                      @csrf
                    <div class="row">
                      <div class="col-md-4">
                          <div class="form-group">
                            <label>Start Date <span class="span-red">*</span></label>
                              <div id="datepicker-joining" class="input-group date datepicker ">
                                <input type="text" class="form-control" autocomplete="off" id="start_date" name="start_date" placeholder="Enter start date">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                              </div>
                              <label id="start_date-error" class="error mt-2 text-danger" for="start_date" style="display: inline-block;"></label>  
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>End Date <span class="span-red">*</span></label>
                              <div id="datepicker-joining" class="input-group date datepicker ">
                                <input type="text" class="form-control" autocomplete="off" id="end_date" name="end_date" placeholder="Enter end date">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                              </div>
                              <label id="end_date-error" class="error mt-2 text-danger" for="end_date" style="display: inline-block;"></label>
                          </div>
                        </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">DA Rate <span class="span-red">*</span></label>
                                    <input type="text" class="form-control" id="da_rate" name="da_rate" placeholder="Enter da rate" autocomplete="off">
                              </div>
                          </div>
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                  </form>
                </div>
              </div>
         </div>
      </div>
    </div>

  @endsection
  @section('page-script')

  <script type="text/javascript">
    $(document).ready(function() {
     
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
      $.validator.addMethod("daRate", function (value, element) {
            return this.optional(element) || /^[0-9\s.-]*$/.test(value);
        }, "Please use only numbers");

     $("#ti_form").validate({
            rules: {
              start_date: {
                required: true 
              },
              end_date: {
                required: true
              },
              da_rate: {
                required: true,
                minlength: 1,
                maxlength: 5,
                daRate:true

              }

             
            },
            messages: {
              start_date: {
                required: 'Please select start date'
              },
              end_date: {
                required: 'Please select end date'
              },
              da_rate: {                    
                required: 'Please enter da rate',
                minlength: 'Da Rate minimum 1 numbers',
                maxlength: 'Da Rate maximum 5 numbers'
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