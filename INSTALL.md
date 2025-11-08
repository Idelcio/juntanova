# 🚀 Guia de Instalação Rápida - Junta Nova

## Passo 1: Instalar Dependências

Abra o terminal na pasta do projeto e execute:

```bash
npm install
```

## Passo 2: Configurar MongoDB

### Opção A: MongoDB Atlas (Recomendado - Grátis)

1. Acesse https://www.mongodb.com/cloud/atlas
2. Crie uma conta grátis
3. Crie um cluster grátis (M0)
4. Clique em "Connect"
5. Crie um usuário de banco de dados
6. Permita acesso de qualquer IP (0.0.0.0/0)
7. Copie a connection string
8. Cole no arquivo `.env` na variável `MONGODB_URI`

Exemplo:
```
MONGODB_URI=mongodb+srv://usuario:senha@cluster0.xxxxx.mongodb.net/juntanova
```

### Opção B: MongoDB Local

1. Baixe e instale: https://www.mongodb.com/try/download/community
2. Inicie o serviço MongoDB
3. No `.env`, use: `MONGODB_URI=mongodb://localhost:27017/juntanova`

## Passo 3: Configurar Email

### Para Gmail:

1. Acesse sua conta Google
2. Vá em "Segurança" → "Verificação em duas etapas" (ative se não estiver)
3. Em "Senhas de app", gere uma nova senha
4. No arquivo `.env`, configure:

```env
EMAIL_USER=seu_email@gmail.com
EMAIL_PASS=senha_de_app_gerada_aqui
```

## Passo 4: Configurar Mercado Pago (Opcional - para testes pode pular)

1. Crie conta em https://www.mercadopago.com.br
2. Acesse https://www.mercadopago.com.br/developers
3. Vá em "Suas integrações" → "Criar aplicação"
4. Copie o "Access Token" (use o de TESTE primeiro)
5. Cole no `.env`:

```env
MP_ACCESS_TOKEN=TEST-xxxxxxxxxx
```

## Passo 5: Adicionar Imagens do Produto

1. Crie a pasta `public/images` se não existir
2. Copie as 4 imagens do produto para lá com os nomes:
   - produto1.jpg
   - produto2.jpg
   - produto3.jpg
   - produto4.jpg

**Se não tiver as imagens agora**, o site funcionará mas mostrará imagens quebradas.

## Passo 6: Inicializar o Banco de Dados

Execute o script de inicialização:

```bash
node scripts/init-db.js
```

Isso criará:
- Usuário admin
- Produto Junta Nova
- Depoimentos de exemplo

**Anote as credenciais de admin que aparecerem!**

## Passo 7: Iniciar o Servidor

```bash
npm start
```

Ou para desenvolvimento:

```bash
npm run dev
```

## Passo 8: Acessar o Sistema

### Site Público
```
http://localhost:3000
```

### Painel Admin
```
http://localhost:3000/admin/login

Email: admin@juntanova.com
Senha: admin123
```

## ✅ Checklist de Configuração

- [ ] Dependências instaladas (`npm install`)
- [ ] MongoDB configurado e rodando
- [ ] Arquivo `.env` configurado com MongoDB
- [ ] Email configurado no `.env`
- [ ] Imagens colocadas em `public/images/`
- [ ] Banco inicializado (`node scripts/init-db.js`)
- [ ] Servidor iniciado (`npm start`)

## 🆘 Problemas Comuns

### Erro: "Cannot connect to MongoDB"
- Verifique se o MongoDB está rodando
- Confira a string de conexão no `.env`
- No Atlas, verifique se liberou o IP 0.0.0.0/0

### Erro ao enviar email
- Verifique se gerou a senha de app do Gmail
- Confira o email e senha no `.env`
- **Para testes iniciais**: pode deixar sem configurar, o site funcionará, só não enviará emails

### Erro no Mercado Pago
- **Para testes iniciais**: pode deixar com um token falso
- O site funcionará, mas o pagamento não será processado
- Configure depois para produção

### Imagens não aparecem
- Verifique se as imagens estão em `public/images/`
- Verifique os nomes dos arquivos (produto1.jpg, produto2.jpg, etc)

## 🎯 Após Instalação

1. Acesse o painel admin
2. Vá em "Estoque" e configure a quantidade disponível
3. Teste fazer um pedido como cliente
4. Verifique se recebeu o email de confirmação

## 📞 Suporte

Se tiver problemas, entre em contato: idelcioforest@gmail.com
