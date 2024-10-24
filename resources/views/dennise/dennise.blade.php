<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Para Ti</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .image-container {
            transition: transform 0.3s ease;
        }

        .image-container:hover {
            transform: scale(1.05);
        }

        .music-button {
            transition: all 0.3s ease;
        }

        .music-button:hover {
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .float-animation {
            animation: floating 3s ease-in-out infinite;
        }

        /* Animaciones para las flores */
        @keyframes sway {
            0% { transform: rotate(0deg); }
            50% { transform: rotate(15deg); }
            100% { transform: rotate(0deg); }
        }

        .sway-animation {
            animation: sway 3s ease-in-out infinite;
        }

        .flower {
            transform-origin: bottom center;
        }

        .petal {
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<div class="min-h-screen relative overflow-hidden bg-gradient-to-b from-orange-400 via-pink-500 to-purple-900">
    <!-- Sol -->
    <div class="absolute top-20 left-1/2 transform -translate-x-1/2 w-32 h-32 bg-yellow-500 rounded-full blur-lg opacity-75"></div>

    <!-- Flores -->
    <!-- Grupo 1 - Lado izquierdo -->
    <div class="absolute bottom-10 left-5 sway-animation" style="animation-delay: 0s;">
        <div class="flower">
            <div class="w-6 h-6 bg-pink-400 rounded-full"></div>
            <div class="w-4 h-12 bg-green-500 -mt-1 ml-1 rotate-12"></div>
        </div>
    </div>

    <div class="absolute bottom-16 left-12 sway-animation" style="animation-delay: 0.2s;">
        <div class="flower">
            <div class="w-5 h-5 bg-purple-300 rounded-full"></div>
            <div class="w-4 h-10 bg-green-600 -mt-1 ml-0.5 -rotate-6"></div>
        </div>
    </div>

    <div class="absolute bottom-24 left-20 sway-animation" style="animation-delay: 0.4s;">
        <div class="flower">
            <div class="w-7 h-7 bg-red-300 rounded-full"></div>
            <div class="w-4 h-14 bg-green-500 -mt-1 ml-1.5 rotate-3"></div>
        </div>
    </div>

    <!-- Grupo 2 - Lado derecho -->
    <div class="absolute bottom-12 right-8 sway-animation" style="animation-delay: 0.6s;">
        <div class="flower">
            <div class="w-6 h-6 bg-yellow-300 rounded-full"></div>
            <div class="w-4 h-12 bg-green-500 -mt-1 ml-1 -rotate-12"></div>
        </div>
    </div>

    <div class="absolute bottom-20 right-16 sway-animation" style="animation-delay: 0.8s;">
        <div class="flower">
            <div class="w-5 h-5 bg-pink-200 rounded-full"></div>
            <div class="w-4 h-10 bg-green-600 -mt-1 ml-0.5 rotate-6"></div>
        </div>
    </div>

    <!-- Grupo 3 - Flores flotantes -->
    <div class="absolute bottom-32 left-1/4 float-animation" style="animation-delay: 1s;">
        <div class="flower">
            <div class="w-4 h-4 bg-purple-200 rounded-full"></div>
            <div class="w-3 h-8 bg-green-500 -mt-1 ml-0.5"></div>
        </div>
    </div>

    <div class="absolute bottom-40 right-1/4 float-animation" style="animation-delay: 1.2s;">
        <div class="flower">
            <div class="w-4 h-4 bg-pink-100 rounded-full"></div>
            <div class="w-3 h-8 bg-green-500 -mt-1 ml-0.5"></div>
        </div>
    </div>

    <!-- Flores grandes decorativas -->
    <div class="absolute bottom-16 left-1/3 sway-animation" style="animation-delay: 1.4s;">
        <div class="flower">
            <div class="w-8 h-8 bg-red-200 rounded-full"></div>
            <div class="w-5 h-16 bg-green-600 -mt-1 ml-1.5 rotate-12"></div>
        </div>
    </div>

    <div class="absolute bottom-24 right-1/3 sway-animation" style="animation-delay: 1.6s;">
        <div class="flower">
            <div class="w-8 h-8 bg-purple-400 rounded-full"></div>
            <div class="w-5 h-16 bg-green-600 -mt-1 ml-1.5 -rotate-12"></div>
        </div>
    </div>

    <!-- Imagen de la chica -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 image-container float-animation">
        <img src="https://res.cloudinary.com/dpabol1z3/image/upload/v1729739473/aloor0qbmjnesfe49y4g.jpg"
             alt="Hermosa chica"
             class="w-64 h-64 object-cover rounded-full border-4 border-white shadow-lg">
    </div>

    <!-- Mensaje principal -->
    <div class="absolute top-1/4 left-1/2 transform -translate-x-1/2 text-center">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 tracking-wide">
            En tus ojos encuentro
        </h1>
        <p class="text-xl md:text-2xl text-white font-light italic">
            el más hermoso atardecer,<br>
            donde cada destello<br>
            ilumina mi mundo entero
        </p>
    </div>

    <!-- Botón de música -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-20">
        <button id="musicButton" class="music-button bg-white text-purple-900 px-6 py-3 rounded-full font-bold flex items-center space-x-2 hover:bg-purple-100">
            <span id="musicIcon">▶️</span>
            <span>Dale Click Corazón ❤️ </span>
        </button>
    </div>

    <!-- Audio element -->
    <audio id="music">
        <source src="{{ asset('/assets/music/dennise.mp3') }}" type="audio/mpeg">
    </audio>
</div>

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
