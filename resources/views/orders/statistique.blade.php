<!-- Section Statistiques à ajouter après le titre et les messages d'alerte -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="fas fa-chart-bar"></i> Tableau de bord</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="statsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock" type="button" role="tab" aria-controls="stock" aria-selected="true">
                            <i class="fas fa-boxes"></i> Stocks produits
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="orders-day-tab" data-bs-toggle="tab" data-bs-target="#orders-day" type="button" role="tab" aria-controls="orders-day" aria-selected="false">
                            <i class="fas fa-calendar-day"></i> Commandes par jour
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="orders-month-tab" data-bs-toggle="tab" data-bs-target="#orders-month" type="button" role="tab" aria-controls="orders-month" aria-selected="false">
                            <i class="fas fa-calendar-alt"></i> Commandes par mois
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="statsTabContent">
                    <!-- Stocks produits -->
                    <div class="tab-pane fade show active" id="stock" role="tabpanel" aria-labelledby="stock-tab">
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="stockChart" height="300"></canvas>
                            </div>
                            <div class="col-md-4">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Stock</th>
                                            <th>Statut</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($burgers as $burger)
                                            <tr>
                                                <td>{{ $burger->name }}</td>
                                                <td>{{ $burger->stock }}</td>
                                                <td>
                                                    @if($burger->stock > 10)
                                                        <span class="badge bg-success">Bon</span>
                                                    @elseif($burger->stock > 5)
                                                        <span class="badge bg-warning">Moyen</span>
                                                    @else
                                                        <span class="badge bg-danger">Critique</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commandes par jour -->
                    <div class="tab-pane fade" id="orders-day" role="tabpanel" aria-labelledby="orders-day-tab">
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="ordersDayChart" height="300"></canvas>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Résumé</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Aujourd'hui
                                                <span class="badge bg-primary rounded-pill">{{ $todayOrdersCount }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Cette semaine
                                                <span class="badge bg-primary rounded-pill">{{ $weekOrdersCount }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Ce mois
                                                <span class="badge bg-primary rounded-pill">{{ $monthOrdersCount }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Moyenne quotidienne
                                                <span class="badge bg-primary rounded-pill">{{ $avgDailyOrders }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commandes par mois -->
                    <div class="tab-pane fade" id="orders-month" role="tabpanel" aria-labelledby="orders-month-tab">
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="ordersMonthChart" height="300"></canvas>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Statistiques annuelles</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Total de l'année
                                                <span class="badge bg-primary rounded-pill">{{ $yearOrdersCount }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Mois le plus actif
                                                <span class="badge bg-success rounded-pill">{{ $busiestMonth }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Mois le moins actif
                                                <span class="badge bg-warning rounded-pill">{{ $slowestMonth }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Moyenne mensuelle
                                                <span class="badge bg-primary rounded-pill">{{ $avgMonthlyOrders }}</span>
                                            </li>
                                        </ul>
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
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des stocks
            const stockCtx = document.getElementById('stockChart').getContext('2d');
            const stockChart = new Chart(stockCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($burgers->pluck('name')) !!},
                    datasets: [{
                        label: 'Stock disponible',
                        data: {!! json_encode($burgers->pluck('stock')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'État des stocks par produit',
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            });

            // Graphique des commandes par jour
            const ordersDayCtx = document.getElementById('ordersDayChart').getContext('2d');
            const ordersDayChart = new Chart(ordersDayCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($dailyOrdersLabels) !!},
                    datasets: [{
                        label: 'Nombre de commandes',
                        data: {!! json_encode($dailyOrdersData) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Commandes par jour (30 derniers jours)',
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            });

            // Graphique des commandes par mois
            const ordersMonthCtx = document.getElementById('ordersMonthChart').getContext('2d');
            const ordersMonthChart = new Chart(ordersMonthCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyOrdersLabels) !!},
                    datasets: [{
                        label: 'Nombre de commandes',
                        data: {!! json_encode($monthlyOrdersData) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Commandes par mois (12 derniers mois)',
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            });
        });
    </script>

@endpush
