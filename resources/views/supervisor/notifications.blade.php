<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($notifications->count() > 0)
                        @foreach($notifications as $notification)
                            <div class="mb-4 p-4 bg-gray-100 rounded">
                                <p>{{ $notification->data['message'] }}</p>
                                <p class="text-sm text-gray-600">{{ $notification->created_at->diffForHumans() }}</p>
                                <a href="{{ route('supervisor.tickets.show', $notification->data['ticket_id']) }}" class="text-blue-600 hover:underline">Voir le ticket</a>
                                <form action="{{ route('supervisor.notifications.read', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:underline ml-4">Marquer comme lu</button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <p>Aucune nouvelle notification.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>