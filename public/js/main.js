// Funções globais do site

// Formatar CPF
function formatarCPF(input) {
  let value = input.value.replace(/\D/g, '');
  if (value.length > 11) value = value.slice(0, 11);

  value = value.replace(/(\d{3})(\d)/, '$1.$2');
  value = value.replace(/(\d{3})(\d)/, '$1.$2');
  value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

  input.value = value;
}

// Formatar CEP
function formatarCEP(input) {
  let value = input.value.replace(/\D/g, '');
  if (value.length > 8) value = value.slice(0, 8);

  value = value.replace(/(\d{5})(\d)/, '$1-$2');

  input.value = value;
}

// Formatar Telefone
function formatarTelefone(input) {
  let value = input.value.replace(/\D/g, '');
  if (value.length > 11) value = value.slice(0, 11);

  if (value.length <= 10) {
    value = value.replace(/(\d{2})(\d)/, '($1) $2');
    value = value.replace(/(\d{4})(\d)/, '$1-$2');
  } else {
    value = value.replace(/(\d{2})(\d)/, '($1) $2');
    value = value.replace(/(\d{5})(\d)/, '$1-$2');
  }

  input.value = value;
}

// Auto-aplicar formatações
document.addEventListener('DOMContentLoaded', function() {
  // CPF
  const cpfInputs = document.querySelectorAll('input[name="cpf"]');
  cpfInputs.forEach(input => {
    input.addEventListener('input', () => formatarCPF(input));
  });

  // CEP
  const cepInputs = document.querySelectorAll('input[name="cep"]');
  cepInputs.forEach(input => {
    input.addEventListener('input', () => formatarCEP(input));
  });

  // Telefone
  const telefoneInputs = document.querySelectorAll('input[name="telefone"]');
  telefoneInputs.forEach(input => {
    input.addEventListener('input', () => formatarTelefone(input));
  });
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({
        behavior: 'smooth'
      });
    }
  });
});
