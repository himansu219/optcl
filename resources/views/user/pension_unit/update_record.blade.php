@extends('user.layout.layout')
@section('section_content')

<div class="content-wrapper">    
  <div class="row">
    <div class="col-12 grid-margin">
       <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
          <li class="breadcrumb-item" ><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item" ><a href="{{ route('pension_unit_update_pension_record') }}">Update Pension Record</a></li>
          <li class="breadcrumb-item active" aria-current="page">View Applications</li>
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
            <h4 class="card-title">Update Pension Record</h4>
              <div class="col-md-4 form-group">
                <label>Change Data Type</label>
                <select class="js-example-basic-single form-control" id="change_data_type" name="change_data_type">
                    <option value="">Select Status</option>
                    @foreach($changed_data_list as $changed_data_list_value)
                        <option value="{{$changed_data_list_value->id}}">{{$changed_data_list_value->change_type}}</option>
                    @endforeach
                </select>
              </div>
          </div>
      </div>

      <div class="card" id="additional_family_pensioner_after_death_div">
        <div class="card-body">
            <h4 class="card-title">Additional Family Pensioner after Death of SP/FP</h4>
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
      @include('user.pension_unit.update_pages.additional_pension')
      
      @include('user.pension_unit.update_pages.bank_change')
      
      @include('user.pension_unit.update_pages.dropped_case_death_case')
      

      <div class="card d-none" id="life_certificate_div">
        <div class="card-body">
            <h4 class="card-title">Life Certificate</h4>
            <form method="post" action="" autocomplete="off" id="life_certificate">
              <div class="row">
                <div class="col-md-4 form-group">
                  <label>PPO No.<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="lc_ppo_number" id="lc_ppo_number" >
                  <label id="lc_ppo_number-error" class="error text-danger" for="lc_ppo_number"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Pension Employee No.<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="lc_pension_emp_no" id="lc_pension_emp_no" >
                  <label id="lc_pension_emp_no-error" class="error text-danger" for="lc_pension_emp_no"></label>
                </div>
                <div class="col-md-4 form-group">
                  <label>Pensioner Name<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="lc_name_pensioner" id="lc_name_pensioner" >
                  <label id="lc_name_pensioner-error" class="error text-danger" for="lc_name_pensioner"></label>
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

      @include('user.pension_unit.update_pages.restoration_commutation')

      @include('user.pension_unit.update_pages.revision_basic_pension')
      
      @include('user.pension_unit.update_pages.tds_information')
      
      @include('user.pension_unit.update_pages.unit_change_receiving_unit_only')

    </div>
  </div>
</div>
@endsection

@section('page-script')
  <script type="text/javascript">
    $(document).ready(function() {
      $("#change_data_type").on('change', function(){
        var change_data_type = $(this).val();
        if(change_data_type == 2){  // Revision of Basic Pension
          $('#additional_family_pensioner_after_death_div').addClass('d-none');
          $('#additional_pension_div').addClass('d-none');
          $('#bank_change_div').addClass('d-none');
          $('#dropped_case_death_case_div').addClass('d-none');
          $('#life_certificate_div').addClass('d-none');
          $('#restoration_commutation_div').addClass('d-none');
          $('#revision_basic_pension_div').removeClass('d-none');
          $('#tds_information_div').addClass('d-none');
          $('#unit_change_receiving_unit_only_div').addClass('d-none');
          // add changed data type id
          $('#revision_basic_pension_changed_type_id').val(change_data_type);
        }else if(change_data_type == 3){ // Restoration of Commutation
          $('#additional_family_pensioner_after_death_div').addClass('d-none');
          $('#additional_pension_div').addClass('d-none');
          $('#bank_change_div').addClass('d-none');
          $('#dropped_case_death_case_div').addClass('d-none');
          $('#life_certificate_div').addClass('d-none');
          $('#restoration_commutation_div').removeClass('d-none');
          $('#revision_basic_pension_div').addClass('d-none');
          $('#tds_information_div').addClass('d-none');
          $('#unit_change_receiving_unit_only_div').addClass('d-none');
          // add changed data type id
          $('#restoration_commutation_changed_type_id').val(change_data_type);          
        }else if(change_data_type == 4){ // Additional Pension
          $('#additional_family_pensioner_after_death_div').addClass('d-none');
          $('#additional_pension_div').removeClass('d-none');
          $('#bank_change_div').addClass('d-none');
          $('#dropped_case_death_case_div').addClass('d-none');
          $('#life_certificate_div').addClass('d-none');
          $('#restoration_commutation_div').addClass('d-none');
          $('#revision_basic_pension_div').addClass('d-none');
          $('#tds_information_div').addClass('d-none');
          $('#unit_change_receiving_unit_only_div').addClass('d-none');
          // add changed data type id
          $('#ap_pension_changed_type_id').val(change_data_type);
        }else if(change_data_type == 5){ // Bank Change
          $('#additional_family_pensioner_after_death_div').addClass('d-none');
          $('#additional_pension_div').addClass('d-none');
          $('#bank_change_div').removeClass('d-none');
          $('#dropped_case_death_case_div').addClass('d-none');
          $('#life_certificate_div').addClass('d-none');
          $('#restoration_commutation_div').addClass('d-none');
          $('#revision_basic_pension_div').addClass('d-none');
          $('#tds_information_div').addClass('d-none');
          $('#unit_change_receiving_unit_only_div').addClass('d-none');
          // add changed data type id
          $('#bank_change_changed_type_id').val(change_data_type);
        }else if(change_data_type == 3){ // Restoration of Commutation
        }else if(change_data_type == 6){  // Unit Change for Receiving Unit (Only)
          $('#additional_family_pensioner_after_death_div').addClass('d-none');
          $('#additional_pension_div').addClass('d-none');
          $('#bank_change_div').addClass('d-none');
          $('#dropped_case_death_case_div').addClass('d-none');
          $('#life_certificate_div').addClass('d-none');
          $('#restoration_commutation_div').addClass('d-none');
          $('#revision_basic_pension_div').addClass('d-none');
          $('#tds_information_div').addClass('d-none');
          $('#unit_change_receiving_unit_only_div').removeClass('d-none');
          // add changed data type id
          $('#unit_change_receiving_unit_only_changed_type_id').val(change_data_type);
        }else if(change_data_type == 7){  // Dropped Case/Death Case
          $('#additional_family_pensioner_after_death_div').addClass('d-none');
          $('#additional_pension_div').addClass('d-none');
          $('#bank_change_div').addClass('d-none');
          $('#dropped_case_death_case_div').removeClass('d-none');
          $('#life_certificate_div').addClass('d-none');
          $('#restoration_commutation_div').addClass('d-none');
          $('#revision_basic_pension_div').addClass('d-none');
          $('#tds_information_div').addClass('d-none');
          $('#unit_change_receiving_unit_only_div').addClass('d-none');
          // add changed data type id
          $('#dropped_case_death_case_changed_type_id').val(change_data_type);
        }else if(change_data_type == 8){  // TDS Information
          $('#additional_family_pensioner_after_death_div').addClass('d-none');
          $('#additional_pension_div').addClass('d-none');
          $('#bank_change_div').addClass('d-none');
          $('#dropped_case_death_case_div').addClass('d-none');
          $('#life_certificate_div').addClass('d-none');
          $('#restoration_commutation_div').addClass('d-none');
          $('#revision_basic_pension_div').addClass('d-none');
          $('#tds_information_div').removeClass('d-none');
          $('#unit_change_receiving_unit_only_div').addClass('d-none');
        }else if(change_data_type == 9){  // Life Certificate
          $('#additional_family_pensioner_after_death_div').addClass('d-none');
          $('#additional_pension_div').addClass('d-none');
          $('#bank_change_div').addClass('d-none');
          $('#dropped_case_death_case_div').addClass('d-none');
          $('#life_certificate_div').removeClass('d-none');
          $('#restoration_commutation_div').addClass('d-none');
          $('#revision_basic_pension_div').addClass('d-none');
          $('#tds_information_div').addClass('d-none');
          $('#unit_change_receiving_unit_only_div').addClass('d-none');
        }else{
          $('#additional_family_pensioner_after_death_div').removeClass('d-none');
          $('#additional_pension_div').addClass('d-none');
          $('#bank_change_div').addClass('d-none');
          $('#dropped_case_death_case_div').addClass('d-none');
          $('#life_certificate_div').addClass('d-none');
          $('#restoration_commutation_div').addClass('d-none');
          $('#revision_basic_pension_div').addClass('d-none');
          $('#tds_information_div').addClass('d-none');
          $('#unit_change_receiving_unit_only_div').addClass('d-none');
          // add changed data type id
          $('#addl_fam_pen_changed_type_id').val(change_data_type);
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

      $("#revision_basic_pension").validate({
        rules: {
            "rbp_ppo_number": {
                required: true,
                ppo_format: true,
            },
            "rbp_pension_emp_no": {
                required: true,
                minlength: 6,
                maxlength: 6,
            },
            "rbp_name_pensioner": {
                required: true,
                minlength: 4,
                maxlength: 100,
            },
            "rbp_basic_amt": {
                required: true,
                amount_only: true,
            },
            "rbp_oo_no": {
                required: true,
            },
            "rbp_oo_no_date": {
                required: true,
            },
        },
        messages: {
            "rbp_ppo_number": {                    
                required: 'Please enter PPO no',
            },
            "rbp_pension_emp_no": {
                required: 'Please enter employee no',
                minlength: 'Employee No should be 6 digits',
                maxlength: 'Employee No should be 6 digits',
            },
            "rbp_name_pensioner": {
                required: 'Please enter pensioner name',
                minlength: 'Name of pensioner must be 4 characters',
                maxlength: 'Employee no should be less than 100 characters', 
            },
            "rbp_basic_amt": {
                required: 'Please enter basic amount',
            },
            "rbp_oo_no": {
                required: 'Please enter O.O. no',
            },
            "rbp_oo_no_date": {
                required: 'Please select O.O. no date',
            },
        },
        submitHandler: function(form, event) {
            $('.page-loader').addClass('d-flex'); 
            event.preventDefault();
            var formData = new FormData(form);
            //$("#logid").prop('disabled',true);

            $.ajax({
                type:'POST',
                url:'{{ route("pension_unit_revision_basic_pension_submission") }}',
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

      $("#bc_bank_name").on('change', function(){
          $('.page-loader').addClass('d-flex');
          var bank_id = $(this).val();
          $.post("{{ route('get_branch') }}",{
              "_token": "{{ csrf_token() }}",
              bank_id:bank_id
          },function(response){
              $('.page-loader').removeClass('d-flex');
              $("#bc_branch_name_address").html(response);
              $("#bc_ifsc_code").val("");
          });
      });

      $("#bc_branch_name_address").on('change',function(){
          var bank_branch_id = $(this).val();
          $('.page-loader').addClass('d-flex');
          $.post("{{ route('get_branch_details') }}",{
              "_token": "{{ csrf_token() }}",
              bank_branch_id:bank_branch_id
          },function(response){
              $('.page-loader').removeClass('d-flex');
              var obj = JSON.parse(response);
              $("#bc_ifsc_code").val(obj.ifsc_code);
              $("#bc_ifsc_code").valid();
          });
      });

      $("#bank_change").validate({
          rules: {
            "bc_ppo_number": {
                required: true,
                ppo_format: true,
            },
            "bc_pension_emp_no": {
                required: true,
                minlength: 6,
                maxlength: 6,
            },
            "bc_name_pensioner": {
                required: true,
                minlength: 4,
                maxlength: 100,
            },
            "bc_savings_bank_ac_no": {
                required: true,
                minlength: 9,
                maxlength: 18,
            },
            "bc_bank_name": {
                required: true,
            },
            "bc_branch_name_address": {
                required: true,
            },
            "bc_ifsc_code": {
                required: true,
            },
            "bc_noc_previous_bank": {
                required: true,
            },
          },
          messages: {
            "bc_ppo_number": {                    
                required: 'Please enter PPO no',
            },
            "bc_pension_emp_no": {
                required: 'Please enter employee no',
                minlength: 'Employee no should be 6 digits',
                maxlength: 'Employee no should be 6 digits',
            },
            "bc_name_pensioner": {
                required: 'Please enter pensioner name',
                minlength: 'Name of pensioner must be 4 characters',
                maxlength: 'Name of pensioner should be less than 100 characters', 
            },
            "bc_savings_bank_ac_no": {
                required: 'Please enter savings bank A/C no',
                minlength: 'Savings bank A/C no. must be 9 digits',
                maxlength: 'Savings bank A/C no. should be less than 18 digits',
            },
            "bc_bank_name": {
                required: 'Please select bank',
            },
            "bc_branch_name_address": {
                required: 'Please select branch',
            },
            "bc_ifsc_code": {
                required: 'Please enter ifsc code',
            },
            "bc_noc_previous_bank": {
                required: 'Please select NOC from previous bank',
            },
          },
          submitHandler: function(form, event) {
              $('.page-loader').addClass('d-flex'); 
              event.preventDefault();
              var formData = new FormData(form);
              //$("#logid").prop('disabled',true);

              $.ajax({
                  type:'POST',
                  url:'{{ route("pension_unit_bank_change_submission") }}',
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

      $("#dropped_case_death_case").validate({
          rules: {
            "dcdc_ppo_number": {
                required: true,
                ppo_format: true,
            },
            "dcdc_pension_emp_no": {
                required: true,
                minlength: 6,
                maxlength: 6,
            },
            "dcdc_name_pensioner": {
                required: true,
                minlength: 4,
                maxlength: 100,
            },
            "dcdc_dod": {
                required: true,
            },
            "dcdc_remark": {
                required: true,
                minlength: 10,
                maxlength: 200,
            },
          },
          messages: {
            "dcdc_ppo_number": {                    
                required: 'Please enter PPO no',
            },
            "dcdc_pension_emp_no": {
                required: 'Please enter employee no',
                minlength: 'Employee no should be 6 digits',
                maxlength: 'Employee no should be 6 digits',
            },
            "dcdc_name_pensioner": {
                required: 'Please enter pensioner name',
                minlength: 'Name of pensioner must be 4 characters',
                maxlength: 'Name of pensioner should be less than 100 characters', 
            },
            "dcdc_dod": {
                required: 'Please select date of death',
            },
            "dcdc_remark": {
                required: 'Please enter remark',
                minlength: 'Remark must be 10 characters',
                maxlength: 'Remark should be less than 200 characters',
            },
          },
          submitHandler: function(form, event) {
              $('.page-loader').addClass('d-flex'); 
              event.preventDefault();
              var formData = new FormData(form);
              //$("#logid").prop('disabled',true);

              $.ajax({
                  type:'POST',
                  url:'{{ route("pension_unit_dropped_case_death_case_submission") }}',
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

      $("#life_certificate").validate({
          rules: {
            "lc_ppo_number": {
                required: true,
            },
            "lc_pension_emp_no": {
                required: true,
            },
            "lc_name_pensioner": {
                required: true,
            },
          },
          messages: {
            "lc_ppo_number": {                    
                required: 'Please enter PPO no',
            },
            "lc_pension_emp_no": {
                required: 'Please enter employee no',
            },
            "lc_name_pensioner": {
                required: 'Please enter pensioner name',
            },
          },
          submitHandler: function(form, event) {
              $('.page-loader').addClass('d-flex'); 
              event.preventDefault();
              var formData = new FormData(form);
              //$("#logid").prop('disabled',true);

              $.ajax({
                  type:'POST',
                  url:'{{ route("existing_pensioner_form_submission") }}',
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
                      location.href = "{{route('existing_pension_list')}}";
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

      $("#restoration_commutation").validate({
          rules: {
            "rc_ppo_number": {
                required: true,
                ppo_format: true,
            },
            "rc_pension_emp_no": {
                required: true,
                minlength: 6,
                maxlength: 6,
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
                minlength: 'Employee no should be 6 digits',
                maxlength: 'Employee no should be 6 digits',
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

      $("#unit_change_receiving_unit_only").validate({
          rules: {
            "ucruo_ppo_number": {
                required: true,
                ppo_format: true,
            },
            "ucruo_pension_emp_no": {
                required: true,
                minlength: 6,
                maxlength: 6,
            },
            "ucruo_name_pensioner": {
                required: true,
                minlength: 4,
                maxlength: 100,
            },
            "urcuo_name_prev_pension_unit": {
                required: true,
            },
            "urcuo_name_new_pension_unit": {
                required: true,
            },
            "ucruo_letter_no_above_changes": {
                required: true,
            },
            "ucruo_date_for_above_changes": {
                required: true,
            },
          },
          messages: {
            "ucruo_ppo_number": {                    
                required: 'Please enter PPO no',
            },
            "ucruo_pension_emp_no": {
                required: 'Please enter employee no',
                minlength: 'Employee no should be 6 digits',
                maxlength: 'Employee no should be 6 digits',
            },
            "ucruo_name_pensioner": {
                required: 'Please enter pensioner name',
                minlength: 'Pensioner name must be 4 characters',
                maxlength: 'Pensioner name should be less than 100 characters',
            },
            "urcuo_name_prev_pension_unit": {
                required: 'Please select name of prev. pension unit',
            },
            "urcuo_name_new_pension_unit": {
                required: 'Please select name of new pension unit',
            },
            "ucruo_letter_no_above_changes": {
                required: 'Please upload letter no for above changes',
            },
            "ucruo_date_for_above_changes": {
                required: 'Please select date for above changes',
            },
          },
          submitHandler: function(form, event) {
              $('.page-loader').addClass('d-flex'); 
              event.preventDefault();
              var formData = new FormData(form);
              //$("#logid").prop('disabled',true);

              $.ajax({
                  type:'POST',
                  url:'{{ route("pension_unit_unit_change_receiving_unit_only_submission") }}',
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
  @yield('page-script-section')
@endsection
