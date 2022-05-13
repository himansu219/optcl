
@section('page-script-section')
<script type="text/javascript">
     $("#revision_basic_pension").validate({
          rules: {
            "rbp_ppo_number": {
                required: true,
                ppo_format: true,
            },
            "rbp_pension_emp_no": {
                required: true,
            },
            "rbp_name_pensioner": {
                required: true,
            },
            "rbp_basic_amt": {
                required: true,
            },
            "rbp_oo_no": {
                required: true,
            },
            "rbp_oo_no_date": {
                required: true,
            },
          },
          messages: {
            "rbp_ppo_number": {                    
                required: 'Please enter PPO no',
            },
            "rbp_pension_emp_no": {
                required: 'Please enter employee no',
            },
            "rbp_name_pensioner": {
                required: 'Please enter pensioner name',
            },
            "rbp_basic_amt": {
                required: 'Please enter basic amount',
            },
            "rbp_oo_no": {
                required: 'Please enter O.O. no',
            },
            "rbp_oo_no_date": {
                required: 'Please select O.O. no date',
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
</script>
@endsection