<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Ticket #{{ $ticket->id }}</h3>
                    
                    <form action="{{ route('agent.tickets.update', $ticket) }}" method="POST" class="mb-6">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Statut</label>
                            <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Ouvert</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Fermé</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="priority" class="block text-gray-700 text-sm font-bold mb-2">Priorité</label>
                            <select name="priority" id="priority" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Basse</option>
                                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>Haute</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Mettre à jour le ticket
                        </button>
                    </form>

                    <p><strong>Description :</strong> {{ $ticket->description }}</p>

                    <h4 class="text-lg font-semibold mt-6 mb-2">Commentaires</h4>
                    @foreach($ticket->comments as $comment)
                        <div class="bg-gray-100 p-4 mb-4 rounded">
                            <p>{{ $comment->content }}</p>
                            <p class="text-sm text-gray-600 mt-2">Par {{ $comment->user->name }} le {{ $comment->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endforeach

                    <form action="{{ route('agent.tickets.comment', $ticket) }}" method="POST" class="mt-6">
                        @csrf
                        <div class="mb-4">
                            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Ajouter un commentaire</label>
                            <textarea name="content" id="content" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                        </div>
                        <button type="submit" class="mt-6 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Ajouter un commentaire
                        </button>
                    </form>

                    <a href="{{ route('agent.tickets') }}" class="mt-6 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour à la liste des tickets
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>