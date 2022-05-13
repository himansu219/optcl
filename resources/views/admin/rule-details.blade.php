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
                  <li class="breadcrumb-item active" aria-current="page">Rules</li>
                  
                </ol>
              </nav>
           <div class="card">
              <div class="card-body">
                  <h4 class="card-title">Filter</h4>
                  <form class="forms-sample" id="filter_rule" method="post" action="{{ route('rule_details') }}">
                      @csrf
                      <div class="row">
                        <div class="col-md-4">
                           <label>Pension Type <span class="span-red">*</span></label>
                            <select class="js-example-basic-single form-control"  id="pension_type" name="pension_type"  autocomplete="off">
                              <option value="">Select Pension Type</option>
                                @foreach($pension_type as $list)
                                  <option @if(!empty($search) && $list->id == $search) selected @endif value="{{$list->id}}">{{$list->pension_type}}</option>
                                @endforeach
                             </select>
                             <label id="pension_type-error" class="error mt-2 text-danger" for="pension_type"></label>
                        </div>
                        <div class="col-md-4">
                          <label>Calculation Type</label>
                           <select class="js-example-basic-single form-control"  id="calculation_type" name="calculation_type"  autocomplete="off">
                             <option value="">Select Calculation Type</option>
                               @foreach($calculation_type as $list)
                                 <option @if(!empty($search2) && $list->id == $search2) selected @endif value="{{$list->id}}">{{$list->calculation_type}}</option>
                               @endforeach
                              </select>
                              <label id="calculation_type-error" class="error mt-2 text-danger" for="calculation_type"></label>
                         </div>
                                              
                      </div>
                      <button type="submit" class="btn btn-success">Filter</button>
                  </form>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                 <h4 class="card-title">Manage Calculation Rules</h4>
                 <a class="btn btn-success btn-sm addClassbtn" href="{{route('rule_add')}}"><i class="icon-plus">ADD</i></a>
                  <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                     
                      <table id="sortable-table-1" class="table table-striped">
                        <thead>
                          <tr>
                            <th>Sl No.</th>
                            <th class="sortStyle">Pension Type</th>
                            <th class="sortStyle">Calculation Type</th> 
                            <th class="sortStyle">Rule Name</th>
                            <th class="sortStyle">Rule Description</th>
                            <th class="sortStyle">Status</th>
                            <th class="sortStyle">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                        @if(count($result))
                        @foreach($result as $key => $list)
                           @php
                              $pension_type = $list->pension_type_id;
                              $calculation_type = $list->calculation_type_id;
                              $pension_type_name = DB::table('optcl_pension_type_master')->where('id', $pension_type)->first();
                              $calculation_type_name = DB::table('optcl_calculation_type_master')->where('id', $calculation_type)->first();
                              
                            @endphp
                          <tr>
                            <td>{{ $result->firstItem() + $key }}</td>
                            <td>{{ !empty($pension_type_name->pension_type) ? $pension_type_name->pension_type : 'NA' }}</td>
                            <td>{{ !empty($calculation_type_name->calculation_type) ? $calculation_type_name->calculation_type : 'NA' }}</td>
                            <td>{{ $list->rule_name }}</td>
                            <td width="300" height="50">{{ $list->rule_description }}</td>
                            <td>
                              <label class="switch">
                              <input type="checkbox" id="{{$list->id}}" class="status_check" @if($list->status == 1 ) checked @endif>
                              <span class="slider round"></span>
                            </label>
                            </td>
                            <td>
                              <a class="" href="{{URL('rule/edit/'.$list->id)}}" title="edit"><i class="fa fa-edit"></i></a>
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
           url: '{{route("check_status_calculation")}}',
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
              location.href=base_url+"/rule-delete/"+userID;
        //   $.post(base_url+"member/delete/",{ pageID:idVal }); 
        //   swal({ title: "Data deleted successfully.", type: "success" })
        // .then(function(){
        //       location.reload();
        //   });
          
         } 
      });
    });

     $("#filter_rule").validate({
            rules: {
              pension_type: {
                required: true 
              }
            },
            messages: {
              pension_type: {
                required: 'Please select pension type'
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