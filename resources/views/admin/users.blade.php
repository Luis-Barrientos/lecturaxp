@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Gestión de usuarios</h2>
    <p class="mt-1 text-sm text-slate-500">{{ $users->count() }} usuarios registrados en la plataforma.</p>
</div>

<div class="bg-white shadow-sm ring-1 ring-slate-900/5 sm:rounded-xl overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-slate-900">Usuario</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Nivel</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">XP Total</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Registro</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Rol actual</th>
                <th class="relative py-3.5 pl-3 pr-6"><span class="sr-only">Cambiar rol</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition-colors">

                    {{-- Nombre y email del usuario --}}
                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm">
                        <p class="font-medium text-slate-900">{{ $user->name }}</p>
                        <p class="text-slate-400">{{ $user->email }}</p>
                    </td>

                    {{-- Nivel --}}
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        Nivel {{ $user->current_level }}
                    </td>

                    {{-- XP total --}}
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-indigo-600 font-medium">
                        {{ number_format($user->total_xp) }} XP
                    </td>

                    {{-- Fecha de registro --}}
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>

                    {{-- Badge de rol actual --}}
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-1 text-xs font-medium text-indigo-700">
                                Admin
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600">
                                Lector
                            </span>
                        @endif
                    </td>

                    {{-- Formulario para cambiar el rol --}}
                    {{-- Usamos PATCH porque es una actualización parcial del recurso --}}
                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm">
                        <form action="{{ route('admin.users.role', $user) }}" method="POST" class="inline flex items-center gap-2 justify-end">
                            @csrf
                            @method('PATCH')

                            {{-- Select con los roles disponibles, pre-seleccionando el actual --}}
                            <select name="role" class="text-sm rounded-md border-slate-300 focus:ring-indigo-500">
                                <option value="lector" @selected($user->role === 'lector')>Lector</option>
                                <option value="admin" @selected($user->role === 'admin')>Admin</option>
                            </select>

                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-2 py-1 text-xs font-semibold text-white hover:bg-indigo-500 transition-all">
                                Guardar
                            </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-sm text-slate-500">
                        No hay usuarios registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection