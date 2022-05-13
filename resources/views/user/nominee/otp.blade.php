@extends('user.layout.landing_page_layout')
@section('title', 'OPTCL')
@section('section_content')
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo">
                <img src="{{url('public')}}/images/logo.png" alt="logo" style="width: 75px; height: 90px;">
              </div>
              <h4>Verify Otp</h4>
              <h6 class="font-weight-light"></h6>
              @if(Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
              @endif
              @if(Session::has('success'))
                  <div class="alert alert-success">{{ Session::get('success') }}</div>
              @endif
              <form class="pt-3" id="login_form" action="{{URL('otp_verify')}}" method="post">
                @csrf
              
                <input type="hidden" name="user_id" value="{{ $id }}">
                <div class="form-group">
                  <label for="exampleInputEmail">Enter Otp </label> <span class="span-red">*</span>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                       <!--  <i class="mdi mdi-account-outline text-primary"></i> -->
                      </span>
                    </div>
                    <input type="text" class="form-control form-control-lg border-left-0" id="otp" name="otp" placeholder="Please Enter otp here." minlength="5" maxlength="6" autocomplete="off">
                      
                  </div>
                  <label id="otp-error" class="error mt-2 text-danger" for="otp"></label> 
                </div>

                <div class="form-group">
                  <label for="exampleInputPassword">Password</label> <span class="span-red">*</span>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                       <!--  <i class="mdi mdi-lock-outline text-primary"></i> -->
                      </span>
                    </div>
                    <input type="password" class="form-control form-control-lg border-left-0 password" id="password" name="password" placeholder="Password" minlength="6" maxlength="16"> 
                                           
                  </div>
                  <label id="password-error" class="error mt-2 text-danger" for="password"></label>
                </div>

                <div class="form-group">
                  <label for="exampleInputPassword">Confirm Password</label> <span class="span-red">*</span>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                       <!--  <i class="mdi mdi-lock-outline text-primary"></i> -->
                      </span>
                    </div>
                    <input type="password" class="form-control form-control-lg border-left-0 password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" minlength="6" maxlength="16"> 
                                          
                  </div>
                  <label id="confirm_password-error" class="error mt-2 text-danger" for="confirm_password"></label>
                  
                </div>
                <!-- <div class="my-2 d-flex justify-content-between align-items-center">
                  <a href="#" class="auth-link text-black">Resend otp</a>
                </div> -->
                <div class="my-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >SUBMIT</button>
                  <!-- <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="login">VERIFY</a> -->
                </div>
                <div class="text-center mt-4 font-weight-light">For resend otp ?
                  <a href="{{URL('resend_otp', array($id))}}" class="text-primary">click here</a>
                </div>
              </form>

            </div>
          </div>
@endsection
@section('page-script')

<script type="text/javascript">
  $(document).ready(function(){
    $('.password').keyup(function () { 
    this.value = this.value.replace(/^[a-zA-Z0-9]{16,6}$/,'');
    });
   $('#otp').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
   });

   // Extraordinary gazette form submission validation
   $("#login_form").validate({
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
               maxlength: 16
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
               digits:'Otp should be only digits.' ,
               required: 'Please enter your otp.',
               minlength: 'Otp should be minimum 5 digits.',
               maxlength: 'Otp should be maximum 6 digits.'
           },
           password: {
               required: 'Password must have 1 block letter,1 small letter and 1 no.',
               minlength: 'Your password must be minimum 6 characters long.',
               maxlength: 'Your password must be maximum 16 characters long.'
           },
          confirm_password: {
          required: "Password must have 1 block letter,1 small letter and 1 no.",
          minlength: "Your password must be at least 6 characters long",
          equalTo: "Please enter the same password as above"
        }
         
          
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


