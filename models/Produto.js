const { pool } = require('../config/database');

const PACOTES_PADRAO = [
  {
    nome: 'Junta Nova',
    descricao: 'Tratamento completo para articulacoes saudaveis (1 pote).',
    preco: 197.0,
    precoPromocional: 139.0,
    estoque: 100,
    peso: '45g',
    capsulas: 30,
    dosagem: '500mg cada',
    tipo: '100% Natural'
  },
  {
    nome: 'Junta Nova - 3 Potes',
    descricao: 'Tratamento de 3 meses (90 capsulas) com desconto especial.',
    preco: 591.0,
    precoPromocional: 357.0,
    estoque: 80,
    peso: '135g',
    capsulas: 90,
    dosagem: '500mg cada',
    tipo: 'Combo Economico'
  },
  {
    nome: 'Junta Nova - 5 Potes',
    descricao: 'Tratamento intensivo de 5 meses (150 capsulas) com melhor oferta.',
    preco: 985.0,
    precoPromocional: 495.0,
    estoque: 60,
    peso: '225g',
    capsulas: 150,
    dosagem: '500mg cada',
    tipo: 'Combo Premium'
  }
];

class Produto {
  // Buscar todos os pacotes ativos
  static async buscarPacotes() {
    await this.garantirPacotesPadrao();
    const [rows] = await pool.execute(
      'SELECT * FROM produtos WHERE ativo = TRUE ORDER BY id'
    );

    rows.forEach(row => {
      row.preco = parseFloat(row.preco);
      row.preco_promocional = row.preco_promocional ? parseFloat(row.preco_promocional) : null;
      row.precoPromocional = row.preco_promocional;
    });

    return rows;
  }

  // Buscar produto principal (pacote de 1 unidade)
  static async buscarPrincipal() {
    await this.garantirPacotesPadrao();
    const [rows] = await pool.execute(
      'SELECT * FROM produtos WHERE ativo = TRUE ORDER BY id ASC LIMIT 1'
    );

    if (rows[0]) {
      // Converter decimais para números
      rows[0].preco = parseFloat(rows[0].preco);
      rows[0].preco_promocional = rows[0].preco_promocional ? parseFloat(rows[0].preco_promocional) : null;
      rows[0].precoPromocional = rows[0].preco_promocional;

      // Buscar imagens
      const [imagens] = await pool.execute(
        'SELECT caminho FROM produto_imagens WHERE produto_id = ? ORDER BY ordem',
        [rows[0].id]
      );
      rows[0].imagens = imagens.map(img => img.caminho);

      // Buscar benefícios
      const [beneficios] = await pool.execute(
        'SELECT beneficio FROM produto_beneficios WHERE produto_id = ?',
        [rows[0].id]
      );
      rows[0].beneficios = beneficios.map(b => b.beneficio);

      // Converter para formato esperado
      rows[0].especificacoes = {
        peso: rows[0].peso,
        capsulas: rows[0].capsulas,
        dosagem: rows[0].dosagem,
        tipo: rows[0].tipo
      };
    }

    return rows[0];
  }

  // Atualizar estoque e preços
  static async atualizar(id, dados) {
    const { estoque, preco, precoPromocional } = dados;

    await pool.execute(
      `UPDATE produtos
       SET estoque = ?, preco = ?, preco_promocional = ?
       WHERE id = ?`,
      [estoque, preco, precoPromocional || null, id]
    );
  }

  static async atualizarPrecos(id, dados) {
    const { preco, precoPromocional } = dados;

    await pool.execute(
      `UPDATE produtos
       SET preco = ?, preco_promocional = ?
       WHERE id = ?`,
      [preco, precoPromocional || null, id]
    );
  }

  // Decrementar estoque
  static async decrementarEstoque(id, quantidade) {
    await pool.execute(
      'UPDATE produtos SET estoque = estoque - ? WHERE id = ?',
      [quantidade, id]
    );
  }

  // Buscar por ID
  static async buscarPorId(id) {
    const [rows] = await pool.execute(
      'SELECT * FROM produtos WHERE id = ?',
      [id]
    );

    if (rows[0]) {
      // Converter decimais para números
      rows[0].preco = parseFloat(rows[0].preco);
      rows[0].preco_promocional = rows[0].preco_promocional ? parseFloat(rows[0].preco_promocional) : null;
      rows[0].precoPromocional = rows[0].preco_promocional;
    }

    return rows[0];
  }

  static async garantirPacotesPadrao() {
    for (const pacote of PACOTES_PADRAO) {
      const [porNome] = await pool.execute(
        'SELECT id FROM produtos WHERE nome = ? AND ativo = TRUE LIMIT 1',
        [pacote.nome]
      );

      if (porNome.length > 0) {
        continue;
      }

      const [porCapsulas] = await pool.execute(
        'SELECT id FROM produtos WHERE capsulas = ? AND ativo = TRUE LIMIT 1',
        [pacote.capsulas]
      );

      if (porCapsulas.length === 0) {
        await pool.execute(
          `INSERT INTO produtos
            (nome, descricao, preco, preco_promocional, estoque, peso, capsulas, dosagem, tipo, ativo)
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE)`,
          [
            pacote.nome,
            pacote.descricao,
            pacote.preco,
            pacote.precoPromocional,
            pacote.estoque,
            pacote.peso,
            pacote.capsulas,
            pacote.dosagem,
            pacote.tipo
          ]
        );
      }
    }
  }
}

module.exports = Produto;
