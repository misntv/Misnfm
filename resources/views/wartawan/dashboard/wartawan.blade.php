@extends('wartawan.layouts.app')

@section('title', 'Dashboard Wartawan')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #cfe7e4; min-height: 100vh;">
    <div class="text-center mb-4">
        <h4 class="fw-bold" style="color: #6b3e26;">Statistik Pembaca</h4>
    </div>

    <!-- Grafik -->
    <div class="bg-white rounded shadow p-3 mb-4">
        <div id="readerChart" style="min-height: 300px;"></div>
    </div>

    <!-- Kotak Statistik -->
    <div class="row text-center">
        <div class="col-md-4 mb-3">
            <div class="bg-white p-4 rounded shadow">
                <h6>Berita Diunggah</h6>
                <h3 class="text-primary fw-bold">{{ $uploadedCount ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="bg-white p-4 rounded shadow">
                <h6>Berita Diajukan</h6>
                <h3 class="text-warning fw-bold">{{ $submittedCount ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="bg-white p-4 rounded shadow">
                <h6>Berita Ditolak</h6>
                <h3 class="text-danger fw-bold">{{ $rejectedCount ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const options = {
            series: [{
                name: 'Jumlah Pembaca',
                data: @json($monthlyReaders ?? [])
            }],
            chart: {
                height: 300,
                type: 'bar',
                toolbar: { show: false }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
            },
            yaxis: {
                title: {
                    text: 'Jumlah Pembaca',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                }
            },
            colors: ['#7f8c8d'],
            title: {
                text: 'Jumlah Pembaca Berita Tahun {{ $currentYear ?? now()->year }}',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                    color: '#6b3e26'
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#readerChart"), options);
        chart.render();
    });
</script>
@endsection
