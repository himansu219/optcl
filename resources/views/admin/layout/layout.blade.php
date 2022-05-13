<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from www.bootstrapdash.com/demo/libertyui/template/demo/vertical-default-light/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 13 Aug 2021 12:04:59 GMT -->

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>OPTCL Pension Portal</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{url('public')}}/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{url('public')}}/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{url('public')}}/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{url('public')}}/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="{{url('public')}}/vendors/feather/feather.css">
    <link rel="stylesheet" href="{{url('public')}}/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- <link rel="stylesheet" href="{{--url('public')--}}/vendors/font-awesome/css/font-awesome.min.css" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <link rel="stylesheet" href="{{url('public')}}/vendors/jquery-bar-rating/fontawesome-stars.css">
    <link rel="stylesheet" href="{{url('public')}}/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="{{url('public')}}/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="{{url('public')}}/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- End plugin css for this page -->
	
	<link rel="stylesheet" href="{{url('public')}}/vendors/jsgrid/jsgrid.min.css">
    <link rel="stylesheet" href="{{url('public')}}/vendors/jsgrid/jsgrid-theme.min.css">
    <!-- inject:css -->
    <link rel="stylesheet" href="{{url('public')}}/css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{url('public')}}/images/logo_1.png" />
    
    <style type="text/css">
    .align_center {
        text-align: center;
        color: royalblue;
    }

    .brand_name {
        color: firebrick !important;
    }

    .step_active {
        background: green !important;
        font-style: bold !important;
        color: #ffffff !important;
        cursor: default !important;
    }

    .step_inactive {
        background: mediumseagreen !important;
        font-style: bold !important;
        color: #ffffff !important;
        cursor: default !important;
    }

    .highlight_link {
        font-weight: bold !important;
        color: #2596be !important;
    }

    .portal_brand {
        font-weight: bold;
    }

    .text-center-normal {
        font-weight: normal;
        text-align: center;
    }

    .brand_logo {
        border-radius: 0 !important;
    }

    .fetch_emp_info_btn {
        width: 80px;
        height: 40px;
        margin-top: 20px;
    }
    .switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 22px;
  }
  
  .switch input { 
    opacity: 0;
    width: 0;
    height: 0;
  }
  
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 6px;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }
  
  .slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 17px;
    left: 1px;
    bottom: 2px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }
  
  input:checked + .slider {
    background-color: #2196F3;
  }
  
  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }
  
  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }
  
  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }
  
  .slider.round:before {
    border-radius: 50%;
  } 

  .sub-sub-menu {
      padding-left: 17px;
      margin-bottom: 0px;
  }
  #sidebar .nav > .nav-item .nav-link {
    position: relative;
}
  #sidebar .nav .nav-item > .nav-link.dropdown:after {
    content: "\e604";
    font-family: 'simple-line-icons';
    position: absolute;
    right: 15px;
    color: #464de4;
    font-weight: bold;
    top: 50%;
    font-size: 10px;
    transform: translateY(-50%);
}
.sidebar .nav .nav-item .nav-link {
    white-space: initial;
    line-height: 21px !important;
}
.sidebar .nav.sub-sub-menu .nav-item .nav-link:before {
    top: 18px;
}



.actionIcons {
    margin-left: -8px;
} 

.table td, .jsgrid .jsgrid-table td {
    font-size: 0.9rem;
    
}

/* .sidebar .nav .nav-item .nav-link {
    padding: 1rem 1.875rem 1rem 1.275rem;
} */
    
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
                <a class="navbar-brand brand-logo brand_name" href="{{ url('/') }}">{{Auth::user()->first_name." ".Auth::user()->last_name}}</a>
                <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}"><img src="{{url('public')}}/images/profile.png" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                @php 
                    $user_type = Auth::user()->user_type;
                    if($user_type == 4) {
                        $designation_id = Auth::user()->designation_id;
                        $designation = DB::table('optcl_user_designation_master')->where('id', $designation_id)->first();
                        $designation_name = $designation->designation_name;
                        $system_user_role = Auth::user()->system_user_role;
                        $unit_name = '';

                        if($system_user_role == 1){
                            // OPTCL unit id value
                            $optcl_unit_id = Auth::user()->optcl_unit_id;
                            $unitDetails = DB::table('optcl_unit_master')->where('id', $optcl_unit_id)->where('status', 1)->where('deleted', 0)->first();

                            $roleDetails = DB::table('optcl_user_role_master')->where('id', 1)->where('status', 1)->where('deleted', 0)->first();

                            $unit_name = '(' . $designation_name . ' - ' . $unitDetails->unit_name  . ')';
                        } else if($system_user_role == 2) {
                            $roleDetails = DB::table('optcl_user_role_master')->where('id', 2)->where('status', 1)->where('deleted', 0)->first();
                            $unit_name = ' - '.$roleDetails->type_name.' ('.$designation_name.')';
                        }
                        
                        $role = DB::table('optcl_user_role_master')->where('id', $designation->user_role_id)->first();
                        $level = '';
                        if(!empty($role)) {
                            $level = $role->type_name;
                        }

                    } else {
                        $user_type = DB::table('optcl_user_type')->where('id', $user_type)->first()->user_type;
                        $designation_name = $user_type;
                    }
                @endphp
                @if($user_type == 4)
                <span class="portal_brand">OPTCL Pension Portal {{ $unit_name }}</span>
                @else
                <span class="portal_brand">OPTCL Pension Portal{{' - '.$designation_name}}</span>
                @endif
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="icon-bell mx-0"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <a class="dropdown-item">
                                <p class="mb-0 font-weight-normal float-left">You have 4 new notifications
                                </p>
                                <span class="badge badge-pill badge-warning float-right">View all</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class=" icon-ban mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-medium">Proposal Approved at Division</h6>
                                    <p class="font-weight-light small-text">
                                        Just now
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="icon-cursor-move mx-0"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="icon-envelope mx-0"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                            <div class="dropdown-item">
                                <p class="mb-0 font-weight-normal float-left"><a href="{{URL('admin.logout')}}">Logout</a>
                                </p>
                            </div>
                            <div class="dropdown-divider"></div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_settings-panel.html -->
            <!-- partial -->
            <!-- partial:partials/_sidebar.html -->

         <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <div class="nav-link">
                            <div class="profile-image ">
                                <img src="{{ url('public')}}/images/profile.png" alt="image" class="brand_logo" />
                            </div>
                            <div class="profile-name">
                                <p class="name">
                                </p>
                            </div>
                        </div>
                    </li>                    
                    @if(Auth::user()->user_type == 4 && Auth::user()->is_admin == 1)
                    <!-- Admin Sidebar -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin_dashboard')}}">
                        <i class="icon-menu menu-icon"></i>
                            <span class="menu-title highlight_link">Dashboard</span>
                        </a>
                    </li>            
                    <li class="nav-item">
                        <a class="nav-link dropdown" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Master Management</span>
                        </a>
                        <div class="collapse" id="tables">
                        <ul class="nav flex-column sub-menu">

                            <li class="nav-item">
                                <a class="nav-link dropdown" data-toggle="collapse" href="#tables-1" aria-expanded="false" aria-controls="tables">
                                    <span class="menu-title">Localization</span>
                                </a>
                                <div class="collapse" id="tables-1">
                                    <ul class="nav flex-column sub-sub-menu">
                                        <li class="nav-item"> <a class="nav-link" href="{{route('country_details')}}">Country</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('state_details')}}">State</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('district_details')}}">District</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link dropdown" data-toggle="collapse" href="#tables-2" aria-expanded="false" aria-controls="tables">
                                    <span class="menu-title">Bank Master</span>
                                </a>
                                <div class="collapse" id="tables-2">
                                    <ul class="nav flex-column sub-sub-menu">
                                        <li class="nav-item"> <a class="nav-link" href="{{route('bank_name_details')}}">Bank Name</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('bank_branch_details')}}">Bank Brach</a></li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link dropdown" data-toggle="collapse" href="#tables-3" aria-expanded="false" aria-controls="tables">
                                    <span class="menu-title">Units</span>
                                </a>
                                <div class="collapse" id="tables-3">
                                    <ul class="nav flex-column sub-sub-menu">
                                        <li class="nav-item"> <a class="nav-link" href="{{route('pension_unit_details')}}">Pension Drawable Unit</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('unit_details')}}">OPTCL Unit</a></li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link dropdown" data-toggle="collapse" href="#tables-4" aria-expanded="false" aria-controls="tables">
                                    <span class="menu-title">Relation</span>
                                </a>
                                <div class="collapse" id="tables-4">
                                    <ul class="nav flex-column sub-sub-menu">
                                        <li class="nav-item"> <a class="nav-link" href="{{route('religion_details')}}">Religion</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('nominee_details')}}">Nominee Preference</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('gender_details')}}">Gender</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('relation_details')}}">Relation</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link dropdown" data-toggle="collapse" href="#tables-5" aria-expanded="false" aria-controls="tables">
                                    <span class="menu-title">Designation</span>
                                </a>
                                <div class="collapse" id="tables-5">
                                    <ul class="nav flex-column sub-sub-menu">
                                        <li class="nav-item"> <a class="nav-link" href="{{route('designation_details')}}">Employee Designation</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('user_designation_details')}}">User Designation</a></li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link dropdown" data-toggle="collapse" href="#tables-6" aria-expanded="false" aria-controls="tables">
                                    <span class="menu-title">Calculation</span>
                                </a>
                                <div class="collapse" id="tables-6">
                                    <ul class="nav flex-column sub-sub-menu">
                                        <li class="nav-item"> <a class="nav-link" href="{{route('da_details')}}">DA Rate</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('ti_details')}}">TI Rate</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('rule_details')}}">Calculation Rule</a></li>
                                        <li class="nav-item"> <a class="nav-link" href="{{route('commutation_details')}}">Commutation</a></li>
                                    </ul>
                                </div>
                            </li>
                            
                            {{-- <li class="nav-item"> <a class="nav-link" href="{{route('form16_details')}}">Form 16</a></li> --}}
                            
                        </ul>
                        </div>
                    </li>            
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user_details')}}">
                        <i class="fa fa-user menu-icon"></i>
                            <span class="menu-title highlight_link">User Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('employee_details')}}">
                        <i class="fa fa-users menu-icon"></i>
                            <span class="menu-title highlight_link">Employees</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('form16_details')}}">
                        <i class="icon-target menu-icon"></i>
                            <span class="menu-title highlight_link">Form 16</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('change_password')}}">
                        <i class="fa fa-lock menu-icon"></i>
                            <span class="menu-title highlight_link">Change Password</span>
                        </a>
                    </li>
                    @endif 

                       
                        <!-- <a class="nav-link" href="index.html">
                            <i class="icon-flag menu-icon"></i>
                            <span class="menu-title highlight_link">Check Proposal Status</span>
                        </a>
                        <a class="nav-link" href="index.html">
                            <i class="icon-check menu-icon"></i>
                            <span class="menu-title highlight_link">Update Pension Record</span>
                        </a>
                        <a class="nav-link" href="index.html">
                            <i class="icon-handbag menu-icon"></i>
                            <span class="menu-title highlight_link">View PPO Details</span>
                        </a>
                        <a class="nav-link" href="index.html">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title highlight_link">View Gratuity Details</span>
                        </a>
                        <a class="nav-link" href="index.html">
                            <i class="icon-briefcase menu-icon"></i>
                            <span class="menu-title highlight_link">View Calculation Sheet</span>
                        </a>
                        <a class="nav-link" href="index.html">
                            <i class="icon-handbag menu-icon"></i>
                            <span class="menu-title highlight_link">View Pension Statement</span>
                        </a>
                        <a class="nav-link" href="index.html">
                            <i class="icon-target menu-icon"></i>
                            <span class="menu-title highlight_link">View Form 16</span>
                        </a> -->
                    
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
            
            @section('container')
            @show
              <!-- partial:../../partials/_footer.html -->
              <footer class="footer">
                    <div class="container-fluid clearfix">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2021 <a href="#">OPTCL</a>. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Designed by NIC, Odisha State Center</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ url('public')}}/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ url('public')}}/vendors/select2/select2.min.js"></script>
    <script src="{{ url('public')}}/js/select2.js"></script>
    <script src="{{ url('public')}}/vendors/moment/moment.min.js"></script>
    <script src="{{ url('public')}}/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('public')}}/js/formpickers.js"></script>
    <script src="{{ url('public')}}/js/file-upload.js"></script>
    <script src="{{ url('public')}}/vendors/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{ url('public')}}/js/form-repeater.js"></script>
    <script src="{{ url('public')}}/vendors/jquery-validation/jquery.validate.min.js"></script>

    <script src="{{ url('public')}}/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="{{ url('public')}}/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="{{ url('public')}}/js/data-table.js"></script>

  <script src="{{ url('public')}}/vendors/sweetalert/sweetalert.min.js"></script>
  <script src="{{ url('public')}}/vendors/jquery.avgrund/jquery.avgrund.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="{{ url('public')}}/js/alerts.js"></script>
  <script src="{{ url('public')}}/js/avgrund.js"></script>

  <script src="{{ url('public')}}/js/paginate.js"></script>
  

    @section('page-script')
    @show
   
</body><!-- Mirrored from www.bootstrapdash.com/demo/libertyui/template/demo/vertical-default-light/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 13 Aug 2021 12:05:30 GMT -->

</html>