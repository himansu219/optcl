@extends('user.layout.layout')
@section('container') 
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
                        <li class="breadcrumb-item" ><a href="#">User Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                        
                      </ol>
                   </nav>
                   <div class="card">
              
                    <div class="card-body">
                      <h4 class="card-title">Filter</h4>
                      <form class="forms-sample" id="filter_user" method="post" action="{{ route('user_details') }}">
                          @csrf
                          <div class="row">
                            <div class="col-md-4">
                              <label>User Type <span class="span-red">*</span></label>
                              <select class="js-example-basic-single form-control"  id="user_role" name="user_role"  autocomplete="off">
                                <option value="">Select User Type</option>
                                  @foreach($user_type as $list)
                                  {{-- <option value="{{$list->id}}">{{$list->type_name}}</option> --}}
                                  <option @if(!empty($search) && $list->id == $search) selected @endif  value="{{$list->id}}">{{$list->type_name}}</option>

                                  @endforeach
                              </select>
                              <label id="user_role-error" class="error mt-2 text-danger" for="user_role"></label>
                            </div>
                            <div class="col-md-4">
                            <label>User Designation <span class="span-red">*</span></label>
                              <select class="js-example-basic-single form-control"  id="designation" name="designation" autocomplete="off">
                                  {{-- <option value="">Select User Designation</option> --}}
                                  <option @if(!empty($search2) && $list->id == $search2) selected @endif  value="">Select User Designation</option>
                              </select>
                              <label id="designation-error" class="error mt-2 text-danger" for="designation"></label>
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
                  <h4 class="card-title">Manage Users</h4>
                  <a class="btn btn-success btn-sm addClassbtn header-absolute-btn" href="{{route('user_add')}}">
                    <i class="icon-plus"></i>ADD</a>
              {{-- <input type="hidden" name="user_id" value="{{$data->id}}"> --}}
                  <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        
                      <table id="sortable-table-1" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Sl No.</th>
                            <!-- <th class="sortStyle">Country Name<i class="mdi mdi-chevron-down"></i></th>-->
                            <th class="sortStyle">User Name</th> 
                            {{-- <th class="sortStyle">Employee Code</th>  --}}
                            <th class="sortStyle">User Type</th>
                            <th class="sortStyle">User Designation</th>
                            <th class="sortStyle">Unit</th>
                            {{-- <th class="sortStyle">Pension Unit</th> --}}
                            <th class="sortStyle">Status</th>
                            <th class="sortStyle text-center">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                        @if(count($data))
                        @foreach($data as $key => $list)
                            @php
                              $user_role = $list->system_user_role;
                              $designation = $list->designation_id;
                              $optcl_unit = $list->optcl_unit_id;
                              $pension_unit = $list->pension_unit_id;
                              $user_role_name = DB::table('optcl_user_role_master')->where('id', $user_role)->first();
                              $designation_name = DB::table('optcl_user_designation_master')->where('id', $designation)->first();
                              $unit_name ='';

                              if(!empty($optcl_unit)){
                                $optcl_unit_name = DB::table('optcl_unit_master')->where('id', $optcl_unit)->first();
                                $unit_name = $optcl_unit_name->unit_name;
                              } elseif(!empty($pension_unit)) {
                                
                                $pension_unit_name = DB::table('optcl_pension_unit_master')
                                ->where('id', $pension_unit)->first();
                                
                                $unit_name = $pension_unit_name->pension_unit_name;
                              } else {
                                $unit_name ='';
                              }
                              @endphp
                          <tr>
                            <td >{{ $data->firstItem() + $key }}</td>
                            <td>{{ $list->username }}</td> 
                            {{-- <td>{{ $list->employee_code }}</td>  --}}
                            <td>{{ !empty($user_role_name->type_name) ? $user_role_name->type_name : 'NA' }}</td>
                            <td>{{ !empty($designation_name->designation_name) ? $designation_name->designation_name : 'NA' }}</td>
                            
                            <td>{{ (!empty($unit_name)) ? $unit_name : 'NA' }}</td>
                             
                            <td>
                              <label class="switch">
                              <input type="checkbox" id="{{$list->id}}" class="status_check" @if($list->status == 1 ) checked @endif>
                              <span class="slider round"></span>
                            </label>
                            </td>
                            <td class="text-center">




                              <div class="btn-group border-0">
                                <a href="#" class="btn btn-default dropdown-toggle dropdown-toggle-split action-dropdown" id="dropdownMenuSplitButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <span class="icon-menu"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton1">
                                  <a class="dropdown-item resendEmail" name="{{$list->id}}" href="#"  title="email"><i class="fa fa-envelope-o"></i> Mail</a>
                                  <a class="dropdown-item" href="{{URL('user/edit/'.$list->id)}}" title="edit"><i class="fa fa-edit"></i> Edit</a>
                                  <a class="dropdown-item deleteUser"  title="delete" name="{{$list->id}}"><i class="fa fa-trash-o"></i> Delete</a>
                                </div>
                              </div>



                              {{-- <div class="dropdown show">
                                <div class="fa fa-bars">
                                  <a href="#" class="list-icons-item dropdown-toggle actionIcons" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></a>
                                    <div class="dropdown position-static">
                                        
                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                          <a href="#" class="dropdown-item resendEmail" name="{{$list->id}}"><i class="fa fa-envelope-o"></i> Mail</a>
                                          <a href="{{URL('user/edit/'.$list->id)}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                          <a href="#" class="dropdown-item deleteUser" name="{{$list->id}}"><i class="fa fa-trash-o"></i> Delete</a>
                                      </div>
                                    </div>
                                </div>
                              </div>   --}}
                              {{-- <a class="resendEmail" name="{{$list->id}}" href="#"  title="email"><i class="fa fa-envelope-o"></i></a>&nbsp;
                              <a class="" href="{{URL('user/edit/'.$list->id)}}" title="edit"><i class="fa fa-edit"></i></a>
                              <a class="deleteUser" name="{{$list->id}}" href="#" title="delete"><i class="fa fa-trash-o"></i></a> --}}
                            </td>
                          </tr>
                          @endforeach
                          @else
                          <tr><td colspan="10" align="center">No Data Found</td></tr>    
                          @endif
                        </tbody>
                      </table>
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
           url: '{{route("check_status_user")}}',
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
              location.href=base_url+"/user-delete/"+userID;
        //   $.post(base_url+"member/delete/",{ pageID:idVal }); 
        //   swal({ title: "Data deleted successfully.", type: "success" })
        // .then(function(){
        //       location.reload();
        //   });
          
         } 
        });
    });

    $(".resendEmail").on("click",function () {
    var userID = $(this).attr('name');
    //alert(userID);
    swal( {
      title: "Are you sure",
      text: "Want to resend password generation link to the recipient ?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, send it!',
      closeOnConfirm: true,
      closeOnCancel: true,
       })
        .then((value) => { 
          if(value){ 
              location.href=base_url+"/user/resend-email/"+userID;
          } 
        });
    });

    $("#filter_user").validate({
            rules: {
              user_role: {
                required: true 
              },
              designation: {
                required: true 
              }
            },
            messages: {
              user_role: {
                required: 'Please select user type'
              },
              designation: {
                required: 'Please select user designation'
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
    $('#user_role').change(function(){
        let uid=$(this).val();
        //console.log(uid);
				$('#designation').html('<option value="">Select User Designation</option>')
        
				$.ajax({
					url:"{{route('user_desgnation_data')}}",
					type:'post',
					data:'uid='+uid+'&_token={{csrf_token()}}',
					success:function(result){
             $('#designation').html(result);
             $('#designation').val("<?php echo $search2 ?>");
					}
				});
		  }).trigger('change');


  });
</script>
  

@endsection