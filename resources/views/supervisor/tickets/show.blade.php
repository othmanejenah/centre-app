<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du ticket #') . $ticket->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Informations du ticket</h3>
                    
                    <form action="{{ route('supervisor.tickets.update', $ticket) }}" method="POST" class="mb-6">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                                <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Ouvert</option>
                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Fermé</option>
                                </select>
                            </div>
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">Priorité</label>
                                <select name="priority" id="priority" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Basse</option>
                                    <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                    <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>Haute</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Mettre à jour le ticket
                            </button>
                        </div>
                    </form>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Description</h4>
                        <p>{{ $ticket->description }}</p>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Agent assigné</h4>
                        <p>{{ $ticket->user->name }}</p>
                    </div>

                    <h3 class="text-lg font-semibold mb-4">Commentaires</h3>
                    @foreach($ticket->comments as $comment)
                        <div class="bg-gray-100 p-4 mb-4 rounded">
                            <p>{{ $comment->content }}</p>
                            <p class="text-sm text-gray-600 mt-2">Par {{ $comment->user->name }} le {{ $comment->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endforeach

                    <form action="{{ route('supervisor.tickets.comment', $ticket) }}" method="POST" class="mt-6">
                        @csrf
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Ajouter un commentaire</label>
                            <textarea name="content" id="content" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ajouter un commentaire
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>