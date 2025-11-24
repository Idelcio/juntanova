<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedidoConfirmadoMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public Pedido $pedido;
    protected string $audiencia;

    public function __construct(Pedido $pedido, string $audiencia = 'admin')
    {
        $this->pedido = $pedido->loadMissing('usuario', 'itens');
        $this->audiencia = $audiencia === 'cliente' ? 'cliente' : 'admin';
    }

    public function build()
    {
        $subject = $this->audiencia === 'cliente'
            ? 'Confirmacao do pedido #'.$this->pedido->numero_pedido
            : 'Nova venda - Pedido #'.$this->pedido->numero_pedido;

        $view = $this->audiencia === 'cliente'
            ? 'emails.pedido-confirmado-cliente'
            : 'emails.pedido-confirmado';

        return $this->subject($subject)
            ->view($view);
    }
}
