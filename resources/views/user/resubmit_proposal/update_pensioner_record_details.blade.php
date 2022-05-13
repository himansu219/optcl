@extends('user.layout.layout')

@section('section_content')
<style>
 
  .addClassbtn {
        margin-left:870px;
        margin-top: -40px;
    }
   /* .fontSize {
       font-weight: 600;
   } */
  
</style>
<div class="content-wrapper">
    <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#">Update Pension Record</a></li>
          <li class="breadcrumb-item active" aria-current="page">Listing</li>
        </ol>
      </nav>
    <div class="row">
        
        <div class="col-md-12 grid-margin stretch-card">
            
            <div class="card">
                <div class="card-body">
                    @if(Session::has('error'))
                     <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                    @if(Session::has('success'))
                     <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                      
                      
                    <div class="employe-code-check">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                                <div class="media">
                                    <div class="media-body">
                                        <h6 class="card-title">Update Pension Record</h6>
                                        @php
                                            $dclass = '';
                                            if($flag > 0){
                                            $dclass = 'd-none';
                                            }  
                                        @endphp
                                    <a class="btn btn-success btn-sm addClassbtn {{$dclass}}" href="{{route('update_pension_record_add')}}"><i class="icon-plus">ADD</i></a>
                                        <hr>
                                
                                           <table id="sortable-table-1" class="table table-striped">
                                            <thead>
                                              <tr>
                                                <th class="sortStyle">Sl No.</th>
                                                <th class="sortStyle">Bank A/C No.</th>
                                                <th class="sortStyle">Bank Name</th>
                                                <th class="sortStyle">Branch</th>
                                                <th class="sortStyle">IFSC Code</th>
                                                <th class="sortStyle">Created at</th>
                                                <th class="sortStyle">Status</th>
                                                <th class="sortStyle">Action</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                                
                                            @if(count($data))
                                                @foreach ($data as $key => $list)
                                                @php
                                                $bank_branch_id = $list->bank_branch_id;
                                                $ifsc = DB::table('optcl_bank_branch_master')->where('id', $bank_branch_id)->first();
                                                $bname = DB::table('optcl_bank_master')->where('id', $ifsc->bank_id)->first();
                                                $status = '';
                                                $hideedit='';
                                                    if($list->is_approved == 0){
                                                        //$show = 'Yes';
                                                        $dClass = 'badge-warning';
                                                        $status = 'Pending';

                                                    }else{
                                                        //$show = 'No';
                                                        $dClass = 'badge-success';
                                                        $status = 'Approved';
                                                        $hideedit = 'd-none';
                                                        
                                                    }
                                                @endphp
                                                <tr class="tablerow" style="background-color: white">
                                                    <td>{{ $data->firstItem() + $key }}</td>
                                                    <td>{{ $list->bank_ac_no }}</td>
                                                    <td>{{ $bname->bank_name }}</td>
                                                    <td>{{ $ifsc->branch_name }}</td>
                                                    <td>{{ $ifsc->ifsc_code }}</td>
                                                    <td>{{ date("d-m-Y h:i A", strtotime($list->created_at)) }}</td>
                                                    
                                                    <td><label class="badge {{$dClass}} ">{{ $status }}</label></td>
                                                    <td><a href="{{URL('user/update-pension-record/edit/'.$list->id)}}" title="edit"><i class="fa fa-edit {{$hideedit}}"></i></a>
                                                    <a href="{{URL('user/update-pension-record/view/'.$list->id)}}" title="view"><i class="icon-eye"></i></a>
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
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
              


 @endsection
 @section('page-script')

 @endsection