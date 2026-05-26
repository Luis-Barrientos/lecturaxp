@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900">Editar logro</h2>
        <p class="mt-1 text-sm text-slate-500">Modificando: {{ $achievement->name }}</p>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 sm:rounded-xl">
        {{-- DIFERENCIA 1: action apunta a update con el logro, y usamos PUT --}}
        <form action="{{ route('admin.achievements.update', $achievement) }}" method="POST" class="px-4 py-6 sm:p-8">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">

                {{-- Nombre --}}
                <div class="sm:col-span-4">
                    <label for="name" class="block text-sm font-medium text-slate-900">Nombre</label>
                    <div class="mt-2">
                        {{-- DIFERENCIA 2: los value usan old() con fallback al valor actual del logro --}}
                        <input type="text" name="name" id="name"
                            value="{{ old('name', $achievement->name) }}" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Icono --}}
                <div class="sm:col-span-2">
                    <label for="icon" class="block text-sm font-medium text-slate-900">
                        Icono <span class="text-slate-400">(emoji)</span>
                    </label>
                    <div class="mt-2">
                        <input type="text" name="icon" id="icon"
                            value="{{ old('icon', $achievement->icon) }}" required maxlength="10"
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                        @error('icon') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Slug --}}
                <div class="sm:col-span-3">
                    <label for="slug" class="block text-sm font-medium text-slate-900">
                        Slug <span class="text-slate-400">(identificador único)</span>
                    </label>
                    <div class="mt-2">
                        <input type="text" name="slug" id="slug"
                            value="{{ old('slug', $achievement->slug) }}" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                        @error('slug') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Tipo de condición --}}
                <div class="sm:col-span-3">
                    <label for="condition_type" class="block text-sm font-medium text-slate-900">Tipo de condición</label>
                    <div class="mt-2">
                        <select name="condition_type" id="condition_type" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            {{-- DIFERENCIA 3: @selected compara con el valor actual del logro --}}
                            <option value="total_logs" @selected(old('condition_type', $achievement->condition_type) === 'total_logs')>Total de sesiones (total_logs)</option>
                            <option value="streak_days" @selected(old('condition_type', $achievement->condition_type) === 'streak_days')>Días de racha (streak_days)</option>
                            <option value="total_xp" @selected(old('condition_type', $achievement->condition_type) === 'total_xp')>XP acumulado (total_xp)</option>
                            <option value="completed_books" @selected(old('condition_type', $achievement->condition_type) === 'completed_books')>Libros completados (completed_books)</option>
                        </select>
                        @error('condition_type') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Valor de la condición --}}
                <div class="sm:col-span-3">
                    <label for="condition_value" class="block text-sm font-medium text-slate-900">Valor de la condición</label>
                    <div class="mt-2">
                        <input type="number" name="condition_value" id="condition_value"
                            value="{{ old('condition_value', $achievement->condition_value) }}" min="1" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                        @error('condition_value') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- XP de recompensa --}}
                <div class="sm:col-span-3">
                    <label for="xp_reward" class="block text-sm font-medium text-slate-900">XP de recompensa</label>
                    <div class="mt-2">
                        <input type="number" name="xp_reward" id="xp_reward"
                            value="{{ old('xp_reward', $achievement->xp_reward) }}" min="0" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                        @error('xp_reward') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-slate-900">Descripción</label>
                    <div class="mt-2">
                        <textarea name="description" id="description" rows="3" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">{{ old('description', $achievement->description) }}</textarea>
                        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-slate-900/10 pt-6">
                <a href="{{ route('admin.achievements') }}"
                    class="text-sm font-semibold text-slate-900">Cancelar</a>
                <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>
</div>
@endsection 