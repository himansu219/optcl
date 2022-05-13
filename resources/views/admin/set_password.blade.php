@extends('admin.layout.landing_page_layout')
@section('title', 'OPTCL')
@section('section_content')

<style type="text/css">
	.input-group {
		border: #243944;
  }
  .fa-info-circle:before {
    content: "\f05a";
    margin-right: -15px;
}
	
</style>
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo">
                <img src="{{ url('public')}}/images/logo.png" alt="logo" style="width: 75px; height: 90px;"/>
              </div>
              <h4>OPTCL Pension Portal</h4>
              <h6 class="font-weight-light">Set User Password</h6>
              @if(Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
              @endif
              @if(Session::has('success'))
                  <div class="alert alert-success">{{ Session::get('success') }}</div>
              @endif
              <span class="text-danger">{{--session('msg')--}}</span>
              <form class="pt-3" id="set_password_form" action="{{URL('verify_set_password')}}" method="post">
                @csrf
              <input type="hidden" name="verification_code" value="{{ $id }}">
                <div class="form-group">
                    <label for="exampleInputPassword">Password<span class="text-danger">*</span></label>
                    <div class="input-group">
                      <div class="input-group-prepend bg-transparent">
                        <span class="input-group-text bg-transparent border-right-0">
                         <i class="mdi mdi-lock-outline text-primary"></i>
                        </span>
                      </div>
                      <input type="password" class="form-control form-control-lg border-left-0" id="new_password" name="new_password" placeholder="Password" minlength="6" maxlength="16" autocomplete="off"> 
                      <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" title="Password must have 1 Block letter,1 Small letter and 1 no. Password must be minimum 6 characters long. Password must be maximum 16 characters long."></i>                   
                    </div>
                      <label id="new_password-error" class="error mt-2 text-danger" for="new_password"></label>
                  </div>
                <div class="form-group">
                  <label for="exampleInputPassword">Confirm Password<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                       <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control form-control-lg border-left-0" id="confirm_password" name="confirm_password" placeholder="Confirm Password" minlength="6" maxlength="16" autocomplete="off"> 
                    {{-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" title="Password must have 1 Block letter,1 Small letter and 1 no. Password must be minimum 6 characters long. Password must be maximum 16 characters long."></i>                       --}}
                  </div>
                    <label id="confirm_password-error" class="error mt-2 text-danger" for="confirm_password"></label>
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
                    <input type="text" class="form-control form-control-lg border-left-0 anch" placeholder="Captcha Value" name="captcha_value" id="captcha_value" maxlength="4" autocomplete="off">
                  </div>
                  <label id="captcha_value-error" class="error text-danger" for="captcha_value"></label>
                </div>
                <div class="my-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >Submit</button>
                  
                </div>
                
              </form>

            </div>
          </div>
@endsection
@section('page-script')         
<script type="text/javascript">
  $(document).ready(function() {
    // validation for Password Policy- 1 block letter,1 small letter , 1 digits and 6 to 16 length
    $.validator.addMethod("passwordPolicy", function (value, element) {
        return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/.test(value);
    }, "Please enter the password in correct format");

    $("#set_password_form").validate({
        rules: {
           new_password: {
               required: true,
               minlength: 6,
               maxlength: 16,
               passwordPolicy: true
           },
           confirm_password: {
               required: true,
               minlength: 6,
               maxlength: 16,
               passwordPolicy: true,
               equalTo: "#new_password"
           },
           captcha_value: {
              required: true,
              maxlength: 4,
           },
        },
        messages: {
           new_password: {
               required: "Please enter password",
               minlength: "Your password must be minimum 6 characters long",
               maxlength: "Your password must be maximum 16 characters long"
           },
           confirm_password: {
               required: "Please enter confirm password",
               minlength: "Your password must be minimum 6 characters long",
               maxlength: "Your password must be maximum 16 characters long",
               equalTo:   "Please enter the same password as above"
           },
           captcha_value: {
              required: "Please enter captcha value",
              maxlength: "Captcha value must be 4 characters",
           },
        },
        submitHandler: function(form, event) { 
          event.preventDefault();
          newPassword();
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

