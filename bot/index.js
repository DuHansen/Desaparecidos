const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
    const browser = await puppeteer.launch({ headless: false });
    const page = await browser.newPage();

    try {
        await page.goto('https://www.desaparecidos.pr.gov.br/desaparecidos/desaparecidos.do?action=iniciarProcesso&m=false', { waitUntil: 'networkidle2' });

        // Aguardar o seletor .photo-wrapper
        await page.waitForSelector('.photo-wrapper');

        let desaparecidos = [];
        const totalPaginas = 85;

        for (let pagina = 1; pagina <= totalPaginas; pagina++) {
            console.log(`Coletando dados da página ${pagina}...`);

            // Coletar dados da página atual
            const dadosPagina = await page.evaluate(() => {
                return Array.from(document.querySelectorAll('.photo-wrapper')).map(wrapper => ({
                    nome: wrapper.querySelector('.person-info h3')?.innerText.trim() || 'Nome não encontrado',
                    foto: wrapper.querySelector('.photo-box img')?.src || 'Sem foto',
                    idade: wrapper.querySelector('.infos-out p:nth-child(1) strong')?.innerText.trim() || 'Idade não informada',
                    desaparecidoEm: wrapper.querySelector('.infos-out p:nth-child(2) strong')?.innerText.trim() || 'Data não informada',
                    cidade: wrapper.querySelector('.infos-out p:nth-child(3) strong')?.innerText.trim() || 'Cidade não informada',
                }));
            });

            desaparecidos.push(...dadosPagina);

            if (pagina < totalPaginas) {
                try {
                    // Encontrar o botão da próxima página
                    const botaoProximo = await page.evaluateHandle((paginaAtual) => {
                        const botoes = Array.from(document.querySelectorAll('.paginationList button.q-btn'));
                        return botoes.find(btn => {
                            const texto = btn.querySelector('.q-btn__content')?.innerText.trim();
                            return texto === String(paginaAtual + 1); // Procura o botão com o número da próxima página
                        });
                    }, pagina);

                    if (botaoProximo) {
                        await botaoProximo.click();
                        console.log(`Clicou no botão da página ${pagina + 1}.`);

                        // Delay para garantir que a página carregue completamente
                        await new Promise(resolve => setTimeout(resolve, 3000)); // 3 segundos de delay

                        // Aguardar o carregamento da próxima página
                        await page.waitForSelector('.photo-wrapper', { timeout: 6000 });
                    } else {
                        console.log(`Botão da página ${pagina + 1} não encontrado.`);
                        break;
                    }
                } catch (error) {
                    console.error(`Erro ao clicar na página ${pagina + 1}:`, error);
                    break;
                }
            }
        }

        fs.writeFileSync('desaparecidos.json', JSON.stringify(desaparecidos, null, 2), 'utf-8');
        console.log('Dados salvos em desaparecidos.json');

    } catch (error) {
        console.error('Erro ao coletar dados:', error);
    } finally {
        await browser.close();
    }
})();