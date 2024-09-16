<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rapports et Statistiques') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="font-bold text-lg mb-4">Appels par jour (7 derniers jours)</h3>
                    <table class="min-w-full divide-y divide-gray-200 mb-8">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre d'appels</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($callsPerDay as $call)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $call->date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $call->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                   <h3 class="font-bold text-lg mb-4">Distribution des statuts de tickets</h3>
                    <table class="min-w-full divide-y divide-gray-200 mb-8">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de tickets</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($ticketStatusDistribution as $status)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($status->status) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $status->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h3 class="font-bold text-lg mb-4">Performance des agents</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre total d'appels</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée moyenne des appels (min)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($agentPerformance as $performance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $performance->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $performance->total_calls }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($performance->avg_duration, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <canvas id="callsPerDayChart" width="400" height="200"></canvas>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('callsPerDayChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($callsPerDay->pluck('date')) !!},
            datasets: [{
                label: 'Nombre d\'appels par jour',
                data: {!! json_encode($callsPerDay->pluck('total')) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

</x-app-layout>