@extends('user.layout.landing_page_layout')
@section('title', 'OPTCL')
@section('section_content')

<style type="text/css">
  .fa-info-circle:before {
    content: "\f05a";
    margin-right: -15px;
}

</style>
{{-- <div class="loader_div"></div> --}}

<div class="col-lg-6 d-flex align-items-center justify-content-center">
  <div class="auth-form-transparent text-left p-3" id="otp_div">
    <div class="brand-logo">
      <img src="{{url('public')}}/images/logo.png" alt="logo" style="width: 75px; height: 90px;"/>
    </div>

    <h4>Nominee Portal!</h4>
    <h6 class="font-weight-light">Verify OTP</h6>
    @if(Session::has('error'))
      <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    @if(Session::has('success'))
      <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    <form class="pt-3" id="otp_form" action="" method="post">
      @csrf
    
     <input type="hidden" name="user_id" id="user_id" value="">
      <div class="form-group">
        <label for="exampleInputEmail">Enter OTP </label> <span class="span-red">*</span>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-cellphone text-primary"></i>
            </span>
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 only_number" id="otp" name="otp" placeholder="OTP" minlength="6" maxlength="6" autocomplete="off">
           
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
      <div class="form-group password-policy">
        <span class="text-info"><strong>Note:</strong> Password must be 6-16 characters long including at least 1 block letter,1 small letter and 1 numeric value.</span>
      </div>
      <div class="my-3">
        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >SUBMIT</button>
      </div>
      <div class="text-center mt-4 font-weight-light">For resend otp ?
        <a href="javascript:void(0)" class="text-primary" id="Resend_Otp">click here</a>
      </div>
    </form>

  </div>

</div>
@endsection
@section('page-script')
<script type="text/javascript">
  $(document).ready(function(){
    // validation for Password Policy- 1 block letter,1 small letter , 1 digits and 6 to 16 length
    $.validator.addMethod("passwordPolicy", function (value, element) {
            return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/.test(value);
        }, "Weak password given. Please check note.");

    // otp form submission validation
    $("#otp_form").validate({
      rules: {
          otp:{
            digits: true,
            required: true,
            minlength: 5,
            maxlength: 6
          },
          password:{
            required: true,
            minlength: 6,
            maxlength: 16,
            passwordPolicy: true
          },
          confirm_password: {
            required: true,
            minlength: 6,
            maxlength:16,
            equalTo: "#password"
          },
      },
      messages: {
        otp: {
            digits:'OTP should be only digits',
            required: 'Please enter OTP',
            minlength: 'OTP must be minimum 6 digits',
            maxlength: 'OTP must be maximum 6 digits'
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
            equalTo: "Confirm password is not match with new password"
        }
      },
      submitHandler: function(form, event) { 
        event.preventDefault();
        newPassword();
        var formData = new FormData(form);
        var user_id = $('#user_id').val(); 
        var otp = $('#otp').val();
        var password = $('#password').val();
        $.ajax({
            type:'POST',
            url:'{{ route("nominee_verify_otp") }}',
            data: formData,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function(response) {
              console.log(response);
              //debugger;
              if(response.status == "false"){
                location.reload();
              } else {
                // Success
                location.href = "{{route('nominee_login')}}";
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


  function newPassword(){
    var password1 = $("#password").val();
    var password2 = $("#confirm_password").val();
    var nonceValue = 'e17e6cb6267d111285cdbe218bd200eb';
    if(password != ""){
      var encryption = new Encryption();
      var password1d = encryption.encrypt(password1, nonceValue);
      var password2d = encryption.encrypt(password2, nonceValue);
      $("#password").val(password1d);
      $("#confirm_password").val(password2d);
    }
  }

   $('#Resend_Otp').on('click',function(e){
      e.preventDefault();
      //alert();
      //var aa;
      var user_id = $('#user_id').val();
      console.log(user_id);
      $('.page-loader').addClass('d-flex');
      $.post('{{ route("nominee_resend_otp") }}',{
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
                  } else {
                    // Success
                    $('.alert-danger').html();
                    $('.alert-danger').hide();
                    $('.alert-success').html(response.success);
                    $('.alert-success').show();
                  }
      });
    });

  });
   </script>
@endsection