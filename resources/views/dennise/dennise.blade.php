<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje Especial</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fuentes de Google (opcionales para mejorar la tipografía) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Estilos personalizados adicionales -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Animación personalizada para el brillo */
        @keyframes sparkle {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.5; }
            100% { transform: scale(1); opacity: 1; }
        }

        .sparkle {
            animation: sparkle 2s infinite;
        }

        /* Mejora de las animaciones de flores */
        @keyframes gentleBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .flower-bounce {
            animation: gentleBounce 3s infinite ease-in-out;
        }
    </style>
</head>
<body>
<div class="min-h-screen relative overflow-hidden bg-gradient-to-b from-orange-400 via-pink-500 to-purple-900">
    <!-- Sol -->
    <div class="absolute top-20 left-1/2 transform -translate-x-1/2 w-32 h-32 bg-yellow-500 rounded-full blur-lg opacity-75"></div>

    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 image-container float-animation">
        <img src="https://res.cloudinary.com/dpabol1z3/image/upload/v1729739473/aloor0qbmjnesfe49y4g.jpg"
             alt="Hermosa chica"
             class="w-64 h-64 object-cover rounded-full border-4 border-white shadow-lg">
    </div>

    <!-- Montañas -->
    <div class="absolute bottom-0 w-full">
        <div class="absolute bottom-0 w-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="fill-purple-900">
                <path d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>

    <!-- Flores con animación mejorada -->
    <div class="absolute bottom-20 left-10 flower-bounce">
        <div class="w-4 h-4 bg-pink-400 rounded-full"></div>
        <div class="w-4 h-8 bg-green-500 -mt-1 rotate-12"></div>
    </div>
    <div class="absolute bottom-24 left-20 flower-bounce" style="animation-delay: 0.5s;">
        <div class="w-4 h-4 bg-purple-400 rounded-full"></div>
        <div class="w-4 h-8 bg-green-500 -mt-1 -rotate-12"></div>
    </div>
    <div class="absolute bottom-16 left-32 flower-bounce" style="animation-delay: 1s;">
        <div class="w-4 h-4 bg-pink-300 rounded-full"></div>
        <div class="w-4 h-8 bg-green-500 -mt-1 rotate-6"></div>
    </div>

    <!-- Mensaje principal -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 tracking-wide">
            En tus ojos encuentro
        </h1>
        <p class="text-xl md:text-2xl text-white font-light italic">
            el más hermoso atardecer,<br>
            donde cada destello<br>
            ilumina mi mundo entero
        </p>
    </div>

    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2">
        <button id="musicButton" class="music-button bg-white text-purple-900 px-6 py-3 rounded-full font-bold flex items-center space-x-2 hover:bg-purple-100">
            <span id="musicIcon">▶️</span>
            <span>Dale Click</span>
        </button>
    </div>
    <audio id="music">
        <source src="{{ asset('music/dennise.mp3') }}" type="audio/mpeg">
    </audio>
</div>

    <!-- Efectos brillantes para los ojos -->
    <div class="absolute top-1/3 left-1/4 w-2 h-2 bg-white rounded-full sparkle"></div>
    <div class="absolute top-1/3 right-1/4 w-2 h-2 bg-white rounded-full sparkle" style="animation-delay: 1s;"></div>
</div>

<!-- Script opcional para efectos adicionales -->
<script>
    // Puedes agregar efectos interactivos aquí si lo deseas
    document.addEventListener('DOMContentLoaded', function() {
        // Por ejemplo, hacer que el mensaje aparezca gradualmente
        const message = document.querySelector('h1');
        message.style.opacity = 0;
        setTimeout(() => {
            message.style.transition = 'opacity 2s ease-in-out';
            message.style.opacity = 1;
        }, 500);
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const musicButton = document.getElementById('musicButton');
        const music = document.getElementById('music');
        const musicIcon = document.getElementById('musicIcon');
        let isPlaying = false;

        musicButton.addEventListener('click', function() {
            if (isPlaying) {
                music.pause();
                musicIcon.textContent = '▶️';
            } else {
                music.play();
                musicIcon.textContent = '⏸️';
            }
            isPlaying = !isPlaying;
        });
    });
</script>
</body>
</html>
