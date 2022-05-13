@extends('user.layout.layout')

@section('section_content')
<style>
  #income_property_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #other_income_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #lic_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #nsc_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ppf_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ety_d_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ety_dd_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  .mdi-pencil{
    font-size:20px;
    color:rgb(74, 172, 74);
  }
  .mdi-pencil:hover{
    color:rgb(0, 100, 0);
  }
  .mdi-delete{
    font-size:20px;
    color:rgb(225, 83, 83);
  }
  .mdi-delete:hover{
    color:rgb(191, 0, 0);
  }
  #sampleTable{
      border: 1px solid rgb(233, 233, 233);
  }
  .addClassbtn {
        margin-left:900px;
    }
  
</style>
<div class="content-wrapper">
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
                      <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                          <li class="breadcrumb-item"><a href="">Tax Declaration</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Listing</li>
                        </ol>
                      </nav>   
                    <div class="employe-code-check">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                                <div class="media">
                                    <div class="media-body">
                                        <h6 class="text-center-normal">TAX DECLARATION</h6>
                                        <hr>
                                
                                           <table id="sortable-table-1" class="table table-striped">
                                            <thead>
                                              <tr>
                                                <th class="sortStyle">Sl No.</th>
                                                <th class="sortStyle">Employee Code</th>
                                                <th class="sortStyle">Status</th>
                                                <th class="sortStyle">Created Date</th>
                                                <th class="sortStyle">Action</th>
                                                
                                                {{-- <th class="sortStyle">Delete</th> --}}
                                                {{--<th class="sortStyle">Status</th>
                                                 <th class="sortStyle">Actions</th> --}}
                                              </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($result))
                                            @foreach($result as $key => $list)
                                                 @php
                                                  //$dClass = '';
                                                  $status = '';
                                                  if($list->is_approved == 0){
                                                      //$show = 'Yes';
                                                      $dClass = 'badge-warning';
                                                      $status = 'Pending';
                                                  }else{
                                                      //$show = 'No';
                                                      $dClass = 'badge-success';
                                                      $status = 'Approved';
                                                      
                                                  }
                                                   @endphp 
                                              <tr>
                                                <td>{{ $result->firstItem() + $key }}</td>
                                                <td>{{ $list->emp_code }}</td>
                                                <td>{{ \Carbon\Carbon::parse($list->created_at)->format('d-m-Y h:i:s') }}</td>
                                                <td>
                                                  <label class="badge {{$dClass}} ">{{ $status }}</label>
                                                      {{-- <label class="badge {{$dClass}}">Pending</label> --}}
                                                </td>
                                                <td><a  href="{{URL('unit-head/tax-declaration/view/'.$list->id)}}"><i class="icon-eye"></i></a></td>
                                                {{-- <td> <a href="{{ asset('public/' . $list->income_property_file) }}" target="_blank">{{ $list->income_property }}</a></td>
                                                <td> <a href="{{ asset('public/' . $list->other_income_file) }}" target="_blank">{{ $list->other_income }}</a></td>
                                                <td> <a href="{{ asset('public/' . $list->lic_file) }}" target="_blank">{{ $list->lic }}</a></td>
                                                <td> <a href="{{ asset('public/' . $list->nsc_file) }}" target="_blank">{{ $list->nsc }}</a></td>
                                                <td><a href="{{ asset('public/' . $list->ppf_file) }}" target="_blank">{{ $list->ppf }}</a></td>
                                                <td> <a href="{{ asset('public/' . $list->eighty_d_file) }}" target="_blank">{{ $list->eighty_d }}</a></td>
                                                <td> <a href="{{ asset('public/' . $list->eighty_dd_file) }}" target="_blank">{{ $list->eighty_dd }}</a></td> --}}


                                                {{-- <td> <a href="{{ asset('public/' . $list->form_16_file_path) }}" target="_blank"><i class="icon-eye"></i></a> </td> --}}
                                                {{-- <td>
                                                  <label class="switch">
                                                  <input type="checkbox" id="{{$list->id}}" class="status_check" @if($list->status == 1 ) checked @endif>
                                                  <span class="slider round"></span>
                                                </label>
                                                </td>
                                                <td>
                                                <a class="" href="{{URL('form16/edit/'.$list->id)}}"title="edit"><i class="icon-close"></i></a>
                                                <a class="deleteUser" name="{{$list->id}}"  href="#"title="delete"><i class="icon-trash"></i></a>
                                                </td> --}}
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
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
              


 @endsection
 @section('page-script')

 @endsection