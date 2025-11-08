require('dotenv').config();
const express = require('express');
const session = require('express-session');
const MySQLStore = require('express-mysql-session')(session);
const { pool, testConnection } = require('./config/database');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

// Testar conexão MySQL
testConnection();

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static('public'));

// Configurar sessão com MySQL
const sessionStore = new MySQLStore({
  clearExpired: true,
  checkExpirationInterval: 900000, // 15 minutos
  expiration: 86400000, // 24 horas
  createDatabaseTable: true,
  schema: {
    tableName: 'sessions',
    columnNames: {
      session_id: 'session_id',
      expires: 'expires',
      data: 'data'
    }
  }
}, pool);

app.use(session({
  secret: process.env.SESSION_SECRET || 'juntanova-secret-key',
  resave: false,
  saveUninitialized: false,
  store: sessionStore,
  cookie: { maxAge: 1000 * 60 * 60 * 24 } // 24 horas
}));

// View engine
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Middleware para disponibilizar dados do usuário nas views
app.use((req, res, next) => {
  res.locals.user = req.session.user || null;
  res.locals.cart = req.session.cart || [];
  next();
});

// Rotas
app.use('/', require('./routes/site'));
app.use('/admin', require('./routes/admin'));
app.use('/api', require('./routes/api'));

// Rota 404
app.use((req, res) => {
  res.status(404).render('404', { title: 'Página não encontrada' });
});

// Inicializar servidor
app.listen(PORT, () => {
  console.log(`🚀 Servidor rodando em http://localhost:${PORT}`);
});
