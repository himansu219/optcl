@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
                  <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                      <!-- <li class="breadcrumb-item"><a href="">Master Management</a></li> -->
                      <li class="breadcrumb-item"><a href="{{route('form16_details')}}">Form 16</a></li>
                      <li class="breadcrumb-item active" aria-current="page">Add Form 16</li> 
                    </ol>
                  </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Form 16</h4>
                  <form class="forms-sample" id="form16_form" method="post" action="{{URL('form16_submit')}}" enctype="multipart/form-data">
                      @csrf
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Form 16 file  <span class="span-red">*</span></label>
                            <input type="file" name="form_16_file_path" id="form_16_file_path" class="file-upload-default">
                              <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload form 16 file">
                                <div class="input-group-append">
                                  <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                </div>
                               
                            </div>
                            <label id="form_16_file_path-error" class="error mt-2 text-danger" for="form_16_file_path"></label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Upload Date <span class="span-red">*</span></label>
                            <div id="datepicker-joining" class="input-group date datepicker ">
                              <input type="text" class="form-control" autocomplete="off" id="upload_date" name="upload_date">
                                <span class="input-group-addon input-group-append border-left">
                                  <span class="mdi mdi-calendar input-group-text"></span>
                                </span>
                            </div>
                            <label id="upload_date-error" class="error mt-2 text-danger" for="upload_date"></label>
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
    $(document).ready(function() {
      $('#upload_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
        //endDate: new Date()
      }); 
      $("#form16_form").validate({
            rules: {
            form_16_file_path: {
                 required: true 
              },
            upload_date: {
                 required: true 
              }
            },
            messages: {
              form_16_file_path: {
                required: 'Please upload file'
              },
              upload_date: {
                required: 'Please select date'
              }
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


        $('ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
        });
        
        $('#form_16_file_path').on('change', function() {
            check_upload_file(this, 'form_16_file_path');
        });

    });

    function check_upload_file(ele, id) {
        $(ele).parent().find('.form-control').val($(ele).val().replace(/C:\\fakepath\\/i, ''));

        $("#" + id + "-error").html("");
        
        var val = ele.value;

        if(val.indexOf('.') !== -1) {
            var ext = ele.value.match(/\.(.+)$/)[1];
            var size = ele.files[0].size;

            if(size > 5000000) {
                $("#" + id + "-error").html('File size less than 5MB allowed');
                $("#" + id + "-error").show();
                ele.value = '';
                $(ele).parent().find('.form-control').val('');
            } else {
                switch (ext) {
                    
                    case 'pdf':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;
                    default:
                        $("#" + id + "-error").html('Please upload only pdf file');
                        $("#" + id + "-error").show();
                        ele.value = '';
                        $(ele).parent().find('.form-control').val('');
                }
            }
        } else {
            $("#" + id + "-error").html('Invalid file type');
            $("#" + id + "-error").show();
            ele.value = '';

            $(ele).parent().find('.form-control').val('');
        }
    }
</script>
 

  @endsection