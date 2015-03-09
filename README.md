# Cobertura Colaborativa para Wordpress

## Instalação

### 1. Configuração e instalação do plugin

- Insira todas as credenciais de API em plugin/controllers/cobcolaborativa-home.php
- Configure a hashtag neste mesmo arquivo ($palavraChave)
- Configure a quantidade de itens que serão guardados com a variávels $qtde
- Aponte um serviço de cron para as seguintes terminações:
  - /agregador/fetcher/instagram
  - /agregador/fetcher/youtube
  - /agregador/fetcher/twitter
  - /agregador/fetcher/flickr
  - **Observação**: A frequência de atualização dos serviços ficará conforme o que for configurado no cron
- Renomeie a pasta /plugin para o /cobertura-colaborativa e mova ela para /wp-contents/plugins/
- Entre no painel administrativo do Wordpress e ative o tema
- Entre na aba de administração criada pelo plugin e configure o mesmo

### 2. Configuração e instalação dentro do tema

- Com o plugin configurado, mova todos os arquivos da pastas /template para o tema de wordpress ativo no momento
- **ATENÇÃO**: Não sobrescreva as pastas /css, /images e /js. Caso elas existam no tema, apenas jogue os conteúdos das mesmas dentro das pastas já existentes
- Inclua o arquivo "functions-cobertura.php" em seu "functions.php"
- Crie uma página com o template "Cobertura Colaborativa"

### 3. Teste o site para ver se está tudo correto
