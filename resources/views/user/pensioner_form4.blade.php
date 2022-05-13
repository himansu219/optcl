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

                                    <div class="tab-pane fade" id="contact-1" role="tabpanel" aria-labelledby="contact-tab">
                                            <h4 class="text-center">LIST OF DOCUMENTS (To be submitted by the applicant for service or family pension as per applicability)</h4>
                                            <h4 class="text-center">PART I (B)</h4>
                                            <br />
                                            <form action="" method="POST" class="forms-sample" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Attach recent passport size photograph with spouse (if married) and single photograph of self (applicant) duly attested</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach three descriptive roll slips each bearing three specimen signatures (L.T.I if illiterate). Particulars of height and identification marks (at least 2 conspicuous marks) and left hand thumb and all fingers impression of the applicant duly attested by the head of office.</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <form class="form-inline repeater">
                                                            <div data-repeater-list="group-a">
                                                              <div data-repeater-item class="d-flex mb-2">
                                                                <label class="sr-only" for="inlineFormInputGroup1">Users</label>
                                                                <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                                                                  <div class="input-group-prepend">
                                                                    <span class="input-group-text">@</span>
                                                                  </div>
                                                                  <input type="text" class="form-control form-control-sm" id="inlineFormInputGroup1" placeholder="Add user">
                                                                </div>
                                                                <button type="submit" class="btn btn-success btn-sm">Submit</button>
                                                                <button data-repeater-delete type="button" class="btn btn-danger btn-sm icon-btn ml-2" >
                                                                  <i class="mdi mdi-delete"></i>
                                                                </button>
                                                              </div>
                                                            </div>
                                                            <button data-repeater-create type="button" class="btn btn-info btn-sm icon-btn ml-2 mb-2">
                                                              <i class="mdi mdi-plus"></i>
                                                            </button>
                                                          </form> -->
                                                        <div class="form-group">
                                                            <label>Attach attested copies of the date of birth certificates of all family members & nominee(s)</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach the Undertaking / Declaration for refund / recovery / of outstanding dues / excess payment wherever applicable</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach attested copy of the first page of Bank Pass Book with IFSC and MICR code</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach copy of cancelled cheque issued by the concerned bank </label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach Indemnity Bond (For the PF account which have not been transferred from the RPFC)</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <h6>Attach attested copies of (Applicable for Family Pension)</h6>
                                                        <div class="form-group">
                                                            <label>a) Death Certificate</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>b) Legal Heir Certificate</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>c) Power of attorney (authorising one of the eligible family pensioner to receive the entire claim on behalf of the family members) – if applicable </label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-12 col-form-label"> Attach guardianship certificate from the competent authority for receiving the pensionary benefits on behalf of minors and mentally retarded child.</label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div class="row" style="margin-left: 0px;">
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="gender" id="gender_male" value="" checked>
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <div class="col-sm-4">
                                                                    <div class="form-radio">
                                                                        <label class="form-check-label">
                                                                            <input type="radio" class="form-check-input" name="gender" id="gender_female" value="option2">
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label></label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label></label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach attested photograph of Applicant </label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach attested photograph of child</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach attested photograph of child</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach certificate from the Medical Board in case of physically handicapped/mentally retarded applicant </label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach affidavit of being unmarried, unemployed, income certificate, etc. by the son or daughter applying for family pension as per rules.</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach attested copy of the PAN Card where the pensioner is coming under Income Tax bracket </label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach proof identity viz. Attested copy of Voter I. Card / Aadhaar Card </label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach any other document(s) may be attached if relevant for sanction & payment of pension/ family pension as per OCS Pension rules – (Specify)</label>
                                                            <input type="file" name="img[]" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <div class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-success mr-2">SUBMIT</button>
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