@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $kategori }}</h3>

                    <p>Total Kategori</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cube"></i>
                </div>
                <a href="{{ route('kategori.index') }}" class="small-box-footer">Lihat <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $produk }}</h3>

                    <p>Total Produk</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <a href="{{ route('produk.index') }}" class="small-box-footer">Lihat <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $supplier }}</h3>

                    <p>Total Supplier</p>
                </div>
                <div class="icon">
                    <i class="fa fa-truck"></i>
                </div>
                <a href="{{ route('supplier.index') }}" class="small-box-footer">Lihat <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $user }}</h3>

                    <p>Total User</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="{{ route('user.index') }}" class="small-box-footer">Lihat <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Grafik Pendapatan {{ tanggal_indonesia($tanggal_awal, false) }} s/d
                        {{ tanggal_indonesia($tanggal_akhir, false) }}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="chart">
                                <!-- Sales Chart Canvas -->
                                <canvas id="salesChart" style="height: 200px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.box -->
        </div>

        <div class="col-lg-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Total Pendapatan Vs Pengeluaran</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="chartContainer" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Stok Opname</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12" style="height: 350px;">
                            <table id="produkTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($opname as $item)
                                        <tr>
                                            <td>{{ $item->kode_produk }}</td>
                                            <td>{{ $item->nama_produk }}</td>
                                            <td>{{ $item->stok }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>
    <script>
        $(function() {
            // Get context with jQuery - using jQuery's .get() method.
            var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
            // This will get the first returned node in the jQuery collection.
            var salesChart = new Chart(salesChartCanvas);

            var salesChartData = {
                labels: {{ json_encode($data_tanggal) }},
                datasets: [{
                    label: 'Pendapatan',
                    fillColor: 'rgba(60,141,188,0.9)',
                    strokeColor: 'rgba(60,141,188,0.8)',
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: {{ json_encode($data_pendapatan) }}
                }]
            };

            var salesChartOptions = {
                pointDot: false,
                responsive: true
            };

            salesChart.Line(salesChartData, salesChartOptions);
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with vertical scroll
            $('#produkTable').DataTable({
                "scrollY": "250px",
                "scrollCollapse": true,
                "searching": false,
                "paging": false
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create the chart
            Highcharts.chart('chartContainer', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: null // Disable title
                },
                xAxis: {
                    categories: ['Total Pendapatan', 'Total Pengeluaran']
                },
                yAxis: {
                    title: {
                        text: 'Amount'
                    }
                },
                series: [{
                    name: 'Pendapatan',
                    color: 'rgba(75, 192, 192, 0.7)', // Green color
                    data: [
                        {{ $totalPendapatan }}
                    ] // Replace with the actual total pendapatan value
                }, {
                    name: 'Pengeluaran',
                    color: 'rgba(255, 99, 132, 0.7)', // Red color
                    data: [{{ $totalKeluar }}] // Replace with the actual total pengeluaran value
                }],
                plotOptions: {
                    column: {
                        borderColor: 'rgba(0, 0, 0, 0.3)', // Border color with alpha
                        borderWidth: 1
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    }
                },

            });
        });
    </script>
@endpush
