require('dotenv').config();
const { pool } = require('../config/database');
const Usuario = require('../models/Usuario');
const Produto = require('../models/Produto');
const Depoimento = require('../models/Depoimento');
const fs = require('fs');
const path = require('path');

async function initDatabase() {
  try {
    console.log('🔄 Inicializando banco de dados MySQL...\n');

    // Ler e executar schema SQL
    const schemaPath = path.join(__dirname, '../database/schema.sql');
    const schema = fs.readFileSync(schemaPath, 'utf8');

    // Executar cada statement SQL separadamente
    const statements = schema.split(';').filter(stmt => stmt.trim());

    for (const statement of statements) {
      if (statement.trim()) {
        try {
          await pool.query(statement);
        } catch (error) {
          // Ignorar erros de "já existe"
          if (!error.message.includes('already exists')) {
            console.error('Erro ao executar:', statement.substring(0, 50) + '...');
            console.error(error.message);
          }
        }
      }
    }

    console.log('✅ Tabelas criadas com sucesso\n');

    // Criar admin padrão
    const adminEmail = process.env.ADMIN_USERNAME || 'admin@juntanova.com';
    const adminExistente = await Usuario.buscarPorEmail(adminEmail);

    if (!adminExistente) {
      await Usuario.criar({
        nome: 'Administrador',
        email: adminEmail,
        senha: process.env.ADMIN_PASSWORD || 'admin123',
        telefone: '(00) 00000-0000',
        cpf: '000.000.000-00',
        endereco: {
          cep: '00000-000',
          rua: 'Rua Admin',
          numero: '0',
          bairro: 'Centro',
          cidade: 'São Paulo',
          estado: 'SP'
        },
        isAdmin: true
      });

      console.log('✅ Admin criado com sucesso');
      console.log(`📧 Email: ${adminEmail}`);
      console.log(`🔑 Senha: ${process.env.ADMIN_PASSWORD || 'admin123'}\n`);
    } else {
      console.log('ℹ️  Admin já existe\n');
    }

    // Criar produto padrão
    const produtoExistente = await Produto.buscarPrincipal();

    if (!produtoExistente) {
      const [result] = await pool.execute(
        `INSERT INTO produtos (nome, descricao, preco, estoque, peso, capsulas, dosagem, tipo, ativo)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
        [
          'Junta Nova',
          'Composto Natural para Articulações - Articulações novas em pouco tempo. 30 cápsulas 500mg cada. 100% Natural, sem contraindicações.',
          139.00,
          100,
          '45g',
          30,
          '500mg cada',
          '100% Natural',
          true
        ]
      );

      const produtoId = result.insertId;

      // Inserir imagens
      const imagens = ['/images/produto1.jpg', '/images/produto2.jpg', '/images/produto3.jpg', '/images/produto4.jpg'];
      for (let i = 0; i < imagens.length; i++) {
        await pool.execute(
          'INSERT INTO produto_imagens (produto_id, caminho, ordem) VALUES (?, ?, ?)',
          [produtoId, imagens[i], i]
        );
      }

      // Inserir benefícios
      const beneficios = [
        'Fortalece as articulações',
        'Aumenta a mobilidade',
        'Reduz desconfortos',
        'Fórmula 100% natural',
        'Sem contraindicações'
      ];

      for (const beneficio of beneficios) {
        await pool.execute(
          'INSERT INTO produto_beneficios (produto_id, beneficio) VALUES (?, ?)',
          [produtoId, beneficio]
        );
      }

      console.log('✅ Produto criado com sucesso\n');
    } else {
      console.log('ℹ️  Produto já existe\n');
    }

    // Criar depoimentos de exemplo
    const depoimentosExistentes = await Depoimento.contar();

    if (depoimentosExistentes === 0) {
      const depoimentos = [
        {
          nome: 'Maria Silva',
          cidade: 'São Paulo',
          estado: 'SP',
          depoimento: 'Produto maravilhoso! Em apenas 2 semanas já senti muita diferença nas minhas articulações. Agora consigo caminhar sem dores.',
          estrelas: 5,
          aprovado: true,
          destaque: true
        },
        {
          nome: 'João Santos',
          cidade: 'Rio de Janeiro',
          estado: 'RJ',
          depoimento: 'Recomendo muito! Minha mãe tem 75 anos e estava com muitas dores nos joelhos. Depois de usar Junta Nova, ela voltou a fazer suas caminhadas.',
          estrelas: 5,
          aprovado: true,
          destaque: true
        },
        {
          nome: 'Ana Paula',
          cidade: 'Belo Horizonte',
          estado: 'MG',
          depoimento: 'Excelente produto! Natural e eficaz. Voltei a praticar exercícios sem desconforto.',
          estrelas: 5,
          aprovado: true,
          destaque: false
        },
        {
          nome: 'Carlos Oliveira',
          cidade: 'Curitiba',
          estado: 'PR',
          depoimento: 'Muito bom! Melhorou bastante a mobilidade das minhas articulações. Estou no segundo mês de uso e os resultados são excelentes.',
          estrelas: 5,
          aprovado: true,
          destaque: false
        },
        {
          nome: 'Fernanda Costa',
          cidade: 'Porto Alegre',
          estado: 'RS',
          depoimento: 'Produto de qualidade! Senti melhora significativa já na primeira semana. Super recomendo!',
          estrelas: 5,
          aprovado: true,
          destaque: true
        },
        {
          nome: 'Roberto Lima',
          cidade: 'Salvador',
          estado: 'BA',
          depoimento: 'Ótimo custo-benefício. Produto natural e realmente funciona. Minhas dores diminuíram muito.',
          estrelas: 5,
          aprovado: true,
          destaque: false
        }
      ];

      for (const dep of depoimentos) {
        await Depoimento.criar(dep);
      }

      console.log('✅ Depoimentos criados com sucesso\n');
    } else {
      console.log('ℹ️  Depoimentos já existem\n');
    }

    console.log('\n🎉 Banco de dados inicializado com sucesso!');
    console.log('\n📝 Informações de acesso:');
    console.log(`Admin Email: ${adminEmail}`);
    console.log(`Admin Senha: ${process.env.ADMIN_PASSWORD || 'admin123'}`);
    console.log('\n🚀 Inicie o servidor com: npm start');
    console.log('🌐 Acesse: http://localhost:3000');
    console.log('🔐 Admin: http://localhost:3000/admin/login\n');

    process.exit(0);
  } catch (error) {
    console.error('❌ Erro ao inicializar banco de dados:', error);
    process.exit(1);
  }
}

initDatabase();
