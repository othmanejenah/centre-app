<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Agent') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Appels récents</h3>
                    @foreach($recentCalls as $call)
                        <div class="mb-2">
                            <p>{{ $call->client_name }} - {{ $call->start_time }}</p>
                        </div>
                    @endforeach

                    <h3 class="text-lg font-semibold mt-6 mb-4">Tickets ouverts</h3>
                    @foreach($openTickets as $ticket)
                        <div class="mb-2">
                            <p>Ticket #{{ $ticket->id }} - {{ $ticket->description }}</p>
                        </div>
                    @endforeach

                    <div class="mt-6">
                        <a href="{{ route('agent.calls.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Nouvel appel
                        </a>
                        <a href="{{ route('agent.tickets.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-4">
                            Nouveau ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>