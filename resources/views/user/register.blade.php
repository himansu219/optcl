@extends('user.layout.landing_page_layout')
@section('title', 'OPTCL')
@section('section_content')
<div class="col-lg-6 d-flex align-items-center justify-content-center">
  <div class="auth-form-transparent text-left p-3">
    <div class="brand-logo">
      <img src="{{url('public')}}/images/logo.png" alt="logo" style="width: 75px; height: 90px;">
    </div>
    <h4>New Pensioner! Join here?</h4>
    <h6 class="font-weight-light">Register here to submit the pension proposal</h6>
    {{session('msg')}}
    <form class="pt-3" id="register_form" method="post" action="{{URL('register_form_submit')}}">
     
      @csrf

      <div class="form-group">
        <label>Pensioner Name</label> <span class="span-red">*</span>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
             <!--  <i class="mdi mdi-account-outline text-primary"></i>-->
            </span> 
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 alphabetsOnly" id="pensioner_name" name="pensioner_name" placeholder="Pensioner Name" autocomplete="off">
           @error('pensioner_name')
          <span class="error_message">{{$message}}</span>
           @enderror
        </div>
        
      </div>
      <div class="form-group">
        <label>Employee Code</label> <span class="span-red">*</span>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
             <!--  <i class="mdi mdi-account-outline text-primary"></i>-->
            </span> 
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 numbersOnly" id="employee_id" name="employee_id" placeholder="Employee Code" maxlength="5" minlength="5" autocomplete="off">
          @error('employee_id')
          <span class="error_message">{{$message}}</span>
          @enderror
        </div>
        
      </div>
      <div class="form-group">
        <label>Aadhaar No</label> <span class="span-red">*</span>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
             <!--  <i class="mdi mdi-email-outline text-primary"></i>-->
            </span> 
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 numbersOnly" id="aadhaar_no" name="aadhaar_no" placeholder="Aadhaar No" maxlength="12" autocomplete="off">
          @error('aadhaar_no')
          <span class="error_message">{{$message}}</span>
          @enderror
        </div>
     
      </div>
      <div class="form-group">
        <label>Mobile No.</label> <span class="span-red">*</span>
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <span class="input-group-text bg-transparent border-right-0">
             <!--  <i class="mdi mdi-lock-outline text-primary"></i> -->
            </span>
          </div>
          <input type="text" class="form-control form-control-lg border-left-0 numbersOnly" id="mobile_no" name="mobile_no" placeholder="Mobile No" maxlength="10" autocomplete="off"> 
            @error('mobile_no')
          <span class="error_message">{{$message}}</span>
          @enderror                      
        </div>
       
      </div>
      <div class="mt-3">
         <!-- <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" href="otp.html">SIGN UP</a> -->
        <button type="button" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="btn_submit">REGISTER</button>
        
      </div>
      <div class="text-center mt-4 font-weight-light">
        Already have an account? <a href="{{route('login_form')}}" class="text-primary">Login</a>
      </div>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script type="text/javascript">
  // Extraordinary gazette form submission validation
  $(document).ready(function(){

      // for block letter in pensioner name field
      $("#pensioner_name").on("keyup",function(){
          this.value = this.value.toUpperCase();
         }); 

    $('.numbersOnly').keyup(function () { 
      this.value = this.value.replace(/[^0-9\.]/g,'');
    });
  });
  $(document).ready(function(){
    $('.alphabetsOnly').keyup(function () { 
      this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
    });
  });
  $("#register_form").validate({
       rules: {
           pensioner_name: {
               required: true,
               minlength: 5,
               maxlength: 50
           },
           employee_id: {
               digits: true,
               required: true,
               minlength: 5,
               maxlength: 5
           },
           aadhaar_no: {
               digits: true,
               required: true,
               minlength: 12,
               maxlength: 12
               
           },
           mobile_no: {
               digits: true,
               required: true,
               minlength: 10,
               maxlength: 10
               
           }
       },
       messages: {
           pensioner_name: {
               required: 'Please enter pensioner name.',
               minlength: 'Pensioner Name should be 5 chars.',
               maxlength: 'Pensioner Name cannot be more than 50 chars.'
           },
           employee_id: {
               digits:'Employee Code should be only Digits.' ,
               required: 'Please Enter Employee ID.',
               minlength: 'Employee Code should be minimum 5 digits.',
               maxlength: 'Employee Code maximum 5 digits.'
           },
           aadhaar_no: {
               digits: 'Aadhaar No should be only Digits.',
               required: 'Please enter Aadhaar number.',
               minlength: 'Aadhaar No should be minimum 12 digits.',
               maxlength: 'Aadhaar No should be maximum 12 digits.'
               
           },
           mobile_no: {
               required: 'Please Enter Mobile No Here.',
               minlength: 'Mobile No should be 10 digits.',
               maxlength: 'Mobile No should be 10 digits.',
               digits: 'Mobile No should be only Digits.'
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
@endsection