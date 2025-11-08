const { pool } = require('../config/database');

class Depoimento {
  // Criar depoimento
  static async criar(dados) {
    const { nome, cidade, estado, depoimento, estrelas, aprovado, destaque } = dados;

    const [result] = await pool.execute(
      `INSERT INTO depoimentos (nome, cidade, estado, depoimento, estrelas, aprovado, destaque)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [nome, cidade, estado, depoimento, estrelas || 5, aprovado !== false, destaque || false]
    );

    return result.insertId;
  }

  // Listar todos
  static async listarTodos() {
    const [rows] = await pool.execute(
      'SELECT * FROM depoimentos ORDER BY data_criacao DESC'
    );
    return rows;
  }

  // Buscar aprovados
  static async buscarAprovados(limit = null) {
    let sql = 'SELECT * FROM depoimentos WHERE aprovado = TRUE ORDER BY destaque DESC, data_criacao DESC';

    if (limit) {
      sql += ' LIMIT ?';
      const [rows] = await pool.execute(sql, [limit]);
      return rows;
    }

    const [rows] = await pool.execute(sql);
    return rows;
  }

  // Buscar por ID
  static async buscarPorId(id) {
    const [rows] = await pool.execute(
      'SELECT * FROM depoimentos WHERE id = ?',
      [id]
    );
    return rows[0];
  }

  // Alternar aprovação
  static async alternarAprovacao(id) {
    await pool.execute(
      'UPDATE depoimentos SET aprovado = NOT aprovado WHERE id = ?',
      [id]
    );
  }

  // Contar
  static async contar() {
    const [rows] = await pool.execute('SELECT COUNT(*) as total FROM depoimentos');
    return rows[0].total;
  }
}

module.exports = Depoimento;
