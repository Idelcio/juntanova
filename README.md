# Junta Nova - E-commerce

Sistema completo de e-commerce para venda do produto Junta Nova (Composto Natural para Articulações).

## 🚀 Funcionalidades

### Site Público
- ✅ Página inicial moderna com design responsivo
- ✅ Página de produto com depoimentos de clientes
- ✅ Sistema de carrinho de compras
- ✅ Cadastro e login de usuários
- ✅ Checkout com cálculo de frete automático
- ✅ Integração com Mercado Pago para pagamentos
- ✅ Exibição de compras recentes por cidade (incentivo social)
- ✅ Design baseado nas cores do produto (azul, amarelo, branco)

### Painel Administrativo
- ✅ Dashboard com estatísticas de vendas
- ✅ Gráficos de vendas por estado e cidade
- ✅ Controle de estoque
- ✅ Gerenciamento de pedidos
- ✅ Gerenciamento de depoimentos
- ✅ Sistema de autenticação para admin

### Integrações
- ✅ **ViaCEP**: Busca automática de endereço por CEP
- ✅ **Mercado Pago**: Processamento de pagamentos
- ✅ **Nodemailer**: Envio de emails de confirmação de pedido

## 📋 Pré-requisitos

- Node.js (v14 ou superior)
- MongoDB (local ou MongoDB Atlas)
- Conta no Mercado Pago (para pagamentos)
- Conta de email (Gmail recomendado para envio de emails)

## 🔧 Instalação

1. **Clone ou baixe o projeto**

2. **Instale as dependências**
```bash
npm install
```

3. **Configure as variáveis de ambiente**

Copie o arquivo `.env.example` para `.env`:
```bash
copy .env.example .env
```

Edite o arquivo `.env` com suas configurações:

```env
# Configurações do Servidor
PORT=3000
NODE_ENV=development

# MongoDB - Use uma das opções abaixo:
# Local:
MONGODB_URI=mongodb://localhost:27017/juntanova
# Ou MongoDB Atlas (recomendado):
# MONGODB_URI=mongodb+srv://usuario:senha@cluster.mongodb.net/juntanova

# Session Secret (gere uma chave aleatória segura)
SESSION_SECRET=sua_chave_secreta_aqui

# Email Configuration (Gmail exemplo)
EMAIL_HOST=smtp.gmail.com
EMAIL_PORT=587
EMAIL_USER=seu_email@gmail.com
EMAIL_PASS=sua_senha_de_app_do_gmail
EMAIL_FROM=noreply@juntanova.com
ADMIN_EMAIL=idelcioforest@gmail.com

# Mercado Pago (obtenha em https://www.mercadopago.com.br/developers)
MP_ACCESS_TOKEN=seu_access_token_do_mercado_pago

# Admin inicial
ADMIN_USERNAME=admin@juntanova.com
ADMIN_PASSWORD=admin123
```

4. **Inicialize o banco de dados**
```bash
node scripts/init-db.js
```

Este comando irá:
- Criar o usuário admin
- Criar o produto Junta Nova
- Criar depoimentos de exemplo

5. **Coloque as imagens do produto**

Copie as 4 imagens que você forneceu para a pasta `public/images/` com os nomes:
- `produto1.jpg`
- `produto2.jpg`
- `produto3.jpg`
- `produto4.jpg`

## 🎯 Como Usar

### Iniciar o servidor

```bash
npm start
```

Ou para desenvolvimento com auto-reload:
```bash
npm run dev
```

O site estará disponível em: `http://localhost:3000`

### Acessar o painel administrativo

1. Acesse: `http://localhost:3000/admin/login`
2. Use as credenciais configuradas no `.env`:
   - Email: admin@juntanova.com (ou o que você configurou)
   - Senha: admin123 (ou a que você configurou)

## 📱 Estrutura do Projeto

```
juntanova/
├── models/              # Modelos do MongoDB
│   ├── Usuario.js
│   ├── Produto.js
│   ├── Pedido.js
│   └── Depoimento.js
├── routes/              # Rotas da aplicação
│   ├── site.js         # Rotas públicas
│   ├── admin.js        # Rotas do admin
│   └── api.js          # APIs (frete, pagamento, etc)
├── views/               # Templates EJS
│   ├── site/           # Páginas públicas
│   └── admin/          # Páginas admin
├── public/              # Arquivos estáticos
│   ├── css/
│   ├── js/
│   └── images/
├── middleware/          # Middlewares
│   └── auth.js
├── scripts/             # Scripts utilitários
│   └── init-db.js
├── server.js            # Servidor principal
└── package.json
```

## 🎨 Funcionalidades Detalhadas

### Sistema de Pedidos
1. Cliente adiciona produtos ao carrinho
2. Faz login ou cadastro
3. Informa endereço de entrega
4. Sistema calcula frete automaticamente baseado no estado
5. Cliente finaliza compra
6. Redirecionado para pagamento Mercado Pago
7. Após pagamento aprovado:
   - Estoque é atualizado automaticamente
   - Email é enviado para o admin (idelcioforest@gmail.com)
   - Pedido aparece no painel admin

### Dashboard Admin
- **Estatísticas**: Total de pedidos, pendentes, pagos, estoque
- **Gráficos**:
  - Vendas por estado (gráfico de barras)
  - Top 10 cidades (gráfico horizontal)
- **Pedidos recentes**: Lista com todos os detalhes
- **Controle de estoque**: Atualização de quantidade e preços
- **Gerenciamento de pedidos**: Alteração de status
- **Depoimentos**: Aprovar/desaprovar depoimentos

### Cálculo de Frete
Baseado na região do Brasil:
- **Sudeste** (SP, RJ, MG, ES): R$ 15,00
- **Sul** (RS, SC, PR): R$ 18,00
- **Centro-Oeste** (GO, MT, MS, DF): R$ 22,00
- **Nordeste**: R$ 25,00
- **Norte**: R$ 30,00

## 🔐 Segurança

- Senhas criptografadas com bcrypt
- Sessões seguras com express-session
- Middleware de autenticação para rotas admin
- Proteção contra SQL injection (uso de Mongoose)

## 📧 Configuração de Email (Gmail)

Para usar o Gmail para enviar emails:

1. Acesse sua conta Google
2. Vá em "Segurança"
3. Ative a "Verificação em duas etapas"
4. Gere uma "Senha de app"
5. Use essa senha no `.env` em `EMAIL_PASS`

## 💳 Configuração Mercado Pago

1. Crie uma conta em https://www.mercadopago.com.br
2. Acesse https://www.mercadopago.com.br/developers
3. Crie uma aplicação
4. Copie o "Access Token" de produção ou teste
5. Cole no `.env` em `MP_ACCESS_TOKEN`

## 🗄️ MongoDB

### Opção 1: MongoDB Local
1. Instale o MongoDB: https://www.mongodb.com/try/download/community
2. Inicie o serviço MongoDB
3. Use: `MONGODB_URI=mongodb://localhost:27017/juntanova`

### Opção 2: MongoDB Atlas (Recomendado - Grátis)
1. Crie uma conta em https://www.mongodb.com/cloud/atlas
2. Crie um cluster gratuito
3. Crie um usuário de banco de dados
4. Permita acesso de qualquer IP (0.0.0.0/0)
5. Copie a connection string
6. Use no `.env`: `MONGODB_URI=mongodb+srv://usuario:senha@cluster.mongodb.net/juntanova`

## 🎯 Recursos do Site

### Design Responsivo
- Mobile-first
- Compatível com tablets e desktops
- Cores baseadas no produto (azul #1565C0, amarelo #FDD835)

### UX/UI
- Navegação intuitiva
- Feedback visual em todas as ações
- Loading states
- Mensagens de erro claras

### SEO Friendly
- Meta tags apropriadas
- URLs semânticas
- Estrutura HTML5

## 📝 Licença

MIT

## 👨‍💻 Suporte

Para dúvidas ou problemas:
- Email: idelcioforest@gmail.com

## 🚀 Próximos Passos (Melhorias Futuras)

- [ ] Sistema de cupons de desconto
- [ ] Programa de afiliados
- [ ] Chat online para suporte
- [ ] Sistema de avaliações com fotos
- [ ] Rastreamento de pedidos
- [ ] Relatórios em PDF
- [ ] API REST para integrações
- [ ] PWA (Progressive Web App)
