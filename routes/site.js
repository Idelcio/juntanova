const express = require('express');
const router = express.Router();
const Usuario = require('../models/Usuario');
const Produto = require('../models/Produto');
const Pedido = require('../models/Pedido');
const Depoimento = require('../models/Depoimento');
const { isAuthenticated } = require('../middleware/auth');

async function obterTabelaPrecos() {
  const pacotes = await Produto.buscarPacotes();
  const tabela = {};

  pacotes.forEach(pacote => {
    const capsulas = Number(pacote.capsulas);
    tabela[capsulas] = parseFloat(pacote.preco_promocional || pacote.preco);
  });

  return {
    base: tabela[30] || 0,
    combo3: tabela[90] || null,
    combo5: tabela[150] || null,
    mapa: tabela
  };
}

function calcularSubtotalComDesconto(quantidade, tabela) {
  if (!quantidade || quantidade <= 0) {
    return { subtotal: 0, precoUnitario: tabela.base || 0 };
  }

  const precoBase = tabela.base || 0;
  const precoCombo5 = tabela.combo5 || (precoBase * 5);
  const precoCombo3 = tabela.combo3 || (precoBase * 3);

  let restante = quantidade;
  let subtotal = 0;

  if (precoCombo5 && restante >= 5) {
    const combos5 = Math.floor(restante / 5);
    subtotal += combos5 * precoCombo5;
    restante -= combos5 * 5;
  }

  if (precoCombo3 && restante >= 3) {
    const combos3 = Math.floor(restante / 3);
    subtotal += combos3 * precoCombo3;
    restante -= combos3 * 3;
  }

  subtotal += restante * precoBase;

  return {
    subtotal,
    precoUnitario: subtotal / quantidade
  };
}

async function recalcularCarrinho(cart) {
  if (!cart || cart.length === 0) {
    return { cart: [], total: 0 };
  }

  const tabela = await obterTabelaPrecos();
  let total = 0;

  cart.forEach(item => {
    const quantidade = item.quantidade || 0;
    const capsulas = Number(item.capsulas || 30);

    if (capsulas === 30) {
      const { subtotal, precoUnitario } = calcularSubtotalComDesconto(quantidade, tabela);
      total += subtotal;
      item.preco = parseFloat(precoUnitario.toFixed(2));
    } else {
      const precoItem = item.preco || tabela.mapa[capsulas] || 0;
      total += precoItem * quantidade;
      item.preco = parseFloat(precoItem.toFixed(2));
    }
  });

  return {
    cart,
    total: parseFloat(total.toFixed(2))
  };
}

// Página Inicial
router.get('/', async (req, res) => {
  try {
    await Produto.garantirPacotesPadrao();
    const produto = await Produto.buscarPrincipal();
    const pacotes = await Produto.buscarPacotes();
    const depoimentos = await Depoimento.buscarAprovados(6);
    const comprasRecentes = await Pedido.comprasRecentes(5);

    res.render('site/index', {
      title: 'Junta Nova - Articulações Novas em Pouco Tempo',
      produto,
      pacotes,
      depoimentos,
      comprasRecentes
    });
  } catch (error) {
    console.error('Erro ao carregar página inicial:', error);
    res.status(500).send('Erro ao carregar página');
  }
});

// Página do Produto
router.get('/produto', async (req, res) => {
  try {
    const produto = await Produto.buscarPrincipal();
    const depoimentos = await Depoimento.buscarAprovados();

    res.render('site/produto', {
      title: 'Junta Nova - Produto',
      produto,
      depoimentos
    });
  } catch (error) {
    console.error('Erro ao carregar produto:', error);
    res.status(500).send('Erro ao carregar produto');
  }
});

// Cadastro
router.get('/cadastro', (req, res) => {
  res.render('site/cadastro', { title: 'Cadastre-se', error: null });
});

router.post('/cadastro', async (req, res) => {
  try {
    const {
      nome,
      email,
      senha,
      telefone,
      cpf,
      cep,
      rua,
      numero,
      complemento,
      bairro,
      cidade,
      estado,
      aceitoTermos
    } = req.body;

    if (!aceitoTermos) {
      return res.render('site/cadastro', {
        title: 'Cadastre-se',
        error: 'Você precisa aceitar os Termos de Uso e a Política de Privacidade para continuar.'
      });
    }

    // Verificar se email já existe
    const usuarioExistente = await Usuario.buscarPorEmail(email);
    if (usuarioExistente) {
      return res.render('site/cadastro', {
        title: 'Cadastre-se',
        error: 'Email já cadastrado'
      });
    }

    const usuarioId = await Usuario.criar({
      nome,
      email,
      senha,
      telefone,
      cpf,
      endereco: { cep, rua, numero, complemento, bairro, cidade, estado }
    });

    const usuario = await Usuario.buscarPorId(usuarioId);

    req.session.user = {
      id: usuario.id,
      nome: usuario.nome,
      email: usuario.email,
      isAdmin: usuario.is_admin
    };

    res.redirect('/checkout');
  } catch (error) {
    console.error('Erro ao cadastrar:', error);
    res.render('site/cadastro', {
      title: 'Cadastre-se',
      error: 'Erro ao cadastrar. Tente novamente.'
    });
  }
});

// Login
router.get('/login', (req, res) => {
  res.render('site/login', { title: 'Login', error: null });
});

// Termos e Política
router.get('/termos', (req, res) => {
  res.render('site/termos', { title: 'Termos de Uso' });
});

router.get('/privacidade', (req, res) => {
  res.render('site/privacidade', { title: 'Política de Privacidade' });
});

router.post('/login', async (req, res) => {
  try {
    const { email, senha } = req.body;
    const usuario = await Usuario.buscarPorEmail(email);

    if (!usuario || !(await Usuario.verificarSenha(senha, usuario.senha))) {
      return res.render('site/login', {
        title: 'Login',
        error: 'Email ou senha inválidos'
      });
    }

    req.session.user = {
      id: usuario.id,
      nome: usuario.nome,
      email: usuario.email,
      isAdmin: usuario.is_admin
    };

    res.redirect('/checkout');
  } catch (error) {
    console.error('Erro no login:', error);
    res.render('site/login', {
      title: 'Login',
      error: 'Erro ao fazer login'
    });
  }
});

// Logout
router.get('/logout', (req, res) => {
  req.session.destroy();
  res.redirect('/');
});

// Adicionar ao carrinho
router.post('/carrinho/adicionar', async (req, res) => {
  try {
    const { produtoId } = req.body;
    const produto = await Produto.buscarPorId(produtoId);

    if (!produto) {
      return res.status(404).json({ error: 'Produto não encontrado' });
    }

    if (!req.session.cart) {
      req.session.cart = [];
    }

    const precoFinal = parseFloat(produto.preco_promocional || produto.preco);

    const itemExistente = req.session.cart.find(item => item.produtoId == produtoId);

    if (itemExistente) {
      itemExistente.quantidade += 1;
    } else {
      req.session.cart.push({
        produtoId: produto.id,
        nome: produto.nome,
        preco: precoFinal,
        capsulas: produto.capsulas,
        quantidade: 1
      });
    }

    const calculo = await recalcularCarrinho(req.session.cart);

    res.json({ success: true, cart: calculo.cart, total: calculo.total });
  } catch (error) {
    console.error('Erro ao adicionar ao carrinho:', error);
    res.status(500).json({ error: 'Erro ao adicionar ao carrinho' });
  }
});

// Ver carrinho
router.get('/carrinho', async (req, res) => {
  const cart = req.session.cart || [];
  const calculo = await recalcularCarrinho(cart);

  res.render('site/carrinho', {
    title: 'Carrinho',
    cart: calculo.cart,
    total: calculo.total
  });
});

// Atualizar quantidade no carrinho
router.post('/carrinho/atualizar', async (req, res) => {
  try {
    const { produtoId, quantidade } = req.body;

    if (!req.session.cart || req.session.cart.length === 0) {
      return res.status(400).json({ error: 'Carrinho vazio', cart: [], total: 0 });
    }

    const itemIndex = req.session.cart.findIndex(item => item.produtoId == produtoId);

    if (itemIndex === -1) {
      return res.status(404).json({ error: 'Item n�o encontrado no carrinho', cart: req.session.cart, total: 0 });
    }

    const quantidadeNova = parseInt(quantidade, 10);

    if (Number.isNaN(quantidadeNova)) {
      return res.status(400).json({ error: 'Quantidade inv�lida', cart: req.session.cart, total: 0 });
    }

    if (quantidadeNova <= 0) {
      req.session.cart.splice(itemIndex, 1);
    } else {
      req.session.cart[itemIndex].quantidade = quantidadeNova;
    }

    const calculo = await recalcularCarrinho(req.session.cart);

    req.session.save((err) => {
      if (err) {
        console.error('Erro ao salvar sess�o:', err);
        return res.status(500).json({ error: 'Erro ao salvar carrinho', cart: [], total: 0 });
      }

      res.json({
        success: true,
        cart: calculo.cart,
        total: calculo.total
      });
    });
  } catch (error) {
    console.error('Erro ao atualizar carrinho:', error);
    res.status(500).json({ error: 'Erro ao atualizar carrinho', cart: [], total: 0 });
  }
});

// Remover item do carrinho
router.post('/carrinho/remover', async (req, res) => {
  try {
    const { produtoId } = req.body;

    if (!req.session.cart || req.session.cart.length === 0) {
      return res.status(400).json({ error: 'Carrinho vazio', cart: [], total: 0 });
    }

    const lengthBefore = req.session.cart.length;
    req.session.cart = req.session.cart.filter(item => item.produtoId != produtoId);

    if (req.session.cart.length === lengthBefore) {
      return res.status(404).json({ error: 'Item n�o encontrado', cart: req.session.cart, total: 0 });
    }

    const calculo = await recalcularCarrinho(req.session.cart);

    req.session.save((err) => {
      if (err) {
        console.error('Erro ao salvar sess�o:', err);
        return res.status(500).json({ error: 'Erro ao salvar carrinho', cart: [], total: 0 });
      }

      res.json({
        success: true,
        cart: calculo.cart,
        total: calculo.total
      });
    });
  } catch (error) {
    console.error('Erro ao remover item:', error);
    res.status(500).json({ error: 'Erro ao remover item', cart: [], total: 0 });
  }
});

// Checkout
router.get('/checkout', isAuthenticated, async (req, res) => {
  try {
    const cart = req.session.cart || [];
    const calculo = await recalcularCarrinho(cart);
    if (cart.length === 0) {
      return res.redirect('/');
    }

    const usuario = await Usuario.buscarPorId(req.session.user.id);

    // Formatar endereco
    usuario.endereco = {
      cep: usuario.cep,
      rua: usuario.rua,
      numero: usuario.numero,
      complemento: usuario.complemento,
      bairro: usuario.bairro,
      cidade: usuario.cidade,
      estado: usuario.estado
    };

    const total = calculo.total;

    res.render('site/checkout', {
      title: 'Finalizar Compra',
      cart: calculo.cart,
      total,
      usuario
    });
  } catch (error) {
    console.error('Erro ao carregar checkout:', error);
    res.status(500).send('Erro ao carregar checkout');
  }
});

// Processar pagamento
router.post('/checkout/processar', isAuthenticated, async (req, res) => {
  try {
    const { cep, rua, numero, complemento, bairro, cidade, estado, valorFrete } = req.body;
    const cart = req.session.cart || [];
    const calculo = await recalcularCarrinho(cart);

    if (cart.length === 0) {
      return res.redirect('/');
    }

    const valorProdutos = calculo.total;
    const valorTotal = valorProdutos + parseFloat(valorFrete);

    const resultado = await Pedido.criar({
      usuario_id: req.session.user.id,
      itens: cart.map(item => ({
        produtoId: item.produtoId,
        nome: item.nome,
        quantidade: item.quantidade,
        preco: item.preco,
        subtotal: item.preco * item.quantidade
      })),
      endereco: { cep, rua, numero, complemento, bairro, cidade, estado },
      valorProdutos,
      valorFrete: parseFloat(valorFrete),
      valorTotal,
      status: 'pendente'
    });

    // Limpar carrinho
    req.session.cart = [];

    // Redirecionar para pagamento Mercado Pago
    res.redirect(`/pagamento/${resultado.id}`);
  } catch (error) {
    console.error('Erro ao processar checkout:', error);
    res.status(500).send('Erro ao processar pedido');
  }
});

module.exports = router;
