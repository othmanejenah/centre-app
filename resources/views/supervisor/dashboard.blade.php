<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord Superviseur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-100 p-4 rounded">
                            <h3 class="font-bold text-lg">Total des appels</h3>
                            <p class="text-3xl">{{ $totalCalls }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded">
                            <h3 class="font-bold text-lg">Total des tickets</h3>
                            <p class="text-3xl">{{ $totalTickets }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded">
                            <h3 class="font-bold text-lg">Tickets ouverts</h3>
                            <p class="text-3xl">{{ $openTickets }}</p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded">
                            <h3 class="font-bold text-lg">Nombre d'agents</h3>
                            <p class="text-3xl">{{ $agents }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-bold text-lg mb-2">Appels récents</h3>
                            <ul>
                                @foreach($recentCalls as $call)
                                    <li class="mb-2">
                                        <span class="font-semibold">{{ $call->client_name }}</span> - 
                                        {{ $call->created_at->format('d/m/Y H:i') }} par {{ $call->user->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Tickets récents</h3>
                            <ul>
                                @foreach($recentTickets as $ticket)
                                    <li class="mb-2">
                                        <span class="font-semibold">#{{ $ticket->id }}</span> - 
                                        {{ Str::limit($ticket->description, 30) }} ({{ $ticket->status }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>