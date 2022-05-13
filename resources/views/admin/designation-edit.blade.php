@extends('user.layout.layout')

@section('container')
 <div class="content-wrapper">
                    <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item" ><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item "><a href="{{route('designation_details')}}">Designations Master</a></li>
                        <li class="breadcrumb-item "><a href="{{route('designation_add')}}">Add Designation</a></li>
                         <li class="breadcrumb-item active" aria-current="page">Edit Designation</li>
                      </ol>
                    </nav>
      
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Designation</h4>
                  <form class="forms-sample" id="designation_form" method="post" action="{{URL('designation_update/'.$result['0']->id)}}">
                      @csrf
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputName1">Designation Name</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="designation_name" name="designation_name" placeholder="Enter designation name" autocomplete="off" value="{{$result['0']->designation_name}}">
                        </div>
                      </div>
                      <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail3">Designation Code</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="designation_code" name="designation_code" placeholder="Enter designation code" autocomplete="off" value="{{$result['0']->designation_code}}">
                        </div>
                      </div> -->
                    </div>
                        <button type="submit" class="btn btn-success mr-2">Update</button>
                  </form>
                </div>
              </div>
  </div>

  @endsection
  @section('page-script')

  <script type="text/javascript">

        $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s,()/-]*$/.test(value);
        }, "Please use only letters, numbers and special characters(,()/-).");

        $.validator.addMethod("designationCode", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s-]*$/.test(value);
        }, "Please use only letters, numbers");


    $("#designation_form").validate({
            rules: {

              designation_name: {
                  required: true,
                  minlength: 5,
                  maxlength: 70,
                  addressReg: true
              }
              // ,
              // designation_code: {
              //     required: true,
              //     minlength: 2,
              //     maxlength: 50,
              //     designationCode: true
              // }
            },
            messages: {
               
              designation_name: {                    
                    required: 'Please enter designation name',
                    minlength: 'Designation name minimum 5 characters',
                    maxlength: 'Designation name maximum 70 characters'
                }
                // ,
                // designation_code: {                    
                //     required: 'Please enter designation code',
                //     minlength: 'Designation code minimum 5 characters',
                //     maxlength: 'Designation code maximum 50 characters'
                // },

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