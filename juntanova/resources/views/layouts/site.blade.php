<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Junta Nova') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' style='stop-color:rgb(255,107,53);stop-opacity:1' /><stop offset='100%25' style='stop-color:rgb(255,107,53);stop-opacity:1' /></linearGradient></defs><circle cx='50' cy='50' r='50' fill='url(%23grad)'/><text x='50' y='50' font-family='Arial,sans-serif' font-size='45' font-weight='bold' fill='white' text-anchor='middle' dominant-baseline='central'>JN</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-blue: #0C4696;
            --primary-orange: #FF6B35;
            --gradient-start: #0C4696;
            --gradient-end: #084080;
        }

        body {
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
        }

        .gradient-blue {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        }

        .btn-primary {
            background: var(--primary-orange);
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 1rem;
        }

        .card-hover {
            transition: all 0.3s;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white py-2">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-center md:justify-end items-center gap-6 text-sm">
                <span><i class="fas fa-shield-alt mr-2"></i>Domínio Verificado</span>
                <span><i class="fas fa-lock mr-2"></i>Compra 100% Segura</span>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 text-white flex items-center justify-center font-bold text-xl shadow-lg">
                        JN
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent">
                            Junta Nova
                        </h1>
                        <p class="text-xs text-gray-500">Articulações novas em pouco tempo</p>
                    </div>
                </a>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center gap-6">
                    <a href="#sobre" class="text-gray-700 hover:text-orange-500 font-medium transition">Sobre</a>
                    <a href="#ingredientes" class="text-gray-700 hover:text-orange-500 font-medium transition">Ingredientes</a>
                    <a href="#comprar" class="text-gray-700 hover:text-orange-500 font-medium transition">Produtos</a>
                    <a href="{{ route('carrinho') }}" class="relative px-4 py-2 rounded-lg hover:bg-orange-50 text-gray-700 transition">
                        <i class="fas fa-shopping-cart mr-2"></i>Carrinho
                        @php $totalItems = collect(session('cart', []))->sum('quantidade'); @endphp
                        @if($totalItems > 0)
                            <span class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                                {{ $totalItems }}
                            </span>
                        @endif
                    </a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-orange-500 font-medium transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-500 font-medium transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                        </a>
                    @endauth
                    <a href="#comprar" class="btn-primary">COMPRAR AGORA</a>
                </nav>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4">
                <div class="flex flex-col gap-3">
                    <a href="#sobre" class="text-gray-700 hover:text-orange-500 font-medium py-2">Sobre</a>
                    <a href="#ingredientes" class="text-gray-700 hover:text-orange-500 font-medium py-2">Ingredientes</a>
                    <a href="#comprar" class="text-gray-700 hover:text-orange-500 font-medium py-2">Produtos</a>
                    <a href="{{ route('carrinho') }}" class="text-gray-700 hover:text-orange-500 font-medium py-2">
                        <i class="fas fa-shopping-cart mr-2"></i>Carrinho
                        @if($totalItems > 0)
                            <span class="bg-orange-500 text-white text-xs font-bold rounded-full px-2 py-1 ml-2">
                                {{ $totalItems }}
                            </span>
                        @endif
                    </a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-orange-500 font-medium py-2 w-full text-left">
                                <i class="fas fa-sign-out-alt mr-2"></i>Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-500 font-medium py-2">
                            <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                        </a>
                    @endauth
                    <a href="#comprar" class="btn-primary text-center">COMPRAR AGORA</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-blue-900 to-blue-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-2xl font-bold mb-4">Junta Nova</h3>
                    <p class="text-blue-200 mb-4">Articulações novas em pouco tempo</p>
                    <p class="text-blue-200 text-sm">100% Natural, sem contraindicações</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Links Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-blue-200 hover:text-white transition">Início</a></li>
                        <li><a href="#sobre" class="text-blue-200 hover:text-white transition">Sobre o Produto</a></li>
                        <li><a href="#comprar" class="text-blue-200 hover:text-white transition">Comprar</a></li>
                        <li><a href="{{ route('carrinho') }}" class="text-blue-200 hover:text-white transition">Carrinho</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contato</h4>
                    <p class="text-blue-200 mb-2"><i class="fas fa-envelope mr-2"></i>juntanova2025@gmail.com</p>
                    <p class="text-blue-200"><i class="fas fa-map-marker-alt mr-2"></i>Vendido em todo o Brasil</p>
                </div>
            </div>

            <div class="border-t border-blue-700 mt-8 pt-8 text-center text-blue-200">
                <p>&copy; {{ date('Y') }} Junta Nova. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobile-menu');
                    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            });
        });

        // Add to Cart
        document.addEventListener('DOMContentLoaded', () => {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.querySelectorAll('[data-add-cart]').forEach((form) => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const produtoId = form.querySelector('input[name="produto_id"]').value;
                    const quantidade = form.querySelector('input[name="quantidade"]').value || 1;

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ produto_id: produtoId, quantidade }),
                        });
                        const data = await response.json();
                        if (!response.ok) {
                            alert(data.message || 'Não foi possível adicionar.');
                            return;
                        }
                        window.location.href = '{{ route("carrinho") }}';
                    } catch (e) {
                        alert('Erro ao adicionar: ' + e.message);
                    }
                });
            });
        });
    </script>
</body>
</html>
