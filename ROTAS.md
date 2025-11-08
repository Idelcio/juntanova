# 🗺️ Mapa de Rotas do Sistema

## 🌐 Rotas Públicas (Site)

### Páginas Principais
| Rota | Método | Descrição |
|------|--------|-----------|
| `/` | GET | Página inicial do site |
| `/produto` | GET | Página do produto com detalhes |
| `/carrinho` | GET | Visualizar carrinho de compras |

### Autenticação
| Rota | Método | Descrição |
|------|--------|-----------|
| `/cadastro` | GET | Formulário de cadastro |
| `/cadastro` | POST | Processar cadastro de novo usuário |
| `/login` | GET | Formulário de login |
| `/login` | POST | Processar login |
| `/logout` | GET | Fazer logout |

### Carrinho e Checkout
| Rota | Método | Descrição |
|------|--------|-----------|
| `/carrinho/adicionar` | POST | Adicionar produto ao carrinho |
| `/checkout` | GET | Página de finalização (requer login) |
| `/checkout/processar` | POST | Processar pedido |

## 🔐 Rotas Admin (Painel Administrativo)

### Acesso Admin
| Rota | Método | Descrição |
|------|--------|-----------|
| `/admin/login` | GET | Login do admin |
| `/admin/login` | POST | Processar login admin |
| `/admin/logout` | GET | Logout admin |

### Dashboard e Estatísticas
| Rota | Método | Descrição |
|------|--------|-----------|
| `/admin/dashboard` | GET | Dashboard com gráficos e estatísticas |

### Gerenciamento de Estoque
| Rota | Método | Descrição |
|------|--------|-----------|
| `/admin/estoque` | GET | Visualizar e editar estoque |
| `/admin/estoque/atualizar` | POST | Atualizar quantidade e preços |

### Gerenciamento de Pedidos
| Rota | Método | Descrição |
|------|--------|-----------|
| `/admin/pedidos` | GET | Listar todos os pedidos |
| `/admin/pedidos/:id/status` | POST | Atualizar status do pedido |

### Gerenciamento de Depoimentos
| Rota | Método | Descrição |
|------|--------|-----------|
| `/admin/depoimentos` | GET | Listar todos os depoimentos |
| `/admin/depoimentos/:id/toggle` | POST | Aprovar/desaprovar depoimento |

## 🔌 API (Integrações)

### CEP e Endereço
| Rota | Método | Descrição |
|------|--------|-----------|
| `/api/cep/:cep` | GET | Buscar endereço por CEP (ViaCEP) |
| `/api/calcular-frete` | POST | Calcular valor do frete |

### Mercado Pago
| Rota | Método | Descrição |
|------|--------|-----------|
| `/api/criar-pagamento/:pedidoId` | POST | Criar preferência de pagamento |
| `/api/webhook/mercadopago` | POST | Receber notificações do MP |

## 📊 Dados Retornados

### Dashboard Admin
O dashboard (`/admin/dashboard`) retorna:
- Total de pedidos
- Pedidos pendentes
- Pedidos pagos
- Estoque atual
- Gráfico de vendas por estado
- Gráfico de top 10 cidades
- Lista de pedidos recentes

### Cálculo de Frete
A API de frete retorna:
```json
{
  "valorFrete": 15.00,
  "prazo": "5-10 dias úteis",
  "endereco": {
    "cidade": "São Paulo",
    "estado": "SP",
    "bairro": "Centro",
    "rua": "Rua Exemplo"
  }
}
```

### Compras Recentes (Página Inicial)
A página inicial mostra as últimas 5 compras com:
- Cidade e estado
- Tempo decorrido desde a compra

## 🛡️ Middlewares de Proteção

### `isAdmin`
Protege rotas que só admins podem acessar.
Usado em: `/admin/*` (exceto login)

### `isAuthenticated`
Protege rotas que precisam de login.
Usado em: `/checkout`

### `redirectIfAuthenticated`
Redireciona usuários já logados.
Usado em: `/admin/login`, `/login`

## 📧 Notificações por Email

Emails são enviados para `idelcioforest@gmail.com` quando:
- Um pedido é pago com sucesso
- O webhook do Mercado Pago confirma o pagamento

O email contém:
- Número do pedido
- Dados do cliente
- Endereço de entrega
- Itens comprados
- Valores (produtos + frete)

## 🎨 Views (Templates EJS)

### Site Público
- `views/site/index.ejs` - Página inicial
- `views/site/produto.ejs` - Página do produto
- `views/site/cadastro.ejs` - Cadastro
- `views/site/login.ejs` - Login
- `views/site/carrinho.ejs` - Carrinho
- `views/site/checkout.ejs` - Checkout
- `views/404.ejs` - Página não encontrada

### Admin
- `views/admin/login.ejs` - Login admin
- `views/admin/dashboard.ejs` - Dashboard
- `views/admin/estoque.ejs` - Gerenciar estoque
- `views/admin/pedidos.ejs` - Gerenciar pedidos
- `views/admin/depoimentos.ejs` - Gerenciar depoimentos

## 🔄 Fluxo de Compra

1. Cliente visita `/` ou `/produto`
2. Adiciona ao carrinho via POST `/carrinho/adicionar`
3. Visualiza carrinho em `/carrinho`
4. Faz login/cadastro em `/login` ou `/cadastro`
5. Vai para `/checkout`
6. Calcula frete via `/api/calcular-frete`
7. Finaliza via POST `/checkout/processar`
8. Sistema cria pedido no banco
9. Redireciona para pagamento (Mercado Pago)
10. Mercado Pago notifica via `/api/webhook/mercadopago`
11. Sistema atualiza pedido, estoque e envia email

## 🔍 Observações

- Todas as rotas admin exigem autenticação
- Checkout exige login do usuário
- APIs são usadas internamente pelo frontend
- Webhook do Mercado Pago é chamado automaticamente
- Sessões expiram em 24 horas
