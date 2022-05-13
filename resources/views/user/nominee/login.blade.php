@extends('user.layout.landing_page_layout')
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
      <img src="{{url('public')}}/images/logo.png" alt="logo" style="width: 75px; height: 90px;"/>
    </div>
    <h4>Nominee Login</h4>
    <h6 class="font-weight-light"></h6>
    @if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    <form class="pt-3" id="login_form" action="{{route('validate_nominee')}}" method="post">
      @csrf
      <div class="form-group">
        <label for="exampleInputEmail">Aadhaar No/Mobile No<span class="span-red">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-account-outline text-primary"></i>
            </span>
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 anns" id="user_name" name="user_name" placeholder="Aadhaar No/Mobile No" maxlength="12" minlength="10" autocomplete="off">
        </div>
        <label id="user_name-error" class="error text-danger" for="user_name"></label> 
      </div>
      <div class="form-group">
        <label for="exampleInputPassword">Password<span class="span-red">*</span></label> 
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
              <i class="mdi mdi-lock-outline text-primary"></i>
            </span>
          </div>
          <input type="password" class="form-control form-control-lg border-left-0" id="user_password" name="user_password" placeholder="Password" autocomplete="off">
        </div>        
        <label id="user_password-error" class="error text-danger" for="user_password"></label> 
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

      <div class="my-3">
        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >LOGIN</button>
      </div>
      <div class="text-center">
        <a href="{{ route('forgot_nominee_password') }}" class="auth-link text-black" >Forgot password?</a>
      </div>
      <div class="text-center mt-4 font-weight-light">
        Don't have an account? <a href="{{ route('nominee_registration') }}" class="text-primary">Create</a>
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
            url: 'reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
                $("#captcha_value").val("");
            }
        });
    });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#login_form").validate({
      rules: {
          user_name: {
          digits: true,
          required: true,
          minlength: 10,
          maxlength: 12
        },
        user_password: {
          required: true,
        },
        captcha_value: {
            required: true,
            maxlength: 4,
        },
      },
      messages: {
          user_name: {
          digits:'Aadhaar No/Mobile No should be only digits.' ,
          required: 'Please enter Aadhaar No/ Mobile No',
          minlength: 'Mobile No should be minimum 10 digits',
          maxlength: 'Aadhaar No maximum 12 digits'
        },
        user_password: {
           required: 'Please enter password',
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
          /*var formData = new FormData(form);
          //$("#logid").prop('disabled',true);
          $.ajax({
              type:'POST',
              url:'{{ route("validate_user") }}',
              data: formData,
              dataType: 'JSON',
              processData: false,
              contentType: false,
              success: function(response) {
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
                      $.ajax({
                          type: 'GET',
                          url: 'reload-captcha',
                          success: function (data) {
                              $(".captcha span").html(data.captcha);
                          }
                      });
                  }else if(response['loginCheckMessage']){
                    location.href = "{{route('login_form')}}";
                  }else{
                    // Success
                    location.href = "{{route('user_dashboard')}}";
                  }
              }
          });*/
      },
      errorPlacement: function(label, element) {
        label.addClass('text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });

    function newPassword(){
      var password = $("#user_password").val();
      var nonceValue = 'e17e6cb6267d111285cdbe218bd200eb';
      if(password != ""){
        var encryption = new Encryption();
        var password1 = encryption.encrypt(password, nonceValue);
        $("#user_password").val(password1);
      }
      
  }


  });
</script>
@endsection