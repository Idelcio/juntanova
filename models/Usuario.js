const { pool } = require('../config/database');
const bcrypt = require('bcryptjs');

class Usuario {
  // Criar novo usuário
  static async criar(dados) {
    const { nome, email, senha, telefone, cpf, endereco } = dados;

    // Hash da senha
    const senhaHash = await bcrypt.hash(senha, 10);

    const [result] = await pool.execute(
      `INSERT INTO usuarios (nome, email, senha, telefone, cpf, cep, rua, numero, complemento, bairro, cidade, estado, pais, is_admin)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        nome,
        email,
        senhaHash,
        telefone,
        cpf,
        endereco.cep,
        endereco.rua,
        endereco.numero,
        endereco.complemento || null,
        endereco.bairro,
        endereco.cidade,
        endereco.estado,
        endereco.pais || 'Brasil',
        dados.isAdmin || false
      ]
    );

    return result.insertId;
  }

  // Buscar por email
  static async buscarPorEmail(email) {
    const [rows] = await pool.execute(
      'SELECT * FROM usuarios WHERE email = ?',
      [email]
    );
    return rows[0];
  }

  // Buscar por ID
  static async buscarPorId(id) {
    const [rows] = await pool.execute(
      'SELECT * FROM usuarios WHERE id = ?',
      [id]
    );
    return rows[0];
  }

  // Verificar senha
  static async verificarSenha(senha, senhaHash) {
    return await bcrypt.compare(senha, senhaHash);
  }

  // Atualizar usuário
  static async atualizar(id, dados) {
    const { nome, telefone, endereco } = dados;

    await pool.execute(
      `UPDATE usuarios
       SET nome = ?, telefone = ?, cep = ?, rua = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, estado = ?
       WHERE id = ?`,
      [
        nome,
        telefone,
        endereco.cep,
        endereco.rua,
        endereco.numero,
        endereco.complemento,
        endereco.bairro,
        endereco.cidade,
        endereco.estado,
        id
      ]
    );
  }
}

module.exports = Usuario;
