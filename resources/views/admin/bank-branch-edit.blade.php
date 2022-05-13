@extends('user.layout.layout')

@section('container')
           <div class="content-wrapper">
                    <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item "><a href="{{route('bank_branch_details')}}">Bank Branch Master</a></li>
                        <li class="breadcrumb-item "><a href="{{route('bank_branch_add')}}">Add Bank Branch</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Bank Branch</li>
                      </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Bank Branch</h4>
                    <form class="forms-sample" id="bank_branch_form" method="post" action="{{URL('bank-branch-update/'.$result['0']->id)}}">
                      @csrf
                         <div class="row">
                            <div class="col-md-4">
                               <div class="form-group">
                                  <label>Bank Name</label> <span class="span-red">*</span>
                                    <select class="js-example-basic-single form-control"  id="bank_name" name="bank_name" autocomplete="off">
                                      <option value="{{$result['0']->bank_id}}">{{$result['0']->bank->bank_name}}</option>
                                      @foreach($bank_name as $list)
                                      <option value="{{$list->id}}">{{$list->bank_name}}</option>
                                      @endforeach
                                     </select>
                                     <label id="bank_name-error" class="error mt-2 text-danger" for="bank_name"></label>
                                </div>
                             </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                  <label for="exampleInputEmail3">Branch Name</label> <span class="span-red">*</span>
                                    <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Enter branch name" autocomplete="off" value="{{$result['0']->branch_name}}">
                                    <label id="branch_name-error" class="error mt-2 text-danger" for="branch_name"></label>
                                </div>
                              </div>
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="exampleInputEmail3">IFSC Code</label> <span class="span-red">*</span>
                                        <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" placeholder="Enter ifsc code" autocomplete="off" value="{{$result['0']->ifsc_code}}">
                                        <label id="ifsc_code-error" class="error mt-2 text-danger" for="ifsc_code"></label>
                                  </div>
                               </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">MICR Code</label>
                                      <input type="text" class="form-control" id="micr_code" name="micr_code" placeholder="Enter micr code" autocomplete="off" value="{{$result['0']->micr_code}}">
                                      <label id="micr_code-error" class="error mt-2 text-danger" for="micr_code"></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Address</label> <span class="span-red">*</span>
                                    <textarea class="form-control" id="address" name="address" placeholder="Enter address" autocomplete="off" value="{{$result['0']->address}}">{{$result['0']->address}}</textarea>
                                    <label id="address-error" class="error mt-2 text-danger" for="address"></label>
                                      <!-- <input type="textarea" class="form-control" id="address" name="address" placeholder="Enter address" autocomplete="off"> -->
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

$.validator.addMethod("branchName", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\s-]*$/.test(value);
}, "Please use only letters");
$.validator.addMethod("ifscCode", function (value, element) {
    return this.optional(element) || /[A-Z|a-z]{4}[0][a-zA-Z0-9]{6}$/.test(value);
}, "Please enter ifsc code correctly");

$.validator.addMethod("addressReg", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s,()/-]*$/.test(value);
}, "Please use only letters, numbers and special characters(,()/-).");


$("#bank_branch_form").validate({
    rules: {
      bank_name: {
        required: true 
      },
      branch_name: {
        required: true,
        minlength: 2,
        maxlength: 70,
        branchName: true
      },

      ifsc_code: {
        required: true,
        minlength: 2,
        maxlength: 20,
        ifscCode: true
      },
      micr_code: {
        minlength: 2,
        maxlength: 20
      },
      address: {
        required: true,
        minlength: 2,
        maxlength: 200,
        addressReg: true
      }
    },
    messages: {
      bank_name: {
        required: 'Please select bank name'
      },
      branch_name: {
        required: 'Please enter branch name',
        minlength: 'Branch name minimum 2 characters',
        maxlength: 'Branch name maximum 70 characters'
      },
      ifsc_code: {                    
        required: 'Please enter ifsc code',
        minlength: 'Ifsc code minimum 2 alphanumeric',
        maxlength: 'Ifsc code maximum 20 alphanumeric'
        },
      micr_code: {
        minlength: 'Micr code minimum 2 alphanumeric',
        maxlength: 'Micr code maximum 20 alphanumeric'
      },
      address: {
        required: 'Please enter address',
        minlength: 'Branch name minimum 2 characters',
        maxlength: 'Branch name maximum 200 characters'
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