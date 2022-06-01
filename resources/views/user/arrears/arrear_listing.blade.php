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
                    <li class="breadcrumb-item active" aria-current="page">Arrear Details</li>
                  </ol>
                </nav>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered mt-3">
                            <tr>
                                <th width="18%">Pensioner Name</th>
                                <td width="15%"></td>
                                <th width="12%">PPO No.</th>
                                <td width="20%"></td>
                                <th width="15%">Created At</th>
                                <td></td>
                            </tr>
                        </table>
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
                                <th colspan="6" class="text-center">Due</th>
                                <th colspan="6" class="text-center">Drawn</th>
                                <th rowspan="2">Rest Pension</th>
                              </tr>
                              <tr>
                                <th>Basic Pension</th>
                                <th>TI Amount</th>
                                <th>Gross Pension</th>
                                <th>Additional Amount</th>
                                <th>Comm Val</th>
                                <th>Net pension</th>
                                <th>Basic Pension</th>
                                <th>TI Amount</th>
                                <th>Gross Pension</th>
                                <th>Additional Amount</th>
                                <th>Comm Val</th>
                                <th>Net pension</th>
                              </tr>
                            </thead>
                            <tbody>
                              @php
                                $i = 1;
                                $rest_total = 0;
                              @endphp
                              @foreach($section_list as $section_data)
                                <tr>
                                  <td>{{$i++}}</td>
                                  <td>{{$section_data->drawn_ti_percentage}}</td>
                                  <td>{{$section_data->due_ti_percentage}}</td>
                                  <td>{{date('M', mktime(0, 0, 0, $section_data->month_value, 10))}}-{{$section_data->year_value}}</td>
                                  <td>{{$section_data->due_basic_amount}}</td>
                                  <td>{{$section_data->due_ti_amount}}</td>
                                  <td>{{$section_data->due_gross_amount}}</td>
                                  <td>{{$section_data->due_additional_amount}}</td>
                                  <td>{{$section_data->due_comm_amount}}</td>
                                  <td>{{$section_data->due_net_pension}}</td>
                                  <td>{{$section_data->drawn_basic_amount}}</td>
                                  <td>{{$section_data->drawn_ti_amount}}</td>
                                  <td>{{$section_data->drawn_gross_pension}}</td>
                                  <td>{{$section_data->drawn_additional_amount}}</td>
                                  <td>{{$section_data->drawn_comm_amount}}</td>
                                  <td>{{$section_data->drawn_net_pension}}</td>
                                  <td>{{ $section_data->rest_pension ? $section_data->rest_pension : 0 }}</td>
                                </tr>
                                @php 
                                  $rest_total += $section_data->rest_pension;
                                @endphp
                              @endforeach
                              <tr>
                                <td colspan="16" class="text-right font-weight-bold">Total</td>
                                <td>{{$rest_total}}</td>
                              </tr>
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