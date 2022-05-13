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
                        <li class="breadcrumb-item active" aria-current="page">User Designation</li>
                         
                      </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Manage User Designation</h4>
                  <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                      <table id="sortable-table-1" class="table table-striped">
                        <thead>
                          <tr>
                            <th>Sl No.</th>
                            <th class="sortStyle">Designation Name</th>
                            <th class="sortStyle">User Label</th>
                            <th class="sortStyle">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                        @if(count($result))
                        @foreach($result as $key => $list)
                        @php
                        $role_id = $list->user_role_id;
                        $role_name = DB::table('optcl_user_role_master')->where('id', $role_id)->first();
                        @endphp
                          <tr>
                            <td>{{ $result->firstItem() + $key }}</td>
                            <td>{{$list->designation_name}}</td>
                            <td>{{ !empty($role_name->type_name) ? $role_name->type_name : 'NA' }}</td>
                            <td>
                              <label class="switch">
                              <input type="checkbox" id="{{$list->id}}" class="status_check" @if($list->status == 1 ) checked @endif>
                              <span class="slider round"></span>
                            </label>
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
    $(document).ready(function(){
       $(function() {
       $('.status_check').change(function() {
       var status = $(this).prop('checked') == true ? 1 : 0; 
       var id = $(this).attr('id'); 
       console.log(id);
       $.ajax({
           type: "POST",
           url: '{{route("check_status_user_designation")}}',
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
    });
  </script>
@endsection