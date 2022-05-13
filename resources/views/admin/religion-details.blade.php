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
                        <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Religion Master</li>
                      </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Manage Religions</h4>
                  <a class="btn btn-success btn-sm addClassbtn" href="{{route('religion_add')}}"><i class="icon-plus">ADD</i></a>
                   <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    
                      <table id="sortable-table-1" class="table table-striped">
                        <thead>
                          <tr>
                            <th>Sl No.</th>
                            <!-- <th class="sortStyle">Country Name<i class="mdi mdi-chevron-down"></i></th>
                            <th class="sortStyle">State Name<i class="mdi mdi-chevron-down"></i></th> -->
                           
                            <!-- <th class="sortStyle">Religion Code</th> -->
                            <th class="sortStyle">Religion Name</th>
                            <th class="sortStyle">Status</th>
                            <th class="sortStyle">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if(count($result))
                          @foreach($result as $key => $list)
                            <tr>
                              <td>{{ $result->firstItem() + $key }}</td>
                              
                              <!-- <td>{{$list->religion_code}}</td> -->
                              <td>{{$list->religion_name}}</td>
                              <td>
                                <label class="switch">
                                <input type="checkbox" id="{{$list->id}}" class="status_check" @if($list->status == 1 ) checked @endif>
                                <span class="slider round"></span>
                              </label>
                              </td>
                              <td>
                                <a class="" href="{{URL('religion/edit/'.$list->id)}}"title="edit"><i class="fa fa-edit"></i></a>
                                <a class="deleteUser" name="{{$list->id}}"  href="#"title="delete"><i class="fa fa-trash-o"></i></a>
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
          url: '{{route("check_status_religion")}}',
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
      //closeOnConfirm: false, closeOnCancel: false,
       })
        .then((value) => { 
          if(value){ 
              location.href=base_url+"/religion_delete/"+userID;
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