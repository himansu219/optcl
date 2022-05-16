@extends('user.layout.layout')
@section('section_content')

<div class="content-wrapper">    
  <div class="row">
    <div class="col-12 grid-margin">
       <nav aria-label="breadcrumb" role="navigation" class="bg-white">
        <ol class="breadcrumb">
          <li class="breadcrumb-item" ><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item" >Update Pension Record</li>
          <li class="breadcrumb-item" ><a href="{{ route('pension_unit_additional_family_pensioner') }}">Addition of Pensioner New Pensioner</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add</li>
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
          <form method="post" action="" autocomplete="off" id="additional_family_pensioner_after_death" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="addl_fam_pen_changed_type_id" id="addl_fam_pen_changed_type_id" value="1" />
              <div class="row">
                <div class="col-md-4 form-group form-group">
                  <label>PPO No.<span class="text-danger">*</span></label>
                  <input type="text" class="form-control ppo_number_format" name="ppo_number" id="ppo_number" minlength="12" maxlength="12">
                  <label id="ppo_number-error" class="error text-danger" for="ppo_number"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Pension Employee No.<span class="text-danger">*</span></label>
                  <input type="text" class="form-control only_number" name="pension_emp_no" id="pension_emp_no" maxlength="6">
                  <label id="pension_emp_no-error" class="error text-danger" for="pension_emp_no"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>DOD of SP/ FP<span class="text-danger">*</span></label>
                  <input type="text" class="form-control datepicker-upto-current" name="dod_sp_fp" id="dod_sp_fp" readonly>
                  <label id="dod_sp_fp-error" class="error text-danger" for="dod_sp_fp"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Name of Family Pensioner<span class="text-danger">*</span></label>
                  <input type="text" class="form-control alpha" name="name_family_pensioner" id="name_family_pensioner" >
                  <label id="name_family_pensioner-error" class="error text-danger" for="name_family_pensioner"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>End Date of Enhanced Family Pension<span class="text-danger">*</span></label>
                  <input type="text" class="form-control datepicker-default" name="eod_enhanced_family_pension" id="eod_enhanced_family_pension" readonly>
                  <label id="eod_enhanced_family_pension-error" class="error text-danger" for="eod_enhanced_family_pension"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Savings Bank A/C No.<span class="text-danger">*</span></label>
                  <input type="text" class="form-control only_number" name="savings_bank_ac_no" id="savings_bank_ac_no" maxlength="18">
                  <label id="savings_bank_ac_no-error" class="error text-danger" for="savings_bank_ac_no"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Bank<span class="text-danger">*</span></label>
                  <select class="js-example-basic-single" style="width:100%" id="bank_name" name="bank_name">
                      <option value="">Select Bank</option>
                      @foreach($banks as $bank)
                          <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                      @endforeach
                  </select>
                  <label id="bank_name-error" class="error text-danger" for="bank_name"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Branch<span class="text-danger">*</span></label>
                  <select class="js-example-basic-single" style="width:100%" id="branch_name_address" name="branch_name_address">
                      <option value="">Select Branch</option>
                  </select>
                  <label id="branch_name_address-error" class="error text-danger" for="branch_name_address"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>IFSC Code<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" placeholder=" Enter ifsc code" readonly>
                  <label id="ifsc_code-error" class="error text-danger" for="ifsc_code"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>NOC From Previous Bank<span class="text-danger">*</span></label>
                  <select class="js-example-basic-single form-control" id="noc_previous_bank" name="noc_previous_bank">
                    <option value="">Select Status</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                  <label id="noc_previous_bank-error" class="error text-danger" for="noc_previous_bank"></label>
                </div>
                <div class="col-md-4 form-group">
                    <label>NOC Document<span class="text-danger">*</span></label>
                    <input type="file" name="noc_previous_bank_attachment" id="noc_previous_bank_attachment" class="file-upload-default dob_attachment_path" >
                    <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                        <div class="input-group-append">
                            <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                        </div>
                    </div>
                    <label id="noc_previous_bank_attachment-error" class="error text-danger" for="noc_previous_bank_attachment"></label>
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
      

      $("#additional_family_pensioner_after_death").validate({
          rules: {
              "ppo_number": {
                  required: true,
                  ppo_format: true,
              },
              "pension_emp_no": {
                  required: false,
                  minlength: 6,
                  maxlength: 6,
              },
              "dod_sp_fp": {
                  required: true,
              },
              "name_family_pensioner": {
                  required: true,
                  minlength: 4,
                  maxlength: 100,
              },
              "eod_enhanced_family_pension": {
                  required: true,
              },
              "bank_name": {
                  required: true,
              },
              "branch_name_address": {
                  required: true,
              },
              "savings_bank_ac_no": {
                  required: true,
              },
              "ifsc_code": {
                  required: true,
              },
              "noc_previous_bank": {
                  required: true,
              },
              "noc_previous_bank_attachment":{
                    required: true,
              },
          },
          messages: {
              "ppo_number": {                    
                  required: 'Please enter PPO no',
              },
              "pension_emp_no": {
                  required: 'Please enter employee no',
                  minlength: 'Employee no should be 6 digits',
                  maxlength: 'Employee no should be 6 digits',
              },
              "dod_sp_fp": {
                  required: 'Please enter DOD of SP/ FP',
              },
              "name_family_pensioner": {
                  required: 'Please enter name of family pensioner',
                  minlength: 'Name of family pensioner must be 4 characters',
                  maxlength: 'Name of family pensioner should be less than 100 characters',
              },
              "eod_enhanced_family_pension": {
                  required: 'Please enter end date of enhanced family pension',
              },
              "bank_name": {
                  required: 'Please slect bank',
              },
              "branch_name_address": {
                  required: 'Please select branch',
              },
              "savings_bank_ac_no": {
                  required: 'Please enter savings bank A/C no',
              },
              "ifsc_code": {
                  required: 'Please enter IFSC code',
              },
              "noc_previous_bank": {
                  required: 'Please select NOC from previous bank',
              },
              "noc_previous_bank_attachment":{
                    required: 'Please upload NOC document',
              }, 
          },
          submitHandler: function(form, event) {
              $('.page-loader').addClass('d-flex'); 
              event.preventDefault();
              var formData = new FormData(form);
              //$("#logid").prop('disabled',true);
              $.ajax({
                  type:'POST',
                  url:'{{ route("pension_unit_update_record_submission") }}',
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