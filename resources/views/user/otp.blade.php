<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from www.bootstrapdash.com/demo/libertyui/template/demo/vertical-default-light/pages/samples/login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 13 Aug 2021 12:06:43 GMT -->
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>OPTCL : Pension Potral</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('vendors/flag-icon-css/css/flag-icon.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />

   <!-- jquery cdn start -->
  <!--  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- jquery cdn end -->

</head>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo">
                <img src="{{ asset('images/logo.png') }}" alt="logo" style="width: 75px; height: 90px;"/>
              </div>
              <h4>Pension Portal!</h4>
              <h6 class="font-weight-light"></h6>

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
                    @error('otp')
                    <span class="error_message">{{$message}}</span>
                    @enderror  
                  </div>
                   
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
                    @error('password')
                    <span class="error_message">{{$message}}</span>
                    @enderror                       
                  </div>
                  
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
                    @error('password')
                    <span class="error_message">{{$message}}</span>
                    @enderror                       
                  </div>
                  
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
          <div class="col-lg-6 login-half-bg d-flex flex-row">
            <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2021  All rights reserved.</p>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{ asset('js/off-canvas.js') }}"></script>
  <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('js/template.js') }}"></script>
  <script src="{{ asset('js/settings.js') }}"></script>
  <script src="{{ asset('js/todolist.js') }}"></script>
  <script src="{{ asset('vendors/jquery-validation/jquery.validate.min.js') }}"></script>
  <!-- endinject -->
</body>
<!-- Mirrored from www.bootstrapdash.com/demo/libertyui/template/demo/vertical-default-light/pages/samples/login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 13 Aug 2021 12:06:43 GMT -->
</html>

<script type="text/javascript">
    $(document).ready(function(){
  $('.password').keyup(function () { 
    this.value = this.value.replace(/^[a-zA-Z0-9]{16,6}$/,'');
  });
  $('#otp').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
  });
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
               digits:'Otp should be Only Digits.' ,
               required: 'Please Enter Your otp.',
               minlength: 'Otp should be minimum 5 digits.',
               maxlength: 'Otp should be maximum 6 digits.'
           },
           password: {
               required: 'Password must have 1 Block letter,1 Small letter and 1 no.',
               minlength: 'Your password must be minimum 6 characters long.',
               maxlength: 'Your password must be maximum 16 characters long.'
           },
          confirm_password: {
          required: "Password must have 1 Block letter,1 Small letter and 1 no.",
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

   </script>


