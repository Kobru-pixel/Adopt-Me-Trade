# Adopt Me Trade - Calculadora de Valores & IA

Sistema completo de verificação de trocas para o jogo **Adopt Me (Roblox)** com barômetro de justiça (Win / Fair / Lose), identificação visual de pets por Inteligência Artificial, sobreposição de tela do jogo, busca com auto-complete e sincronização semanal de valores baseada no [amvgg.com](https://amvgg.com/).

---

## 🌟 Funcionalidades Principais

1. **Janela de Troca Adopt Me (Dois Lados - 9 Slots)**:
   - 9 slots para "Sua Oferta" (*Your Offer*) e 9 slots para "Oferta Deles" (*Their Offer*).
   - Adição e remoção rápida de pets ao clicar nos slots vazios ou ocupados.
   - Suporte completo às variações do Adopt Me: **Fly (F)**, **Ride (R)**, **Neon (N)** e **Mega Neon (M)** com multiplicadores específicos.

2. **Barômetro de Troca W / F / L (Fairness Meter)**:
   - Indicador visual animado calculando em tempo real se a troca é:
     - 🏆 **Big Win** (Grande Vitória - ganho superior a +20%)
     - 🎉 **Win** (Vitória)
     - ⚖️ **Fair** (Troca Justa e equilibrada)
     - ⚠️ **Lose** (Perda)
     - 💀 **Big Lose** (Grande Perda - perda superior a -20%)
   - Exibição de valores totais e diferença exata de pontos de valor.

3. **Trava de Decisão de 5 Segundos (Regra do Jogo)**:
   - Sistema de contagem regressiva de **5 segundos** obrigatórios após qualquer alteração na troca antes de liberar os botões de **Aceitar** ou **Recusar**, prevenindo golpes e confirmações acidentais.

4. **Identificação de Pets por Inteligência Artificial**:
   - 📷 **Câmera / Webcam**: Reconhecimento em tempo real apontando a câmera para a tela.
   - 🖼️ **Envio de Foto**: Upload de screenshots e fotos de pets com análise visual de cores e formatos.
   - 🖥️ **Leitura da Tela do Jogo (Screen Overlay)**: Captura de tela integrada (`navigator.mediaDevices.getDisplayMedia`) que lê a janela do Roblox em tempo real.

5. **Aba de Coleção de Pets & Valores**:
   - Visualização categorizada (Pets, Ovos, Veículos, Brinquedos).
   - Filtros por raridade (*Lendário, Ultra-Raro, Raro, Incomum, Comum*).
   - Clique em qualquer item para inspecionar os valores base, FR, Neon e Mega Neon, com botão para adicionar diretamente à troca.

6. **Múltiplos Idiomas (i18n)**:
   - 🇧🇷 Português (pt-BR)
   - 🇺🇸 Inglês (en-US)
   - 🇪🇸 Espanhol (es-ES)

7. **Sincronização Semanal amvgg.com**:
   - Rotina backend para sincronização semanal dos valores com logs no banco de dados (`sync_logs`).

---

## 🏗️ Arquitetura e Stack Tecnológico

- **Backend**: PHP estruturado com sessões nativas (`session_start()`).
- **Banco de Dados**: MySQL com consultas parametrizadas via **PDO** (prevenção contra SQL Injection) e fallback inteligente para SQLite.
- **Frontend**: HTML5, PHP, CSS3, Tailwind CSS (Design Mobile-First responsivo).
- **Notificações & Modais**:
  - Sistema de **Toast** para feedback não-bloqueante e erros claros.
  - Sistema de **Modal customizado** substituindo todos os alertas e confirmações nativas do navegador.

### Estrutura de Diretórios
```
Adopt-Me-Trade/
├── config/
│   ├── config.php          # Configurações de banco, sessões e constantes
│   └── i18n.php            # Dicionário multilíngue (pt-BR, en-US, es-ES)
├── database/
│   ├── bd.php              # Conexão PDO segura (MySQL + Fallback SQLite)
│   └── database.sql        # Script SQL completo de criação e carga inicial
├── api/
│   ├── items.php           # Listagem e filtragem de itens
│   ├── search.php          # Auto-complete rápido
│   ├── trade_calc.php      # Cálculo de valores e veredito W/F/L
│   ├── detect_pet.php      # Motor de IA e visão computacional
│   └── sync_amvgg.php      # Sincronização periódica com amvgg.com
├── components/
│   ├── header.php          # Barra de topo, navegação e seletor de idiomas
│   ├── trade_view.php      # Janela de troca com 9 slots, barômetro e timer
│   ├── collection_view.php # Aba de coleção categorizada e detalhes
│   ├── item_modal.php      # Modal seletor de pets com variações F/R/N/M
│   ├── scanner_modal.php   # Modal de IA: Câmera, Upload e Leitura de Tela
│   ├── custom_modal.php    # Modais customizados para confirmações
│   └── toast.php           # Container de notificações flutuantes
├── assets/
│   ├── css/
│   │   └── style.css       # Estilos temáticos Adopt Me, Neon e Mega Neon
│   └── js/
│       ├── toast.js        # Gerenciador de Toast
│       ├── modal.js        # Gerenciador de Modais
│       ├── i18n.js         # Gerenciador de idiomas no cliente
│       ├── scanner.js      # IA, Câmera e Captura de Tela do jogo
│       └── trade.js        # Lógica central da troca e timer de 5s
├── index.php               # Aplicação PHP principal
├── index.html              # Versão executável para navegadores
└── README.md
```

---

## 🚀 Como Executar

### Opção 1: Servidor PHP Embutido
Execute no terminal:
```bash
php -S localhost:8000
```
Acesse no navegador: `http://localhost:8000`

### Opção 2: Servidores Web (Apache / XAMPP / Wamp / Docker)
1. Coloque a pasta do projeto no diretório web (ex: `htdocs` ou `www`).
2. Se desejar usar o MySQL, importe o arquivo `database/database.sql` no phpMyAdmin ou MySQL CLI.
3. Configure as credenciais no `config/config.php` (o sistema detecta e inicializa o fallback automaticamente caso o MySQL não esteja rodando).
4. Abra no navegador: `http://localhost/Adopt-Me-Trade/`

### Opção 3: Visualização Direta no Navegador
Abra o arquivo `index.html` em qualquer navegador moderno para testar toda a interface, barômetro W/F/L, câmera/IA e timer de 5 segundos de forma imediata.
