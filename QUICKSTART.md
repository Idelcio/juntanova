# ⚡ Início Rápido - Junta Nova

## 🎯 Para começar AGORA (5 minutos)

### 1. Instalar dependências
```bash
npm install
```

### 2. Configurar MongoDB (escolha uma opção)

**Opção Rápida - MongoDB Atlas (grátis):**
1. Vá em https://www.mongodb.com/cloud/atlas/register
2. Crie uma conta
3. Crie um cluster grátis
4. Clique em "Connect" → "Connect your application"
5. Copie a string de conexão
6. Cole no arquivo `.env` em `MONGODB_URI`

**Opção Local:**
```env
MONGODB_URI=mongodb://localhost:27017/juntanova
```

### 3. Editar arquivo .env (mínimo necessário)

Abra o arquivo `.env` e configure APENAS isso para começar:

```env
MONGODB_URI=sua_string_de_conexao_aqui
```

**Nota:** Email e Mercado Pago podem ser configurados depois. O site funcionará sem eles para testes.

### 4. Inicializar banco de dados
```bash
npm run init-db
```

### 5. Adicionar imagens (OPCIONAL para teste)

Copie as 4 imagens do produto para `public/images/` com os nomes:
- produto1.jpg
- produto2.jpg
- produto3.jpg
- produto4.jpg

**Pode pular esse passo para teste inicial** - o site funcionará com imagens quebradas.

### 6. Iniciar servidor
```bash
npm start
```

### 7. Abrir no navegador

- **Site:** http://localhost:3000
- **Admin:** http://localhost:3000/admin/login
  - Email: admin@juntanova.com
  - Senha: admin123

## ✅ Pronto!

Agora você pode:
1. Navegar pelo site
2. Fazer um pedido de teste
3. Acessar o painel admin
4. Ver gráficos e estatísticas

## 📝 Configurações Completas (fazer depois)

Para produção, configure também:

### Email (para receber notificações de vendas)
```env
EMAIL_USER=seu_email@gmail.com
EMAIL_PASS=senha_de_app_do_gmail
ADMIN_EMAIL=idelcioforest@gmail.com
```

### Mercado Pago (para pagamentos reais)
```env
MP_ACCESS_TOKEN=seu_token_do_mercado_pago
```

Veja [INSTALL.md](INSTALL.md) para instruções detalhadas.

## 🆘 Problemas?

**Erro ao conectar MongoDB?**
- Verifique a string de conexão no `.env`
- No MongoDB Atlas, libere acesso para todos IPs (0.0.0.0/0)

**Site não abre?**
- Verifique se a porta 3000 está livre
- Tente trocar a porta no `.env`: `PORT=3001`

**Mais ajuda:** idelcioforest@gmail.com
