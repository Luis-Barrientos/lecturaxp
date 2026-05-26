@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Panel de administración</h2>
    <p class="mt-1 text-sm text-slate-500">Resumen general de la aplicación.</p>
</div>

{{-- Tarjetas de resumen --}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-900/5 p-6">
        <p class="text-sm font-medium text-slate-500">Usuarios registrados</p>
        <p class="mt-1 text-3xl font-bold text-indigo-600">{{ $totalUsers }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-900/5 p-6">
        <p class="text-sm font-medium text-slate-500">Administradores</p>
        <p class="mt-1 text-3xl font-bold text-indigo-600">{{ $totalAdmins }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-900/5 p-6">
        <p class="text-sm font-medium text-slate-500">Libros en la plataforma</p>
        <p class="mt-1 text-3xl font-bold text-indigo-600">{{ $totalBooks }}</p>
    </div>

</div>

{{-- Accesos rápidos --}}
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <a href="{{ route('admin.users') }}"
        class="bg-white rounded-xl shadow-sm ring-1 ring-slate-900/5 p-6 hover:bg-slate-50 transition-colors flex items-center gap-4">
        <span class="text-3xl">👥</span>
        <div>
            <p class="font-semibold text-slate-900">Gestionar usuarios</p>
            <p class="text-sm text-slate-500">Ver y cambiar roles de usuarios</p>
        </div>
    </a>
    <a href="{{ route('admin.books') }}"
        class="bg-white rounded-xl shadow-sm ring-1 ring-slate-900/5 p-6 hover:bg-slate-50 transition-colors flex items-center gap-4">
        <span class="text-3xl">📚</span>
        <div>
            <p class="font-semibold text-slate-900">Gestionar libros</p>
            <p class="text-sm text-slate-500">Ver y eliminar libros de la plataforma</p>
        </div>
    </a>
</div>
@endsection