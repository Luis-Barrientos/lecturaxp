<x-guest-layout>
    <div class="px-8 py-8">
        <p class="text-sm font-semibold text-amber-500 mb-1">¡Bienvenido! 🎉</p>
        <h2 class="text-2xl font-bold text-slate-900 mb-6">Crear cuenta</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nombre -->
            <div>
                <x-input-label for="name" :value="'Nombre'" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Correo electrónico -->
            <div class="mt-4">
                <x-input-label for="email" :value="'Correo electrónico'" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Contraseña -->
            <div class="mt-4">
                <x-input-label for="password" :value="'Contraseña'" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="'Confirmar contraseña'" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-primary-button class="w-full justify-center py-3">
                    Crear mi cuenta
                </x-primary-button>
            </div>
         </form>
    </div>

    <x-slot name="footer">
        <div class="w-full max-w-md mt-6">
            <!-- Beneficios -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 px-6 py-5 space-y-3">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">¿Por qué unirte?</p>
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 text-sm">📖</span>
                    <span class="text-sm text-slate-600">Lleva el registro de tus lecturas</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-600 text-sm">⚡</span>
                    <span class="text-sm text-slate-600">Gana XP y desbloquea logros</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 text-sm">📚</span>
                    <span class="text-sm text-slate-600">Accede a tu librería personal</span>
                </div>
            </div>

            <!-- Separador -->
            <div class="flex items-center gap-3 mt-6">
                <div class="flex-1 h-0.5 bg-gradient-to-r from-transparent via-indigo-300 to-transparent"></div>
                <span class="text-sm text-indigo-400 font-semibold px-2">o</span>
                <div class="flex-1 h-0.5 bg-gradient-to-r from-transparent via-indigo-300 to-transparent"></div>
            </div>

            <!-- Link login -->
            <div class="mt-4 text-center">
                <p class="text-sm text-slate-500 mb-3">¿Ya tienes cuenta?</p>
                <a href="{{ route('login') }}" class="inline-block w-full py-2.5 px-4 border-2 border-indigo-600 text-indigo-600 font-semibold text-sm rounded-lg hover:bg-indigo-600 hover:text-white transition-all duration-200 text-center">
                    Iniciar sesión
                </a>
            </div>
        </div>
    </x-slot>

</x-guest-layout>