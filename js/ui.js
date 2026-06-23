function garantirJanelaSistema() {
    let overlay = document.getElementById('janela-sistema-overlay');

    if (overlay) {
        return overlay;
    }

    overlay = document.createElement('div');
    overlay.id = 'janela-sistema-overlay';
    overlay.className = 'janela-sistema-overlay is-hidden';
    overlay.innerHTML = `
        <div class="janela-sistema" role="dialog" aria-modal="true" aria-labelledby="janela-sistema-titulo">
            <h3 id="janela-sistema-titulo"></h3>
            <p id="janela-sistema-mensagem"></p>
            <div class="janela-sistema-acoes">
                <button type="button" class="btn" id="janela-sistema-confirmar">OK</button>
                <button type="button" class="btn btn-danger" id="janela-sistema-cancelar">Cancelar</button>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);

    overlay.addEventListener('click', (event) => {
        if (event.target === overlay) {
            const confirmarBtn = overlay.querySelector('#janela-sistema-confirmar');
            const cancelarBtn = overlay.querySelector('#janela-sistema-cancelar');

            if (overlay.dataset.temCancelamento === '1') {
                cancelarBtn.click();
            } else {
                confirmarBtn.click();
            }
        }
    });

    return overlay;
}

function abrirJanelaSistema({ titulo, mensagem, confirmarTexto = 'OK', cancelarTexto = null }) {
    const overlay = garantirJanelaSistema();
    const tituloEl = overlay.querySelector('#janela-sistema-titulo');
    const mensagemEl = overlay.querySelector('#janela-sistema-mensagem');
    const confirmarBtn = overlay.querySelector('#janela-sistema-confirmar');
    const cancelarBtn = overlay.querySelector('#janela-sistema-cancelar');

    tituloEl.textContent = titulo;
    mensagemEl.textContent = mensagem;
    confirmarBtn.textContent = confirmarTexto;
    cancelarBtn.textContent = cancelarTexto || 'Cancelar';
    cancelarBtn.style.display = cancelarTexto ? 'inline-flex' : 'none';
    overlay.dataset.temCancelamento = cancelarTexto ? '1' : '0';
    overlay.classList.remove('is-hidden');

    return new Promise((resolve) => {
        const limpar = () => {
            confirmarBtn.removeEventListener('click', confirmar);
            cancelarBtn.removeEventListener('click', cancelar);
        };

        const confirmar = () => {
            limpar();
            fecharJanelaSistema();
            resolve(true);
        };

        const cancelar = () => {
            limpar();
            fecharJanelaSistema();
            resolve(false);
        };

        confirmarBtn.addEventListener('click', confirmar, { once: true });
        cancelarBtn.addEventListener('click', cancelar, { once: true });
    });
}

function fecharJanelaSistema() {
    const overlay = document.getElementById('janela-sistema-overlay');
    if (overlay) {
        overlay.classList.add('is-hidden');
    }
}

async function mostrarMensagem(titulo, mensagem, confirmarTexto = 'OK') {
    await abrirJanelaSistema({ titulo, mensagem, confirmarTexto });
}

async function confirmarAcao(titulo, mensagem, confirmarTexto = 'OK', cancelarTexto = 'Cancelar') {
    return abrirJanelaSistema({ titulo, mensagem, confirmarTexto, cancelarTexto });
}

async function enviarFormulario(event, url) {
    event.preventDefault();
    const form = event.target;
    const data = new FormData(form);

    const nomes = [...form.querySelectorAll('input[name="nome[]"]')];
    const nomesCadastrados = new Set();
    for (const nomeInput of nomes) {
        const nome = nomeInput.value.trim().replace(/\s+/g, ' ').toLocaleLowerCase('pt-BR');

        if (!nome) {
            await mostrarMensagem('Nome obrigatorio', 'Preencha o nome de todos os jogadores.');
            nomeInput.focus();
            return;
        }

        if (nomesCadastrados.has(nome)) {
            await mostrarMensagem('Nome repetido', 'Não é permitido cadastrar jogadores com nomes idênticos.');
            nomeInput.focus();
            return;
        }

        nomesCadastrados.add(nome);
    }

    const placares = [...form.querySelectorAll('input[name^="p1_"]')];
    for (const placar1 of placares) {
        const index = placar1.name.replace('p1_', '');
        const placar2 = form.querySelector(`[name="p2_${index}"]`);
        if (!placar2) {
            continue;
        }

        const valor1 = Number(placar1.value);
        const valor2 = Number(placar2.value);

        if (
            (!Number.isInteger(valor1) || !Number.isInteger(valor2) || valor1 < 0 || valor1 > 6 || valor2 < 0 || valor2 > 6)
        ) {
            await mostrarMensagem('Placar invalido', 'Informe placares inteiros entre 0 e 6.');
            placar1.focus();
            return;
        }

        if (valor1 === valor2) {
            await mostrarMensagem('Placar empatado', 'Informe um placar sem empate.');
            placar1.focus();
            return;
        }
    }

    try {
        const res = await fetch(url, { method: 'POST', body: data });
        const json = await res.json();

        if (json.status === 'ok') {
            if (json.redirect) {
                window.location.href = json.redirect;
            }
            return;
        }

        await mostrarMensagem('Erro', json.msg || 'Não foi possível processar a solicitacao.');
    } catch (e) {
        await mostrarMensagem('Erro', 'Não foi possível processar a requisicao.');
    }
}

async function zerarSistema() {
    const confirmou = await confirmarAcao(
        'Confirmar ação',
        'Tem certeza? Todos os dados serão apagados.',
        'Sim, zerar',
        'Cancelar'
    );

    if (confirmou) {
        await fetch('utils/zerar.php');
        location.reload();
    }
}

function imprimirRanking() {
    window.print();
}
