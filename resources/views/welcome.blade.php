<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LecturaXP - Cargando...</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color: white; overflow: hidden; flex-direction: column; gap: 40px; }
        
        .book, .book__pg-shadow, .book__pg { animation: cover 5s ease-in-out infinite; }
        .book { background-color: hsl(268, 90%, 65%); border-radius: 0.25em; box-shadow: 0 0.25em 0.5em hsla(0, 0%, 0%, 0.3), 0 0 0 0.25em hsl(278, 100%, 57%) inset; padding: 0.25em; perspective: 37.5em; position: relative; width: 8em; height: 6em; transform: translate3d(0, 0, 0); transform-style: preserve-3d; }
        .book__pg-shadow, .book__pg { position: absolute; left: 0.25em; width: calc(50% - 0.25em); }
        .book__pg-shadow { animation-name: shadow; background-image: linear-gradient(-45deg, hsla(0, 0%, 0%, 0) 50%, hsla(0, 0%, 0%, 0.3) 50%); filter: blur(0.25em); top: calc(100% - 0.25em); height: 3.75em; transform: scaleY(0); transform-origin: 100% 0%; }
        .book__pg { animation-name: pg1; background-color: hsl(223, 10%, 100%); background-image: linear-gradient(90deg, hsla(223, 10%, 90%, 0) 87.5%, hsl(223, 10%, 90%)); height: calc(100% - 0.5em); transform-origin: 100% 50%; }
        .book__pg--2, .book__pg--3, .book__pg--4 { background-image: repeating-linear-gradient(hsl(223, 10%, 10%) 0 0.125em, hsla(223, 10%, 10%, 0) 0.125em 0.5em), linear-gradient(90deg, hsla(223, 10%, 90%, 0) 87.5%, hsl(223, 10%, 90%)); background-repeat: no-repeat; background-position: center; background-size: 2.5em 4.125em, 100% 100%; }
        .book__pg--2 { animation-name: pg2; }
        .book__pg--3 { animation-name: pg3; }
        .book__pg--4 { animation-name: pg4; }
        .book__pg--5 { animation-name: pg5; }
        
        .title { font-size: 32px; font-weight: 700; background: linear-gradient(90deg, #4f46e5, #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .subtitle { font-size: 14px; color: rgba(255, 255, 255, 0.7); }
        
        @keyframes cover { from, 5%, 45%, 55%, 95%, to { animation-timing-function: ease-out; background-color: hsl(278, 84%, 67%); } 10%, 40%, 60%, 90% { animation-timing-function: ease-in; background-color: hsl(271, 90%, 45%); } }
        @keyframes shadow { from, 10.01%, 20.01%, 30.01%, 40.01% { animation-timing-function: ease-in; transform: translate3d(0, 0, 1px) scaleY(0) rotateY(0); } 5%, 15%, 25%, 35%, 45%, 55%, 65%, 75%, 85%, 95% { animation-timing-function: ease-out; transform: translate3d(0, 0, 1px) scaleY(0.2) rotateY(90deg); } 10%, 20%, 30%, 40%, 50%, to { animation-timing-function: ease-out; transform: translate3d(0, 0, 1px) scaleY(0) rotateY(180deg); } 50.01%, 60.01%, 70.01%, 80.01%, 90.01% { animation-timing-function: ease-in; transform: translate3d(0, 0, 1px) scaleY(0) rotateY(180deg); } 60%, 70%, 80%, 90%, to { animation-timing-function: ease-out; transform: translate3d(0, 0, 1px) scaleY(0) rotateY(0); } }
        @keyframes pg1 { from, to { animation-timing-function: ease-in-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(0.4deg); } 10%, 15% { animation-timing-function: ease-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(180deg); } 20%, 80% { animation-timing-function: ease-in; background-color: hsl(223, 10%, 45%); transform: translate3d(0, 0, 1px) rotateY(180deg); } 85%, 90% { animation-timing-function: ease-in-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(180deg); } }
        @keyframes pg2 { from, to { animation-timing-function: ease-in; background-color: hsl(223, 10%, 45%); transform: translate3d(0, 0, 1px) rotateY(0.3deg); } 5%, 10% { animation-timing-function: ease-in-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(0.3deg); } 20%, 25% { animation-timing-function: ease-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(179.9deg); } 30%, 70% { animation-timing-function: ease-in; background-color: hsl(223, 10%, 45%); transform: translate3d(0, 0, 1px) rotateY(179.9deg); } 75%, 80% { animation-timing-function: ease-in-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(179.9deg); } 90%, 95% { animation-timing-function: ease-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(0.3deg); } }
        @keyframes pg3 { from, 10%, 90%, to { animation-timing-function: ease-in; background-color: hsl(223, 10%, 45%); transform: translate3d(0, 0, 1px) rotateY(0.2deg); } 15%, 20% { animation-timing-function: ease-in-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(0.2deg); } 30%, 35% { animation-timing-function: ease-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(179.8deg); } 40%, 60% { animation-timing-function: ease-in; background-color: hsl(223, 10%, 45%); transform: translate3d(0, 0, 1px) rotateY(179.8deg); } 65%, 70% { animation-timing-function: ease-in-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(179.8deg); } 80%, 85% { animation-timing-function: ease-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(0.2deg); } }
        @keyframes pg4 { from, 20%, 80%, to { animation-timing-function: ease-in; background-color: hsl(223, 10%, 45%); transform: translate3d(0, 0, 1px) rotateY(0.1deg); } 25%, 30% { animation-timing-function: ease-in-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(0.1deg); } 40%, 45% { animation-timing-function: ease-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(179.7deg); } 50% { animation-timing-function: ease-in; background-color: hsl(223, 10%, 45%); transform: translate3d(0, 0, 1px) rotateY(179.7deg); } 55%, 60% { animation-timing-function: ease-in-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(179.7deg); } 70%, 75% { animation-timing-function: ease-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(0.1deg); } }
        @keyframes pg5 { from, 30%, 70%, to { animation-timing-function: ease-in; background-color: hsl(223, 10%, 45%); transform: translate3d(0, 0, 1px) rotateY(0); } 35%, 40% { animation-timing-function: ease-in-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(0deg); } 50% { animation-timing-function: ease-in-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(179.6deg); } 60%, 65% { animation-timing-function: ease-out; background-color: hsl(223, 10%, 100%); transform: translate3d(0, 0, 1px) rotateY(0); } }
    </style>
</head>
<body>
    <div class="book">
        <div class="book__pg-shadow"></div>
        <div class="book__pg"></div>
        <div class="book__pg book__pg--2"></div>
        <div class="book__pg book__pg--3"></div>
        <div class="book__pg book__pg--4"></div>
        <div class="book__pg book__pg--5"></div>
    </div>
    
    <div style="text-align: center;">
        <h1 class="title">LecturaXP</h1>
        <p class="subtitle">Cargando...</p>
    </div>
    
    <script>
        function redirect() {
            @if(auth()->check())
                setTimeout(() => { window.location.replace('{{ url("/dashboard") }}'); }, 3000);
            @else
                setTimeout(() => { window.location.replace('{{ route("login") }}'); }, 5000);
            @endif
        }
        
        // Se ejecuta siempre que se visita la página (incluyendo al volver atrás)
        window.addEventListener('pageshow', redirect);
        
        // También ejecutar en caso de que pageshow no se dispare
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', redirect);
        } else {
            redirect();
        }
    </script>
</body>
</html>
