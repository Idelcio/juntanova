# 🧪 Guia de Testes do Sistema

## ✅ Checklist de Testes

### 1. Instalação e Configuração

- [ ] Dependências instaladas com sucesso (`npm install`)
- [ ] MongoDB conectado (aparece "✅ MongoDB conectado com sucesso")
- [ ] Banco inicializado (`npm run init-db`)
- [ ] Servidor inicia sem erros (`npm start`)
- [ ] Site abre em http://localhost:3000

### 2. Testes da Página Inicial

**Acesse:** http://localhost:3000

- [ ] Página carrega corretamente
- [ ] Logo "Junta Nova" aparece
- [ ] Preço R$ 139,00 está visível
- [ ] Botão "Comprar Agora" funciona
- [ ] Benefícios são exibidos
- [ ] Depoimentos aparecem (se houver)
- [ ] Compras recentes aparecem (se houver pedidos)
- [ ] Footer aparece com informações
- [ ] Menu de navegação funciona

### 3. Testes da Página do Produto

**Acesse:** http://localhost:3000/produto

- [ ] Página do produto carrega
- [ ] Descrição do produto aparece
- [ ] Preço está correto
- [ ] Campo de quantidade funciona
- [ ] Botão "Adicionar ao Carrinho" funciona
- [ ] Especificações aparecem (30 cápsulas, etc)
- [ ] Depoimentos dos clientes aparecem
- [ ] Todos os depoimentos são exibidos

### 4. Testes do Carrinho

**Acesse:** http://localhost:3000/carrinho

**Sem produtos:**
- [ ] Mensagem "Carrinho vazio" aparece
- [ ] Botão para voltar às compras funciona

**Com produtos:**
1. Adicione um produto ao carrinho
- [ ] Produto aparece no carrinho
- [ ] Quantidade está correta
- [ ] Subtotal calculado corretamente
- [ ] Total aparece
- [ ] Contador do carrinho no menu atualiza

### 5. Testes de Cadastro

**Acesse:** http://localhost:3000/cadastro

- [ ] Formulário de cadastro aparece
- [ ] Todos os campos estão presentes
- [ ] Formatação automática funciona:
  - [ ] CPF: 000.000.000-00
  - [ ] CEP: 00000-000
  - [ ] Telefone: (00) 00000-0000
- [ ] Busca de CEP funciona (preenche endereço)
- [ ] Lista de estados funciona
- [ ] Validação de campos obrigatórios funciona
- [ ] Cadastro cria usuário com sucesso
- [ ] Redireciona para checkout após cadastro

### 6. Testes de Login

**Acesse:** http://localhost:3000/login

**Com credenciais corretas:**
- [ ] Login funciona
- [ ] Redireciona para página correta
- [ ] Menu mostra "Sair" em vez de "Login"

**Com credenciais incorretas:**
- [ ] Mostra mensagem de erro
- [ ] Não permite acesso

### 7. Testes de Checkout

**Acesse:** http://localhost:3000/checkout (precisa estar logado)

- [ ] Endereço do usuário aparece preenchido
- [ ] Pode editar endereço
- [ ] Resumo do pedido aparece
- [ ] Itens do carrinho aparecem
- [ ] Subtotal está correto
- [ ] Botão "Calcular Frete" funciona
- [ ] Frete é calculado corretamente
- [ ] Total (produtos + frete) está correto
- [ ] Botão "Finalizar" só habilita após calcular frete
- [ ] Finalização cria pedido no banco

### 8. Testes do Painel Admin

**Acesse:** http://localhost:3000/admin/login

**Credenciais:**
- Email: admin@juntanova.com
- Senha: admin123

**Login:**
- [ ] Página de login admin aparece
- [ ] Login com credenciais corretas funciona
- [ ] Login com credenciais erradas mostra erro
- [ ] Redireciona para dashboard após login

**Dashboard:**
- [ ] Estatísticas aparecem (pedidos, estoque)
- [ ] Gráfico de vendas por estado aparece
- [ ] Gráfico de top 10 cidades aparece
- [ ] Pedidos recentes aparecem em tabela
- [ ] Valores são exibidos corretamente

**Estoque:**
- [ ] Página de estoque carrega
- [ ] Informações atuais aparecem
- [ ] Pode alterar quantidade em estoque
- [ ] Pode alterar preço normal
- [ ] Pode alterar preço promocional
- [ ] Atualização salva corretamente

**Pedidos:**
- [ ] Lista de pedidos aparece
- [ ] Informações completas de cada pedido
- [ ] Endereço de entrega completo
- [ ] Dados do cliente (nome, email, telefone)
- [ ] Pode alterar status do pedido
- [ ] Status atualiza corretamente

**Depoimentos:**
- [ ] Lista de depoimentos aparece
- [ ] Pode aprovar depoimento
- [ ] Pode desaprovar depoimento
- [ ] Status muda corretamente

### 9. Testes de API

**CEP:**
```bash
curl http://localhost:3000/api/cep/01310100
```
- [ ] Retorna dados do endereço

**Calcular Frete:**
```bash
curl -X POST http://localhost:3000/api/calcular-frete \
  -H "Content-Type: application/json" \
  -d '{"cep": "01310100"}'
```
- [ ] Retorna valor do frete
- [ ] Retorna prazo de entrega
- [ ] Retorna dados do endereço

### 10. Testes de Email (se configurado)

**Pré-requisito:** Configure email no .env

1. Faça um pedido completo
2. Quando o pedido for marcado como "pago" no admin
- [ ] Email é enviado para idelcioforest@gmail.com
- [ ] Email contém número do pedido
- [ ] Email contém dados do cliente
- [ ] Email contém endereço de entrega
- [ ] Email contém itens comprados
- [ ] Email contém valores

### 11. Testes de Mercado Pago (se configurado)

**Pré-requisito:** Configure Mercado Pago no .env

1. Faça um pedido
2. Na tela de checkout
- [ ] Preferência de pagamento é criada
- [ ] Redireciona para Mercado Pago
- [ ] Após pagamento, webhook atualiza pedido
- [ ] Status muda para "pago"
- [ ] Estoque é decrementado
- [ ] Email é enviado

### 12. Testes de Responsividade

**Desktop:**
- [ ] Layout funciona em tela grande
- [ ] Gráficos aparecem corretamente
- [ ] Tabelas são legíveis

**Tablet (768px):**
- [ ] Layout se adapta
- [ ] Menu funciona
- [ ] Formulários são usáveis

**Mobile (375px):**
- [ ] Layout mobile funciona
- [ ] Menu mobile funciona
- [ ] Textos são legíveis
- [ ] Botões são clicáveis

### 13. Testes de Segurança

**Proteção de Rotas:**
- [ ] /admin sem login redireciona para /admin/login
- [ ] /checkout sem login redireciona para /login
- [ ] Senhas são salvas criptografadas (bcrypt)
- [ ] Sessões expiram após 24h

**Validações:**
- [ ] Campos obrigatórios são validados
- [ ] Email deve ser válido
- [ ] Quantidade não pode ser negativa
- [ ] Estoque não pode ficar negativo

### 14. Testes de Integração Completa

**Fluxo Completo de Compra:**

1. Cliente acessa site
2. Vê compras recentes de outras cidades
3. Clica em "Produto"
4. Vê depoimentos de clientes
5. Adiciona produto ao carrinho
6. Faz cadastro
7. Vai para checkout
8. Calcula frete
9. Finaliza pedido
10. Admin recebe email
11. Admin vê pedido no painel
12. Admin atualiza status para "enviado"
13. Admin vê gráficos atualizados

- [ ] Todo o fluxo funciona sem erros

## 🐛 Registro de Problemas

Use esta seção para anotar problemas encontrados:

| Problema | Severidade | Status | Solução |
|----------|------------|--------|---------|
| | | | |

## 📊 Relatório de Testes

**Data:** ___/___/______

**Testado por:** _________________

**Versão:** 1.0.0

**Total de testes:** 100+

**Passou:** ____

**Falhou:** ____

**Comentários:**
_______________________________________________
_______________________________________________
_______________________________________________

## 💡 Dicas para Testes

1. **Teste com dados reais:** Use CEPs reais, emails válidos
2. **Limpe o banco:** Para testar do zero, delete o banco e rode `npm run init-db` novamente
3. **Use navegador anônimo:** Para testar sem cache
4. **Teste em diferentes navegadores:** Chrome, Firefox, Safari, Edge
5. **Use DevTools:** Console do navegador para ver erros
6. **Monitore logs:** Terminal onde o servidor está rodando

## 🔄 Resetar Sistema para Testes

```bash
# Parar servidor (Ctrl+C)

# Deletar banco de dados (MongoDB)
# No MongoDB Compass ou mongosh:
# use juntanova
# db.dropDatabase()

# Ou limpar collections específicas:
# db.pedidos.deleteMany({})
# db.usuarios.deleteMany({isAdmin: false})

# Reinicializar
npm run init-db

# Iniciar novamente
npm start
```

## ✅ Aprovação Final

Todos os testes principais passaram?
- [ ] Sim, sistema pronto para uso
- [ ] Não, verificar problemas listados acima

**Aprovado por:** _________________

**Data:** ___/___/______
