@extends('user.layout.layout')
@section('section_content')

<div class="content-wrapper">    
  <div class="row">
    <div class="col-12 grid-margin">
       <nav aria-label="breadcrumb" role="navigation" class="bg-white">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" ><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item" >Update Pension Record</li>
            <li class="breadcrumb-item" ><a href="{{ route('pension_unit_dropped_case_death_case_list') }}">Dropped Case/Death Case</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
        <h4 class="card-title">Dropped Case/Death Case</h4>
        <form method="post" action="" autocomplete="off" id="dropped_case_death_case">
            @csrf
            <input type="hidden" id="dropped_case_death_case_changed_type_id" name="dropped_case_death_case_changed_type_id">
            <input type="hidden" id="cr_application_id" name="cr_application_id" value="{{ $request_details->id }}">
            <div class="row">
            <div class="col-md-4 form-group">
                <label>PPO No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control ppo_number_format" name="dcdc_ppo_number" id="dcdc_ppo_number"  value="{{ $request_details->ppo_no }}" readonly>
                <label id="dcdc_ppo_number-error" class="error text-danger" for="dcdc_ppo_number"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pension Employee No.</label>
                <input type="text" class="form-control only_number" maxlength="6" name="dcdc_pension_emp_no" id="dcdc_pension_emp_no"  value="{{ $request_details->pensioner_emp_no }}" readonly>
                <label id="dcdc_pension_emp_no-error" class="error text-danger" for="dcdc_pension_emp_no"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pensioner Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control alpha" maxlength="100" name="dcdc_name_pensioner" id="dcdc_name_pensioner"  value="{{ $request_details->pensioner_name }}" readonly>
                <label id="dcdc_name_pensioner-error" class="error text-danger" for="dcdc_name_pensioner"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Date of Death<span class="text-danger">*</span></label>
                <input type="text" class="form-control datepicker-upto-current" name="dcdc_dod" id="dcdc_dod" readonly  value="{{ date('d/m/Y',strtotime($request_details->dod)) }}">
                <label id="dcdc_dod-error" class="error text-danger" for="dcdc_dod"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Remark<span class="text-danger">*</span></label>
                <textarea name="dcdc_remark" id="dcdc_remark" class="form-control remark_box" maxlength="200">{{ $request_details->remark_value }}</textarea>
                <label id="dcdc_remark-error" class="error text-danger" for="dcdc_remark"></label>
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
      
        $("#dropped_case_death_case").validate({
            rules: {
                "dcdc_ppo_number": {
                    required: true,
                    ppo_format: true,
                },
                "dcdc_pension_emp_no": {
                    required: false,
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
                    url:'{{ route("pension_unit_dropped_case_death_case_edit_submission") }}',
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
                        location.href = "{{route('pension_unit_dropped_case_death_case_list')}}";
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