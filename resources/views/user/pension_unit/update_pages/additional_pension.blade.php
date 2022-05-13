<div class="card d-none" id="additional_pension_div">
    <div class="card-body">
        <h4 class="card-title">Additional Pension</h4>
        <form method="post" action="" autocomplete="off" id="additional_pension">
            @csrf
            <input type="hidden" name="ap_pension_changed_type_id" id="ap_pension_changed_type_id" >
            <div class="row">
            <div class="col-md-4 form-group">
                <label>PPO No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control ppo_number_format" maxlength="12" name="ap_ppo_number" id="ap_ppo_number" >
                <label id="ap_ppo_number-error" class="error text-danger" for="ap_ppo_number"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Employee No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control only_number" maxlength="6" name="ap_pension_emp_no" id="ap_pension_emp_no" >
                <label id="ap_pension_emp_no-error" class="error text-danger" for="ap_pension_emp_no"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Name of Pensioner<span class="text-danger">*</span></label>
                <input type="text" class="form-control alpha" maxlength="100" name="ap_name_family_pensioner" id="ap_name_family_pensioner" >
                <label id="ap_name_family_pensioner-error" class="error text-danger" for="ap_name_family_pensioner"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Date of Birth<span class="text-danger">*</span></label>
                <input type="text" class="form-control datepicker-upto-current" name="ap_dob" id="ap_dob" readonly>
                <label id="ap_dob-error" class="error text-danger" for="ap_dob"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Effective Date<span class="text-danger">*</span></label>
                <input type="text" class="form-control datepicker-from-current" name="ap_effective_date" id="ap_effective_date" readonly>
                <label id="ap_effective_date-error" class="error text-danger" for="ap_effective_date"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Additional Rate<span class="text-danger">*</span></label>
                <input type="text" class="form-control amount_type" maxlength="8" name="ap_additional_rate" id="ap_additional_rate" >
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
@section('page-script-section')
<script type="text/javascript">
    $(document).ready(function() {        
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
                  url:'{{ route("pension_unit_additional_pension_submission") }}',
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