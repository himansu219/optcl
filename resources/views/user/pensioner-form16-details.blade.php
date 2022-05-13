@extends('user/layout.layout')

@section('section_content')
      
<div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
                  <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Form 16</li>
                      </ol>
                  </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">View Form 16</h4>
                   <div class="row">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    
                      <table id="sortable-table-1" class="table table-striped">
                        <thead>
                          <tr>
                            <th>Sl No.</th>
                            <th class="sortStyle">Form 16 File</th>
                            <th class="sortStyle">Upload Date</th>
                            {{-- <th class="sortStyle">View</th> --}}
                          </tr>
                        </thead>
                        <tbody>
                        @if(count($result))
                        @foreach($result as $key => $list)
                          <tr>
                            <td>{{ $result->firstItem() + $key }}</td>
                            {{-- <td>{{$list->form_16_file_path}}</td> --}}
                            <td>
                              <a href="{{ asset('public/' . $list->form_16_file_path) }}" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                              </td>
                            <td>{{ \Carbon\Carbon::parse($list->upload_date)->format('d-m-Y') }}</td>
                            
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

 
  @endsection