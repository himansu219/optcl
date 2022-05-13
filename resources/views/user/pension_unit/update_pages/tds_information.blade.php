<div class="card d-none" id="tds_information_div">
    <div class="card-body">
        <h4 class="card-title">TDS Information</h4>
        <form method="post" action="" autocomplete="off" id="tds_information">
            <div class="row">
            <div class="col-md-4 form-group">
                <label>PPO No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="ti_ppo_number" id="ti_ppo_number" >
                <label id="ti_ppo_number-error" class="error text-danger" for="ti_ppo_number"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pension Employee No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="ti_pension_emp_no" id="ti_pension_emp_no" >
                <label id="ti_pension_emp_no-error" class="error text-danger" for="ti_pension_emp_no"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pensioner Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="ti_name_pensioner" id="ti_name_pensioner" >
                <label id="ti_name_pensioner-error" class="error text-danger" for="ti_name_pensioner"></label>
            </div>  
            <div class="col-md-4 form-group">
                <label>Other Income<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="ti_other_income" id="ti_other_income" >
                <label id="ti_other_income-error" class="error text-danger" for="ti_other_income"></label>
            </div>  
            <div class="col-md-4 form-group">
                <label>Option<span class="text-danger">*</span></label>
                <select class="js-example-basic-single" style="width:100%" id="ti_option" name="ti_option">
                <option value="">Select Option</option>
                <option value="I">I</option>
                <option value="II">II</option>
                </select>
                <label id="ti_option-error" class="error text-danger" for="ti_option"></label>
            </div>               
            </div>
            <div class="row">
            <div class="col-md-4 form-group mt-2">
                <input type="submit" class="btn btn-success" value="Submit">
            </div>
            </div>
        </form>
    </div>
</div>
@section('page-script-section')
<script type="text/javascript">
    $(document).ready(function() {
        $("#tds_information").validate({
            rules: {
                "ti_ppo_number": {
                    required: true,
                    ppo_format: true,
                },
                "ti_pension_emp_no": {
                    required: true,
                },
                "ti_name_pensioner": {
                    required: true,
                },
                "ti_other_income": {
                    required: true,
                },
                "ti_option": {
                    required: true,
                },
            },
            messages: {
                "ti_ppo_number": {                    
                    required: 'Please enter PPO no',
                },
                "ti_pension_emp_no": {
                    required: 'Please enter employee no',
                },
                "ti_name_pensioner": {
                    required: 'Please enter pensioner name',
                },
                "ti_other_income": {
                    required: 'Please enter basic amount',
                },
                "ti_option": {
                    required: 'Please enter O.O. no',
                },
            },
            submitHandler: function(form, event) {
                $('.page-loader').addClass('d-flex'); 
                event.preventDefault();
                var formData = new FormData(form);
                //$("#logid").prop('disabled',true);

                $.ajax({
                    type:'POST',
                    url:'{{ route("pension_unit_revision_basic_pension_submission") }}',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                    //console.log(response);
                    $('.page-loader').removeClass('d-flex');
                    if(response['error']){
                        //$("#logid").prop('disabled',false);
                        for (i in response['error']) {
                            var element = $('#' + i);
                            var id = response['error'][i]['id'];
                            var eValue = response['error'][i]['eValue'];
                            //console.log(id);
                            //console.log(eValue);
                            $("#"+id).show();
                            $("#"+id).html(eValue);
                        }
                    }else{
                        location.href = "{{route('pension_unit_update_pension_record')}}";
                    }
                    }
                });             
            },
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                //$(element).parent().addClass('has-success');
                $(element).addClass('form-control-danger');
            }
        });
    });
</script>
@endsection