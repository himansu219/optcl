@extends('user.layout.landing_page_layout')
@section('title', 'OPTCL')
@section('section_content')

<div class="col-lg-6 d-flex align-items-center justify-content-center">
  

  {{-- for otp page --}}
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
    
    <form class="pt-3" id="otp_form" action="" method="post" autocomplete="off">
      @csrf
    
     <input type="hidden" name="user_id" id="user_id" value="{{ Session::get('tem_user_id') }}">
      <div class="form-group">
        <label for="exampleInputEmail">Enter OTP </label> <span class="span-red">*</span>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-cellphone text-primary"></i>
            </span>
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 only_number" id="otp" name="otp" placeholder="OTP" minlength="6" maxlength="6">
           
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
      <!-- <div class="my-2 d-flex justify-content-between align-items-center">
        <a href="#" class="auth-link text-black">Resend otp</a>
      </div> -->
      <div class="form-group">
          <span class="text-info"><strong>Note:</strong> Password must contain at least 6 characters, including capital letter, small letter and number.</span>    
      </div>
      <div class="my-3">
        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >SUBMIT</button>
      </div>
      <div class="text-center mt-4 font-weight-light">For resend otp ?
        <a href="{{ route('nominee_resend_otp') }}" class="text-primary">click here</a>
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
                equalTo: "#password"
          },
          captcha_value: {
              required: true,
              maxlength: 4,
          },
       },
       messages: {
            otp: {
                digits:'OTP should be only digits',
                required: 'Please enter your OTP',
                minlength: 'OTP should be minimum 6 digits',
                maxlength: 'OTP should be maximum 6 digits'
            },
            password: {
                required: 'Please enter your password',
                minlength: 'Password must be minimum 6 characters long',
                maxlength: 'Password must be maximum 16 characters long'
            },
            confirm_password: {
                required: "Please enter confirm password",
                minlength: "Password must be at least 6 characters long",
                maxlength: 'Password must be maximum 16 characters long',
                equalTo: "Password and confirm password not matched"
            },
            captcha_value: {
                required: "Please enter captcha value",
                maxlength: "Captcha value must be 4 characters",
            },
      },
       submitHandler: function(form, event) { 
          //debugger;
          event.preventDefault();
          newPassword();
          $('.page-loader').addClass('d-flex');
          var formData = new FormData(form);
          $.ajax({
              type:'POST',
              url:'{{ route("nominee_registration_otp_submission") }}',
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
                if(response.error){
                  $("#password").val('');
                  $("#confirm_password").val('');
                  for (i in response.error) {
                      var element = $('#' + i);
                      var id = response.error[i].id;
                      var eValue = response.error[i].eValue;
                      $("#"+id).show();
                      $("#"+id).html(eValue);
                  }
                }else{
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
        $(element).parent().addClass('has-danger');
        $(element).addClass('form-control-danger');
      }
   });

    function newPassword(){
      var password = $("#password").val();
      var confirm_password = $("#confirm_password").val();
      var nonceValue = 'e17e6cb6267d111285cdbe218bd200eb';
      if(password != ""){
        var encryption = new Encryption();
        var password1 = encryption.encrypt(password, nonceValue);
        var password2 = encryption.encrypt(confirm_password, nonceValue);
        $("#password").val(password1);
        $("#confirm_password").val(password2);
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