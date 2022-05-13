@extends('user.layout.layout')

@section('section_content')

<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Pension Proposal List</h4>
            <div class="row">
              <div class="table-sorter-wrapper col-lg-12 table-responsive">
                <table id="sampleTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Sl No.</th>
                      <th class="sortStyle">Name</th>
                      <th class="sortStyle">Aadhaar No.</th>
                      <th class="sortStyle">Designation</th>
                      <th class="sortStyle">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($proposal_listing as $key => $list)
                    <tr>
                      <td>{{-- $key + 1 --}}</td>
                      <td>{{$list->name}}</td>
                      <td>{{$list->aadhaar_no}}</td>
                      <td>{{$list->designation}}</td>
                      <td>
                        <a class="btn btn-outline-primary" href="{{ route('proposal_view', array($list->id)) }}">View</a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
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

  $(document).ready(function() {
    $('#sampleTable').DataTable({
      lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
      "iDisplayLength": 10,
      "rowCallback": function (nRow, aData, iDisplayIndex) {
         var oSettings = this.fnSettings ();
         $("td:nth-child(1)", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
         return nRow;
      },
      "columnDefs": [
          { "searchable": false, "targets": 0 }
      ]
    });
  });
</script>
@endsection