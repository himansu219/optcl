@extends('user.layout.landing_page_layout')
@section('title', 'OPTCL')
@section('section_content')

<style type="text/css">
  #otp_div {
  display: none;
  }
  /*.alert-danger{
    display: none;
  }
  .alert-success{
    display: none;
  }*/
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
    <h4>Nominee Portal!</h4>
    <h6 class="font-weight-light">Forgot Password</h6>
    @if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
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
          <input type="text" class="form-control form-control-lg border-left-0 only_number" id="mobile_no" name="mobile_no" placeholder="Enter Aadhaar No/Mobile No " maxlength="12" minlength="10" autocomplete="off">
         </div>
         <label id="mobile_no-error" class="error text-danger" for="mobile_no"></label>
         
      </div>
      <div class="my-3">
        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="verify_mobile_aadhaar_no">Verify</button>
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
  $(document).ready(function(){
    // aadhaar/mobile no must be number only 
    $('#mobile_no').keyup(function () { 
      this.value = this.value.replace(/[^0-9\.]/g,'');
    });
    //  otp must be number only
    $('#otp').keyup(function () { 
      this.value = this.value.replace(/[^0-9\.]/g,'');
    });
    // validation for Password Policy- 1 block letter,1 small letter , 1 digits and 6 to 16 length
    $.validator.addMethod("passwordPolicy", function (value, element) {
            return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/.test(value);
        }, "Please enter the password in correct format");
    // for hide the otp form div
    // $('#otp_div').hide();
    // for hide the alert messages
    // $('.alert-success').hide();
    // $('.alert-danger').hide();

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
          //$('.loader_div').show();
          $('.page-loader').addClass('d-flex');
          var formData = new FormData(form);
          var mobile_aadhar_no = $('#mobile_no').val();
          console.log(mobile_aadhar_no);
          
          $.ajax({
              type:'POST',
              url:'{{ route("verify_nominee_mobile_aadhaar_no") }}',
              data: formData,
              dataType: 'JSON',
              processData: false,
              contentType: false,
              success: function(response) {
                //$('.loader_div').hide();
                $('.page-loader').removeClass('d-flex');
                console.log(response);
                  if(response.status == 'false'){
                   location.reload();
                  }else{
                    // Success
                    location.href = "{{route('verify_otp')}}";
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