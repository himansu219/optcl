@extends('user/layout.layout')

@section('section_content')
 <div class="content-wrapper">
    
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card card-statistics">
          <div class="row">
            <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
              <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <i class="mdi mdi-account-multiple-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                    <div class="wrapper text-center text-sm-left">
                      <p class="card-text mb-0">New Users</p>
                      <div class="fluid-container">
                        <h3 class="card-title mb-0">65,650</h3>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
              <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <i class="mdi mdi-checkbox-marked-circle-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                    <div class="wrapper text-center text-sm-left">
                      <p class="card-text mb-0">New Feedbacks</p>
                      <div class="fluid-container">
                        <h3 class="card-title mb-0">32,604</h3>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
              <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <i class="mdi mdi-trophy-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                    <div class="wrapper text-center text-sm-left">
                      <p class="card-text mb-0">Employees</p>
                      <div class="fluid-container">
                        <h3 class="card-title mb-0">17,583</h3>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
              <div class="card-body">
                  <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <i class="mdi mdi-target text-primary mr-0 mr-sm-4 icon-lg"></i>
                    <div class="wrapper text-center text-sm-left">
                      <p class="card-text mb-0">Total Sales</p>
                      <div class="fluid-container">
                        <h3 class="card-title mb-0">61,119</h3>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="chartjs-size-monitor">
              <div class="chartjs-size-monitor-expand">
                <div class=""></div>
              </div>
              <div class="chartjs-size-monitor-shrink">
                <div class=""></div>
              </div>
            </div>
            <h5 class="card-title">The Current Chart</h5>
            <canvas id="current-chart" height="190" width="572" style="display: block; width: 572px; height: 190px;" class="chartjs-render-monitor"></canvas>
          </div>
          <!-- <div class="border-top py-4 px-4">
            <p class="mb-0 text-gray">Projects Status</p>
            <div class="d-flex align-items-end">
              <h2 class="mb-0 display-2 font-weight-semibold">76,533</h2>
              <p class="mb-2 ml-1">PCS</p>
            </div>
          </div> -->
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="table-responsive">
            <table class="table center-aligned-table">
              <thead>
                <tr>
                  <th class="border-bottom-0">Sl No.</th>
                  <th class="border-bottom-0">Pensioner Name</th>
                  <th class="border-bottom-0">Application Type</th>
                  <th class="border-bottom-0">Application No.</th>
                  <th class="border-bottom-0">Employee Code</th>
                  <th class="border-bottom-0">PPO No</th>
                  <th class="border-bottom-0">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Rasmita Biswala</td>
                  <td>Service Pensioner</td>
                  <td>202200001</td>
                  <td>42600</td>
                  <td>03/2022/00001</td>
                  <td><label class="badge badge-success">Bill Generated</label></td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Sasmita Behera</td>
                  <td>Service Pensioner</td>
                  <td>202200002</td>
                  <td>42601</td>
                  <td>03/2022/00002</td>
                  <td><label class="badge badge-success">Bill Generated</label></td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Parineeti Jena</td>
                  <td>Family Pensioner</td>
                  <td>202200003</td>
                  <td>42603</td>
                  <td>03/2022/00003</td>
                  <td><label class="badge badge-success">Bill Generated</label></td>
                </tr>
                <tr>
                  <td>4</td>
                  <td>Susmita Sahoo</td>
                  <td>Service Pensioner</td>
                  <td>202200004</td>
                  <td>42604</td>
                  <td>03/2022/00004</td>
                  <td><label class="badge badge-success">PPO Order Generated</label></td>
                </tr>
                <tr>
                  <td>5</td>
                  <td>Sanooj Rout</td>
                  <td>Family Pensioner</td>
                  <td>202200005</td>
                  <td>42605</td>
                  <td>03/2022/00005</td>
                  <td><label class="badge badge-success">Bill Generated</label></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    
  </div> 

  @endsection

  @section('page-script')
  <script type="text/javascript">
    $(function() {
     
      if ($("#current-chart").length) {
        var CurrentChartCanvas = $("#current-chart").get(0).getContext("2d");
        var CurrentChart = new Chart(CurrentChartCanvas, {
          type: 'bar',
          data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [{
                label: 'Pensioner',
                data: [330, 380, 230, 400, 309, 530, 340, 200],
                backgroundColor: 'rgba(70, 77, 228, 1)'
              },
              /* {
                label: 'Target',
                data: [600, 600, 600, 600, 600, 600, 600],
                backgroundColor: 'rgba(238, 242, 245, 1)'
              } */
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            layout: {
              padding: {
                left: 0,
                right: 0,
                top: 20,
                bottom: 0
              }
            },
            scales: {
              yAxes: [{
                display: false,
                gridLines: {
                  display: false
                }
              }],
              xAxes: [{
                stacked: true,
                ticks: {
                  beginAtZero: true,
                  fontColor: "#354168"
                },
                gridLines: {
                  color: "rgba(0, 0, 0, 0)",
                  display: false
                },
                barPercentage: 0.4
              }]
            },
            legend: {
              display: false
            },
            elements: {
              point: {
                radius: 0
              }
            }
          }
        });
      }
    });
  </script>
  @endsection