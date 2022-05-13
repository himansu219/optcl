@extends('user.layout.layout')

@section('section_content')

<style>
.addClassbtn {
        margin-left:900px;
    }
.fa {
    font-size: 18px;
}
</style>
<div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
                    @if(Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                    <nav aria-label="breadcrumb" role="navigation">
                          <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item" ><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Districts Master</li>
                      </ol>
                    </nav>

                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Filter</h4>
                    <form class="forms-sample" id="filter_district" method="post" action="{{ route('district_details') }}">
                        @csrf
                        <div class="row">
                          <div class="col-md-4">
                          <label>Country</label>
                          <select class="js-example-basic-single form-control"  id="country" name="country"  autocomplete="off">
                          <option value="">Select Country</option>
                          @foreach($country as $list)
                          <option @if(!empty($search) && $list->id == $search) selected @endif  value="{{$list->id}}">{{$list->country_name}}</option>
                          @endforeach
                          </select>
                          <label id="country-error" class="error mt-2 text-danger" for="country"></label>
                          </div>
                          <div class="col-md-4">
                          <label>State</label>
                            <select class="js-example-basic-single form-control"  id="state" name="state" autocomplete="off">
                                <option @if(!empty($search2) && $list->id == $search2) selected @endif  value="">Select State</option>
                            </select>
                            <label id="state-error" class="error mt-2 text-danger" for="state"></label>
                          </div>
                          <div class="col-md-4">
                          <label></label>
                          </div>
                        </div>
                         <button type="submit" class="btn btn-success">Filter</button>
                    </form>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Manage Districts</h4>
                   <a class="btn btn-success btn-sm addClassbtn" href="{{route('district_add')}}"><i class="icon-plus">ADD</i></a>    
                  <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                      <table id="sortable-table-1" class="table table-striped">
                        <thead>
                          <tr>
                            <th class="sortStyle">Sl No.</th>
                            <!-- <th class="sortStyle">Country Name</th> -->
                            <th class="sortStyle">State Name</th> 
                            <th class="sortStyle">District Name</th>
                            <th class="sortStyle">Status</th>
                            <th class="sortStyle">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if(count($data))
                          @foreach($data as $key => $list)
                          <tr>
                            <td>{{ $data->firstItem() + $key }}</td>
                            <td>{{ $list->state->state_name }}</td>
                            <td>{{ $list->district_name }}</td>
                            <td>
                              <label class="switch">
                              <input type="checkbox" id="{{$list->id}}" class="status_check" @if($list->status == 1 ) checked @endif>
                              <span class="slider round"></span>
                            </label>
                            </td>
                            <td>
                            <a class="" href="{{URL('district/edit/'.$list->id)}}" title="edit"><i class="fa fa-edit"></i></a>
                            <a class="deleteUser" name="{{$list->id}}" href="#" title="delete"><i class="fa fa-trash-o"></i></a>
                            </td>
                          </tr>
                          @endforeach
                          @else
                          <tr><td colspan="10" align="center">No Data Found</td></tr>    
                          @endif
                        </tbody>
                      </table>
                          @if ($data->lastPage() > 1)
                              <nav aria-label="..." class="float-right">
                                  <ul class="pagination">
                                      <li class="{{ ($data->currentPage() == 1) ? ' disabled' : '' }} page-item">
                                          <a class="page-link" href="{{ $data->url(1) }}">Previous</a>
                                      </li>
                                      @for ($i = 1; $i <= $data->lastPage(); $i++)
                                          <li class="{{ ($data->currentPage() == $i) ? ' active' : '' }}  page-item">
                                              <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                                          </li>
                                      @endfor
                                      <li class="{{ ($data->currentPage() == $data->lastPage()) ? ' disabled' : '' }} page-item">
                                          <a class="page-link" href="{{ $data->url($data->currentPage()+1) }}" >Next</a>
                                      </li>
                                  </ul>
                              </nav>
                            @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
</div>  
       


  @endsection
  @section('page-script')

  <script type="text/javascript">
    var base_url = "{{url('/')}}";
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
     $(function() {
     $('.status_check').change(function() {
     var status = $(this).prop('checked') == true ? 1 : 0; 
     var id = $(this).attr('id'); 
     console.log(id);
     $.ajax({
         type: "POST",
         url: '{{route("check_status_district")}}',
         data: 'status='+status+'&_token={{csrf_token()}}&id='+id,
         success: function(data){
           if(data.status == 'true'){
               location.reload();
           }
         console.log(data.success)
          }
       });
    });
   });
   $(".deleteUser").on("click",function () {
    var userID = $(this).attr('name');
    //alert(userID);
   swal( {
      title: "Are you sure",
      text: "Want to delete this ?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: true,
      closeOnCancel: true,
       })
        .then((value) => { 
          if(value){ 
              location.href=base_url+"/district-delete/"+userID;
        //   $.post(base_url+"member/delete/",{ pageID:idVal }); 
        //   swal({ title: "Data deleted successfully.", type: "success" })
        // .then(function(){
        //       location.reload();
        //   });
          
         } 
        });
   });

       $("#filter_district").validate({
            rules: {
              country: {
                required: true 
              },
              state: {
                required: true 
              }
            },
            messages: {
              country: {
                required: 'Please select country name'
              },
              state: {
                required: 'Please select state name'
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
     $('#country').change(function(){
        let cid=$(this).val();
        console.log(cid);
				$('#state').html('<option value="">Select State</option>')
				$.ajax({
					url:"{{route('statedata')}}",
					type:'post',
					data:'cid='+cid+'&_token={{csrf_token()}}',
					success:function(result){
             $('#state').html(result);
             $('#state').val("<?php echo $search2 ?>");           
					}
				});
			}).trigger('change');

        
        
    });
  </script>

@endsection