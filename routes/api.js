const express = require('express');
const router = express.Router();
const axios = require('axios');
const nodemailer = require('nodemailer');
const Pedido = require('../models/Pedido');
const Produto = require('../models/Produto');

// Mercado Pago desabilitado temporariamente
// Descomente abaixo quando quiser habilitar:
/*
const { MercadoPagoConfig, Preference, Payment } = require('mercadopago');
let client = null;
let preference = null;
let payment = null;

if (process.env.MP_ACCESS_TOKEN) {
  client = new MercadoPagoConfig({
    accessToken: process.env.MP_ACCESS_TOKEN
  });
  preference = new Preference(client);
  payment = new Payment(client);
}
*/

// Configurar email
const transporter = nodemailer.createTransport({
  host: process.env.EMAIL_HOST,
  port: process.env.EMAIL_PORT,
  secure: false,
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASS
  }
});

// Buscar CEP
router.get('/cep/:cep', async (req, res) => {
  try {
    const { cep } = req.params;
    const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`);

    if (response.data.erro) {
      return res.status(404).json({ error: 'CEP não encontrado' });
    }

    res.json(response.data);
  } catch (error) {
    console.error('Erro ao buscar CEP:', error);
    res.status(500).json({ error: 'Erro ao buscar CEP' });
  }
});

// Calcular frete
router.post('/calcular-frete', async (req, res) => {
  try {
    const { cep } = req.body;

    // Buscar informações do CEP
    const cepResponse = await axios.get(`https://viacep.com.br/ws/${cep}/json/`);

    if (cepResponse.data.erro) {
      return res.status(404).json({ error: 'CEP não encontrado' });
    }

    // Cálculo simplificado de frete baseado no estado
    const estado = cepResponse.data.uf;
    let valorFrete = 15.00; // Frete base

    // Tabela de frete por região
    const regiaoSul = ['RS', 'SC', 'PR'];
    const regiaoSudeste = ['SP', 'RJ', 'MG', 'ES'];
    const regiaoCentroOeste = ['GO', 'MT', 'MS', 'DF'];
    const regiaoNordeste = ['BA', 'SE', 'AL', 'PE', 'PB', 'RN', 'CE', 'PI', 'MA'];
    const regiaoNorte = ['AM', 'RR', 'AP', 'PA', 'TO', 'RO', 'AC'];

    if (regiaoSudeste.includes(estado)) {
      valorFrete = 15.00;
    } else if (regiaoSul.includes(estado)) {
      valorFrete = 18.00;
    } else if (regiaoCentroOeste.includes(estado)) {
      valorFrete = 22.00;
    } else if (regiaoNordeste.includes(estado)) {
      valorFrete = 25.00;
    } else if (regiaoNorte.includes(estado)) {
      valorFrete = 30.00;
    }

    res.json({
      valorFrete,
      prazo: '5-10 dias úteis',
      endereco: {
        cidade: cepResponse.data.localidade,
        estado: cepResponse.data.uf,
        bairro: cepResponse.data.bairro,
        rua: cepResponse.data.logradouro
      }
    });
  } catch (error) {
    console.error('Erro ao calcular frete:', error);
    res.status(500).json({ error: 'Erro ao calcular frete' });
  }
});

// Criar preferência de pagamento Mercado Pago (DESABILITADO)
router.post('/criar-pagamento/:pedidoId', async (req, res) => {
  try {
    const pedido = await Pedido.buscarPorId(req.params.pedidoId);

    if (!pedido) {
      return res.status(404).json({ error: 'Pedido não encontrado' });
    }

    // Mercado Pago desabilitado - retorna simulação
    console.log('⚠️ Mercado Pago desabilitado - Simulando pagamento para pedido:', pedido.numero_pedido);

    res.json({
      id: 'demo-' + pedido.id,
      init_point: '#', // Sem redirect
      message: 'Mercado Pago desabilitado. Configure MP_ACCESS_TOKEN no .env para habilitar.'
    });
  } catch (error) {
    console.error('Erro ao criar pagamento:', error);
    res.status(500).json({ error: 'Erro ao criar pagamento' });
  }
});

// Webhook Mercado Pago (DESABILITADO)
router.post('/webhook/mercadopago', async (req, res) => {
  console.log('⚠️ Webhook do Mercado Pago desabilitado');
  res.sendStatus(200);
});

// Função para enviar email
async function enviarEmailConfirmacao(pedido) {
  try {
    if (!process.env.EMAIL_USER || !process.env.EMAIL_PASS) {
      console.log('⚠️ Email não configurado. Pulando envio de email.');
      return;
    }

    const mailOptions = {
      from: process.env.EMAIL_FROM,
      to: process.env.ADMIN_EMAIL,
      subject: `Nova Venda - Pedido ${pedido.numero_pedido}`,
      html: `
        <h2>Nova Venda Realizada!</h2>
        <p><strong>Número do Pedido:</strong> ${pedido.numero_pedido}</p>
        <p><strong>Cliente:</strong> ${pedido.usuario.nome}</p>
        <p><strong>Email:</strong> ${pedido.usuario.email}</p>
        <p><strong>Telefone:</strong> ${pedido.usuario.telefone}</p>

        <h3>Endereço de Entrega:</h3>
        <p>
          ${pedido.rua}, ${pedido.numero}${pedido.complemento ? ' - ' + pedido.complemento : ''}<br>
          ${pedido.bairro}<br>
          ${pedido.cidade} - ${pedido.estado}<br>
          CEP: ${pedido.cep}
        </p>

        <h3>Itens:</h3>
        <ul>
          ${pedido.itens.map(item => `
            <li>${item.nome} - Quantidade: ${item.quantidade} - R$ ${parseFloat(item.subtotal).toFixed(2)}</li>
          `).join('')}
        </ul>

        <p><strong>Valor dos Produtos:</strong> R$ ${parseFloat(pedido.valor_produtos).toFixed(2)}</p>
        <p><strong>Frete:</strong> R$ ${parseFloat(pedido.valor_frete).toFixed(2)}</p>
        <p><strong>Valor Total:</strong> R$ ${parseFloat(pedido.valor_total).toFixed(2)}</p>

        <p><strong>Status:</strong> ${pedido.status}</p>
        <p><strong>Data:</strong> ${new Date(pedido.data_pedido).toLocaleString('pt-BR')}</p>
      `
    };

    await transporter.sendMail(mailOptions);
    console.log('✅ Email de confirmação enviado com sucesso!');
  } catch (error) {
    console.error('❌ Erro ao enviar email:', error);
  }
}

module.exports = router;
