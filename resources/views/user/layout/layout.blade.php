<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
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
	<link rel="stylesheet" href="{{url('public')}}/css/croppie.css">
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
             background: #d9f1fd !important;
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
            font-weight: 500 !important;
            color: #5c5c5c !important;
        }
        .step_active .highlight_link {
            font-weight: bold !important;
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
            width: 100px;
            height: 40px;
            margin-top: 23px;
        }
    </style>

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
    <div class="page-loader">
        <div class="circle-loader"></div>
    </div>

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
                        } else if($system_user_role == 3) {
                            $roleDetails = DB::table('optcl_user_role_master')->where('id', 3)->where('status', 1)->where('deleted', 0)->first();
                            $unit_name = ' - '.$roleDetails->type_name.' ('.$designation_name.')';
                        } else if($system_user_role == 4) {
                            $pension_unit_id = Auth::user()->pension_unit_id;
                            $unitDetails = DB::table('optcl_pension_unit_master')->where('id', $pension_unit_id)->where('status', 1)->where('deleted', 0)->first();
                            $roleDetails = DB::table('optcl_user_role_master')->where('id', 4)->where('status', 1)->where('deleted', 0)->first();
                            $unit_name = '(' . $designation_name . ' - ' . $unitDetails->pension_unit_name  . ')';
                        }else if($system_user_role == 5) {
                            $designation_id = Auth::user()->designation_id;
                            $degDetails = DB::table('optcl_user_designation_master')->where('id', $designation_id)->where('status', 1)->where('deleted', 0)->first();
                            $unit_name = '(' . $designation_name. ')';
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
                        @php
                            $application_details = DB::table('optcl_pension_application_form')->where('user_id', Auth::user()->id)->first();
                            $notifications = DB::table('optcl_user_notification')->where('view_status', 0)->where('user_id', Auth::user()->id)->orderBy('id','DESC')->limit(15)->get();
                            $totNotification = $notifications->count();
                        @endphp
                        <span class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="icon-bell mx-0"></i>
                            @if($totNotification > 0)<span class="count"></span>@endif
                        </span>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list noti-div" aria-labelledby="notificationDropdown">

                            
                            <span class="dropdown-item" style="padding: 11px 20px!important;">
                                <p class="mb-0 font-weight-normal float-left">You have {{$totNotification}} new notification(s)
                                </p>
                                <a href="{{ route('notifications') }}" class="badge badge-pill badge-warning float-right">View all</a>
                            </span>
                            @if($totNotification > 0)
                                @foreach($notifications as $notification)
                                    <div class="dropdown-divider"></div>
                                    
                                        <div class="preview-item-content" style="padding: 17px 20px 0;">
                                            <!-- Bold - font-weight-medium -->
                                            <h6 class="preview-subject @if($notification->view_status != 0) font-weight-medium @endif" style="line-height: 18px; font-size: 14px;">{!! $notification->status_message !!}</h6>
                                            <p class="font-weight-light small-text">{{ date('d F Y h:i A', strtotime($notification->created_at)) }}</p>
                                        </div>
                                @endforeach
                            @else
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-item-content">
                                        <!-- Bold - font-weight-medium -->
                                        <h6 class="preview-subject text-center">No data found</h6>
                                    </div>
                                </a>
                            @endif

                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="icon-envelope mx-0"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                            <div class="dropdown-item">
                                <p class="mb-0 font-weight-normal float-left"><a href="{{route('user_logout')}}">Logout</a>
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
                                <img src="{{url('public')}}/images/profile.png" alt="image" class="brand_logo" />
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
                                                <li class="nav-item"> <a class="nav-link" href="{{route('bank_branch_details')}}">Bank Branch</a></li>
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
                    <!-- Employee user Sidebar -->
					@if(Auth::user()->user_type == 1)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user_dashboard') }}">
                            <i class="icon-menu menu-icon"></i>
                                <span class="menu-title highlight_link">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('check_employee') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">Apply Pension Application</span>
                            </a>
                        </li>
                        @if($application_details)
                            @if(in_array($application_details->application_status_id, [3,8,11,30]))
                                <li class="nav-item"> 
                                    <a class="nav-link" href="{{ route('resubmit_page') }}">
                                        <i class="icon-cup menu-icon"></i>
                                        <span class="menu-title highlight_link">Resubmit Pension Details</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('view_details') }}">
                                <i class="icon-flag menu-icon"></i>
                                <span class="menu-title highlight_link">Check Application Status</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('update_pension_record') }}">
                                <i class="icon-check menu-icon"></i>
                                <span class="menu-title highlight_link">Update Pension Record</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="#">
                                <i class="icon-handbag menu-icon"></i>
                                <span class="menu-title highlight_link">View PPO Details</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="#">
                                <i class="icon-grid menu-icon"></i>
                                <span class="menu-title highlight_link">View Gratuity Details</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="#">
                                <i class="icon-briefcase menu-icon"></i>
                                <span class="menu-title highlight_link">View Calculation Sheet</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="#">
                                <i class="icon-handbag menu-icon"></i>
                                <span class="menu-title highlight_link">View Pension Statement</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{route('form16_show')}}">
                                <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">View Form 16</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{route('tax_declaration')}}">
                                <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Tax Declaration</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
					@endif
					
                    <!-- OPTCL Unit Dealing Assistant -->
					@if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 2)
    					<li class="nav-item">
    						<a class="nav-link" href="{{ route('user_dashboard') }}">
    						<i class="icon-menu menu-icon"></i>
    							<span class="menu-title highlight_link">Dashboard</span>
    						</a>
    					</li>
    					<li class="nav-item"> 
    						<a class="nav-link" href="{{ route('dealing_applications') }}">
    							<i class="icon-cup menu-icon"></i>
    							<span class="menu-title highlight_link">View Applications</span>
    						</a>
    					</li>

                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('view_applicant') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">View Applicant</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('add_applicant') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">Add Applicant</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('existing_pension_list') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">Existing Applications</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
					@endif

                    <!-- OPTCL Unit Finance Executive -->
                    @if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 3)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user_dashboard') }}">
                            <i class="icon-menu menu-icon"></i>
                                <span class="menu-title highlight_link">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('ddo_applications') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">View Applications</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                  		 
                    @endif
                    
                    <!-- OPTCL Unit  Unit Head(DDO, Ass. General Manager) -->
                    @if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 4)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user_dashboard') }}">
                            <i class="icon-menu menu-icon"></i>
                                <span class="menu-title highlight_link">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('unit_head_applications') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">View Applications</span>
                            </a>
                        </li>
                  		<li class="nav-item"> 
                            <a class="nav-link" href="{{route('unit_head_tax_declaration')}}">
                                <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Tax Declaration</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                    @endif


                    <!-- HR Wing Dealing Assistant user Sidebar -->
                    @if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 5)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user_dashboard') }}">
                            <i class="icon-menu menu-icon"></i>
                                <span class="menu-title highlight_link">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('hr_dealing_applications') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">View Applications</span>
                            </a>
                        </li>
                  		<li class="nav-item"> 
                            <a class="nav-link" href="{{route('dealing_assistant_tax_declaration')}}">
                                <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Tax Declaration</span>
                            </a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 7)
                    <!-- HR Wing Sanction Authority user Sidebar -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user_dashboard') }}">
                            <i class="icon-menu menu-icon"></i>
                                <span class="menu-title highlight_link">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('hr_sanction_authority_applications') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">View Applications</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('assignments') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">Assignments</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                    @endif                    

                    @if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 6)
                          <li class="nav-item">
                              <a class="nav-link" href="{{ route('user_dashboard') }}">
                              <i class="icon-menu menu-icon"></i>
                                  <span class="menu-title highlight_link">Dashboard</span>
                              </a>
                          </li>
                          <li class="nav-item"> 
                              <a class="nav-link" href="{{ route('hr_executive_applications') }}">
                                  <i class="icon-cup menu-icon"></i>
                                  <span class="menu-title highlight_link">View Applications</span>
                              </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 8)
                          <li class="nav-item">
                              <a class="nav-link" href="{{ route('user_dashboard') }}">
                              <i class="icon-menu menu-icon"></i>
                                  <span class="menu-title highlight_link">Dashboard</span>
                              </a>
                          </li>
                          <li class="nav-item"> 
                              <a class="nav-link" href="{{ route('initiator_applications') }}">
                                  <i class="icon-cup menu-icon"></i>
                                  <span class="menu-title highlight_link">View Applications</span>
                              </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                    @endif
                    
                    @if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 9)
                          <li class="nav-item">
                              <a class="nav-link" href="{{ route('user_dashboard') }}">
                              <i class="icon-menu menu-icon"></i>
                                  <span class="menu-title highlight_link">Dashboard</span>
                              </a>
                          </li>
                          <li class="nav-item"> 
                              <a class="nav-link" href="{{ route('verifier_applications') }}">
                                  <i class="icon-cup menu-icon"></i>
                                  <span class="menu-title highlight_link">View Applications</span>
                              </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 10)
                          <li class="nav-item">
                              <a class="nav-link" href="{{ route('user_dashboard') }}">
                              <i class="icon-menu menu-icon"></i>
                                  <span class="menu-title highlight_link">Dashboard</span>
                              </a>
                          </li>
                          <li class="nav-item"> 
                              <a class="nav-link" href="{{ route('approver_applications') }}">
                                  <i class="icon-cup menu-icon"></i>
                                  <span class="menu-title highlight_link">View Applications</span>
                              </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                    @endif
                    <!-- Billing Officer -->
                    @if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 11)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user_dashboard') }}">
                            <i class="icon-menu menu-icon"></i>
                                <span class="menu-title highlight_link">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('pension_unit_applications') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">View Applications</span>
                            </a>
                        </li>                           
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{route('billing_officer_approval_list_list')}}">
                                <i class="fa fa-database menu-icon"></i>
                                <span class="menu-title highlight_link">Monthly Changed Data</span>
                            </a>
                        </li>                           
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{route('billing_officer_list')}}">
                                <i class="fa fa-database menu-icon" ></i>
                                <span class="menu-title highlight_link">Bill Generation</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{route('pension_unit_life_certificate_list_page')}}">
                                <i class="fa fa-database menu-icon" ></i>
                                <span class="menu-title highlight_link">Life Certificate</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{route('billing_officer_arrears')}}">
                                <i class="fa fa-database menu-icon" ></i>
                                <span class="menu-title highlight_link">Arrears</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                                <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                    @endif
                    
                    @if(Auth::user()->user_type == 4 && Auth::user()->designation_id == 12)
                        <!-- Pension Unit Unit Head user Sidebar -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user_dashboard') }}">
                            <i class="icon-menu menu-icon"></i>
                                <span class="menu-title highlight_link">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('pension_unit_applications') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">View Applications</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link dropdown" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title highlight_link">Physical Verification</span>
                            </a>
                            <div class="collapse" id="tables">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="{{ route('pending_physical_verification') }}">Pending</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="{{ route('district_details') }}">Completed</a></li>
                                </ul>
                            </div>                           
                        </li> 
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{route('monthly_changed_data_list')}}">
                                <i class="fa fa-database menu-icon"></i>
                                <span class="menu-title highlight_link">Monthly Changed Data</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link dropdown" data-toggle="collapse" href="#tables2" aria-expanded="false" aria-controls="tables">
                                <i class="fa fa-database menu-icon"></i>
                                <span class="menu-title highlight_link">Update Pension Record</span>
                            </a>
                            <div class="collapse" id="tables2">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> 
                                        <a class="nav-link" href="{{ route('pension_unit_additional_family_pensioner') }}">Addition of Pensioner New Pensioner</a>
                                    </li>
                                    <li class="nav-item"> 
                                        <a class="nav-link" href="{{ route('pension_unit_revision_basic_pension') }}">Revision of Basic Pension</a>
                                    </li>
                                    <li class="nav-item"> 
                                        <a class="nav-link" href="{{ route('pension_unit_restoration_commutation') }}">Restoration of Commutation</a>
                                    </li>
                                    <li class="nav-item"> 
                                        <a class="nav-link" href="{{ route('pension_unit_additional_pension_listing') }}">Additional Pension</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('pension_unit_bank_change_list') }}">Bank Change</a>
                                    </li>
                                    <li class="nav-item"> 
                                        <a class="nav-link" href="{{ route('pension_unit_unit_change_receiving_unit_only_list') }}">Unit Change for Receiving Unit (Only)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('pension_unit_dropped_case_death_case_list') }}">Dropped Case/Death Case</a>
                                    </li>
                                    <li class="nav-item"> 
                                        <a class="nav-link" href="{{ route('pension_unit_tds_information_list_page') }}">TDS Information</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item"> 
                              <a class="nav-link" href="{{route('existing_pension_list')}}">
                                  <i class="fa fa-database menu-icon"></i>
                                  <span class="menu-title highlight_link">Existing Pensioner</span>
                              </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                    @endif
                    
                    <!-- Nominee user Sidebar -->
					@if(Auth::user()->user_type == 2)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user_dashboard') }}">
                            <i class="icon-menu menu-icon"></i>
                                <span class="menu-title highlight_link">Dashboard</span>
                            </a>
                        </li>
                        @if(!$application_details)
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('nominee_application_form') }}">
                                <i class="icon-cup menu-icon"></i>
                                <span class="menu-title highlight_link">Apply Pension Application</span>
                            </a>
                        </li>
                        @endif
                        @if($application_details)
                            @if(in_array($application_details->application_status_id, [3,8,11,30]))
                                <li class="nav-item"> 
                                    <a class="nav-link" href="{{ route('family_pensioner_form_page') }}">
                                        <i class="icon-cup menu-icon"></i>
                                        <span class="menu-title highlight_link">Resubmit Pension Details</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('nominee_application_view_details') }}">
                                <i class="icon-flag menu-icon"></i>
                                <span class="menu-title highlight_link">Check Application Status</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('update_pension_record') }}">
                                <i class="icon-check menu-icon"></i>
                                <span class="menu-title highlight_link">Update Pension Record</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="#">
                                <i class="icon-handbag menu-icon"></i>
                                <span class="menu-title highlight_link">View PPO Details</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="#">
                                <i class="icon-grid menu-icon"></i>
                                <span class="menu-title highlight_link">View Gratuity Details</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="#">
                                <i class="icon-briefcase menu-icon"></i>
                                <span class="menu-title highlight_link">View Calculation Sheet</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="#">
                                <i class="icon-handbag menu-icon"></i>
                                <span class="menu-title highlight_link">View Pension Statement</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{route('form16_show')}}">
                                <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">View Form 16</span>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{route('tax_declaration')}}">
                                <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Tax Declaration</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('user_change_password')}}">
                            <i class="icon-target menu-icon"></i>
                                <span class="menu-title highlight_link">Change Password</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
            
                @yield('section_content')
                <!-- partial:../../partials/_footer.html -->

                <div class="modal fade" id="img_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">View Image</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <img id="img-show" width="450" height="250">
                            </div>
                        </div>
                    </div>
                </div>
				
				<div class="modal fade" id="pdf_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pdf-label">View PDF</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" style="height: 400px;">
                                <object data="" id="pdf-obj" type="application/pdf" width="100%" height="100%"></object>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer">
                    <div class="container-fluid clearfix">
                        <span class="d-block text-center text-sm-left d-sm-inline-block">© Copyright {{date('Y')}} | OPTCL. All Rights Reserved.</span>
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
    <script src="{{url('public')}}/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <script src="{{url('public')}}/vendors/chart.js/Chart.min.js"></script>
    <script src="{{url('public')}}/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="{{url('public')}}/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script> 
    <script src="{{url('public')}}/vendors/jquery-bar-rating/jquery.barrating.min.js"></script>
    <script src="{{url('public')}}/vendors/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="{{url('public')}}/vendors/raphael/raphael.min.js"></script>
    <script src="{{url('public')}}/vendors/morris.js/morris.min.js"></script>
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{url('public')}}/vendors/select2/select2.min.js"></script>
    <script src="{{url('public')}}/js/select2.js"></script>
    <script src="{{url('public')}}/vendors/moment/moment.min.js"></script>
    <script src="{{url('public')}}/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="{{url('public')}}/js/formpickers.js"></script>
    <script src="{{url('public')}}/js/file-upload.js"></script>
    <script src="{{url('public')}}/vendors/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{url('public')}}/js/form-repeater.js"></script>
    <script src="{{url('public')}}/vendors/jquery-validation/jquery.validate.min.js"></script>
    <script src="{{url('public')}}/js/custom/additional-methods.js"></script>
    <script src="{{url('public')}}/js/custom/custom_script.js"></script>
	
	
	<script src="{{url('public')}}/js/off-canvas.js"></script>
    <script src="{{url('public')}}/js/hoverable-collapse.js"></script>
    <!-- <script src="{{-- url('public') --}}/js/template.js"></script> -->
    <!-- <script src="{{--url('public') --}}/js/settings.js"></script> -->
    <script src="{{url('public')}}/js/todolist.js"></script>
    <script src="{{url('public')}}/vendors/jsgrid/jsgrid.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- Custom js for this page-->
    <script src="{{url('public')}}/js/js-grid.js"></script>
	<script src="{{url('public')}}/vendors/sweetalert/sweetalert.min.js"></script>
    <script src="{{url('public')}}/js/alerts.js"></script>
	<script src="{{url('public')}}/js/tooltips.js"></script>
	<script src="{{url('public')}}/js/croppie.min.js"></script>
	<script src="{{url('public')}}/js/custom/jquery.number.min.js"></script>

    <script type="text/javascript">
        window.history.forward();        

        $(document).ready(function() {
            $(".alert").fadeTo(2000, 500).slideUp(500, function () {
                $(".alert").slideUp(500);
            });
            
            $(document).on('click', '.document_img', function() {
                var src = $(this).attr('src');

                $('#img-show').attr('src', src);
                $('#img_show').modal('show');
            });
			
			$(document).on('click', '.document_img_span', function() {
                var src = $(this).attr('data-img');

                $('#img-show').attr('src', src);
                $('#img_show').modal('show');
            });
			
			$(document).on('click', '.document_pdf_span', function() {
                var src = $(this).attr('data-pdf');
                var title = $(this).attr('data-title');

                $('#pdf-label').text(title);
                $('#pdf-obj').attr('data', src);
                $('#pdf_show').modal('show');
            });
        });
    </script>

    @yield('page-script')
</body>
</html>