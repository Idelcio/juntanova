<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #0f172a; }
        .card { border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; }
        .items { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .items th, .items td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; font-size: 14px; }
        .items th { background: #f1f5f9; }
    </style>
</head>
<body>
    <h1>Ola {{ $pedido->usuario->nome ?? 'cliente' }},</h1>
    <p>Recebemos o pagamento do seu pedido #{{ $pedido->numero_pedido }} e ele ja esta em preparacao.</p>

    <div class="card">
        <p><strong>Total:</strong> R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</p>
        <p><strong>Frete:</strong> R$ {{ number_format($pedido->valor_frete, 2, ',', '.') }}</p>
        <p><strong>Endereco de entrega:</strong> {{ $pedido->rua }}, {{ $pedido->numero }} {{ $pedido->bairro ? ' - '.$pedido->bairro : '' }} - {{ $pedido->cidade }}/{{ $pedido->estado }} - CEP {{ $pedido->cep }}</p>
    </div>

    <h3>Itens do pedido</h3>
    <table class="items">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtd.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->itens as $item)
                <tr>
                    <td>{{ $item->nome }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Assim que o pedido for enviado voce recebera um novo email com o codigo de rastreio.</p>
    <p>Qualquer duvida, responda esta mensagem ou fale conosco pelo email juntanova2025@gmail.com.</p>

    <p>Obrigado por comprar com a Junta Nova!<br>Equipe Junta Nova</p>
</body>
</html>
