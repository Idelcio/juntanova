const mysql = require('mysql2/promise');

// Pool de conexões
const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'juntanova',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// Testar conexão
async function testConnection() {
  try {
    const connection = await pool.getConnection();
    console.log('✅ MySQL conectado com sucesso');
    connection.release();
    return true;
  } catch (error) {
    console.error('❌ Erro ao conectar MySQL:', error.message);
    return false;
  }
}

module.exports = { pool, testConnection };
