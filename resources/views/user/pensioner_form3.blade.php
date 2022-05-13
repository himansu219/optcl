@extends('UserView/layout.layout')

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

                                    <div class="tab-pane fade" id="nominee-1" role="tabpanel" aria-labelledby="contact-tab">
                                            <h4 class="text-center">Nominee Details </h4>
                                            <br />
                                            <form action="" method="POST" class="forms-sample" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail3">Name</label>
                                                            <input type="email" class="form-control" id="exampleInputEmail3" placeholder="Enter Name here.">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Date of Birth</label>
                                                            <div id="nominee-datepicker" class="input-group date datepicker">
                                                                <input type="text" class="form-control">
                                                                <span class="input-group-addon input-group-append border-left">
                                                                    <span class="mdi mdi-calendar input-group-text"></span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Gender</label>
                                                            <div class="col-sm-4">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="gender" id="gender_male" value="" checked>
                                                                        Male
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <div class="form-radio">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="gender" id="gender_female" value="option2">
                                                                        Female
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Relation with Pensioner</label>
                                                            <select class="js-example-basic-single" style="width:100%">
                                                                <option value="AL">Son</option>
                                                                <option value="WY">Daughter</option>
                                                                <option value="WY">Father</option>
                                                                <option value="WY">Mother</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Marital Status</label>
                                                            <select class="js-example-basic-single" style="width:100%">
                                                                <option value="AL">Married</option>
                                                                <option value="WY">Unmarried</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail3">Aadhar No.</label>
                                                            <input type="email" class="form-control" id="exampleInputEmail3" placeholder="Enter Aadhar no here.">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail3">Mobile No</label>
                                                            <input type="email" class="form-control" id="exampleInputEmail3" placeholder="Enter Mobile no here.">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail3">Savings Bank A/C No.(Single or Joint Account with Spouse)</label>
                                                            <input type="text" class="form-control" id="exampleInputEmail3" placeholder=" Saving A/C No">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Name of the Bank </label>
                                                            <select class="js-example-basic-single" style="width:100%">
                                                                <option value="AL">State Bank Of India (SBI)</option>
                                                                <option value="WY">ICIC</option>
                                                                <option value="AM">Bank Of Baroda</option>
                                                                <option value="CA">Punjab National Bank</option>
                                                                <option value="CA">HDFC Bank</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Name Address of the Branch </label>
                                                            <select class="js-example-basic-single" style="width:100%">
                                                                <option value="AL">Bhubaneswar</option>
                                                                <option value="WY">Cuttack</option>
                                                                <option value="AM">Keonjhar</option>
                                                                <option value="CA">Jajpur</option>
                                                                <option value="CA">Ganjam</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail3">IFSC Code</label>
                                                            <input type="text" class="form-control" id="exampleInputEmail3" placeholder=" Enter ifsc code" value="SBIN0567656">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-success mr-2 btn-prev">PREVIOUS</button>
                                                <button type="submit" class="btn btn-success mr-2 btn-next">SAVE</button>
                                            </form>
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
        $('.datepickerClass').datepicker();
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

        $('.form_1_').hide();
        $('#form_1_submit').hide();

        $('#fetch_emp_info').click(function() {
            $('.form_1_').show();
            $('#form_1_submit').show();
            $('#fetch_emp_code').hide();
            $('#fetch_emp_info').hide();
            $('#fetch_emp_info_label').hide();
        });


     

    });

    
    
    </script>

@endsection