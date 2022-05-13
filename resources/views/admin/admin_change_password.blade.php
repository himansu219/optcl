@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
                    @if(Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif  
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Change Password</li> 
                        </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Change Password <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title" title="Password must have 1 Block letter,1 Small letter and 1 no. Password must be minimum 6 characters long. Password must be maximum 16 characters long."></i></h4>
                  <form class="forms-sample" id="change_password_form" method="post" action="{{URL('change_password')}}">
                      @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputName1">Current Password</label> <span class="span-red">*</span>
                                <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current password" minlength="6" maxlength="16" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputName1">New Password</label> <span class="span-red">*</span>
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" minlength="6" maxlength="16" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputName1">Confirm Password</label> <span class="span-red">*</span>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Enter confirm_password" minlength="6" maxlength="16"autocomplete="off">
                            </div>
                        </div>
                   </div>
                      <button type="submit" class="btn btn-success mr-2">Submit</button>
                  </form>
                </div>
              </div>
       
   </div>

  @endsection
  @section('page-script')

  <script type="text/javascript">
        // validation for Password Policy- 1 block letter,1 small letter , 1 digits and 6 to 16 length
        $.validator.addMethod("passwordPolicy", function (value, element) {
            return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/.test(value);
        }, "Please enter the password in correct format");
        
 $("#change_password_form").validate({
            rules: {

             current_password: {
                  required: true,
                  minlength: 6,
                  maxlength: 16,
                  passwordPolicy: true
              },
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
              }
            },
            messages: {
               
            current_password: {                    
                    required: 'Please enter current password',
                    minlength: 'Password length minimum 6 characters',
                    maxlength: 'Password length maximum 16 characters'
                 },
             new_password: {                    
                required: 'Please enter new password',
                minlength: 'Password length minimum 6 characters',
                maxlength: 'Password length maximum 16 characters'
                },
             confirm_password: {                    
                required: 'Please enter confirm password',
                minlength: 'Password length minimum 6 characters',
                maxlength: 'Password length maximum 16 characters'
                },

            },
            errorPlacement: function(label, element) {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-success')
                $(element).addClass('form-control-danger')
            }
        });



  </script>


@endsection