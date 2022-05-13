@extends('user.layout.layout')

@section('container')
            <div class="content-wrapper">
                    <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item "><a href="{{route('bank_name_details')}}">Bank Names Master</a></li>
                         <li class="breadcrumb-item active" aria-current="page">Add Bank Name</li>
                      </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Bank Name</h4>
                  <form class="forms-sample" id="bank_name_form" method="post" action="{{URL('bank-name-submit')}}">
                      @csrf
                    <div class="row">
                       <div class="col-md-4">
                          <div class="form-group">
                            <label>Bank Type</label> <span class="span-red">*</span>
                              <select class="js-example-basic-single" style="width:100%" id="bank_type" name="bank_type" autocomplete="off">
                                <option value="">Select Bank Type</option>
                                  @foreach($bank_type as $list)
                                <option value="{{$list->id}}">{{$list->bank_type}}</option>
                                  @endforeach
                                                                            
                              </select>
                              <label id="bank_type-error" class="error mt-2 text-danger" for="bank_type" style="display: inline-block;"></label>
                          </div>
                       </div>
                     <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail3">Bank Name</label> <span class="span-red">*</span>
                              <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Enter bank name" autocomplete="off">
                        </div>
                      </div>
                     <!-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail3">Bank Code</label> <span class="span-red">*</span>
                              <input type="text" class="form-control" id="bank_code" name="bank_code" placeholder="Enter bank code" autocomplete="off">
                        </div>
                      </div> -->
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                  </form>
                </div>
              </div>
            </div>
@endsection
  @section('page-script')

  <script type="text/javascript">

        $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s-]*$/.test(value);
        }, "Please use only letters");
    $("#bank_name_form").validate({
            rules: {
              bank_type: {
                required: true 
              },
              bank_name: {
                required: true,
                minlength: 2,
                maxlength: 70,
                addressReg: true
              },

              bank_code: {
                  required: true,
                  minlength: 2,
                  maxlength: 20,
                  addressReg: true
              }
            },
            messages: {
              bank_type: {
                required: 'Please select bank type'
              },
              bank_name: {
                required: 'Please enter bank name',
                minlength: 'Bank name minimum 2 characters',
                maxlength: 'Bank name maximum 70 characters'
              },
              bank_code: {                    
                    required: 'Please enter bank name',
                    minlength: 'Bank code minimum 2 characters',
                    maxlength: 'Bank code maximum 20 characters'
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