@extends('user.layout.landing_page_layout')
@section('title', 'OPTCL')
@section('section_content')
<style type="text/css">
  
  
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


<div class="col-lg-6 d-flex align-items-center justify-content-center">
  
  <div class="auth-form-transparent text-left p-3" id="otp_div">
    <div class="brand-logo">
      <img src="{{url('public')}}/images/logo.png" alt="logo" style="width: 75px; height: 90px;"/>
    </div>
    <h4>Pension Portal!</h4>
    <h6 class="font-weight-light">Verify OTP</h6>
        <div class="alert alert-danger"></div>
        <div class="alert alert-success"></div>
    <form class="pt-3" id="otp_form" action="" method="post">
        @csrf
    
        <input type="hidden" name="user_id" id="user_id" value="{{ Session::get('temp_user_id') }}">
        <div class="form-group">
            <label for="exampleInputEmail">Enter OTP </label> <span class="span-red">*</span>
            <div class="input-group">
            <div class="input-group-prepend bg-transparent">
                <span class="input-group-text bg-transparent border-right-0">
                <i class="mdi mdi-cellphone text-primary"></i>
                </span>
            </div>
            <input type="text" class="form-control form-control-lg border-left-0" id="otp" name="otp" placeholder="OTP" minlength="6" maxlength="6" autocomplete="off">
            
            </div>
            <label id="otp-error" class="error text-danger" for="otp"></label>
        </div>

        <div class="form-group">
            <label for="exampleInputPassword">Password</label> <span class="span-red">*</span>
            <div class="input-group">
            <div class="input-group-prepend bg-transparent">
                <span class="input-group-text bg-transparent border-right-0">
                <i class="mdi mdi-lock-outline text-primary"></i>
                </span>
            </div>
            <input type="password" class="form-control form-control-lg border-left-0 password" id="password" name="password" placeholder="Password" minlength="6" maxlength="16">
        </div>       
        <label id="password-error" class="error text-danger" for="password"></label>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword">Confirm Password</label> <span class="span-red">*</span>
            <div class="input-group">
            <div class="input-group-prepend bg-transparent">
                <span class="input-group-text bg-transparent border-right-0">
                <i class="mdi mdi-lock-outline text-primary"></i> 
                </span>
            </div>
            <input type="password" class="form-control form-control-lg border-left-0 password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" minlength="6" maxlength="16">
            </div>
            <label id="confirm_password-error" class="error text-danger" for="confirm_password"></label>
        </div>
        <div class="my-2 d-flex">
          <span class="text-info fs-14"><strong>Note:</strong> Password must contain at least 6 characters, including capital letter, small letter and number.</span>
        </div>
        <div class="my-3">
            
            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >SUBMIT</button>
            <!-- <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="login">VERIFY</a> -->
        </div>
        <div class="text-center mt-4 font-weight-light">For resend otp ?
            <a href="javascript:void(0)" class="text-primary" id="resend_otp">click here</a>
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
    // for hide the alert messages
    $('.alert-success').hide();
    $('.alert-danger').hide();
    // validation for Password Policy- 1 block letter,1 small letter , 1 digits and 6 to 16 length
    $.validator.addMethod("passwordPolicy", function (value, element) {
            return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/.test(value);
        }, "Please enter the password in correct format");

    // otp form submission validation
    $("#otp_form").validate({
       rules: {
           otp: {
               digits: true,
               required: true,
               minlength: 5,
               maxlength: 6
           },
           password:
            {
               required: true,
               minlength: 6,
               maxlength: 16,
               passwordPolicy: true
            },
          confirm_password: {
                required: true,
                minlength: 6,
                maxlength:16,
                passwordPolicy: true,
                equalTo: "#password"
        },
       },
       messages: {
            otp: {
                digits:'OTP should be only digits',
                required: 'Please enter OTP',
                minlength: 'OTP should be minimum 6 digits',
                maxlength: 'OTP should be maximum 6 digits'
            },
            password: {
                required: 'Please enter password',
                minlength: 'Password must be minimum 6 characters long',
                maxlength: 'Password must be maximum 16 characters long'
            },
            confirm_password: {
                required: "Please enter confirm password",
                minlength: "Password must be at least 6 characters long",
                maxlength: 'Password must be maximum 16 characters long',
                equalTo: "Password and confirm password not matched"
            }
      },
       submitHandler: function(form, event) { 
          event.preventDefault();
          newPassword();
          var formData = new FormData(form);
          $.ajax({
              type:'POST',
              url:'{{ route("admin_verify_otp") }}',
              data: formData,
              dataType: 'JSON',
              processData: false,
              contentType: false,
              success: function(response) {
                  if(response.status == "false"){
                    $('.alert-success').html();
                    $('.alert-success').hide();
                    $('.alert-danger').html(response.error);
                    $('.alert-danger').show();
                  } else {
                    // Success
                    $('.alert-danger').html();
                    $('.alert-danger').hide();
                    $('.alert-success').html(response.success);
                    $('.alert-success').show();
                    setTimeout(function(){
                      location.href = "{{route('admin_login_form')}}";
                    }, 3000);
                  }
              }
          });          
      },errorPlacement: function(label, element) {
            label.addClass('mt-2 text-danger');
            label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
            $(element).parent().addClass('has-danger');
            $(element).addClass('form-control-danger');
      }
   });

    $('#resend_otp').on('click',function(e){
      e.preventDefault();
      //alert();
      //var aa;
      var user_id = $('#user_id').val();
      $('.page-loader').addClass('d-flex');
      $.post('{{ route("admin_resend_otp") }}',{
        "_token": "{{ csrf_token() }}",
        "user_id":user_id,
      },function(response){
          $('.page-loader').removeClass('d-flex');
          console.log(response);
          if(response.status == "false"){
                    $('.alert-success').html();
                    $('.alert-success').hide();
                    $('.alert-danger').html(response.error);
                    $('.alert-danger').show();
                    $('#otp').val('');
                    $('#password').val('');
                    $('#confirm_password').val('');
                    setTimeout(function(){
                      $('.alert-danger').slideUp(500);
                    }, 3000);
                  } else {
                    // Success
                    $('.alert-danger').html();
                    $('.alert-danger').hide();
                    $('.alert-success').html(response.success);
                    $('.alert-success').show();
                    setTimeout(function(){
                      $('.alert-success').slideUp(500);
                    }, 3000);
                  }
      });
    });

    function newPassword(){
            var otpValue = $("#otp_value").val();
            var passwordValue = $("#password").val();
            var confirmPassword = $("#confirm_password").val();
            var nonceValue = 'e17e6cb6267d111285cdbe218bd200eb';            
            var encryption = new Encryption();
            var passwordValue1 = encryption.encrypt(passwordValue, nonceValue);
            var confirmPassword1 = encryption.encrypt(confirmPassword, nonceValue);
            $("#password").val(passwordValue1);
            $("#confirm_password").val(confirmPassword1);
            return true;
    }


  });
   </script>
@endsection