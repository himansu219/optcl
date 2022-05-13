@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
              <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                  <li class="breadcrumb-item"><a href="{{route('unit_details')}}">Unit Master</a></li>
                  <li class="breadcrumb-item"><a href="{{route('unit_add')}}">Add Unit</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit Unit</li> 
                </ol>
              </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"> Unit form</h4>
                  <form class="forms-sample" id="unit_form" method="post" action="{{URL('unit_update/'.$result['0']->id)}}">
                      @csrf
                    <div class="row">
                      <!-- <div class="col-md-4">
                        <div class="form-group">
                          <label>District </label> <span class="span-red">*</span>
                            <select class="js-example-basic-single" style="width:100%" id="district" name="district" value="">
                              <option value="{{$result['0']->district_id}}">{{$result['0']->district_id}}</option>
                                @foreach($district as $list)
                              <option value="{{$list->district_name}}">{{$list->district_name}}</option>
                                @endforeach
                                                                          
                            </select>
                        </div>
                       </div> -->
                     <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputName1">Unit Name</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="unit_name" name="unit_name" placeholder="Enter unit name" autocomplete="off" value="{{$result['0']->unit_name}}">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail3">Unit Code</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="unit_code" name="unit_code" placeholder="Enter unit code" autocomplete="off" value="{{$result['0']->unit_code}}">
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
            return this.optional(element) || /^[a-zA-Z0-9\s,()/-]*$/.test(value);
        }, "Please use only letters, numbers and special characters(,()/-).");

        $.validator.addMethod("unitCode", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s-]*$/.test(value);
        }, "Please use only letters, numbers");

    $("#unit_form").validate({
            rules: {

              // country: {
              //     required: true

              // },
              // state: {
              //   required: true
              // },
              // district: {
              //   required: true
              // },
              unit_name: {
                  required: true,
                  minlength: 5,
                  maxlength: 70,
                  addressReg: true
              },
              unit_code: {
                  required: true,
                  minlength: 2,
                  maxlength: 50,
                  unitCode: true
              }
            },
            messages: {
                // country: {                    
                //     required: 'Please enter country name'
                   
                // },
                // state: {
                //     required: 'Please enter state name'
                  
                // },
                // district: {
                //     required: 'Please select district name'
                    
                // },
                unit_name: {                    
                    required: 'Please enter unit name',
                    minlength: 'Unit name minimum 5 characters',
                    maxlength: 'Unit name maximum 70 characters'
                },
                unit_code: {                    
                    required: 'Please enter unit code',
                    minlength: 'Unit code minimum 5 characters',
                    maxlength: 'Unit code maximum 50 characters'
                },

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
  <!-- <script type="text/javascript">

		jQuery(document).ready(function(){
			jQuery('#country').change(function(){
        let cid=jQuery(this).val();
        console.log(cid);
				jQuery('#state').html('<option value="">Select State</option>')
				jQuery.ajax({
					url:'/getState',
					type:'post',
					data:'cid='+cid+'&_token={{csrf_token()}}',
					success:function(result){
             jQuery('#state').html(result);
                        
					}
				});
			});
        
        jQuery('#state').change(function(){
          let sid=jQuery(this).val();
          jQuery.ajax({
            url:'/getDistrict',
            type:'post',
            data:'sid='+sid+'&_token={{csrf_token()}}',
            success:function(result){
                jQuery('#district').html(result);
                          
            }
          });
        });
        
    });
  </script> -->

@endsection