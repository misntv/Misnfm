<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-3" style="margin-top: 0.2rem;">
        <!-- Statistik Stasiun -->
        <div class="row">
            <div class="col-12">
                <div class="card card-round">
                    <div class="card-body">
                        <div id="listenerStatisticsChart" style="min-height: 310px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Combined Statistics -->
        <div class="row mt-1">
            <div class="col-12">
                <div class="card card-stats card-round">
                    <div class="card-body p-2 p-md-3">
                        <div class="row g-2 g-md-3">
                            <!-- Stasiun Terdaftar -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="stat-item d-flex align-items-center p-2 h-100 rounded-3"
                                    style="background-color: #e3f2fd;">
                                    <div class="icon-stat me-2 me-md-3 flex-shrink-0">
                                        <div class="icon-big icon-primary bubble-shadow-small"
                                            style="width: 40px; height: 40px; font-size: 1.2rem;">
                                            <i class="fas fa-broadcast-tower"></i>
                                        </div>
                                    </div>
                                    <div class="stat-content">
                                        <p class="card-category mb-1 text-muted"
                                            style="font-size: 0.85rem; font-weight: bold;">
                                            Stasiun Terdaftar</p>
                                        <h4 class="card-title mb-0 fw-bold" style="font-size: 1.2rem;">
                                            <?php echo e($stationCount); ?>

                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Berita Diunggah -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="stat-item d-flex align-items-center p-2 h-100 rounded-3"
                                    style="background-color: #e8f5e9;">
                                    <div class="icon-stat me-2 me-md-3 flex-shrink-0">
                                        <div class="icon-big icon-info bubble-shadow-small"
                                            style="width: 40px; height: 40px; font-size: 1.2rem;">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    </div>
                                    <div class="stat-content">
                                        <p class="card-category mb-1 text-muted"
                                            style="font-size: 0.85rem; font-weight: bold;">
                                            Berita Diunggah</p>
                                        <h4 class="card-title mb-0 fw-bold" style="font-size: 1.2rem;">
                                            <?php echo e($publishedNewsCount); ?>

                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Anggota Wartawan -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="stat-item d-flex align-items-center p-2 h-100 rounded-3"
                                    style="background-color: #fff3e0;">
                                    <div class="icon-stat me-2 me-md-3 flex-shrink-0">
                                        <div class="icon-big icon-success bubble-shadow-small"
                                            style="width: 40px; height: 40px; font-size: 1.2rem;">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                    </div>
                                    <div class="stat-content">
                                        <p class="card-category mb-1 text-muted"
                                            style="font-size: 0.85rem; font-weight: bold;">
                                            Anggota Wartawan</p>
                                        <h4 class="card-title mb-0 fw-bold" style="font-size: 1.2rem;">
                                            <?php echo e($journalistCount); ?>

                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Berita Pengajuan -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="stat-item d-flex align-items-center p-2 h-100 rounded-3"
                                    style="background-color: #fce4ec;">
                                    <div class="icon-stat me-2 me-md-3 flex-shrink-0">
                                        <div class="icon-big icon-warning bubble-shadow-small"
                                            style="width: 40px; height: 40px; font-size: 1.2rem;">
                                            <i class="fas fa-file-upload"></i>
                                        </div>
                                    </div>
                                    <div class="stat-content">
                                        <p class="card-category mb-1 text-muted"
                                            style="font-size: 0.85rem; font-weight: bold;">
                                            Berita Pengajuan</p>
                                        <h4 class="card-title mb-0 fw-bold" style="font-size: 1.2rem;">
                                            <?php echo e($submittedNewsCount); ?>

                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .stat-item {
                transition: all 0.3s ease;
                border: 1px solid rgba(0, 0, 0, 0.05);
            }

            .stat-item:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            .icon-stat {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .icon-big {
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
            }

            @media (max-width: 768px) {
                .card-title {
                    font-size: 1.1rem;
                }

                .card-category {
                    font-size: 0.8rem;
                }
            }

            @media (max-width: 576px) {
                .card-title {
                    font-size: 1rem;
                }

                .card-category {
                    font-size: 0.75rem;
                }

                .icon-big {
                    width: 35px !important;
                    height: 35px !important;
                    font-size: 1rem !important;
                }
            }
        </style>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Data dari controller
            const topStations = <?php echo json_encode($topStations, 15, 512) ?>;
            const monthlyData = <?php echo json_encode($monthlyData, 15, 512) ?>;
            const currentYear = <?php echo json_encode($currentYear, 15, 512) ?>;

            // Siapkan data series untuk chart
            const seriesData = topStations.map(station => {
                const stats = monthlyData[station.id] || {};

                const data = Array.from({ length: 12 }, (_, i) => {
                    const month = i + 1;
                    return Math.round(stats[month] || 0);
                });

                return {
                    name: station.nama_stasiun,
                    data: data
                };
            });

            // Konfigurasi chart yang diperbaiki
            const chartOptions = {
                series: seriesData,
                chart: {
                    type: 'line',
                    height: '100%',
                    toolbar: {
                        show: true,
                        tools: {
                            download: false,
                            selection: false,
                            zoom: false,
                            pan: false,
                            reset: false
                        }
                    },
                    animations: {
                        enabled: true
                    }
                },
                colors: ['#2ecc71', '#f39c12', '#1abc9c', '#3498db', '#e74c3c'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 5,
                    hover: {
                        size: 7
                    }
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                    axisBorder: {
                        show: true,
                        color: '#cccccc',
                        height: 1,
                        offsetX: 0,
                        offsetY: 0
                    },
                    axisTicks: {
                        show: true,
                        color: '#cccccc'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Pendengar',
                        offsetX: -10, // negatif = geser ke kiri, positif = geser ke kanan
                        style: {
                            color: '#964734',
                            fontSize: '18px',
                            fontWeight: 'bold'
                        }
                    },

                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: function (val) {
                            return Math.round(val).toLocaleString();
                        },
                        style: {
                            colors: '#333333'
                        }
                    },
                    axisBorder: {
                        show: true,
                        color: '#cccccc',
                        width: 1,
                        offsetX: 0,
                        offsetY: 0
                    },
                    axisTicks: {
                        show: true,
                        color: '#cccccc'
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                    strokeDashArray: 4,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (val) {
                            return Math.round(val).toLocaleString() + ' pendengar';
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '12px',
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    }
                },
                title: {
                    text: `Statistik Stasiun yang Paling Diminati ${currentYear}`,
                    align: 'center',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold',
                        color: '#964734'
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#listenerStatisticsChart"), chartOptions);
            chart.render();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/misntvbi/laravel_misnfm/resources/views/admin/dashboard/admin.blade.php ENDPATH**/ ?>