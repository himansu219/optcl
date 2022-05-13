@extends('user.layout.layout')

@section('section_content')
<div class="content-wrapper">
                <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item "><a href="{{route('pension_unit_details')}}">Pension Units Master</a></li>
                         <li class="breadcrumb-item active" aria-current="page">Add Pension Unit </li>
                      </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Pension Unit</h4>
                  <form class="forms-sample" id="pension_unit_form" method="post" action="{{URL('pension_unit_submit')}}">
                      @csrf
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputName1">Unit Name</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="unit_name" name="unit_name" placeholder="Enter unit name" autocomplete="off">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail3">Unit Code</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="unit_code" name="unit_code" placeholder="Enter unit code" autocomplete="off">
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

        $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s,()/-]*$/.test(value);
        }, "Please use only letters, numbers and special characters(,()/-).");

        $.validator.addMethod("pensionCode", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s-]*$/.test(value);
        }, "Please use only letters, numbers");

    $("#pension_unit_form").validate({
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
                  pensionCode: true
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
                    required: 'Please enter pension unit name',
                    minlength: 'Pension unit minimum 5 characters',
                    maxlength: 'Pension unit maximum 70 characters'
                },
                unit_code: {                    
                    required: 'Please enter pension unit code',
                    minlength: 'pension unit minimum 5 characters',
                    maxlength: 'pension unit maximum 50 characters'
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