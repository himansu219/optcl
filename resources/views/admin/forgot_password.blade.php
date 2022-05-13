@extends('user.layout.landing_page_layout')
@section('title', 'OPTCL')
@section('section_content')
<style type="text/css">
  #otp_div {
    display: none;
  }
  .alert-danger{
    display: none;
  }
  .alert-success{
    display: none;
  }
  .fa-info-circle:before {
    content: "\f05a";
    margin-right: -15px;
}

</style>

{{-- <div class="loader_div"></div> --}}

<div class="col-lg-6 d-flex align-items-center justify-content-center">
  <div class="auth-form-transparent text-left p-3" id="forgot_password_div">
    <div class="brand-logo">
      <img src="{{url('public')}}/images/logo.png" alt="logo" style="width: 75px; height: 90px;"/>
    </div>
    <h4>Pension Portal!</h4>
    <h6 class="font-weight-light">Forgot Password</h6>
        <div class="alert alert-danger"></div>
        <div class="alert alert-success"></div>
    <form class="pt-3" id="forgot_password_form" action="" method="post">
      @csrf
      <div class="form-group">
        <label for="exampleInputEmail">Aadhaar No/Mobile No.</label> <span class="span-red">*</span>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
             <i class="mdi mdi-lock-outline text-primary"></i>
            </span>
          </div>
          <input type="text" class="form-control form-control-lg border-left-0" id="mobile_no" name="mobile_no" placeholder="Enter Aadhaar No/Mobile No " maxlength="12" minlength="10" autocomplete="off">
         </div>
         <label id="mobile_no-error" class="error text-danger" for="mobile_no"></label>
      </div>
      <div class="my-3">
        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="verify_mobile_aadhaar_no">Verify</button>
      </div>
      <div class="text-center mt-4 font-weight-light">
        Already have an account? <a href="{{route('admin_login_form')}}" class="text-primary">Login</a>
      </div>
    </form>
  </div>

</div>
@endsection
@section('page-script')
<script type="text/javascript">
  $(document).ready(function(){
    // aadhaar/mobile no must be number only 
    $('#mobile_no').keyup(function () { 
      this.value = this.value.replace(/[^0-9\.]/g,'');
    });
    //  otp must be number only
    $('#otp').keyup(function () { 
      this.value = this.value.replace(/[^0-9\.]/g,'');
    });
    // for hide the otp form div
    $('#otp_div').hide();
    // for hide the alert messages
    $('.alert-success').hide();
    $('.alert-danger').hide();
    // validation for Password Policy- 1 block letter,1 small letter , 1 digits and 6 to 16 length
    $.validator.addMethod("passwordPolicy", function (value, element) {
            return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/.test(value);
    }, "Please enter the password in correct format");
    // forgot password form submission validation
    $("#forgot_password_form").validate({
        rules: {
            mobile_no: {
                digits: true,
                required: true,
                minlength: 10,
                maxlength: 12
            }
        },
        messages: {
            mobile_no: {
                digits:'Aadhar No/Mobile No should be only digits' ,
                required: 'Please enter Aadhar No/Mobile No',
                minlength: 'Mobile No should be minimum 10 digits',
                maxlength: 'Aadhaar No maximum 12 digits'
            }
        },
        submitHandler: function(form, event) { 
            event.preventDefault();
            $('.page-loader').addClass('d-flex');
            var formData = new FormData(form);          
            $.ajax({
                type:'POST',
                url:'{{ route("admin_verify_mobile_aadhaar_no") }}',
                data: formData,
                dataType: 'JSON',
                processData: false,
                contentType: false,
                success: function(response) {
                  $('.page-loader').removeClass('d-flex');
                  if(response.status == 'false'){
                    $('.alert-danger').html(response.error);
                    $('.alert-danger').show();
                    $('.alert-success').html();
                    // $('#forgot_password_div').show();
                    setTimeout(function(){
                      $('.alert-danger').slideUp(500);
                    }, 3000);
                  }else{
                    // Success
                    // $('#otp_div').show();
                    // $('#user_id').val(response.user_id);
                    // $('.alert-success').html(response.success);
                    // $('.alert-danger').hide();
                    // $('.alert-danger').html();
                    // $('.alert-success').show();
                    // $('#forgot_password_div').hide();
                    location.href = "{{route('set_forgot_password')}}";
                  }
                }
            });
        },
        errorPlacement: function(label, element) {
          label.addClass('mt-2 text-danger');
          label.insertAfter(element);
        },
        highlight: function(element, errorClass) {
            $(element).parent().addClass('has-danger')
            $(element).addClass('form-control-danger')
        }
      });


    });
   </script>
@endsection