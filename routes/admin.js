const express = require('express');
const router = express.Router();
const { isAdmin, redirectIfAuthenticated } = require('../middleware/auth');
const Usuario = require('../models/Usuario');
const Produto = require('../models/Produto');
const Pedido = require('../models/Pedido');
const Depoimento = require('../models/Depoimento');

// Login Admin
router.get('/login', redirectIfAuthenticated, (req, res) => {
  res.render('admin/login', { title: 'Login Admin', error: null });
});

router.post('/login', async (req, res) => {
  try {
    const { email, senha } = req.body;
    const usuario = await Usuario.buscarPorEmail(email);

    if (!usuario || !usuario.is_admin || !(await Usuario.verificarSenha(senha, usuario.senha))) {
      return res.render('admin/login', {
        title: 'Login Admin',
        error: 'Email ou senha inválidos'
      });
    }

    req.session.user = {
      id: usuario.id,
      nome: usuario.nome,
      email: usuario.email,
      isAdmin: usuario.is_admin
    };

    res.redirect('/admin/dashboard');
  } catch (error) {
    console.error('Erro no login:', error);
    res.render('admin/login', {
      title: 'Login Admin',
      error: 'Erro ao fazer login'
    });
  }
});

// Logout
router.get('/logout', (req, res) => {
  req.session.destroy();
  res.redirect('/admin/login');
});

// Dashboard
router.get('/dashboard', isAdmin, async (req, res) => {
  try {
    const totalPedidos = await Pedido.contar();
    const pedidosPendentes = await Pedido.contar({ status: 'pendente' });
    const pedidosPagos = await Pedido.contar({ status: 'pago' });

    const produto = await Produto.buscarPrincipal();
    const estoque = produto ? produto.estoque : 0;

    const vendasPorEstado = await Pedido.vendasPorEstado();
    const vendasPorCidade = await Pedido.vendasPorCidade(10);
    const pedidosRecentes = await Pedido.pedidosRecentes(10);

    res.render('admin/dashboard', {
      title: 'Dashboard Admin',
      stats: {
        totalPedidos,
        pedidosPendentes,
        pedidosPagos,
        estoque
      },
      vendasPorEstado,
      vendasPorCidade,
      pedidosRecentes
    });
  } catch (error) {
    console.error('Erro no dashboard:', error);
    res.status(500).send('Erro ao carregar dashboard');
  }
});

// Gerenciar Estoque
router.get('/estoque', isAdmin, async (req, res) => {
  try {
    const produto = await Produto.buscarPrincipal();
    res.render('admin/estoque', { title: 'Gerenciar Estoque', produto });
  } catch (error) {
    console.error('Erro ao carregar estoque:', error);
    res.status(500).send('Erro ao carregar estoque');
  }
});

router.post('/estoque/atualizar', isAdmin, async (req, res) => {
  try {
    const { estoque, preco, precoPromocional } = req.body;
    const produto = await Produto.buscarPrincipal();

    if (produto) {
      await Produto.atualizar(produto.id, {
        estoque: parseInt(estoque),
        preco: parseFloat(preco),
        precoPromocional: precoPromocional ? parseFloat(precoPromocional) : null
      });
    }

    res.redirect('/admin/estoque');
  } catch (error) {
    console.error('Erro ao atualizar estoque:', error);
    res.status(500).send('Erro ao atualizar estoque');
  }
});

// Gerenciar Pacotes
router.get('/pacotes', isAdmin, async (req, res) => {
  try {
    await Produto.garantirPacotesPadrao();
    const pacotes = await Produto.buscarPacotes();
    res.render('admin/pacotes', { title: 'Gerenciar Pacotes', pacotes });
  } catch (error) {
    console.error('Erro ao carregar pacotes:', error);
    res.status(500).send('Erro ao carregar pacotes');
  }
});

router.post('/pacotes/:id', isAdmin, async (req, res) => {
  try {
    const { preco, precoPromocional } = req.body;

    await Produto.atualizarPrecos(req.params.id, {
      preco: parseFloat(preco),
      precoPromocional: precoPromocional ? parseFloat(precoPromocional) : null
    });

    res.redirect('/admin/pacotes');
  } catch (error) {
    console.error('Erro ao atualizar pacote:', error);
    res.status(500).send('Erro ao atualizar pacote');
  }
});

// Gerenciar Pedidos
router.get('/pedidos', isAdmin, async (req, res) => {
  try {
    const pedidos = await Pedido.listarTodos();
    res.render('admin/pedidos', { title: 'Gerenciar Pedidos', pedidos });
  } catch (error) {
    console.error('Erro ao carregar pedidos:', error);
    res.status(500).send('Erro ao carregar pedidos');
  }
});

router.post('/pedidos/:id/status', isAdmin, async (req, res) => {
  try {
    const { status } = req.body;
    await Pedido.atualizarStatus(req.params.id, status);
    res.redirect('/admin/pedidos');
  } catch (error) {
    console.error('Erro ao atualizar status:', error);
    res.status(500).send('Erro ao atualizar status');
  }
});

// Gerenciar Depoimentos
router.get('/depoimentos', isAdmin, async (req, res) => {
  try {
    const depoimentos = await Depoimento.listarTodos();
    res.render('admin/depoimentos', { title: 'Gerenciar Depoimentos', depoimentos });
  } catch (error) {
    console.error('Erro ao carregar depoimentos:', error);
    res.status(500).send('Erro ao carregar depoimentos');
  }
});

router.post('/depoimentos/:id/toggle', isAdmin, async (req, res) => {
  try {
    await Depoimento.alternarAprovacao(req.params.id);
    res.redirect('/admin/depoimentos');
  } catch (error) {
    console.error('Erro ao atualizar depoimento:', error);
    res.status(500).send('Erro ao atualizar depoimento');
  }
});

module.exports = router;
