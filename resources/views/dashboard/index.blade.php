@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<?php
    $totalTareos = json_decode($totalTareos);
    $pendientes = json_decode($pendientes);
    $jsPend = $pendientes[0]->total;
    $traslado = json_decode($traslado);
    $jsTra = $traslado[0]->total;
    $procesos = json_decode($procesos);
    $jsPro = $procesos[0]->total;
    $pendaprob = json_decode($pendaprob);
    $jsPendAprob = $pendaprob[0]->total;
    $aprobado = json_decode($aprobado);
    $jsAprob = $aprobado[0]->total;
?>
<div class="container-fluid sourcesans">
    <div class="row mb-2">
        <div class="col-sm-6">

        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
<!-- Info boxes -->
<div class="row sourcesans">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-desktop"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Mi IP</span>
                <span class="info-box-number">{{ $mycpu }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-bell"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Labores del día</span>
                <span class="info-box-number">{{ $numLab }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users-cog"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Operadores registrados</span>
                <span class="info-box-number">{{ $numOper }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-calendar-check"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Hoy</span>
                <span class="info-box-number">{{ $hoy }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Estado tareos hoy</h3>

                <!-- <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div> -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-responsive">
                            <canvas id="pieChart" height="150"></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <style>
                            .pendiente { color: #FEBE03 !important;}
                            .traslado { color: #FFA500 !important;}
                            .proceso { color: #03A9F4 !important;}
                            .pendaprob { color: #FF7E00 !important;}
                            .aprob { color: #4CAF50 !important;}
                            </style>
                        <ul class="chart-legend clearfix">
                            <li><i class="fas fa-circle pendiente"></i> Pendiente</li>
                            <li><i class="fas fa-circle traslado"></i> Traslado</li>
                            <li><i class="fas fa-circle proceso"></i> En Proceso</li>
                            <li><i class="fas fa-circle pendaprob"></i> Pend. Aprobar</li>
                            <li><i class="fas fa-circle aprob"></i> Aprobado</li>
                        </ul>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer p-0">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <label class="nav-link">
                            Total de tareos
                            <span class="float-right text-dark">
                                <i class="fas fa-chart-pie mr-2"></i>
                                {{ $totalTareos[0]->total }}
                            </span>
                        </label>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="#" class="nav-link">
                            India
                            <span class="float-right text-success">
                                <i class="fas fa-arrow-up text-sm"></i> 4%
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            China
                            <span class="float-right text-warning">
                                <i class="fas fa-arrow-left text-sm"></i> 0%
                            </span>
                        </a>
                    </li> -->
                </ul>
            </div>
            <!-- /.footer -->
        </div>
    </div>

</div>
@stop
@section('js')
<script>
//-------------
  // - PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
  let d1 = parseInt('{!! $jsPend !!}');
  let d2 = parseInt('{!! $jsTra !!}');
  let d3 = parseInt('{!! $jsPro !!}');
  let d4 = parseInt('{!! $jsPendAprob !!}');
  let d5 = parseInt('{!! $jsAprob !!}');

  var pieData = {
    labels: [
      'Pendiente',
      'Traslado',
      'En Proceso',
      'Pend. Aprobar',
      'Aprobado',
    ],
    datasets: [
      {
        data: [ d1, d2, d3, d4, d5],
        // data: [ 4, 3, 2, 1],
        backgroundColor: ['#FEBE03', '#FFA500', '#03A9F4', '#FF7E00', '#4CAF50']
      }
    ]
  }
  var pieOptions = {
    legend: {
      display: false
    }
  }
  // Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  // eslint-disable-next-line no-unused-vars
  var pieChart = new Chart(pieChartCanvas, {
    type: 'doughnut',
    data: pieData,
    options: pieOptions
  })

  //-----------------
  // - END PIE CHART -
  //-----------------
</script>
@stop
