@extends('admin.layouts.app')

@section('title', 'Statistik Pemutaran')

@section('content')
<div class="container-fluid px-4 my-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-black d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Statistik Pemutaran Radio</h3>
                    <div class="badge bg-light text-primary">
                        <i class="fas fa-info-circle me-1"></i>
                        Pilih stasiun dan periode waktu
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('playlist.statistics') }}" class="row g-3 align-items-end mb-4">
                        <div class="col-md-4">
                            <label for="radio_id" class="form-label">Stasiun Radio</label>
                            <select class="form-select" id="radio_id" name="radio_id" required>
                                <option value="">Pilih Stasiun Radio</option>
                                @foreach($allRadios as $radio)
                                    <option value="{{ $radio->id }}" {{ $selectedRadioId == $radio->id ? 'selected' : '' }}>
                                        {{ $radio->nama_stasiun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="time_period" class="form-label">Tampilkan Data</label>
                            <select class="form-select" id="time_period" name="time_period">
                                <option value="monthly" {{ $timePeriod == 'monthly' ? 'selected' : '' }}>Per Bulan (Tahunan)</option>
                                <option value="daily" {{ $timePeriod == 'daily' ? 'selected' : '' }}>Per Hari (Bulanan)</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="year" class="form-label">Tahun</label>
                            <select class="form-select" id="year" name="year">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2" id="month-select-container" style="{{ $timePeriod == 'monthly' ? 'display:none;' : '' }}">
                            <label for="month" class="form-label">Bulan</label>
                            <select class="form-select" id="month" name="month">
                                @foreach($months as $key => $month)
                                    <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tombol filter -->
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-filter w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-filter me-2"></i>
                                <span>Filter</span>
                            </button>
                        </div>

                        <!-- Tombol unduh dengan dropdown -->
                        <div class="col-md-auto">
                            <div class="btn-group w-100">
                                <button type="button" class="btn btn-export dropdown-toggle w-100 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-2"></i>
                                    <span>Unduh</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end w-100">
                                    <li><a class="dropdown-item" href="#" id="export-excel"><i class="fas fa-file-excel text-success me-2"></i> Excel</a></li>
                                    <li><a class="dropdown-item" href="#" id="export-pdf"><i class="fas fa-file-pdf text-danger me-2"></i> PDF</a></li>
                                </ul>
                            </div>
                        </div>
                    </form>


                    @if($selectedRadio)
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">Statistik {{ $selectedRadio->nama_stasiun }}</h4>
                            <span class="badge bg-primary">
                                @if($timePeriod == 'monthly')
                                    Tahun {{ $selectedYear }}
                                @else
                                    {{ $months[$selectedMonth] }} {{ $selectedYear }}
                                @endif
                            </span>
                        </div>
                        
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="radioStatisticsChart"></canvas>
                        </div>
                        
                        <div class="mt-4">
                            <div class="row">
                                <!-- Detail Pendengar Card -->
                                <div class="col-md-6">
                                    <div class="card shadow-sm border-0" style="border-radius: 12px; height: 100%;">
                                        <!-- Card Header -->
                                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center pt-3 px-3 pb-2 border-bottom-0">
                                            <h5 class="card-title m-0" style="color: #000; font-weight: 500;">
                                                <i class="fas fa-users me-2"></i>Detail Pendengar
                                            </h5>
                                            @if(count($labels) > 5)
                                            <button class="btn btn-sm btn-link text-primary p-0 toggle-all-data" type="button" 
                                                    style="font-size: 0.85rem; text-decoration: none;">
                                                Lihat Semua <i class="fas fa-chevron-down ms-1"></i>
                                            </button>
                                            @endif
                                        </div>
                                        <div class="card-body pt-0 px-3 pb-2">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-borderless mb-0">
                                                    <thead>
                                                        <tr class="text-uppercase small text-muted">
                                                            <th style="width: 40%">{{ $timePeriod == 'monthly' ? 'Bulan' : 'Tanggal' }}</th>
                                                            <th class="text-end" style="width: 30%">Pendengar</th>
                                                            <th class="text-end" style="width: 30%">%</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $total = array_sum($statisticsData);
                                                            $visibleRows = 5;
                                                        @endphp
                                                        
                                                        <!-- Visible Rows (First 5) -->
                                                        @foreach(array_slice($labels, 0, $visibleRows, true) as $index => $label)
                                                        <tr class="py-1">
                                                            <td class="align-middle">
                                                                <span class="d-block text-truncate" style="max-width: 120px;">{{ $label }}</span>
                                                            </td>
                                                            <td class="text-end align-middle">
                                                                <span class="badge bg-light text-dark fw-normal px-2">
                                                                    {{ number_format($statisticsData[$index]) }}
                                                                </span>
                                                            </td>
                                                            <td class="text-end align-middle">
                                                                <div class="d-flex align-items-center justify-content-end">
                                                                    <small class="text-muted me-2">
                                                                        {{ $total > 0 ? round(($statisticsData[$index]/$total)*100, 1) : 0 }}%
                                                                    </small>
                                                                    <div class="progress" style="height: 6px; width: 60px;">
                                                                        <div class="progress-bar bg-primary" 
                                                                             style="width: {{ $total > 0 ? ($statisticsData[$index]/$total)*100 : 0 }}%">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        
                                                        <!-- Hidden Rows (Initially hidden) -->
                                                        @if(count($labels) > $visibleRows)
                                                            @foreach(array_slice($labels, $visibleRows, null, true) as $index => $label)
                                                            <tr class="py-1 additional-row" style="display: none;">
                                                                <td class="align-middle">
                                                                    <span class="d-block text-truncate" style="max-width: 120px;">{{ $label }}</span>
                                                                </td>
                                                                <td class="text-end align-middle">
                                                                    <span class="badge bg-light text-dark fw-normal px-2">
                                                                        {{ number_format($statisticsData[$index]) }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-end align-middle">
                                                                    <div class="d-flex align-items-center justify-content-end">
                                                                        <small class="text-muted me-2">
                                                                            {{ $total > 0 ? round(($statisticsData[$index]/$total)*100, 1) : 0 }}%
                                                                        </small>
                                                                        <div class="progress" style="height: 6px; width: 60px;">
                                                                            <div class="progress-bar bg-primary" 
                                                                                 style="width: {{ $total > 0 ? ($statisticsData[$index]/$total)*100 : 0 }}%">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            
                                                            <!-- "Show More" Row -->
                                                            <tr class="show-more-row">
                                                                <td colspan="3" class="text-center small text-muted pt-2">
                                                                    <button class="btn btn-sm btn-link text-primary p-0 toggle-data" type="button">
                                                                        +{{ count($labels) - $visibleRows }} data lainnya <i class="fas fa-chevron-down ms-1"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Top 10 Radios Card (moved to right side) -->
                                <div class="col-md-6">
                                    <div class="card shadow-sm" style="height: 100%;">
                                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center pt-3 px-3 pb-2 border-bottom-0">
                                            <h5 class="card-title m-0" style="color: #000; font-weight: 500;">
                                                <i class="fas fa-trophy me-2"></i>10 Radio Teratas
                                            </h5>
                                        </div>
                                        <div class="card-body pt-0 px-3 pb-2">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-borderless mb-0">
                                                    <thead>
                                                        <tr class="text-uppercase small text-muted">
                                                            <th width="50px" class="text-center align-middle">No</th>
                                                            <th class="text-center align-middle">Nama Radio</th>
                                                            <th class="text-center align-middle">Pendengar</th>
                                                            <th class="text-center align-middle">Trend</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($topRadios as $index => $radio)
                                                        <tr class="py-1">
                                                            <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                                            <td class="align-middle">
                                                                <div class="text-start align-middle">
                                                                    <span>{{ $radio->nama_stasiun }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <span class="badge bg-light text-dark fw-normal px-2">
                                                                    {{ number_format($radio->jumlah_pendengar) }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                @if($radio->jumlah_pendengar > ($radio->last_month_listeners ?? 0))
                                                                    <span class="badge bg-success">
                                                                        <i class="fas fa-arrow-up me-1"></i>
                                                                    </span>
                                                                @elseif($radio->jumlah_pendengar < ($radio->last_month_listeners ?? 0))
                                                                    <span class="badge bg-danger">
                                                                        <i class="fas fa-arrow-down me-1"></i>
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-secondary">
                                                                        <i class="fas fa-equals"></i>
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center py-4">Tidak ada data radio</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle month select visibility
        const timePeriodSelect = document.getElementById('time_period');
        const monthSelectContainer = document.getElementById('month-select-container');
        const toggleButtons = document.querySelectorAll('.toggle-data');
        const toggleAllButton = document.querySelector('.toggle-all-data');
        
        timePeriodSelect.addEventListener('change', function() {
            monthSelectContainer.style.display = this.value === 'daily' ? 'block' : 'none';
        });

        // Ganti bagian chart script dengan yang ini
        @if($selectedRadio && isset($statisticsData) && isset($labels))
        const mainColor = '#024950'; // Warna utama
        const darkAccent = '#024950'; // Warna aksen lebih gelap
        const lightAccent = '#ffffff'; // Warna aksen lebih terang
        const textColor = '#000000'; // Warna teks
        const backgroundColor = '#ffffff'; // Warna background

        // Main Chart
        const ctx = document.getElementById('radioStatisticsChart').getContext('2d');

        // Gradient untuk chart utama
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, mainColor);
        gradient.addColorStop(1, lightAccent);

        const chartConfig = {
            type: '{{ $timePeriod == "monthly" ? "bar" : "line" }}',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Jumlah Pendengar',
                    data: @json($statisticsData),
                    backgroundColor: gradient,
                    borderColor: darkAccent,
                    borderWidth: {{ $timePeriod == "monthly" ? 1 : 2 }},
                    pointBackgroundColor: backgroundColor,
                    pointBorderColor: darkAccent,
                    pointRadius: {{ $timePeriod == "monthly" ? 0 : 4 }},
                    pointHoverRadius: 6,
                    pointBorderWidth: 1.5,
                    @if($timePeriod == 'daily')
                    tension: 0.3,
                    fill: true,
                    @endif
                    shadowColor: 'rgba(91, 174, 184, 0.2)',
                    shadowBlur: 8,
                    shadowOffsetX: 2,
                    shadowOffsetY: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                backgroundColor: backgroundColor,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: darkAccent,
                        titleColor: backgroundColor,
                        bodyColor: backgroundColor,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return '👥 ' + context.raw.toLocaleString() + ' pendengar';
                            },
                            footer: function(context) {
                                const total = {{ array_sum($statisticsData) }};
                                const value = context[0].raw;
                                const percentage = total > 0 ? (value/total*100).toFixed(1) : 0;
                                return '📈 ' + percentage + '% dari total';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(175, 221, 229, 0.3)'
                        },
                        ticks: {
                            color: textColor,
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor
                        }
                    }
                }
            }
        };

        new Chart(ctx, chartConfig);
        @endif

        function toggleRows(show) {
            const additionalRows = document.querySelectorAll('.additional-row');
            const showMoreRow = document.querySelector('.show-more-row');
            
            if (show) {
                // Show all additional rows
                additionalRows.forEach(row => {
                    row.style.display = 'table-row';
                    setTimeout(() => row.style.opacity = '1', 10);
                });
                showMoreRow.style.display = 'none';
                
                // Update all toggle buttons
                document.querySelectorAll('.toggle-data, .toggle-all-data').forEach(btn => {
                    btn.innerHTML = 'Sembunyikan <i class="fas fa-chevron-up ms-1"></i>';
                });
            } else {
                // Hide additional rows
                additionalRows.forEach(row => {
                    row.style.opacity = '0';
                    setTimeout(() => row.style.display = 'none', 300);
                });
                showMoreRow.style.display = 'table-row';
                
                // Update all toggle buttons
                document.querySelectorAll('.toggle-data, .toggle-all-data').forEach(btn => {
                    btn.innerHTML = `+{{ count($labels) - $visibleRows }} data lainnya <i class="fas fa-chevron-down ms-1"></i>`;
                });
            }
        }

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const additionalRows = document.querySelectorAll('.additional-row');
                const isHidden = additionalRows[0].style.display === 'none';
                toggleRows(isHidden);
            });
        });
        
        if (toggleAllButton) {
            toggleAllButton.addEventListener('click', function() {
                const additionalRows = document.querySelectorAll('.additional-row');
                const isHidden = additionalRows[0].style.display === 'none';
                toggleRows(isHidden);
            });
        }

        // Export to Excel
        document.getElementById('export-excel').addEventListener('click', function() {
            const labels = @json($labels);
            const statisticsData = @json($statisticsData);
            const total = {{ $totalListeners }}; // Use the calculated total from controller
            
            // Prepare data
            const data = [
                ['Periode', 'Jumlah Pendengar', 'Persentase'],
                ...labels.map((label, index) => {
                    const value = statisticsData[index] || 0;
                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) + '%' : '0%';
                    
                    return [
                        label, 
                        value, 
                        percentage
                    ];
                })
            ];
            
            // Create workbook
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);
            XLSX.utils.book_append_sheet(wb, ws, "Statistik");
            
            // Generate filename
            const radioName = @json($selectedRadio ? Str::slug($selectedRadio->nama_stasiun) : 'radio');
            const period = @json($timePeriod == 'monthly' ? 'tahun-' . $selectedYear : 'bulan-' . $months[$selectedMonth] . '-' . $selectedYear);
            const filename = `statistik-${radioName}-${period}.xlsx`;
            
            // Export
            XLSX.writeFile(wb, filename);
        });

        // Export to PDF
        document.getElementById('export-pdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Title
            doc.setFontSize(16);
            doc.text(`Statistik Pemutaran Radio`, 14, 15);
            
            // Radio name
            doc.setFontSize(12);
            doc.text(`Stasiun: ${@json($selectedRadio ? $selectedRadio->nama_stasiun : '')}`, 14, 22);
            
            // Period info
            doc.setFontSize(10);
            doc.setTextColor(100);
            
            // Fixed period string construction
            let periodText;
            if (@json($timePeriod == 'monthly')) {
                periodText = `Tahun: ${@json($selectedYear)}`;
            } else {
                const monthName = @json($months[$selectedMonth] ?? '');
                periodText = `Periode: ${monthName} ${@json($selectedYear)}`;
            }
            doc.text(periodText, 14, 28);
            
            // Chart image
            const chartCanvas = document.getElementById('radioStatisticsChart');
            if (chartCanvas) {
                const chartImage = chartCanvas.toDataURL('image/png');
                doc.addImage(chartImage, 'PNG', 15, 35, 180, 80);
            }

            // Use consistent data source
            const labels = @json($labels);
            const statisticsData = @json($statisticsData);
            const total = {{ $totalListeners }};
            
            // Table data
            const headers = [['Periode', 'Pendengar', 'Persentase']];
            const rows = labels.map((label, index) => {
                const value = statisticsData[index] || 0;
                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) + '%' : '0%';
                
                return [label, value.toString(), percentage];
            });
            
            doc.autoTable({
                head: headers,
                body: rows,
                startY: chartCanvas ? 125 : 40,
                styles: { fontSize: 9 },
                headStyles: { fillColor: [59, 130, 246] }
            });
            
            // Footer
            const date = new Date().toLocaleString();
            doc.setFontSize(8);
            doc.setTextColor(150);
            doc.text(`Dibuat pada: ${date}`, 14, doc.internal.pageSize.height - 10);
            
            // Generate filename
            const radioName = @json($selectedRadio ? Str::slug($selectedRadio->nama_stasiun) : 'radio');
            const period = @json($timePeriod == 'monthly' ? 'tahun-' . $selectedYear : 'bulan-' . $months[$selectedMonth] . '-' . $selectedYear);
            const filename = `statistik-${radioName}-${period}.pdf`;
            
            doc.save(filename);
        });
    });
</script>
@endsection