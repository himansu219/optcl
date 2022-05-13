@extends('admin.layout.landing_page_layout')
@section('title', 'OPTCL')
@section('section_content')

<style type="text/css">
	.input-group {
		border: #243944;
	}
</style>
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo">
                <img src="{{ url('public')}}/images/logo.png" alt="logo" style="width: 75px; height: 90px;"/>
              </div>
              <h4>OPTCL User Login</h4>
              <h6 class="font-weight-light"></h6>
              @if(Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
              @endif
              @if(Session::has('success'))
                  <div class="alert alert-success">{{ Session::get('success') }}</div>
              @endif
              <span class="text-danger">{{--session('msg')--}}</span>
              <form class="pt-3" id="login_form" action="{{URL('admin_login_submit')}}" method="post">
                @csrf

                <div class="form-group">
                  <label for="exampleInputEmail">Mobile No. <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                      <i class="mdi mdi-cellphone text-primary"></i> 
                      </span>
                    </div>
                    <input type="text" class="form-control form-control-lg border-left-0 only_number" id="user_id" name="user_id" placeholder="Enter Mobile No." maxlength="10" minlength="10" autocomplete="off">
                    @error('user_id')
                    <span class="error_message">{{$message}}</span>
                    @enderror  
                  </div>
                   <label id="user_id-error" class="error mt-2 text-danger" for="user_id"></label>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword">Password<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                       <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control form-control-lg border-left-0" id="password" name="password" placeholder="Password" minlength="6" maxlength="16" autocomplete="off"> 
                    @error('password')
                    <span class="error_message">{{$message}}</span>
                    @enderror                       
                  </div>
                    <label id="password-error" class="error mt-2 text-danger" for="password"></label>
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
                    <input type="text" class="form-control form-control-lg border-left-0 anch text-lowercase" placeholder="Captcha Value" name="captcha_value" id="captcha_value" maxlength="4" autocomplete="off">
                  </div>
                  <label id="captcha_value-error" class="error text-danger" for="captcha_value"></label>
                </div>
                <!-- <div class="my-2 d-flex justify-content-between align-items-center">
                  <a href="" class="auth-link text-black" >Forgot password?</a>
                </div> -->
                <div class="my-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >LOGIN</button>
                  <!-- <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="login">LOGIN</a> -->
                </div>
                <div class="text-center">
                  <a href="{{ route('admin_forgot_password') }}" class="auth-link text-black" >Forgot password?</a>
                </div>
              </form>

            </div>
          </div>
@endsection
@section('page-script')         
<script type="text/javascript">
  $(document).ready(function() {

    $.validator.addMethod("onlyNumber", function (value, element) {
        return this.optional(element) || /^[0-9\s-]*$/.test(value);
    }, "Please use only numbers"); 

    $("#login_form").validate({
        rules: {
           user_id: {
               required: true,
               onlyNumber: true,
               minlength: 10,
               maxlength: 10
           },
           password: {
               required: true,
               minlength: 6,
               maxlength: 16
           },
           captcha_value: {
              required: true,
              maxlength: 4,
           },
        },
        messages: {
           user_id: {
               required: 'Please enter mobile no.',
               minlength: 'Mobile No. should be minimum 10 digits.',
               maxlength: 'Mobile No. maximum 10 digits.'
           },
           password: {
               required: 'Please enter a password.',
               minlength: 'Your password must be minimum 6 characters long.',
               maxlength: 'Your password must be maximum 16 characters long.'
           },
           captcha_value: {
              required: "Please enter captcha value",
              maxlength: "Captcha value must be 4 characters",
           },
        },
        submitHandler: function(form, event) { 
          event.preventDefault();
          newPassword()
          form.submit();
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

    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });

    function newPassword(){
      var password = $("#password").val();
      var nonceValue = 'e17e6cb6267d111285cdbe218bd200eb';
      if(password != ""){
        var encryption = new Encryption();
        var password1 = encryption.encrypt(password, nonceValue);
        $("#password").val(password1);
      }   
    }
  });
</script>
@endsection

