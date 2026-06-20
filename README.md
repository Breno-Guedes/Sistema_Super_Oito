# Super 8 Beach Tennis

Sistema web desenvolvido em PHP para gerenciamento de torneios no formato **Super 8 de Beach Tennis**, permitindo cadastro de participantes, geração automática de rodadas, lançamento de resultados e acompanhamento da classificação em tempo real.

## Funcionalidades

### Cadastro de Participantes
- Cadastro de exatamente **8 jogadores**.
- Registro de nome completo e apelido (opcional).
- Armazenamento dos dados em arquivos JSON.

### Geração Automática de Rodadas
O sistema possui dois formatos de competição:

#### Duplas Rotativas
- Sorteio inteligente pré-definido.
- Todos os jogadores atuam com parceiros diferentes ao longo do torneio.
- Geração automática de **7 rodadas**.

#### Duplas Fixas
- Formação de **4 duplas fixas**.
- Geração automática dos confrontos entre as duplas.
- Total de **7 rodadas**.

### Controle das Partidas
- Registro dos placares das partidas.
- Atualização automática dos resultados.
- Controle de status das rodadas (pendente/concluída).

### Classificação
- Ranking atualizado automaticamente.
- Classificação parcial por rodada.
- Classificação final do torneio.
- Impressão da tabela de classificação.

### Critérios de Desempate
1. Pontos
2. Games vencidos
3. Saldo de games
4. Ordem alfabética

---

## Estrutura do Projeto

```text
sitemaSuperOito/
│
├── classificacao/
│   └── classificacao.php
│
├── configuracao/
│   ├── configuracao.php
│   └── gerar_rodadas.php
│
├── css/
│   └── style.css
│
├── data/
│   ├── participantes.json
│   ├── rodadas.json
│   └── classificacoes.json
│
├── js/
│   └── ui.js
│
├── participantes/
│   ├── cadastro.php
│   └── salvar_participantes.php
│
├── rodadas/
│   ├── rodadas.php
│   └── salvar_placar.php
│
├── utils/
│   ├── json_helper.php
│   ├── pontuacao.php
│   ├── sorteio.php
│   └── zerar.php
│
└── index.php
```

---

## Tecnologias Utilizadas

- PHP
- HTML5
- CSS3
- JavaScript
- JSON (persistência de dados)

---

## Como Executar

### Pré-requisitos
- PHP 7.4 ou superior
- Servidor web local (XAMPP, WAMP, Laragon ou similar)

### Passos

1. Clone ou extraia o projeto:

```bash
git clone <repositorio>
```

ou copie a pasta para o diretório do servidor.

2. Inicie o servidor Apache.

3. Acesse:

```text
http://localhost/sitemaSuperOito
```

4. Siga o fluxo:

```text
1. Cadastrar Jogadores
↓
2. Gerar Rodadas
↓
3. Lançar Resultados
↓
4. Consultar Classificação
```

---

## Sistema de Pontuação

### Vitória
- 3 pontos

### Derrota
- 0 pontos

Além dos pontos, o sistema registra:

- Jogos disputados
- Vitórias
- Derrotas
- Games vencidos
- Games perdidos
- Saldo de games

---

## Persistência de Dados

Os dados são armazenados em arquivos JSON:

| Arquivo | Descrição |
|----------|------------|
| participantes.json | Jogadores cadastrados |
| rodadas.json | Rodadas e confrontos |
| classificacoes.json | Dados de classificação |

---

## Reinicialização do Torneio

O sistema possui a opção **"Zerar Torneio"**, que remove os dados armazenados e permite iniciar uma nova competição.

---

## Fluxo de Utilização

### Etapa 1
Cadastrar os 8 participantes.

### Etapa 2
Escolher o formato do torneio:
- Duplas Rotativas
- Duplas Fixas

### Etapa 3
Gerar automaticamente as 7 rodadas.

### Etapa 4
Lançar os resultados das partidas.

### Etapa 5
Acompanhar a classificação parcial ou final.

---

## Autor

**Breno de Souza Guedes**

Sistema desenvolvido para gerenciamento de torneios Super 8 de Beach Tennis.