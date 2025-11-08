const Produto = require('./models/Produto');

(async () => {
  const pacotes = await Produto.buscarPacotes();
  const tabela = {};
  pacotes.forEach(p => {
    tabela[Number(p.capsulas)] = parseFloat(p.preco_promocional || p.preco);
  });
  console.log('Tabela', tabela);
})();
