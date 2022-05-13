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
          <li class="breadcrumb-item active" aria-current="page">Unit Change for Receiving Unit (Only)</li>
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
        <h4 class="card-title">Unit Change for Receiving Unit (Only)</h4>
        <form method="post" action="" autocomplete="off" id="unit_change_receiving_unit_only" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="unit_change_receiving_unit_only_changed_type_id" id="unit_change_receiving_unit_only_changed_type_id" >
            <input type="hidden" id="cr_application_id" name="cr_application_id" value="{{ $request_details->id }}">
            <div class="row">
            <div class="col-md-4 form-group">
                <label>PPO No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control ppo_number_format" maxlength="12" name="ucruo_ppo_number" id="ucruo_ppo_number"  value="{{ $request_details->ppo_no }}">
                <label id="ucruo_ppo_number-error" class="error text-danger" for="ucruo_ppo_number"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pension Employee No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control only_number" maxlength="6" name="ucruo_pension_emp_no" id="ucruo_pension_emp_no"  value="{{ $request_details->pensioner_emp_no }}">
                <label id="ucruo_pension_emp_no-error" class="error text-danger" for="ucruo_pension_emp_no"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pensioner Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control alpha" maxlength="100" name="ucruo_name_pensioner" id="ucruo_name_pensioner" value="{{ $request_details->pensioner_name }}">
                <label id="ucruo_name_pensioner-error" class="error text-danger" for="ucruo_name_pensioner"></label>
            </div>   
            <div class="col-md-4 form-group">
                <label>Name of Prev. Pension Unit<span class="text-danger">*</span></label>
                <select class="js-example-basic-single" style="width:100%" id="urcuo_name_prev_pension_unit" name="urcuo_name_prev_pension_unit">
                    <option value="">Select Name of the Unit</option>
                    @foreach($pension_units as $unitData)
                        <option value="{{$unitData->id}}" @if($unitData->id == $request_details->urcuo_name_prev_pension_unit) selected @endif>{{$unitData->pension_unit_name}}</option>
                    @endforeach
                </select>
                <label id="urcuo_name_prev_pension_unit-error" class="error text-danger" for="urcuo_name_prev_pension_unit"></label>
            </div>   
            <div class="col-md-4 form-group">
                <label>Name of New Pension Unit<span class="text-danger">*</span></label>
                <select class="js-example-basic-single" style="width:100%" id="urcuo_name_new_pension_unit" name="urcuo_name_new_pension_unit">
                    <option value="">Select Name of the Unit</option>
                    @foreach($pension_units as $unitData)
                        <option value="{{$unitData->id}}" @if($unitData->id == $request_details->urcuo_name_new_pension_unit) selected @endif>{{$unitData->pension_unit_name}}</option>
                    @endforeach
                </select>
                <label id="urcuo_name_new_pension_unit-error" class="error text-danger" for="urcuo_name_new_pension_unit"></label>
            </div>  
            <div class="col-md-4 form-group">
                <label>Letter No. for Above Changes<span class="text-danger">*</span> <a href="{{ url('/') }}/public/{{ $request_details->ucruo_letter_no_above_changes }}" target="_blank"><i class="fa fa-paperclip"></i></a></label>
                <input type="hidden" class="form-control" name="hidden_ucruo_letter_no_above_changes" id="hidden_ucruo_letter_no_above_changes"  value="{{ $request_details->ucruo_letter_no_above_changes }}">
                <input type="file" name="ucruo_letter_no_above_changes" id="ucruo_letter_no_above_changes" class="file-upload-default dob_attachment_path">
                <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                    <div class="input-group-append">
                        <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                    </div>
                </div>
                <label id="ucruo_letter_no_above_changes-error" class="error text-danger" for="ucruo_letter_no_above_changes"></label>
            </div> 
            <div class="col-md-4 form-group">
                <label>Date for above Changes<span class="text-danger">*</span></label>
                <input type="text" class="form-control datepicker-default" name="ucruo_date_for_above_changes" id="ucruo_date_for_above_changes" readonly value="{{ date('d/m/Y',strtotime($request_details->ucruo_date_for_above_changes)) }}">
                <label id="ucruo_date_for_above_changes-error" class="error text-danger" for="ucruo_date_for_above_changes"></label>
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
                    required: {
                        depends:function(){
                            if($.trim($("#hidden_ucruo_letter_no_above_changes").val()) == ""){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
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
                    url:'{{ route("pension_unit_unit_change_receiving_unit_only_edit_submission") }}',
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