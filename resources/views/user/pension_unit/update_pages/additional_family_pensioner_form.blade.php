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
          <li class="breadcrumb-item active" aria-current="page">Additional Family Pensioner after Death of SP/FP</li>
        </ol>
      </nav>
      @if(Session::has('error'))
          <div class="alert alert-danger">{{ Session::get('error') }}</div>
      @endif
      @if(Session::has('success'))
          <div class="alert alert-success">{{ Session::get('success') }}</div>
      @endif
      
      <div class="card" id="additional_family_pensioner_after_death_div">
        <div class="card-body">
            <h4 class="card-title">Additional Family Pensioner after Death of SP/FP</h4>
            <form method="post" action="" autocomplete="off" id="additional_family_pensioner_after_death_update" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="addl_fam_pen_changed_type_appl_id" id="addl_fam_pen_changed_type_appl_id" value="{{ $addl_family_pen_details->id }}">
              <div class="row">
                <div class="col-md-4 form-group form-group">
                  <label>PPO No.<span class="text-danger">*</span></label>
                  <input type="text" class="form-control ppo_number_format" name="ppo_number" id="ppo_number" value="{{ $addl_family_pen_details->ppo_no }}" minlength="12" maxlength="12">
                  <label id="ppo_number-error" class="error text-danger" for="ppo_number"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Pension Employee No.<span class="text-danger">*</span></label>
                  <input type="text" class="form-control only_number" name="pension_emp_no" id="pension_emp_no" value="{{ $addl_family_pen_details->pension_emp_no }}" maxlength="6">
                  <label id="pension_emp_no-error" class="error text-danger" for="pension_emp_no"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>DOD of SP/ FP<span class="text-danger">*</span></label>
                  <input type="text" class="form-control datepicker-upto-current" name="dod_sp_fp" id="dod_sp_fp" value="{{ date('d/m/Y', strtotime($addl_family_pen_details->dod_sp_fp)) }}" readonly>
                  <label id="dod_sp_fp-error" class="error text-danger" for="dod_sp_fp"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Name of Family Pensioner<span class="text-danger">*</span></label>
                  <input type="text" class="form-control alpha" name="name_family_pensioner" id="name_family_pensioner" value="{{ $addl_family_pen_details->name_family_pensioner }}">
                  <label id="name_family_pensioner-error" class="error text-danger" for="name_family_pensioner"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>End Date of Enhanced Family Pension<span class="text-danger">*</span></label>
                  <input type="text" class="form-control datepicker-default" name="eod_enhanced_family_pension" id="eod_enhanced_family_pension" value="{{ date('d/m/Y', strtotime($addl_family_pen_details->end_date_enhan_fam_pension)) }}" readonly>
                  <label id="eod_enhanced_family_pension-error" class="error text-danger" for="eod_enhanced_family_pension"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Savings Bank A/C No.<span class="text-danger">*</span></label>
                  <input type="text" class="form-control only_number" name="savings_bank_ac_no" id="savings_bank_ac_no" value="{{ $addl_family_pen_details->sb_bank_ac_number }}">
                  <label id="savings_bank_ac_no-error" class="error text-danger" for="savings_bank_ac_no"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Bank<span class="text-danger">*</span></label>
                  <select class="js-example-basic-single" style="width:100%" id="bank_name" name="bank_name">
                      <option value="">Select Bank</option>
                      @foreach($banks as $bank)
                          <option value="{{$bank->id}}" @if($bank->id == $addl_family_pen_details->bank_id) {{'selected'}} @endif>{{$bank->bank_name}}</option>
                      @endforeach
                  </select>
                  <label id="bank_name-error" class="error text-danger" for="bank_name"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Branch<span class="text-danger">*</span></label>
                  <select class="js-example-basic-single" style="width:100%" id="branch_name_address" name="branch_name_address">
                      <option value="">Select Branch</option>
                      <option value="{{ $addl_family_pen_details->bank_branch_id }}" selected>{{ $addl_family_pen_details->branch_name }}</option>
                  </select>
                  <label id="branch_name_address-error" class="error text-danger" for="branch_name_address"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>IFSC Code<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" placeholder=" Enter ifsc code" readonly value="{{ $addl_family_pen_details->ifsc_code }}">
                  <label id="ifsc_code-error" class="error text-danger" for="ifsc_code"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>NOC From Previous Bank<span class="text-danger">*</span></label>
                  <select class="js-example-basic-single form-control" id="noc_previous_bank" name="noc_previous_bank">
                    <option value="">Select Status</option>
                    <option value="1" @if($addl_family_pen_details->noc_from_pre_bank == 1) {{'selected'}} @endif>Yes</option>
                    <option value="0" @if($addl_family_pen_details->noc_from_pre_bank == 0) {{'selected'}} @endif>No</option>
                  </select>
                  <label id="noc_previous_bank-error" class="error text-danger" for="noc_previous_bank"></label>
                </div>
                <div class="col-md-4 form-group">
                    <label>NOC Document<span class="text-danger">*</span><a href="{{ url('/') }}/public/{{ $addl_family_pen_details->noc_document }}" target="_blank"><i class="fa fa-paperclip"></i></a></label>
                    <input type="file" name="noc_previous_bank_attachment" id="noc_previous_bank_attachment" class="file-upload-default dob_attachment_path" >
                    <input type="hidden" name="hidden_noc_previous_bank_attachment" id="hidden_noc_previous_bank_attachment" value="{{ $addl_family_pen_details->noc_document }}" >
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

      $("#additional_family_pensioner_after_death_update").validate({
          rules: {
              "ppo_number": {
                  required: true,
                  ppo_format: true,
              },
              "pension_emp_no": {
                  required: true,
              },
              "dod_sp_fp": {
                  required: true,
              },
              "name_family_pensioner": {
                  required: true,
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
                required: {
                      depends:function(){
                          if($.trim($("#hidden_noc_previous_bank_attachment").val()) == ""){
                            console.log(1);
                              return true;
                          }else{
                            console.log(2);
                              return false;
                          }
                      }
                },
              },
          },
          messages: {
              "ppo_number": {                    
                  required: 'Please enter PPO no',
              },
              "pension_emp_no": {
                  required: 'Please enter employee no',
              },
              "dod_sp_fp": {
                  required: 'Please enter DOD of SP/ FP',
              },
              "name_family_pensioner": {
                  required: 'Please enter name of family pensioner',
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
                  url:'{{ route("pension_unit_update_record_edit_submission") }}',
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

      $("#bank_name").on('change', function(){
          $('.page-loader').addClass('d-flex');
          var bank_id = $(this).val();
          $.post("{{ route('get_branch') }}",{
              "_token": "{{ csrf_token() }}",
              bank_id:bank_id
          },function(response){
              $('.page-loader').removeClass('d-flex');
              $("#branch_name_address").html(response);
              $("#ifsc_code").val("");
          });
      });

      $("#branch_name_address").on('change',function(){
          var bank_branch_id = $(this).val();
          $('.page-loader').addClass('d-flex');
          $.post("{{ route('get_branch_details') }}",{
              "_token": "{{ csrf_token() }}",
              bank_branch_id:bank_branch_id
          },function(response){
              $('.page-loader').removeClass('d-flex');
              var obj = JSON.parse(response);
              $("#ifsc_code").val(obj.ifsc_code);
              $("#ifsc_code").valid();
          });
      });



    });
  </script>
@endsection