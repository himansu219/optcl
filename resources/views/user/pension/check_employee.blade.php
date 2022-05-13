@extends('user.layout.layout')

@section('section_content')
<div class="content-wrapper">
    @if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <img src="{{url('public')}}/images/logo_1.png" alt="image" class="brand_logo_1" />
                    <img src="{{url('public')}}/images/logo_2.png" alt="image" class="brand_logo_2" />
                    <h4 class="card-title align_center mb-2">ODISHA POWER TRANSMISSION CORPORATION LTD.</h4>
                    <h5 class="card-description align_center mb-1">(A Govt. of Odisha Undertaking)</h5>
                    <h5 class="card-description align_center mb-1">Gridco Pension Trust Fund</h5>
                    <p class="card-description align_center mb-1">Regd. Off – Janpath, Bhubaneswar – 751022</p>

                  <div class="employe-code-check">
                    <ul class="nav nav-tabs d-flex justify-content-center mt-5" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="true">1. PENSION DETAILS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="false">2. PERSONAL DETAILS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nominee-tab" data-toggle="tab" href="#nominee-1" role="tab" aria-controls="nominee-tab" aria-selected="false">3. NOMINEES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact-1" role="tab" aria-controls="contact-1" aria-selected="false">4. LIST OF DOCUMENTS</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                            <div class="media">
                                <div class="media-body">
                                    <h4 class="mt-0 text-center">PENSION/FAMILY PENSION FORM (Provisional / Final / Revised) PART - I</h4>
                                    <h6 class="text-center-normal">(To be completed by the Employee/Applicant for sanction & payment of Pension/Family pension, Death-cum-retirement Gratuity, Service Gratuity & Commuted value of pension)</h6>
                                    <hr>
                                    <form method="POST" autocomplete="off" id="form_fetch_data" action="{{ route('submit_check_employee') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword4" id="fetch_emp_info_label">Employee Code/Aadhaar No<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control anns" id="fetch_emp_code" name="fetch_emp_code" value="{{ Auth::user()->employee_code }}" placeholder="Employee Code/Aadhaar No" readonly maxlength="12">
                                                    <label id="fetch_emp_code-error" class="error  text-danger" for="fetch_emp_code"></label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm fetch_emp_info_btn" name="fetch_emp_info" id="fetch_emp_info">SUBMIT</button>
                                        </div>
                                    </form>
                                    <br />
                                    
                                </div>
                            </div>
                        </div>
               
                    </div>
                  </div>

                   </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
@endsection
@section('page-script')
<!-- Custom js for this page-->
<script type="text/javascript">
    $(document).ready(function() {
       
        $('.sidebar ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
            //$(this).closest('.nav-link').removeClass("step_active").addClass('step_inactive');
        });
        
             $('.btn-next').click(function() {
            debugger
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });

        $('.btn-prev').click(function() {
            debugger
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });
    });
</script>
<script type="text/javascript">
$(document).ready(function(){
    $('#form_fetch_data').validate({
        rules: {
            fetch_emp_code: {
                required: true,
                digits: true,
                minlength: 5,
                maxlength: 12
            }
        },
        messages: {
            fetch_emp_code: {
                required: 'Please enter employee code/aadhaar no',
                digits: 'Please enter digits only',
                minlength: 'Employee Code minimum of 5 digits',
                maxlength: 'Aadhaar No maximum upto 12 digits'
            }
        },
      /*submitHandler: function(form, event) { 
          event.preventDefault();
          var formData = new FormData(form);
          $.ajax({
              type:'POST',
              url:'{{ route("pensioner_form") }}',
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
          });
      },*/
               
    });
});


  
</script>
@endsection