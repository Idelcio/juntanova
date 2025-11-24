@extends('layouts.site', ['title' => 'Junta Nova - Articulações Novas em Pouco Tempo'])

@section('content')
    <!-- Hero Section -->
    <section class="relative text-white overflow-hidden min-h-screen flex items-center w-screen -ml-[50vw] left-1/2"
        style="background-image: url('/imagens/Idosos_correndo.jpg'); background-size: cover; background-position: left center; background-attachment: fixed;">
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/75 via-blue-800/60 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-4 py-20 relative z-10 w-full">
            <div class="flex flex-col items-start">
                <!-- Text Content -->
                <div>
                    <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
                        Junta Nova
                    </h1>
                    <h2 class="text-2xl md:text-3xl font-light mb-8 text-blue-100">
                        Articulações novas em pouco tempo
                    </h2>

                    <!-- Image -->
                    <div class="relative max-w-sm mb-8">
                        <div class="relative z-10 transform hover:scale-105 transition duration-300">
                            <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-4 shadow-2xl">
                                <div class="bg-white rounded-2xl p-3">
                                    <img src="/imagens/produto2.jpg" alt="Junta Nova" class="w-full h-auto rounded-xl"
                                        onerror="this.src='https://via.placeholder.com/300x400/0C4696/FFFFFF?text=Junta+Nova'">
                                </div>
                            </div>
                        </div>

                        <!-- Badge -->
                        <div class="absolute -top-3 -right-3 z-20">
                            <div class="bg-orange-500 text-white rounded-full p-3 shadow-2xl animate-bounce">
                                <p class="text-center font-bold text-sm">100%</p>
                                <p class="text-xs text-center">Seguro</p>
                            </div>
                        </div>

                        <!-- Secure Site Badge -->
                        <div class="absolute -bottom-3 -left-5 z-20">
                            <img src="/imagens/siteseguro.png" alt="Site Seguro" class="w-20 h-auto shadow-xl">
                        </div>
                    </div>

                    @if (isset($produtos[0]))
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-8 inline-block">
                            <p class="text-blue-100 text-sm mb-2">Por apenas</p>
                            <p class="text-5xl font-bold">
                                R$ {{ number_format($produtos[0]->preco_promocional ?? $produtos[0]->preco, 2, ',', '.') }}
                            </p>
                            <p class="text-blue-100 text-sm mt-2">30 cápsulas | 500mg cada</p>
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-4">
                        <a href="#comprar"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-2xl transform hover:scale-105 transition">
                            Ver Pacotes e Promoções
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Badges Row -->
            <div class="flex flex-wrap justify-center items-center gap-8 mt-12">
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-6 py-3">
                    <i class="fas fa-shield-alt text-3xl text-green-400"></i>
                    <div>
                        <p class="font-bold text-sm">Compra Segura</p>
                        <p class="text-xs text-blue-100">SSL Certificado</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-6 py-3">
                    <img src="/imagens/mercado_pago.png" alt="Mercado Pago" class="h-8 w-auto"
                        onerror="this.style.display='none'">
                    <div>
                        <p class="font-bold text-sm">Pagamento</p>
                        <p class="text-xs text-blue-100">100% Seguro</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-6 py-3">
                    <i class="fas fa-truck text-3xl text-green-400"></i>
                    <div>
                        <p class="font-bold text-sm">Frete Grátis</p>
                        <p class="text-xs text-blue-100">Sul e Sudeste*</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z"
                    fill="rgb(249 250 251)" />
            </svg>
        </div>
    </section>

    <!-- Sobre o Produto -->
    <section id="sobre" class="mb-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="section-title">O que é Junta Nova?</h2>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                    Um suplemento 100% natural pensado para quem sente o impacto do tempo nos joelhos
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="bg-white rounded-2xl shadow-xl p-8 card-hover">
                        <p class="text-gray-700 mb-6 leading-relaxed">
                            <strong class="text-blue-900">Junta Nova</strong> é um suplemento 100% natural pensado para quem
                            sente o impacto do tempo nos joelhos: rigidez ao levantar, estalos, perda de confiança para
                            subir escadas ou praticar atividade física.
                        </p>

                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-orange-500 text-xl mt-1"></i>
                                <span class="text-gray-700">Reforça a cartilagem dos joelhos e evita o atrito entre os
                                    ossos</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-orange-500 text-xl mt-1"></i>
                                <span class="text-gray-700">Mantém a elasticidade e a lubrificação para movimentos mais
                                    suaves</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-orange-500 text-xl mt-1"></i>
                                <span class="text-gray-700">Reduz inchaço e desconfortos após caminhadas ou
                                    exercícios</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-orange-500 text-xl mt-1"></i>
                                <span class="text-gray-700">Protege contra o envelhecimento articular e o desgaste
                                    diário</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-orange-500 text-xl mt-1"></i>
                                <span class="text-gray-700">Devolve segurança para agachar, subir degraus e viver sem
                                    limitações</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <img src="/imagens/produto3.jpg" alt="Junta Nova" class="rounded-2xl shadow-2xl w-full h-auto"
                        onerror="this.src='https://via.placeholder.com/500x600/0C4696/FFFFFF?text=Junta+Nova'">
                </div>
            </div>
        </div>
    </section>

    <!-- Benefícios -->
    <section class="bg-gradient-to-r from-blue-50 to-orange-50 -mx-4 py-16 mb-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="section-title">Por que escolher Junta Nova?</h2>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg card-hover">
                    <div class="text-6xl mb-4">💊</div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">100% Natural</h3>
                    <p class="text-gray-600">Composto natural para articulações sem contraindicações</p>
                </div>

                <div class="bg-white rounded-2xl p-8 text-center shadow-lg card-hover">
                    <div class="text-6xl mb-4">⚡</div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">Resultados Rápidos</h3>
                    <p class="text-gray-600">Articulações novas em pouco tempo de uso</p>
                </div>

                <div class="bg-white rounded-2xl p-8 text-center shadow-lg card-hover">
                    <div class="text-6xl mb-4">✅</div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">Sem Contraindicações</h3>
                    <p class="text-gray-600">Seguro para uso contínuo</p>
                </div>

                <div class="bg-white rounded-2xl p-8 text-center shadow-lg card-hover">
                    <div class="text-6xl mb-4">🇧🇷</div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">Entrega Nacional</h3>
                    <p class="text-gray-600">Enviamos para todo o Brasil</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Ingredientes -->
    <section id="ingredientes" class="mb-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="section-title">Ingredientes Naturais e Poderosos</h2>
                <p class="text-gray-600 text-lg">Fórmula cientificamente desenvolvida</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-xl text-center card-hover">
                    <div class="w-32 h-32 mx-auto mb-6 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="fas fa-dna text-5xl text-blue-900"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-4">Colágeno Tipo II</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Proteína estrutural que ajuda a repor a matriz da cartilagem, mantendo os joelhos firmes e elásticos
                        ao longo dos anos.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-xl text-center card-hover">
                    <div class="w-32 h-32 mx-auto mb-6 bg-orange-50 rounded-full flex items-center justify-center">
                        <i class="fas fa-leaf text-5xl text-orange-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-4">Uncaria tomentosa</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Conhecida como unha-de-gato, oferece ação antioxidante e anti-inflamatória, protegendo as
                        articulações contra o desgaste.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-xl text-center card-hover">
                    <div class="w-32 h-32 mx-auto mb-6 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="fas fa-spa text-5xl text-blue-900"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-4">Boswellia serrata</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Resina tradicional que auxilia na lubrificação das articulações, reduz o inchaço e ajuda a manter os
                        joelhos livres de rigidez.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pacotes e Preços -->
    <section id="comprar" class="bg-gradient-to-r from-blue-900 to-blue-800 -mx-4 py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-4">
                    Escolha o Melhor Pacote Para Você
                </h2>
                <p class="text-blue-100 text-xl">Quanto mais você compra, mais você economiza!</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach ($produtos as $index => $produto)
                    <div
                        class="bg-white rounded-3xl p-8 shadow-2xl relative {{ $index === 1 ? 'transform md:scale-110 z-10 border-4 border-orange-500' : '' }} card-hover">
                        @if ($index === 1)
                            <div
                                class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-orange-500 text-white px-6 py-2 rounded-full font-bold text-sm shadow-lg">
                                MAIS VENDIDO
                            </div>
                        @endif

                        @if ($index === 2)
                            <div
                                class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-2 rounded-full font-bold text-sm shadow-lg">
                                MELHOR OFERTA
                            </div>
                        @endif

                        <div class="text-center mb-6">
                            <div class="mb-6">
                                @if ($produto->capsulas == 30)
                                    <img src="/imagens/junta1.png" alt="Kit 1 Mês" class="w-32 h-auto mx-auto"
                                        onerror="this.src='https://via.placeholder.com/150x200/0C4696/FFFFFF?text=Kit+1'">
                                @elseif($produto->capsulas == 90)
                                    <img src="/imagens/junta3.png" alt="Kit 3 Meses" class="w-40 h-auto mx-auto"
                                        onerror="this.src='https://via.placeholder.com/200x250/FF6B35/FFFFFF?text=Kit+3'">
                                @else
                                    <img src="/imagens/junta5.png" alt="Kit 5 Meses" class="w-48 h-auto mx-auto"
                                        onerror="this.src='https://via.placeholder.com/250x300/0C4696/FFFFFF?text=Kit+5'">
                                @endif
                            </div>
                            <h3 class="text-2xl font-bold text-blue-900 mb-2">{{ $produto->nome }}</h3>
                            <p class="text-gray-600">{{ $produto->descricao }}</p>
                            <p class="text-sm text-gray-500 mt-2">Estoque: {{ $produto->estoque }} unidades</p>
                        </div>

                        <div class="text-center mb-6">
                            @if ($produto->preco_promocional)
                                <p class="text-gray-400 line-through text-lg">
                                    De R$ {{ number_format($produto->preco, 2, ',', '.') }}
                                </p>
                                <p class="text-4xl font-extrabold text-orange-500 mb-2">
                                    R$ {{ number_format($produto->preco_promocional, 2, ',', '.') }}
                                </p>
                            @else
                                <p class="text-4xl font-extrabold text-blue-900 mb-2">
                                    R$ {{ number_format($produto->preco, 2, ',', '.') }}
                                </p>
                            @endif
                            <p class="text-gray-600">
                                ou 12x de R$
                                {{ number_format(($produto->preco_promocional ?? $produto->preco) / 12, 2, ',', '.') }}
                            </p>
                        </div>

                        <form action="{{ route('api.carrinho.add') }}" method="POST" data-add-cart>
                            @csrf
                            <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                            <input type="hidden" name="quantidade" value="1">
                            <button type="submit"
                                class="w-full {{ $index === 1 ? 'bg-orange-500 hover:bg-orange-600' : 'bg-blue-900 hover:bg-blue-800' }} text-white py-4 rounded-xl font-bold text-lg shadow-lg transform hover:scale-105 transition">
                                Comprar Agora
                            </button>
                        </form>

                        <ul class="mt-6 space-y-3 text-sm text-gray-700">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-green-500"></i>
                                {{ $produto->capsulas }} cápsulas
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-truck text-green-500"></i>
                                @if ($produto->capsulas >= 90)
                                    Frete GRÁTIS
                                @else
                                    Frete calculado no checkout
                                @endif
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-shield-alt text-green-500"></i>
                                Compra 100% segura
                            </li>
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Depoimentos -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="section-title">O que nossos clientes dizem</h2>
                <p class="text-gray-600 text-lg">Veja os resultados reais de quem já usa Junta Nova</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Depoimento 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 shadow-lg card-hover">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-blue-900 to-blue-700 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                            M
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900">Maria Silva</h4>
                            <div class="flex text-orange-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">"Depois de 2 meses usando Junta Nova, consigo subir escadas sem dor.
                        Voltei a fazer minhas caminhadas diárias!"</p>
                </div>

                <!-- Depoimento 2 -->
                <div class="bg-gradient-to-br from-orange-50 to-white rounded-2xl p-8 shadow-lg card-hover">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                            J
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900">João Santos</h4>
                            <div class="flex text-orange-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">"Incrível! Os estalos nos joelhos diminuíram muito. Me sinto mais jovem
                        e disposto para trabalhar."</p>
                </div>

                <!-- Depoimento 3 -->
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 shadow-lg card-hover">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-16 h-16 bg-gradient-to-r from-blue-900 to-blue-700 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                            A
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900">Ana Costa</h4>
                            <div class="flex text-orange-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">"Produto excelente! Recomendo para todos que sofrem com dores nas
                        articulações. Resultados em poucas semanas."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-20 bg-gradient-to-r from-blue-50 to-orange-50 -mx-4">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="section-title">Perguntas Frequentes</h2>
                <p class="text-gray-600 text-lg">Tire suas dúvidas sobre o Junta Nova</p>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <details class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <summary
                        class="cursor-pointer px-6 py-5 font-bold text-blue-900 hover:bg-blue-50 transition flex justify-between items-center">
                        <span>Como devo tomar Junta Nova?</span>
                        <i class="fas fa-chevron-down"></i>
                    </summary>
                    <div class="px-6 py-4 text-gray-700 border-t">
                        <p>Recomendamos tomar 2 cápsulas por dia, preferencialmente junto com as refeições. Para melhores
                            resultados, mantenha o uso contínuo por pelo menos 3 meses.</p>
                    </div>
                </details>

                <!-- FAQ 2 -->
                <details class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <summary
                        class="cursor-pointer px-6 py-5 font-bold text-blue-900 hover:bg-blue-50 transition flex justify-between items-center">
                        <span>Junta Nova tem contraindicações?</span>
                        <i class="fas fa-chevron-down"></i>
                    </summary>
                    <div class="px-6 py-4 text-gray-700 border-t">
                        <p>Junta Nova é um produto 100% natural e não possui contraindicações conhecidas. Porém, se você
                            possui alguma condição de saúde específica ou está grávida/amamentando, consulte seu médico
                            antes de iniciar o uso.</p>
                    </div>
                </details>

                <!-- FAQ 3 -->
                <details class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <summary
                        class="cursor-pointer px-6 py-5 font-bold text-blue-900 hover:bg-blue-50 transition flex justify-between items-center">
                        <span>Em quanto tempo verei resultados?</span>
                        <i class="fas fa-chevron-down"></i>
                    </summary>
                    <div class="px-6 py-4 text-gray-700 border-t">
                        <p>Os resultados variam de pessoa para pessoa. Muitos clientes relatam melhora nas primeiras
                            semanas, mas para resultados ótimos, recomendamos o uso contínuo por 3 a 6 meses.</p>
                    </div>
                </details>

                <!-- FAQ 4 -->
                <details class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <summary
                        class="cursor-pointer px-6 py-5 font-bold text-blue-900 hover:bg-blue-50 transition flex justify-between items-center">
                        <span>Qual a forma de pagamento?</span>
                        <i class="fas fa-chevron-down"></i>
                    </summary>
                    <div class="px-6 py-4 text-gray-700 border-t">
                        <p>Aceitamos todas as formas de pagamento pelo Mercado Pago: cartão de crédito (parcelado em até
                            12x), PIX, boleto bancário e carteiras digitais. Todas as transações são 100% seguras.</p>
                    </div>
                </details>

                <!-- FAQ 5 -->
                <details class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <summary
                        class="cursor-pointer px-6 py-5 font-bold text-blue-900 hover:bg-blue-50 transition flex justify-between items-center">
                        <span>Como funciona a entrega?</span>
                        <i class="fas fa-chevron-down"></i>
                    </summary>
                    <div class="px-6 py-4 text-gray-700 border-t">
                        <p>Enviamos para todo o Brasil via Correios ou transportadora. Na compra de 3 ou mais potes, o frete
                            é GRÁTIS para Sul e Sudeste. O prazo de entrega varia de acordo com sua região (7 a 20 dias
                            úteis).</p>
                    </div>
                </details>

                <!-- FAQ 6 -->
                <details class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <summary
                        class="cursor-pointer px-6 py-5 font-bold text-blue-900 hover:bg-blue-50 transition flex justify-between items-center">
                        <span>Junta Nova é aprovado pela ANVISA?</span>
                        <i class="fas fa-chevron-down"></i>
                    </summary>
                    <div class="px-6 py-4 text-gray-700 border-t">
                        <p>Sim! Junta Nova é um suplemento alimentar registrado e aprovado pela ANVISA, seguindo todas as
                            normas de qualidade e segurança exigidas pela legislação brasileira.</p>
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="text-center py-20">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-4xl md:text-5xl font-extrabold text-blue-900 mb-6">
                Comece sua jornada para articulações saudáveis
            </h2>
            <p class="text-2xl text-gray-600 mb-8">Mais saúde, mais vida!</p>
            <a href="#comprar"
                class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-12 py-5 rounded-xl font-bold text-xl shadow-2xl transform hover:scale-105 transition">
                Escolher Meu Pacote
            </a>
        </div>
    </section>
@endsection
