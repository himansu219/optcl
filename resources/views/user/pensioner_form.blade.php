@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title align_center">ODISHA POWER TRANSMISSION CORPORATION LTD.</h4>
                    <h5 class="card-description align_center">(A Govt. of Odisha Undertaking)</h5>
                    <h5 class="card-description align_center">Gridco Pension Trust Fund</h5>
                    <p class="card-description align_center">Regd. Off – Janpath, Bhubaneswar – 751022</p>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active step_active" id="home-tab" data-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="true">1. PENSION/FAMILY PENSION FORM</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link step_inactive" id="profile-tab" data-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="false">2. PERSONAL DETAILS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link step_inactive" id="nominee-tab" data-toggle="tab" href="#nominee-1" role="tab" aria-controls="nominee-tab" aria-selected="false">3. NOMINEES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link step_inactive" id="contact-tab" data-toggle="tab" href="#contact-1" role="tab" aria-controls="contact-1" aria-selected="false">4. LIST OF DOCUMENTS</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                            <div class="media">
                                <div class="media-body">
                                    <h4 class="mt-0 text-center">PENSION/FAMILY PENSION FORM (Provisional / Final / Revised) PART - I</h4>
                                    <h6 class="text-center-normal">(To be completed by the Employee/Applicant for sanction & payment of Pension/Family pension, Death-cum-retirement Gratuity, Service Gratuity & Commuted value of pension)</h6>
                                    <hr>
                                    <h6 class="text-center-normal">PARTICULARS OF EMPLOYEE/EX-EMPLOYEE</h6>
                                    <hr>
                                    <br />
                                    <form method="POST" autocomplete="off" id="form_fetch_data" action="{{URL('fetch_employee_data')}}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword4" id="fetch_emp_info_label">Employee Code/Aadhaar No</label>
                                                    <input type="text" class="form-control" id="fetch_emp_code" name="fetch_emp_code" value="" placeholder="Employee Code/Aadhaar No" maxlength="12" required="">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-success btn-sm fetch_emp_info_btn" name="fetch_emp_info" id="fetch_emp_info">SUBMIT</button>
                                        </div>
                                    </form>
                                    <br />
                                    
                                </div>
                            </div>
                        </div>
               
                    </div>
                    <div class="tab-pane fade show" id="nominee-tab" role="tabpanel" aria-labelledby="nominee-tab">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
 @endsection
@section('js')
<!-- Custom js for this page-->
<script type="text/javascript">
    $(document).ready(function() {
       
        $('ul.nav li a').click(function(e) {
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
    $('#fetch_emp_code').keyup(function () { 
          this.value = this.value.replace(/[^0-9\.]/g,'');
           });
 });

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
                required: 'Please enter employee code/Aadhaar No',
                digits: 'Please enter digits only',
                minlength: 'Employee Code minimum of 5 digits',
                maxlength: 'Aadhaar No maximum upto 12 digits'
            }
        },
    // submitHandler: function(form) {
    //     //console.log('data');
    //     $.ajax({
    //         url: "{{URL('fetch_employee_data')}}", 
    //         type: "post",             
    //         data: $(form).serialize(),
    //         cache: false,             
    //         processData: false,      
    //         success: function(data) {
    //             console.log(data);
    //             if (data['success']) {
    //                 $("#emp_code").val(data['success']['employee_id']);
    //                 $("#aadhaar_no").val(data['success']['aadhaar_no']);
    //                 $("#name").val(data['success']['pensioner_name']);

    //             } else {
    //                 $(".errormsg").val(data['error']['message']);
    //             }
                
    //         },
    //         error: function(){
    //             //console.log(data);
    //         }
    //     });
    //     return false;
    // }
           
});
  
</script>
@endsection