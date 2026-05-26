<x-guest-layout>
    <div class="px-8 py-8">
        <p class="text-sm font-semibold text-amber-500 mb-1">¡Estamos casi! 📧</p>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Verificar correo electrónico</h2>
        <p class="text-sm text-slate-500 mb-6">¡Gracias por registrarte! Antes de empezar, verifica tu correo electrónico haciendo clic en el enlace que te hemos enviado.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm font-medium text-green-800">✅ Hemos enviado un nuevo enlace de verificación a tu correo electrónico.</p>
            </div>
        @endif

        <p class="text-sm text-slate-600 mb-6">Si no recibiste el correo, te enviaremos otro sin problema.</p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full rounded-lg bg-amber-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200">
                Reenviar correo de verificación
            </button>
        </form>
    </div>

    <x-slot name="footer">
        <div class="border-t border-slate-200 mt-8 pt-8">
            <div class="text-center">
                <p class="text-sm text-slate-600 mb-4">¿Necesitas salir de tu cuenta?</p>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-indigo-600 hover:text-indigo-500 font-semibold text-sm">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </x-slot>
</x-guest-layout>
