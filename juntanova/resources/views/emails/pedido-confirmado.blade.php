<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #0f172a; }
        .card { border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; }
        .tag { display: inline-block; padding: 4px 8px; background: #f97316; color: #fff; border-radius: 6px; font-size: 12px; }
    </style>
</head>
<body>
    <h1>Nova Venda - Pedido #{{ $pedido->numero_pedido }}</h1>
    <p>Pagamento confirmado pelo Mercado Pago.</p>

    <div class="card">
        <p><strong>Cliente:</strong> {{ $pedido->usuario->nome ?? '' }}</p>
        <p><strong>Email:</strong> {{ $pedido->usuario->email ?? '' }}</p>
        <p><strong>Telefone:</strong> {{ $pedido->usuario->telefone ?? '' }}</p>
        <p><strong>CPF:</strong> {{ $pedido->usuario->cpf ?? '' }}</p>
        <p><strong>Endereço:</strong> {{ $pedido->rua }}, {{ $pedido->numero }} {{ $pedido->bairro ? ' - '.$pedido->bairro : '' }} - {{ $pedido->cidade }}/{{ $pedido->estado }}</p>
    </div>

    <h3>Produtos</h3>
    <ul>
        @foreach($pedido->itens as $item)
            <li>{{ $item->nome }} — {{ $item->quantidade }} un — R$ {{ number_format($item->subtotal, 2, ',', '.') }}</li>
        @endforeach
    </ul>

    <p><strong>Total:</strong> R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</p>
    <p><strong>Frete:</strong> R$ {{ number_format($pedido->valor_frete, 2, ',', '.') }}</p>

    <p class="tag">Acesse o admin para detalhes: {{ route('admin.pedidos.show', $pedido->id) }}</p>
</body>
</html>
