<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="px-8 py-8">
        <p class="text-sm font-semibold text-amber-500 mb-1">Bienvenido de nuevo 👋</p>
        <h2 class="text-2xl font-bold text-slate-900 mb-6">Iniciar sesión</h2>
        
        <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="'Correo electrónico'" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="'Contraseña'" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Recuérdame</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif

            <x-primary-button class="ms-3 bg-amber-500 hover:bg-amber-600 text-white transition-colors duration-200">
                Iniciar sesión
            </x-primary-button>
        </div>
    </form>
    </div>

    <x-slot name="footer">
        <div class="w-full max-w-md mt-6">
            <!-- Separador -->
            <div class="flex items-center gap-3">
                <div class="flex-1 h-0.5 bg-gradient-to-r from-transparent via-indigo-300 to-transparent"></div>
                <span class="text-sm text-indigo-400 font-semibold px-2">o</span>
                <div class="flex-1 h-0.5 bg-gradient-to-r from-transparent via-indigo-300 to-transparent"></div>
            </div>
            <!-- Link registro -->
            <div class="mt-4 text-center">
                <p class="text-sm text-slate-500 mb-3">¿No tienes cuenta aún?</p>
                <a href="{{ route('register') }}" class="inline-block w-full py-2.5 px-4 border-2 border-indigo-600 text-indigo-600 font-semibold text-sm rounded-lg hover:bg-indigo-600 hover:text-white transition-all duration-200 text-center">
                    Crear cuenta nueva
                </a>
            </div>
        </div>
        <!-- Badges de confianza -->
        <div class="flex items-center gap-4 mt-8">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-200">
                <span class="text-base">🔒</span>
                <span class="text-xs font-semibold text-slate-600">Seguro</span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-200">
                <span class="text-base">📚</span>
                <span class="text-xs font-semibold text-slate-600">+1000 libros</span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-200">
                <span class="text-base">⭐</span>
                <span class="text-xs font-semibold text-slate-600">Gratuito</span>
            </div>
        </div>
    </x-slot>

</x-guest-layout>
