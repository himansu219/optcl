@extends('user.layout.landing_page_layout')
@section('title', 'OPTCL')
@section('section_content')

<div class="col-lg-6 d-flex align-items-center justify-content-center">
  <div class="auth-form-transparent text-left p-3" id="register_otp_div">
    <div class="brand-logo">
      <img src="{{url('public')}}/images/logo.png" alt="logo" style="width: 75px; height: 90px;">
    </div>
    <h4>New Nominee! Join here?</h4>
    <h6 class="font-weight-light">Register here to submit the family pension application</h6>
    <form class="pt-3" id="nominee_register_form" method="post" action="" autocomplete="off">
     
      @csrf
      <div class="">
        <div class="form-group row">
            <div class="col-sm-6">
              <div class="form-radio">
                  <label class="form-check-label">
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" class="form-check-input" name="emp_code" id="emp_code" value="employee_code" checked>
                    Employee Code
                  </label>
              </div>
          </div>
          <div class="col-sm-6">
              <div class="form-radio">
                  <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="emp_code" id="ppo" value="ppo_no">
                    PPO No.
                  </label>
              </div>
          </div>
        </div>
      </div>
      <div class="form-group employeeCode">
        <label>Employee Code <span class="span-red">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-account-outline text-primary"></i>
            </span> 
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 numbersOnly only_number" id="employee_code" name="employee_code" placeholder="Employee Code" maxlength="5" minlength="5">
        </div>
        <label id="employee_code-error" class="error mt-2 text-danger" for="employee_code"></label>
      </div>
      <div class="form-group ppoNo">
        <label>PPO No <span class="span-red">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-account-outline text-primary"></i>
            </span> 
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 numbersOnly only_number" id="ppo_no" name="ppo_no" placeholder="PPO No" maxlength="5" minlength="5">
        </div>
        <label id="ppo_no-error" class="error mt-2 text-danger" for="ppo_no"></label>
      </div>
      <div class="form-group">
        <label>Employee Aadhaar No</label>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-card-account-details-outline text-primary"></i>
            </span> 
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 only_number" id="employee_aadhaar_no" name="employee_aadhaar_no" placeholder="Aadhaar No" maxlength="12">
        </div>
        <label id="employee_aadhaar_no-error" class="error mt-2 text-danger" for="employee_aadhaar_no"></label>
      </div>
      <div class="form-group">
        <label>Person Name <span class="span-red">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-account-outline text-primary"></i>
            </span> 
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 text-uppercase alpha" id="person_name" name="person_name" maxlength="50" placeholder="Person Name">
        </div>
        <label id="person_name-error" class="error mt-2 text-danger" for="person_name"></label>
      </div>
      <div class="form-group">
        <label>Person Aadhaar No <span class="span-red">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-card-account-details-outline text-primary"></i>
            </span> 
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 numbersOnly only_number" id="nominee_aadhaar_no" name="nominee_aadhaar_no" placeholder="Aadhaar No" maxlength="12">
        </div>
        <label id="nominee_aadhaar_no-error" class="error mt-2 text-danger" for="nominee_aadhaar_no"></label>
      </div>
      <div class="form-group">
        <label>Person Mobile No. <span class="span-red">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-cellphone text-primary"></i>
            </span>
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 numbersOnly only_number" id="mobile_no" name="mobile_no" placeholder="Mobile No" maxlength="10">                       
        </div>
        <label id="mobile_no-error" class="error mt-2 text-danger" for="mobile_no"></label>
      </div>
      <div class="form-group captcha">
        <label for="captcha_image">Captcha <i id="reload">↻</i></label>
        <div class="input-group">
          <span>{!! captcha_img() !!}</span>
        </div>
      </div>
      <div class="form-group">
        <label for="captcha_value">Captcha Value<span class="span-red">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-lock-outline text-primary"></i>
            </span>
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 anch text-lowercase" placeholder="Captcha Value" name="captcha_value" id="captcha_value" maxlength="4">
        </div>
        <label id="captcha_value-error" class="error text-danger" for="captcha_value"></label>
      </div>
      <div class="mt-3">
         <!-- <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" href="otp.html">SIGN UP</a> -->
        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >REGISTER</button>
        
      </div>
      <div class="text-center mt-4 font-weight-light">
        Already have an account? <a href="{{route('nominee_login')}}" class="text-primary">Login</a>
      </div>
    </form>
  </div>

</div>
@endsection

@section('page-script')
<script type="text/javascript">
    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: '{{route("reloadcaptcha")}}',
            success: function (data) {
                $(".captcha span").html(data.captcha);
                $("#captcha_value").val("");
            }
        });
    });
</script>
<script type="text/javascript">
  // Extraordinary gazette form submission validation
  $(document).ready(function(){
    $('.ppoNo').hide();
    $('input[type="radio"]').click(function() {
    //debugger;
    if ($(this).is(':checked') && $(this).val() == 'ppo_no') {
        //debugger
        $('.ppoNo').show();
        $('.employeeCode').hide();
        $('#ppo_no').val('');
        $('#employee_code').val('');
    } else {
        $('.ppoNo').hide();
        $('.employeeCode').show();

        $('#ppo_no').val('');
        $('#employee_code').val('');
    }
    });

    // validation for Password Policy- 1 block letter,1 small letter , 1 digits and 6 to 16 length
    $.validator.addMethod("passwordPolicy", function (value, element) {
          return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/.test(value);
    }, "Please enter the password in correct format");

  
    $("#nominee_register_form").validate({
      onkeyup: false,
      rules: {
        person_name:{
          required: {depends:function(){
                        $(this).val($.trim($(this).val()));
                        return true; }
                    },
          minlength: 5,
          maxlength: 50,
        },
        employee_code: {
           required: true,
           minlength: 5,
           maxlength: 5,
        },
        ppo_no: {
           required: true,
           minlength: 5,
           maxlength: 5,
        },
        employee_aadhaar_no: {
          minlength: 12,
          maxlength: 12
        },
        nominee_aadhaar_no: {
          required: true,
          minlength: 12,
          maxlength: 12,
          remote:{
              url:'{{ route("validate_aadhar_number") }}',
              type:"post",
              data:{
                  '_token': function() {
                     return '{{ csrf_token() }}';
                  }
              }
          },
        },
        mobile_no: {
          required: true,
          minlength: 10,
          maxlength: 10,
          remote:{
              url:'{{ route("validate_mobile_number") }}',
              type:"post",
              data:{
                  '_token': function() {
                     return '{{ csrf_token() }}';
                  }
              }
          },
        },
        captcha_value: {
           required: true,
           maxlength: 4,
        },
      },
      messages: {
        person_name: {
           required: 'Please enter person name',
           minlength: 'Person name should be minimum 5 characters',
           maxlength: 'Person name cannot be more than 50 characters'
        },
        employee_code: {
           required: 'Please enter employee code',
           minlength: 'Employee code should be minimum 5 digits',
           maxlength: 'Employee code maximum 5 digits'
        },
        ppo_no: {
           required: 'Please enter PPO No',
           minlength: 'PPO No should be minimum 5 digits',
           maxlength: 'PPO No maximum 5 digits'
        },
        employee_aadhaar_no: {
          required:  'Please enter Aadhaar No',
          minlength: 'Aadhaar No should be minimum 12 digits',
          maxlength: 'Aadhaar No should be maximum 12 digits',
        },
        nominee_aadhaar_no: {
          required: 'Please enter Aadhaar No',
          minlength: 'Person Aadhaar No should be minimum 12 digits',
          maxlength: 'Person Aadhaar No should be maximum 12 digits', 
          remote: 'Aadhaar No already exists',                        
        },
        mobile_no: {
          required: 'Please enter Mobile No',
          minlength: 'Person Mobile No should be 10 digits',
          maxlength: 'Person Mobile No should be 10 digits',
          remote: 'Mobile no already exits',
        },
        captcha_value: {
          required: "Please enter captcha value",
          maxlength: "Captcha value must be 4 characters"
        },        
      },
      submitHandler: function(form, event) { 
        event.preventDefault();
        //$('.loader_div').show();
        $('.page-loader').addClass('d-flex');
        var formData = new FormData(form);
        
        $.ajax({
            type:'POST',
            url:'{{ route("nominee_registration_form_submit") }}',
            data: formData,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function(response) {
              $.ajax({
                  type: 'GET',
                  url: '{{route("reloadcaptcha")}}',
                  success: function (data) {
                      $(".captcha span").html(data.captcha);
                      $("#captcha_value").val("");
                  }
              });
              $('.page-loader').removeClass('d-flex');
              //console.log(response.error);
              if(response.error){
                for (i in response.error) {
                    var element = $('#' + i);
                    var id = response.error[i].id;
                    var eValue = response.error[i].eValue;
                    $("#"+id).show();
                    $("#"+id).html(eValue);
                }
              }else{
                location.href = "{{route('nominee_registration_verify_otp')}}";
              }              
            }
        });
      },
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger');
        $(element).addClass('form-control-danger');
      },
   });
  

});  
</script>
@endsection