const { pool } = require('../config/database');

class Pedido {
  // Criar novo pedido
  static async criar(dados) {
    const connection = await pool.getConnection();

    try {
      await connection.beginTransaction();

      // Gerar número do pedido
      const [countResult] = await connection.execute('SELECT COUNT(*) as total FROM pedidos');
      const numeroPedido = `JN${String(countResult[0].total + 1).padStart(6, '0')}`;

      // Inserir pedido
      const [result] = await connection.execute(
        `INSERT INTO pedidos (numero_pedido, usuario_id, cep, rua, numero, complemento, bairro, cidade, estado, pais,
         valor_produtos, valor_frete, valor_total, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
        [
          numeroPedido,
          dados.usuario_id,
          dados.endereco.cep,
          dados.endereco.rua,
          dados.endereco.numero,
          dados.endereco.complemento,
          dados.endereco.bairro,
          dados.endereco.cidade,
          dados.endereco.estado,
          dados.endereco.pais || 'Brasil',
          dados.valorProdutos,
          dados.valorFrete,
          dados.valorTotal,
          dados.status || 'pendente'
        ]
      );

      const pedidoId = result.insertId;

      // Inserir itens do pedido
      for (const item of dados.itens) {
        await connection.execute(
          `INSERT INTO pedido_itens (pedido_id, produto_id, nome, quantidade, preco, subtotal)
           VALUES (?, ?, ?, ?, ?, ?)`,
          [pedidoId, item.produtoId, item.nome, item.quantidade, item.preco, item.subtotal]
        );
      }

      await connection.commit();

      return { id: pedidoId, numeroPedido };
    } catch (error) {
      await connection.rollback();
      throw error;
    } finally {
      connection.release();
    }
  }

  // Buscar por ID
  static async buscarPorId(id) {
    const [rows] = await pool.execute(
      `SELECT p.*, u.nome as usuario_nome, u.email as usuario_email, u.telefone as usuario_telefone
       FROM pedidos p
       JOIN usuarios u ON p.usuario_id = u.id
       WHERE p.id = ?`,
      [id]
    );

    if (rows[0]) {
      // Buscar itens
      const [itens] = await pool.execute(
        'SELECT * FROM pedido_itens WHERE pedido_id = ?',
        [id]
      );
      rows[0].itens = itens;

      // Formatar usuario
      rows[0].usuario = {
        id: rows[0].usuario_id,
        nome: rows[0].usuario_nome,
        email: rows[0].usuario_email,
        telefone: rows[0].usuario_telefone
      };

      // Formatar endereco
      rows[0].endereco = {
        cep: rows[0].cep,
        rua: rows[0].rua,
        numero: rows[0].numero,
        complemento: rows[0].complemento,
        bairro: rows[0].bairro,
        cidade: rows[0].cidade,
        estado: rows[0].estado,
        pais: rows[0].pais
      };
    }

    return rows[0];
  }

  // Buscar por número
  static async buscarPorNumero(numeroPedido) {
    const [rows] = await pool.execute(
      'SELECT * FROM pedidos WHERE numero_pedido = ?',
      [numeroPedido]
    );
    return rows[0];
  }

  // Listar todos
  static async listarTodos() {
    const [rows] = await pool.execute(
      `SELECT p.*, u.nome as usuario_nome, u.email as usuario_email
       FROM pedidos p
       JOIN usuarios u ON p.usuario_id = u.id
       ORDER BY p.data_pedido DESC`
    );

    // Formatar cada pedido
    for (let pedido of rows) {
      const [itens] = await pool.execute(
        'SELECT * FROM pedido_itens WHERE pedido_id = ?',
        [pedido.id]
      );
      pedido.itens = itens;
      pedido.usuario = {
        nome: pedido.usuario_nome,
        email: pedido.usuario_email
      };
    }

    return rows;
  }

  // Contar pedidos
  static async contar(filtro = {}) {
    let sql = 'SELECT COUNT(*) as total FROM pedidos';
    let params = [];

    if (filtro.status) {
      sql += ' WHERE status = ?';
      params.push(filtro.status);
    }

    const [rows] = await pool.execute(sql, params);
    return rows[0].total;
  }

  // Atualizar status
  static async atualizarStatus(id, status) {
    await pool.execute(
      'UPDATE pedidos SET status = ? WHERE id = ?',
      [status, id]
    );
  }

  // Atualizar pagamento
  static async atualizarPagamento(id, dados) {
    await pool.execute(
      `UPDATE pedidos
       SET mercadopago_id = ?, mercadopago_status = ?, status = ?, data_aprovacao = ?
       WHERE id = ?`,
      [dados.mercadoPagoId, dados.mercadoPagoStatus, dados.status, dados.dataAprovacao || null, id]
    );
  }

  // Pedidos recentes
  static async pedidosRecentes(limit = 10) {
    const [rows] = await pool.execute(
      `SELECT p.*, u.nome as usuario_nome
       FROM pedidos p
       JOIN usuarios u ON p.usuario_id = u.id
       ORDER BY p.data_pedido DESC
       LIMIT ?`,
      [limit]
    );

    for (let pedido of rows) {
      pedido.usuario = { nome: pedido.usuario_nome };
      pedido.endereco = {
        cidade: pedido.cidade,
        estado: pedido.estado
      };
    }

    return rows;
  }

  // Compras recentes para página inicial
  static async comprasRecentes(limit = 5) {
    const [rows] = await pool.execute(
      `SELECT cidade, estado, data_pedido
       FROM pedidos
       WHERE status IN ('pago', 'processando', 'enviado', 'entregue')
       ORDER BY data_pedido DESC
       LIMIT ?`,
      [limit]
    );
    return rows;
  }

  // Vendas por estado
  static async vendasPorEstado() {
    const [rows] = await pool.execute(
      `SELECT estado as _id, COUNT(*) as total, SUM(valor_total) as valor
       FROM pedidos
       WHERE status IN ('pago', 'processando', 'enviado', 'entregue')
       GROUP BY estado
       ORDER BY total DESC`
    );
    return rows;
  }

  // Vendas por cidade (top 10)
  static async vendasPorCidade(limit = 10) {
    const [rows] = await pool.execute(
      `SELECT cidade, estado, COUNT(*) as total, SUM(valor_total) as valor
       FROM pedidos
       WHERE status IN ('pago', 'processando', 'enviado', 'entregue')
       GROUP BY cidade, estado
       ORDER BY total DESC
       LIMIT ?`,
      [limit]
    );

    // Formatar para o formato esperado
    return rows.map(row => ({
      _id: { cidade: row.cidade, estado: row.estado },
      total: row.total,
      valor: row.valor
    }));
  }
}

module.exports = Pedido;
