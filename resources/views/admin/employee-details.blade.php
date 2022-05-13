@extends('user.layout.layout')

@section('container')
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
                        <!-- <li class="breadcrumb-item" ><a href="#">Master Management</a></li> -->
                        <li class="breadcrumb-item active" aria-current="page">Pensioner</li>
                        
                      </ol>
                    </nav>
              <!-- <div class="card">
              
               <div class="card-body">
                  <h4 class="card-title">Filter</h4>
                  <form class="forms-sample" id="filter_employee_form" method="post" action="{{ route('employee_details') }}">
                      @csrf
                      <div class="row">
                       <div class="col-md-4">
                          <div class="form-group">
                            <label>Start Date  <span class="span-red">*</span></label>
                              <div id="datepicker-joining" class="input-group date datepicker ">
                                <input type="text" class="form-control" autocomplete="off" id="start_date" name="start_date" placeholder="Enter start date">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                              </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>End Date  <span class="span-red">*</span></label>
                              <div id="datepicker-joining" class="input-group date datepicker ">
                                <input type="text" class="form-control" autocomplete="off" id="end_date" name="end_date" placeholder="Enter end date">
                                  <span class="input-group-addon input-group-append border-left">
                                    <span class="mdi mdi-calendar input-group-text"></span>
                                  </span>
                              </div>
                          </div>
                        </div>
                                              
                      </div><br>
                      <button type="submit" class="btn btn-success">Filter</button>
                  </form>
                </div>
              </div> -->
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Manage Pensioner</h4>
                  <a class="btn btn-success btn-sm addClassbtn" href="{{route('employee_add')}}"><i class="icon-plus">ADD</i></a>                  
                  <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    
                      <table id="sortable-table-1" class="table table-striped">
                        <thead>
                          <tr>
                            <th>Sl No.</th>
                            <th class="sortStyle">Name</th>
                            <th class="sortStyle">Designation</th>
                            <th class="sortStyle">OPTCL Unit</th>
                            <th class="sortStyle">Status</th>
                            <th class="sortStyle">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                        
                        @if(count($result))
                        
                        @foreach($result as $key => $list)
                            @php
                              
                              $designation = $list->designation_id;
                              $optcl_unit = $list->optcl_unit_id;
                             
                              $designation_name = DB::table('optcl_user_designation_master')->where('id', $designation)->first();
                              $optcl_unit_name = DB::table('optcl_unit_master')->where('id', $optcl_unit)->first();
                            @endphp
                        
                          <tr>
                            <td>{{ $result->firstItem() + $key }}</td>
                            <td>{{ $list->employee_name }}</td> 
                            <td>{{ !empty($designation_name->designation_name) ? $designation_name->designation_name : 'NA' }}</td>
                            <td>{{ !empty($optcl_unit_name->unit_name) ? $optcl_unit_name->unit_name : 'NA'}}</td>
                            <td>
                              <label class="switch">
                              <input type="checkbox" id="{{$list->id}}" class="status_check" @if($list->status == 1 ) checked @endif>
                              <span class="slider round"></span>
                            </label>
                            </td>
                            <td>
                              {{-- <div class="dropdown show">
                                <div class="fa fa-bars">
                                  <a href="#" class="list-icons-item dropdown-toggle actionIcons" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></a>
                                    <div class="dropdown position-static">
                                        
                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                          <a href="{{URL('employee/edit/'.$list->id)}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                          <a href="#" class="dropdown-item deleteUser" name="{{$list->id}}"><i class="fa fa-trash-o"></i> Delete</a>
                                      </div>
                                    </div>
                                </div>
                              </div> --}}
                            <a class="" href="{{URL('employee/edit/'.$list->id)}}" title="edit"><i class="fa fa-edit"></i></a>
                            <a class="deleteUser" name="{{$list->id}}" href="#" title="delete"><i class="fa fa-trash-o"></i></a>
                            </td>
                          </tr>
                          @endforeach
                          @else
                          <tr><td colspan="10" align="center">No Data Found</td></tr>    
                          @endif
                        </tbody>
                      </table>
                      @if ($result->lastPage() > 1)
                          <nav aria-label="..." class="float-right">
                              <ul class="pagination">
                                  <li class="{{ ($result->currentPage() == 1) ? ' disabled' : '' }} page-item">
                                      <a class="page-link" href="{{ $result->url(1) }}">Previous</a>
                                  </li>
                                  @for ($i = 1; $i <= $result->lastPage(); $i++)
                                      <li class="{{ ($result->currentPage() == $i) ? ' active' : '' }}  page-item">
                                          <a class="page-link" href="{{ $result->url($i) }}">{{ $i }}</a>
                                      </li>
                                  @endfor
                                  <li class="{{ ($result->currentPage() == $result->lastPage()) ? ' disabled' : '' }} page-item">
                                      <a class="page-link" href="{{ $result->url($result->currentPage()+1) }}" >Next</a>
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
           url: '{{route("check_status_employee")}}',
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
              location.href=base_url+"/employee_delete/"+userID;
        //   $.post(base_url+"member/delete/",{ pageID:idVal }); 
        //   swal({ title: "Data deleted successfully.", type: "success" })
        // .then(function(){
        //       location.reload();
        //   });
          
         } 
        });
    });

});  
</script>

@endsection