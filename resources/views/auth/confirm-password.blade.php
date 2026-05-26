<x-guest-layout>
    <div class="px-8 py-8">
        <p class="text-sm font-semibold text-amber-500 mb-1">Seguridad primero 🔒</p>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Confirmar contraseña</h2>
        <p class="text-sm text-slate-500 mb-6">Esta es una sección protegida. Por favor, confirma tu contraseña para continuar.</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Contraseña -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-slate-900 mb-2">Contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-lg border-0 px-3.5 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition-all duration-200 focus:shadow-md sm:text-sm sm:leading-6">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full rounded-lg bg-amber-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200">
                Confirmar
            </button>
        </form>
    </div>

    <x-slot name="footer">
        <div class="border-t border-slate-200 mt-8 pt-8">
            <p class="text-center text-sm text-slate-600">
                ¿Olvidaste tu contraseña?
                <a href="{{ route('forgot-password') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">
                    Recuperarla aquí
                </a>
            </p>
        </div>
    </x-slot>
</x-guest-layout>
