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
                          <li class="breadcrumb-item" ><a href="#">Master Management</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Bank Branch</li>
                        </ol>
                  </nav>
            <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Filter</h4>
                  <form class="forms-sample" id="filter_bank_name" method="post" action="{{ route('bank_branch_details') }}">
                      @csrf
                      <div class="row">
                        <div class="col-md-4">
                        <label>Bank Name</label> <span class="span-red">*</span>
                        <select class="js-example-basic-single form-control"  id="bank_name" name="bank_name"  autocomplete="off">
                        <option value="">Select Bank Name</option>
                        @foreach($bank_name as $list)
                        <option @if(!empty($search) && $list->id == $search) selected @endif value="{{$list->id}}">{{$list->bank_name}}</option>
                        @endforeach
                        </select>
                        <label id="bank_name-error" class="error mt-2 text-danger" for="bank_name"></label>
                        </div>
                                              
                      </div>
                      <button type="submit" class="btn btn-success">Filter</button>
                  </form>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Manage Bank Branches</h4>
                  <a class="btn btn-success btn-sm addClassbtn" href="{{route('bank_branch_add')}}"><i class="icon-plus">ADD</i></a>
                  <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                      <table id="sortable-table-1" class="table table-striped">
                        <thead>
                          <tr>
                            <th>Sl No.</th>
                            <th class="sortStyle">Bank Name</th>
                            <th class="sortStyle">Branch Name</th> 
                            <th class="sortStyle">IFSC Code</th>
                            <th class="sortStyle">MICR Code</th>
                            <th class="sortStyle">Status</th>
                            <th class="sortStyle">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                        @if(count($result))
                        @foreach($result as $key => $list)
                          <tr>
                            <td>{{ $result->firstItem() + $key }}</td>
                            <td>{{ $list->bank->bank_name }}</td> 
                            <td>{{ $list->branch_name }}</td>
                            <td>{{ $list->ifsc_code }}</td>
                            <td>{{ $list->micr_code }}</td>
                            <td>
                              <label class="switch">
                              <input type="checkbox" id="{{$list->id}}" class="status_check" @if($list->status == 1 ) checked @endif>
                              <span class="slider round"></span>
                            </label>
                            </td>
                            <td>
                            <a class="" href="{{URL('bank-branch/edit/'.$list->id)}}" title="edit"><i class="fa fa-edit"></i></a>
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
           url: '{{route("check_status_bank_branch")}}',
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
              location.href=base_url+"/bank-branch-delete/"+userID;
        //   $.post(base_url+"member/delete/",{ pageID:idVal }); 
        //   swal({ title: "Data deleted successfully.", type: "success" })
        // .then(function(){
        //       location.reload();
        //   });
          
         } 
        });
    });

       $("#filter_bank_name").validate({
            rules: {
              bank_name: {
                required: true 
              }
            },
            messages: {
              bank_name: {
                required: 'Please select bank name'
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
  });   
 </script>

@endsection