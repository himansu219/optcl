<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title')</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{url('public')}}/vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="{{url('public')}}/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="{{url('public')}}/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="{{url('public')}}/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="{{url('public')}}/vendors/feather/feather.css">
  <link rel="stylesheet" href="{{url('public')}}/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="{{url('public')}}/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{url('public')}}/images/logo_1.png" />
  <link rel="stylesheet" href="{{url('public')}}/css/vertical-layout-light/style.css">
</head>
<body>
  <div class="page-loader">
    <div class="circle-loader"></div>
  </div>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          @yield('section_content')
          <div class="col-lg-6 login-half-bg d-flex flex-row">
            <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; {{date('Y')}}  All rights reserved.</p>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{url('public')}}/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{url('public')}}/js/off-canvas.js"></script>
  <script src="{{url('public')}}/js/hoverable-collapse.js"></script>
  <script src="{{url('public')}}/js/template.js"></script>
  <script src="{{url('public')}}/js/settings.js"></script>
  <script src="{{url('public')}}/js/todolist.js"></script>
  <!-- Password Encryption -->
  <script src="{{url('public')}}/js/custom/crypto-js.min.js"></script>
  <script src="{{url('public')}}/js/custom/sha256.js"></script>
  <script src="{{url('public')}}/js/custom/Encryption.js"></script>
  <!-- jQuery Validation -->
  <script src="{{url('public')}}/vendors/jquery-validation/jquery.validate.min.js"></script>
  <script src="{{url('public')}}/js/custom/additional-methods.js"></script>
  <script src="{{url('public')}}/js/custom/custom_script.js"></script>

  <!-- endinject -->
  <script type="text/javascript">
        $(document).ready(function() {
            $(".alert").fadeTo(2000, 500).slideUp(500, function () {
                $(".alert").slideUp(500);
            });
          });
  </script>
  @yield('page-script')
</body>
</html>