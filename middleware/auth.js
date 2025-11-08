// Middleware para proteger rotas do admin
exports.isAdmin = (req, res, next) => {
  if (req.session.user && req.session.user.isAdmin) {
    return next();
  }
  res.redirect('/admin/login');
};

// Middleware para verificar se está logado
exports.isAuthenticated = (req, res, next) => {
  if (req.session.user) {
    return next();
  }
  res.redirect('/login');
};

// Middleware para redirecionar se já está logado
exports.redirectIfAuthenticated = (req, res, next) => {
  if (req.session.user) {
    if (req.session.user.isAdmin) {
      return res.redirect('/admin/dashboard');
    }
    return res.redirect('/');
  }
  next();
};
