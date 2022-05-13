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
          <li class="breadcrumb-item active" aria-current="page">Revision of Basic Pension</li>
        </ol>
      </nav>
      
      <div class="card">
        <div class="card-body">
        <h4 class="card-title">Revision of Basic Pension</h4>
        <form method="post" action="" autocomplete="off" id="revision_basic_pension">
            @csrf
            <input type="hidden" name="revision_basic_pension_changed_type_id" id="revision_basic_pension_changed_type_id" value="2">
            <input type="hidden" name="rbp_application_id" id="rbp_application_id" value="{{ $request_details->id }}">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>PPO No.<span class="text-danger">*</span></label>
                    <input type="text" class="form-control ppo_number_format" maxlength="12" name="rbp_ppo_number" id="rbp_ppo_number"  value="{{ $request_details->ppo_no }}">
                    <label id="rbp_ppo_number-error" class="error text-danger" for="rbp_ppo_number"></label>
                </div>
                <div class="col-md-4 form-group">
                    <label>Pension Employee No.<span class="text-danger">*</span></label>
                    <input type="text" class="form-control only_number" maxlength="6" name="rbp_pension_emp_no" id="rbp_pension_emp_no"  value="{{ $request_details->pensioner_emp_no }}">
                    <label id="rbp_pension_emp_no-error" class="error text-danger" for="rbp_pension_emp_no"></label>
                </div>
                <div class="col-md-4 form-group">
                    <label>Pensioner Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control alpha" maxlength="100" name="rbp_name_pensioner" id="rbp_name_pensioner"  value="{{ $request_details->pensioner_name }}">
                    <label id="rbp_name_pensioner-error" class="error text-danger" for="rbp_name_pensioner"></label>
                </div>  
                <div class="col-md-4 form-group">
                    <label>Receive Basic Amount<span class="text-danger">*</span></label>
                    <input type="text" class="form-control amount_type" maxlength="8" name="rbp_basic_amt" id="rbp_basic_amt"  value="{{ $request_details->pensioner_basic_amount }}">
                    <label id="rbp_basic_amt-error" class="error text-danger" for="rbp_basic_amt"></label>
                </div>  
                <div class="col-md-4 form-group">
                    <label>O.O No.<span class="text-danger">*</span></label>
                    <input type="text" class="form-control only_number" maxlength="30" name="rbp_oo_no" id="rbp_oo_no"  value="{{ $request_details->oo_no }}">
                    <label id="rbp_oo_no-error" class="error text-danger" for="rbp_oo_no"></label>
                </div>     
                <div class="col-md-4 form-group">
                    <label>O.O No. Date<span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker-default" name="rbp_oo_no_date" id="rbp_oo_no_date" readonly value="{{  date('d/m/Y',strtotime($request_details->oo_no_date)) }}">
                    <label id="rbp_oo_no_date-error" class="error text-danger" for="rbp_oo_no_date"></label>
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
                    url:'{{ route("pension_unit_revision_basic_pension_edit_submission") }}',
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