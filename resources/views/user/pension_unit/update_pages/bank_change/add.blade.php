@extends('user.layout.layout')
@section('section_content')

<div class="content-wrapper"> 
      @if(Session::has('error'))
          <div class="alert alert-danger">{{ Session::get('error') }}</div>
      @endif
      @if(Session::has('success'))
          <div class="alert alert-success">{{ Session::get('success') }}</div>
      @endif
  <div class="row">
    <div class="col-12 grid-margin">
       <nav aria-label="breadcrumb" role="navigation" class="bg-white">
        <ol class="breadcrumb">
          <li class="breadcrumb-item" ><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item" >Update Pension Record</li>
          <li class="breadcrumb-item" ><a href="{{ route('pension_unit_bank_change_list') }}">Bank Change</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
      </nav>
      @if(Session::has('error'))
          <div class="alert alert-danger">{{ Session::get('error') }}</div>
      @endif
      @if(Session::has('success'))
          <div class="alert alert-success">{{ Session::get('success') }}</div>
      @endif
      
      <div class="card" id="bank_change_div">
          <div class="card-body">
              <h4 class="card-title">Bank Change</h4>
              <form method="post" action="" autocomplete="off" id="bank_change">
                  @csrf
                  <input type="hidden" name="bank_change_changed_type_id" id="bank_change_changed_type_id" >
                  <div class="row">
                  <div class="col-md-4 form-group">
                      <label>PPO No.<span class="text-danger">*</span></label>
                      <input type="text" class="form-control ppo_number_format" maxlength="12" name="bc_ppo_number" id="bc_ppo_number" >
                      <label id="bc_ppo_number-error" class="error text-danger" for="bc_ppo_number"></label>
                  </div>
                  <div class="col-md-4 form-group">
                      <label>Employee No.<span class="text-danger">*</span></label>
                      <input type="text" class="form-control only_number" name="bc_pension_emp_no" id="bc_pension_emp_no" readonly>
                      <label id="bc_pension_emp_no-error" class="error text-danger" for="bc_pension_emp_no"></label>
                  </div>
                  <div class="col-md-4 form-group">
                      <label>Pensioner Name<span class="text-danger">*</span></label>
                      <input type="text" class="form-control alpha" maxlength="100" name="bc_name_pensioner" id="bc_name_pensioner" readonly>
                      <label id="bc_name_pensioner-error" class="error text-danger" for="bc_name_pensioner"></label>
                  </div>
                  <div class="col-md-4 form-group">
                      <label>Savings Bank A/C No.<span class="text-danger">*</span></label>
                      <input type="text" class="form-control only_number" maxlength="18" name="bc_savings_bank_ac_no" id="bc_savings_bank_ac_no" >
                      <label id="bc_savings_bank_ac_no-error" class="error text-danger" for="bc_savings_bank_ac_no"></label>
                  </div>
                  <div class="col-md-4 form-group">
                      <label>Bank<span class="text-danger">*</span></label>
                      <select class="js-example-basic-single" style="width:100%" id="bc_bank_name" name="bc_bank_name">
                          <option value="">Select Bank</option>
                          @foreach($banks as $bank)
                              <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                          @endforeach
                      </select>
                      <label id="bc_bank_name-error" class="error text-danger" for="bc_bank_name"></label>
                  </div>
                  <div class="col-md-4 form-group">
                      <label>Branch<span class="text-danger">*</span></label>
                      <select class="js-example-basic-single" style="width:100%" id="bc_branch_name_address" name="bc_branch_name_address">
                          <option value="">Select Branch</option>
                      </select>
                      <label id="bc_branch_name_address-error" class="error text-danger" for="bc_branch_name_address"></label>
                  </div>
                  <div class="col-md-4 form-group">
                      <label>IFSC Code<span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="bc_ifsc_code" name="bc_ifsc_code" placeholder=" Enter ifsc code" readonly>
                      <label id="bc_ifsc_code-error" class="error text-danger" for="bc_ifsc_code"></label>
                  </div>
                  <div class="col-md-4 form-group">
                      <label>NOC From Previous Bank<span class="text-danger">*</span></label>
                      <select class="form-control" style="width:100%" id="bc_noc_previous_bank" name="bc_noc_previous_bank">
                          <option value="">Select Status</option>
                          <option value="1">Yes</option>
                          <option value="0">No</option>
                      </select>
                      <label id="bc_noc_previous_bank-error" class="error text-danger" for="bc_noc_previous_bank"></label>
                  </div>
                  <div class="col-md-4 form-group d-none" id="noc_document_div">
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

        $("#bc_noc_previous_bank").on('change', function(){
          var noc_status = $(this).val();
          if(noc_status == 1){
            $("#noc_document_div").removeClass('d-none');
          }else{
            $("#noc_document_div").addClass('d-none');
          }
        });

        $("#bc_ppo_number").on('keyup', function (){
          var ppo_no = $(this).val();
          $.post("{{ route('pension_unit_bank_change_pensioner_details') }}",
          { 
            "_token": "{{ csrf_token() }}",
            "ppo_no": ppo_no,
          },function(response){
            console.log(response);
            //$("#rbp_basic_amt").val(response.basic_amount);
            $("#bc_pension_emp_no").val(response.employee_no);
            $("#bc_name_pensioner").val(response.pensioner_name);
            
          });         
        });
      
        $("#bank_change").validate({
            rules: {
                "bc_ppo_number": {
                    required: true,
                    ppo_format: true,
                },
                "bc_pension_emp_no": {
                    required: false,
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
                "noc_previous_bank_attachment":{
                    required: {
                        depends:function(){
                            if($.trim($("#hidden_noc_previous_bank_attachment").val()) == ""){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
            },
            messages: {
                "bc_ppo_number": {                    
                    required: 'Please enter PPO no',
                },
                "bc_pension_emp_no": {
                    required: 'Please enter employee no', 
                },
                "bc_name_pensioner": {
                    required: 'Please enter pensioner name',
                    minlength: 'Name of pensioner must be 4 characters',
                    maxlength: 'Employee no should be less than 100 characters', 
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
                    url:'{{ route("pension_unit_bank_change_edit_submission") }}',
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
                            location.href = "{{route('pension_unit_bank_change_list')}}";
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

    });
  </script>
@endsection