@extends('user.layout.layout')
@section('section_content')

<div class="content-wrapper">    
  <div class="row">
    <div class="col-12 grid-margin">
       <nav aria-label="breadcrumb" role="navigation" class="bg-white">
        <ol class="breadcrumb">
          <li class="breadcrumb-item" ><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item" >Update Pension Record</li>
          <li class="breadcrumb-item" ><a href="{{ route('pension_unit_restoration_commutation') }}">Restoration of Commutation</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
      </nav>
      @if(Session::has('error'))
          <div class="alert alert-danger">{{ Session::get('error') }}</div>
      @endif
      @if(Session::has('success'))
          <div class="alert alert-success">{{ Session::get('success') }}</div>
      @endif
      
      <div class="card" id="restoration_commutation_div">
          <div class="card-body">
              <h4 class="card-title">Restoration of Commutation</h4>
              <form method="post" action="" autocomplete="off" id="restoration_commutation">
                  @csrf
                  <input type="hidden" id="restoration_commutation_changed_type_id" name="restoration_commutation_changed_type_id" value="3">
                  <div class="row">
                      <div class="col-md-4 form-group">
                          <label>PPO No.<span class="text-danger">*</span></label>
                          <input type="text" class="form-control ppo_number_format" maxlength="12" name="rc_ppo_number" id="rc_ppo_number" >
                          <label id="rc_ppo_number-error" class="error text-danger" for="rc_ppo_number"></label>
                      </div>
                      <div class="col-md-4 form-group">
                          <label>Pension Employee No.</label>
                          <input type="text" class="form-control only_number" maxlength="6" name="rc_pension_emp_no" id="rc_pension_emp_no" readonly>
                          <label id="rc_pension_emp_no-error" class="error text-danger" for="rc_pension_emp_no"></label>
                      </div>
                      <div class="col-md-4 form-group">
                          <label>Pensioner Name<span class="text-danger">*</span></label>
                          <input type="text" class="form-control alpha" maxlength="100" name="rc_name_pensioner" id="rc_name_pensioner" readonly>
                          <label id="rc_name_pensioner-error" class="error text-danger" for="rc_name_pensioner"></label>
                      </div>  
                      <div class="col-md-4 form-group">
                          <label>Receive Commutation Amount<span class="text-danger">*</span></label>
                          <select class="form-control" name="rc_rcv_comm_amt" id="rc_rcv_comm_amt">
                            <option value="">Select Commutation</option>                            
                          </select>
                          <label id="rc_rcv_comm_amt-error" class="error text-danger" for="rc_rcv_comm_amt"></label>
                      </div>  
                      <div class="col-md-4 form-group">
                          <label>Date of Restoration<span class="text-danger">*</span></label>
                          <input type="text" class="form-control datepicker-default" name="rc_dor" id="rc_dor" readonly>
                          <label id="rc_dor-error" class="error text-danger" for="rc_dor"></label>
                      </div>                
                  </div>
                  <div class="row">
                      <div class="col-md-4 form-group mt-2">
                          <input type="submit" class="btn btn-success" value="Submit">
                      </div>
                  </div>
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
      $.validator.addMethod("amount_only", function (value, element) {
          return this.optional(element) || /^\d{1,8}(?:\.\d{1,2})?$/.test(value);
      }, "Please enter in amount format");

      $.validator.addMethod("ppo_format", function (value, element) {
          return this.optional(element) || /[0-9]{4}\b[\/]{1}[0-9]{2}\b[\/]{1}[0-9]{4}\b/i.test(value); 
      }, "Please enter valid PPO no");
      
      $("#rc_ppo_number").on('keyup', function (){
        var ppo_no = $(this).val();
        $.post("{{ route('pension_unit_restoration_commutation_pensioner_commutation_details') }}",
        { 
          "_token": "{{ csrf_token() }}",
          "ppo_no": ppo_no,
        },function(response){         
          if(response.employee_no){
            $("#rc_pension_emp_no").val(response.employee_no);
            $("#rc_name_pensioner").val(response.pensioner_name);
            $("#rc_name_pensioner").valid();
            $("#rc_rcv_comm_amt").html(response.commutation_list);
          }else{
            $("#rc_pension_emp_no").val('');
            $("#rc_name_pensioner").val('');
            $("#rc_rcv_comm_amt").html('<option value="">Select Commutation</option>');
          }
          
        });         
      });

      $("#restoration_commutation").validate({
          rules: {
            "rc_ppo_number": {
                required: true,
                ppo_format: true,
            },
            "rc_pension_emp_no": {
                required: false,
            },
            "rc_name_pensioner": {
                required: true,
                minlength: 4,
                maxlength: 100,
            },
            "rc_rcv_comm_amt": {
                required: true,
                amount_only: true,
            },
            "rc_dor": {
                required: true,
            },
          },
          messages: {
            "rc_ppo_number": {                    
                required: 'Please enter PPO no',
            },
            "rc_pension_emp_no": {
                required: 'Please enter employee no',
            },
            "rc_name_pensioner": {
                required: 'Please enter pensioner name',
                minlength: 'Name of pensioner must be 4 characters',
                maxlength: 'Name of pensioner should be less than 100 characters', 
            },
            "rc_rcv_comm_amt": {
                required: 'Please enter receive commutation amount',
            },
            "rc_dor": {
                required: 'Please select date of restoration',
            },
          },
          submitHandler: function(form, event) {
              $('.page-loader').addClass('d-flex'); 
              event.preventDefault();
              var formData = new FormData(form);
              //$("#logid").prop('disabled',true);

              $.ajax({
                  type:'POST',
                  url:'{{ route("pension_unit_restoration_commutation_submission") }}',
                  data: formData,
                  dataType: 'JSON',
                  processData: false,
                  contentType: false,
                  success: function(response) {
                  //console.log(response);
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
                      location.href = "{{route('pension_unit_restoration_commutation')}}";
                    }
                  }
              });             
          },
          errorPlacement: function(label, element) {
              label.addClass('text-danger');
              label.insertAfter(element);
          },
          highlight: function(element, errorClass) {
              //$(element).parent().addClass('has-success');
              $(element).addClass('form-control-danger');
          }
      });
    });
  </script>
@endsection