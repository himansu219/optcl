@extends('user.layout.layout')
@section('section_content')

<div class="content-wrapper">    
  <div class="row">
    <div class="col-12 grid-margin">
       <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
          <li class="breadcrumb-item" ><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item" ><a href="{{ route('pension_unit_update_pension_record') }}">Update Pension Record</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit</li>
          <li class="breadcrumb-item active" aria-current="page">Additional Pension</li>
        </ol>
      </nav>
      @if(Session::has('error'))
          <div class="alert alert-danger">{{ Session::get('error') }}</div>
      @endif
      @if(Session::has('success'))
          <div class="alert alert-success">{{ Session::get('success') }}</div>
      @endif
      
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Additional Pension</h4>
          <form method="post" action="" autocomplete="off" id="additional_pension">
              @csrf
              <input type="hidden" name="ap_pension_changed_type_id" id="ap_pension_changed_type_id" value="4">
              <input type="hidden" name="additional_pension_id_value" id="additional_pension_id_value" value="{{ $request_details->id }}">
              <div class="row">
              <div class="col-md-4 form-group">
                  <label>PPO No.<span class="text-danger">*</span></label>
                  <input type="text" class="form-control ppo_number_format" maxlength="12" name="ap_ppo_number" id="ap_ppo_number" value="{{ $request_details->ppo_no }}">
                  <label id="ap_ppo_number-error" class="error text-danger" for="ap_ppo_number"></label>
              </div>
              <div class="col-md-4 form-group">
                  <label>Employee No.<span class="text-danger">*</span></label>
                  <input type="text" class="form-control only_number" maxlength="6" name="ap_pension_emp_no" id="ap_pension_emp_no" value="{{ $request_details->pensioner_emp_no }}">
                  <label id="ap_pension_emp_no-error" class="error text-danger" for="ap_pension_emp_no"></label>
              </div>
              <div class="col-md-4 form-group">
                  <label>Name of Pensioner<span class="text-danger">*</span></label>
                  <input type="text" class="form-control alpha" maxlength="100" name="ap_name_family_pensioner" id="ap_name_family_pensioner" value="{{ $request_details->pensioner_name }}">
                  <label id="ap_name_family_pensioner-error" class="error text-danger" for="ap_name_family_pensioner"></label>
              </div>
              <div class="col-md-4 form-group">
                  <label>Date of Birth<span class="text-danger">*</span></label>
                  <input type="text" class="form-control datepicker-upto-current" name="ap_dob" id="ap_dob" readonly value="{{ date('d/m/Y',strtotime($request_details->dob)) }}">
                  <label id="ap_dob-error" class="error text-danger" for="ap_dob"></label>
              </div>
              <div class="col-md-4 form-group">
                  <label>Effective Date<span class="text-danger">*</span></label>
                  <input type="text" class="form-control datepicker-default" name="ap_effective_date" id="ap_effective_date" readonly value="{{ date('d/m/Y',strtotime($request_details->effective_date)) }}">
                  <label id="ap_effective_date-error" class="error text-danger" for="ap_effective_date"></label>
              </div>
              <div class="col-md-4 form-group">
                  <label>Additional Rate<span class="text-danger">*</span></label>
                  <input type="text" class="form-control amount_type" maxlength="8" name="ap_additional_rate" id="ap_additional_rate" value="{{ $request_details->additional_rate }}">
                  <label id="ap_additional_rate-error" class="error text-danger" for="ap_additional_rate"></label>
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
      
      $("#additional_pension").validate({
        rules: {
          "ap_ppo_number": {
              required: true,
              ppo_format: true,
          },
          "ap_pension_emp_no": {
              required: true,
              minlength: 6,
              maxlength: 6,
          },
          "ap_name_family_pensioner": {
              required: true,
              minlength: 4,
              maxlength: 100,
          },
          "ap_dob": {
              required: true,
          },
          "ap_effective_date": {
              required: true,
          },
          "ap_additional_rate": {
              required: true,
              amount_only: true,
          },
        },
        messages: {
          "ap_ppo_number": {                    
              required: 'Please enter PPO no',
          },
          "ap_pension_emp_no": {
              required: 'Please enter employee no',
              minlength: 'Employee no should be 6 digits',
              maxlength: 'Employee no should be 6 digits',
          },
          "ap_name_family_pensioner": {
              required: 'Please enter name of pensioner',
              minlength: 'Name of pensioner must be 4 characters',
              maxlength: 'Name of pensioner should be less than 100 characters',    
          },
          "ap_dob": {
              required: 'Please select date of birth',
          },
          "ap_effective_date": {
              required: 'Please slect effective date',
          },
          "ap_additional_rate": {
              required: 'Please enter additional rate',
          },
        },
        submitHandler: function(form, event) {
            $('.page-loader').addClass('d-flex'); 
            event.preventDefault();
            var formData = new FormData(form);
            //$("#logid").prop('disabled',true);

            $.ajax({
                type:'POST',
                url:'{{ route("pension_unit_update_additional_pension_page_submission") }}',
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
                    location.href = "{{route('pension_unit_update_pension_record')}}";
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