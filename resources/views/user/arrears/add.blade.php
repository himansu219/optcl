@extends('user.layout.layout')

@section('section_content')
<style type="text/css">
    .tablerow {
         background-color: white;
     }
    
</style>
<div class="content-wrapper">
    <div class="row">
       <div class="col-12 grid-margin">
            @if(Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
                <nav aria-label="breadcrumb" role="navigation" class="bg-white">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('billing_officer_arrears')}}">Arrears</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
                <div class="card">
                  <div class="card-body">     
                    <!-- <h4 class="card-title">Pension Proposal List</h4>            -->
                    <form class="forms-sample" id="arrear_form_id" method="post" autocomplete="off">
                      @csrf
                      <input type="hidden" id="application_id" name="application_id">
                      <input type="hidden" id="application_type" name="application_type">
                      <input type="hidden" id="pensioner_type" name="pensioner_type">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="exampleInputEmail3">PPO No.<span class="span-red">*</span></label>
                              <input type="text" class="form-control ppo_number_format" id="arrear_ppo_no" name="arrear_ppo_no" maxlength="12">
                              <label id="arrear_ppo_no-error" class="error mt-2 text-danger" for="arrear_ppo_no"></label>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="exampleInputEmail3">Pensioner Name<span class="span-red">*</span></label>
                              <input type="text" class="form-control" id="arrear_pensioner_name" name="arrear_pensioner_name" readonly>
                              <label id="arrear_pensioner_name-error" class="error mt-2 text-danger" for="arrear_pensioner_name"></label>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="exampleInputEmail3">From Date<span class="span-red">*</span></label>
                              <input type="text" class="form-control datepicker-default" id="arraer_from_date" name="arraer_from_date" readonly>
                              <label id="arraer_from_date-error" class="error mt-2 text-danger" for="arraer_from_date"></label>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="exampleInputEmail3">To Date<span class="span-red">*</span></label>
                              <input type="text" class="form-control datepicker-default" id="arrear_to_date" name="arrear_to_date" readonly>
                              <label id="arrear_to_date-error" class="error mt-2 text-danger" for="arrear_to_date"></label>
                          </div>
                        </div>
                      </div>
                      <h4>Due</h2>
                      <div class="row">
                            <div class="col-md-3">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">TI Percentage<span class="span-red">*</span></label>
                                  <input type="text" class="form-control only_number" maxlength="3" id="due_arrear_ti_percentage" name="due_arrear_ti_percentage">
                                  <label id="due_arrear_ti_percentage-error" class="error mt-2 text-danger" for="due_arrear_ti_percentage"></label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">Basic Pension<span class="span-red">*</span></label>
                                  <input type="text" class="form-control amount_type" maxlength="8"ssss id="due_arrear_basic_pension" name="due_arrear_basic_pension">
                                  <label id="due_arrear_basic_pension-error" class="error mt-2 text-danger" for="due_arrear_basic_pension"></label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">Additional Pension<span class="span-red">*</span></label>
                                  <input type="text" class="form-control amount_type" maxlength="8" id="due_arrear_additional_pension_amount" name="due_arrear_additional_pension_amount">
                                  <label id="due_arrear_additional_pension_amount-error" class="error mt-2 text-danger" for="due_arrear_additional_pension_amount"></label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                  <label for="exampleInputEmail3">Commmutation<span class="span-red">*</span></label>
                                  <input type="text" class="form-control amount_type" maxlength="8" id="due_arrear_commutation_amount" name="due_arrear_commutation_amount">
                                  <label id="due_arrear_commutation_amount-error" class="error mt-2 text-danger" for="due_arrear_commutation_amount"></label>
                              </div>
                            </div>
                      </div>
                      <h4>Drawn</h2>
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="exampleInputEmail3">TI Percentage<span class="span-red">*</span></label>
                              <input type="text" class="form-control only_number" maxlength="3" id="drawn_ti_percentage" name="drawn_ti_percentage">
                              <label id="drawn_ti_percentage-error" class="error mt-2 text-danger" for="drawn_ti_percentage"></label>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="exampleInputEmail3">Basic Pension<span class="span-red">*</span></label>
                              <input type="text" class="form-control amount_type" maxlength="8" id="drawn_besic_pension" name="drawn_besic_pension">
                              <label id="drawn_besic_pension-error" class="error mt-2 text-danger" for="drawn_besic_pension"></label>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="exampleInputEmail3">Additional Pension<span class="span-red">*</span></label>
                              <input type="text" class="form-control amount_type" maxlength="8" id="drawn_additional_pension" name="drawn_additional_pension">
                              <label id="drawn_additional_pension-error" class="error mt-2 text-danger" for="drawn_additional_pension"></label>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="exampleInputEmail3">Commmutation<span class="span-red">*</span></label>
                              <input type="text" class="form-control amount_type" maxlength="8" id="drawn_commutation" name="drawn_commutation">
                              <label id="drawn_commutation-error" class="error mt-2 text-danger" for="drawn_commutation"></label>
                          </div>
                        </div>
                      </div>                 
                      <button type="submit" class="btn btn-success mr-2">Submit Arrear</button>
                    </form>
                  </div>
                </div>

                <div class="card">
                  <div class="card-body">
                      <h4 class="card-title">Arrear List</h4>

                      <div class="row">
                        <div class="" style="overflow-y: auto;">
                          <table id="sampleTable" class="table table-striped table-bordered" style="width:2000px">
                            <thead>
                              <tr>
                                <th rowspan="2">Sl No.</th>
                                <th rowspan="2">Pre Rev TI</th>
                                <th rowspan="2">Rev TI</th>
                                <th rowspan="2">Month/Period</th>
                                <th colspan="5" class="text-center">Due</th>
                                <th colspan="5" class="text-center">Drawn</th>
                              </tr>
                              <tr>
                                <th>Basic Pension</th>
                                <th>TI Amount</th>
                                <th>Gross Pension</th>
                                <th>Comm Val</th>
                                <th>Net pension</th>
                                <th>Basic Pension</th>
                                <th>TI Amount</th>
                                <th>Gross Pension</th>
                                <th>Comm Val</th>
                                <th>Net pension</th>
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
    
    $(document).ready(function(){
         $('.numbersOnly').keyup(function () { 
            this.value = this.value.replace(/[^0-9\.]/g,'');
         });
         $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s-]*$/.test(value);
         }, "Please use only letters");

         $("#arrear_ppo_no").on('keyup', function (){
          var ppo_no = $(this).val();
          $.post("{{ route('billing_officer_pensioner_details') }}",
          { 
            "_token": "{{ csrf_token() }}",
            "ppo_no": ppo_no,
          },function(response){
            console.log(response);
            //$("#rbp_basic_amt").val(response.basic_amount);
            //$("#rbp_pension_emp_no").val(response.employee_no);
            $("#arrear_pensioner_name").val(response.pensioner_name);

            $("#application_id").val(response.application_id);
            $("#pensioner_type").val(response.pensioner_type);
            $("#application_type").val(response.application_type);
          });         
        });
                   
          // form validation 
          $("#arrear_form_id").validate({
              rules: {
                arrear_ppo_no: {
                  required: true,
                },
                arrear_pensioner_name: {
                  required: true,
                },
                arraer_from_date: {
                  required: true,
                },
                arrear_to_date: {
                  required: true,
                },
                due_arrear_ti_percentage: {
                  required: true,
                  maxlength: 3,
                },
                due_arrear_basic_pension: {
                  required: true,
                  maxlength: 8,
                },
                due_arrear_additional_pension_amount: {
                  required: true,
                  maxlength: 8,
                },
                drawn_ti_percentage: {
                  required: true,
                  maxlength: 3,
                },
                drawn_besic_pension: {
                  required: true,
                  maxlength: 8,
                },
                drawn_additional_pension: {
                  required: true,
                  maxlength: 8,
                },
                drawn_commutation: {
                  required: true,
                  maxlength: 8,
                }              
              },
              messages: {
                arrear_ppo_no: {
                  required: 'Please enter PPO no',
                },
                arrear_pensioner_name: {
                  required: 'Please enter pensioner name',
                },
                arraer_from_date: {
                  required: 'Please select from date',
                },
                arrear_to_date: {
                  required: 'Please select to date',
                },
                due_arrear_ti_percentage: {
                  required: 'Please enter TI percentage',
                  maxlength: 'Percentage must less than 3 digits',
                },
                due_arrear_basic_pension: {
                  required: 'Please enter basic pension',
                  maxlength: 'Amount must less than 8 digits',
                },
                due_arrear_additional_pension_amount: {
                  required: 'Please enter additional pension',
                  maxlength: 'Amount must less than 8 digits',
                },
                due_arrear_commutation_amount: {
                  required: 'Please enter commutation',
                  maxlength: 'Amount must less than 8 digits',
                },
                drawn_ti_percentage: {
                  required: 'Please enter TI percentage',
                  maxlength: 'Percentage must less than 3 digits',
                },
                drawn_besic_pension: {
                  required: 'Please enter basic pension',
                  maxlength: 'Amount must less than 8 digits',
                },
                drawn_additional_pension: {
                  required: 'Please enter additional pension',
                  maxlength: 'Amount must less than 8 digits',
                },
                drawn_commutation: {
                  required: 'Please enter commutation',
                  maxlength: 'Amount must less than 8 digits',
                }
              },
               submitHandler: function(form, event) { 
                $('.page-loader').addClass('d-flex');
                  event.preventDefault();
                  var formData = new FormData(form);
                  //$("#logid").prop('disabled',true);
                  $.ajax({
                      type:'POST',
                      url:'{{ route("billing_officer_arrear_submission") }}',
                      data: formData,
                      dataType: 'JSON',
                      processData: false,
                      contentType: false,
                      success: function(response) {
                        $('.page-loader').removeClass('d-flex');
                          if(response['error']){
                            //$("#logid").prop('disabled',false);
                              for (i in response['error']) {
                                  var element = $('#' + i);
                                  var id = response['error'][i]['id'];
                                  var eValue = response['error'][i]['eValue'];
                                  //console.log(id);
                                  //console.log(eValue);
                                  $("#"+id).show();
                                  $("#"+id).html(eValue);
                              }
                          }else{
                            // Success
                            $("#sampleTable").append(response['results']);
                            //location.reload();
                            //location.href = "{{route('billing_officer_arrears')}}";
                          }
                      }
                  });
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