@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
                      <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                          <li class="breadcrumb-item" ><a href="#">Master Management</a></li>
                          <li class="breadcrumb-item"><a href="{{route('religion_details')}}">Religion Master</a></li>
                          <li class="breadcrumb-item"><a href="{{route('religion_add')}}">Add Religion</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Edit Religion</li> 
                        </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Religion</h4>
                  <form class="forms-sample" id="religion_form" method="post" action="{{URL('religion_update/'.$result['0']->id)}}">
                      @csrf
                    <div class="row">
                      <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail3">Religion Code</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="religion_code" name="religion_code" placeholder="Enter religion code" autocomplete="off" value="{{$result['0']->religion_code}}">
                        </div>
                      </div> -->
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputName1">Religion Name</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="religion_name" name="religion_name" placeholder="Enter religion name"  autocomplete="off" value="{{$result['0']->religion_name}}">
                        </div>
                      </div>
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
            return this.optional(element) || /^[a-zA-Z/s-]*$/.test(value);
        }, "Please use only letters");


    $("#religion_form").validate({
            rules: {

              religion_name: {
                  required: true,
                  minlength: 2,
                  maxlength: 70,
                  addressReg: true
              }
             
            },
            messages: {
               
              religion_name: {                    
                    required: 'Please enter religion name',
                    minlength: 'Religion name minimum 2 characters',
                    maxlength: 'Religion name maximum 70 characters'
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



  </script>
  

@endsection