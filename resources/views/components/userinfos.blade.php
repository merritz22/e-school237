@props(['user'])

<div class="bg-white rounded shadow mb-4">
    <div class="px-6 py-3 border-b border-gray-200">
        <h6 class="text-sm font-semibold text-gray-700">Informations générales</h6>
    </div>
    <div class="p-6">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full sm:w-1/2 px-4">
                <table class="w-full border-collapse">
                    <tbody>
                        <tr>
                            <th class="text-left pr-4 w-2/5 py-2">Nom d'utilisateur:</th>
                            <td class="py-2">{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-left pr-4 w-2/5 py-2">Prénom:</th>
                            <td class="py-2">{{ $user->first_name }}</td>
                        </tr>
                        <tr>
                            <th class="text-left pr-4 w-2/5 py-2">Nom:</th>
                            <td class="py-2">{{ $user->last_name }}</td>
                        </tr>
                        <tr>
                            <th class="text-left pr-4 w-2/5 py-2">Email:</th>
                            <td class="py-2">{{ $user->email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-full sm:w-1/2 px-4">
                <table class="w-full border-collapse">
                    <tbody>
                        <tr>
                            <th class="text-left pr-4 w-2/5 py-2">Rôle:</th>
                            <td class="py-2">
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded
                                    {{ $user->role === 'admin' ? 'bg-red-600 text-white' : 
                                        ($user->role === 'moderator' ? 'bg-yellow-400 text-black' : 
                                        ($user->role === 'author' ? 'bg-blue-400 text-white' : 'bg-gray-400 text-white')) }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-left pr-4 w-2/5 py-2">Statut:</th>
                            <td class="py-2">
                                @if($user->is_active)
                                    <span class="inline-block px-3 py-1 text-sm font-medium rounded bg-green-600 text-white">Actif</span>
                                @else
                                    <span class="inline-block px-3 py-1 text-sm font-medium rounded bg-red-600 text-white">Inactif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-left pr-4 w-2/5 py-2">Inscription:</th>
                            <td class="py-2">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="text-left pr-4 w-2/5 py-2">Dernière connexion:</th>
                            <td class="py-2">{{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Jamais' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>