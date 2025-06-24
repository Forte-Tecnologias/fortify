### Fortify


## 🚀 Começando: Configurando seu Ambiente

Siga este guia passo a passo para deixar o projeto rodando na sua máquina.

### 1. Pré-requisitos

Antes de começar, você precisa ter as ferramentas essenciais instaladas.

| Ferramenta              | Linux (Ex: Ubuntu/Debian)                                                                                                                                                                            | Windows                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| ----------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **IDE (VS Code)**       | [Visual Studio Code](https://code.visualstudio.com/) <br> `sudo snap install --classic code`                                                                                                         | [Visual Studio Code](https://code.visualstudio.com/) <br> Baixe e instale normalmente pelo instalador oficial.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| **Ambiente de Dev**     | O terminal do Linux já é o ambiente ideal.                                                                                                                                                           | **Opção 1 (Melhor Prática): [WSL 2](https://learn.microsoft.com/pt-br/windows/wsl/install)**<br>O WSL (Subsistema do Windows para Linux) permite rodar um ambiente Linux real no Windows. É a melhor forma de desenvolver, pois se assemelha aos servidores de produção. **Se escolher o WSL, siga os passos da coluna "Linux" dentro do terminal do WSL.**<br><br>**Opção 2 (Excelente para Windows Nativo): [Scoop](https://scoop.sh/)**<br>Scoop é um gerenciador de pacotes para a linha de comando que simplifica enormemente a instalação de ferramentas de desenvolvimento no Windows. **Se escolher o Scoop, siga as instruções detalhadas abaixo.** |
| **Git**                 | `sudo apt update && sudo apt install git`                                                                                                                                                            | **Se usar Scoop:** `scoop install git`<br>**Se não:** Instale o **[Git for Windows](https://git-scm.com/download/win)**.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| **PHP >= 8.2**          | ```sudo apt install php php-xml php-dom php-curl php-sqlite3 zip unzip php-mbstring```                             | **Se usar Scoop:** `scoop install php`<br>**Se não:** Use a versão incluída no Laragon ou instale manualmente.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| **Composer**            | ```sudo apt install composer``` | **Se usar Scoop:** `scoop install composer`<br>**Se não:** Baixe e execute o **[instalador do Composer](https://getcomposer.org/download/)**.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                |
| **Node.js >= 18 (LTS)** | ```sudo apt install nodejs npm```                                                                         | **Se usar Scoop:** `scoop install nodejs-lts`<br>**Se não:** Baixe e instale a versão **[LTS do site oficial](https://nodejs.org/)**.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |

---

> ###  Configurar com o Scoop: O Guia Detalhado
>
> Se você escolheu usar o **Scoop** no Windows (ótima escolha!), aqui está o passo a passo completo. Abra o **PowerShell** para executar estes comandos.
>
> **1. Instale o Scoop**
>
> Primeiro, você precisa instalar o próprio Scoop. Pode ser que você precise permitir a execução de scripts primeiro.
> ```powershell
> # Permite a execução de scripts para o seu usuário atual
> Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
>
> # Instala o Scoop
> Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression
> ```
>
> **2. Adicione o "Bucket" Extra**
>
> Muitos pacotes de desenvolvimento, incluindo o PHP, estão no repositório (bucket) "extras".
> ```powershell
> scoop bucket add extras
> ```
>
> **3. Instale as Ferramentas**
>
> Agora, instale tudo o que você precisa com comandos simples.
> ```powershell
> # Instala o Git
> scoop install git
>
> # Instala a versão mais recente e estável do PHP (já vem com as extensões necessárias)
> scoop install php
>
> # Instala o Composer (ele detectará o PHP automaticamente)
> scoop install composer
>
> # Instala a versão LTS (Long-Term Support) do Node.js (inclui o npm)
> scoop install nodejs-lts
> ```
>
> **Pronto!** Com isso, seu ambiente de desenvolvimento nativo do Windows está completo e pronto para uso. Você pode fechar e abrir o PowerShell para garantir que todos os comandos (`git`, `php`, `composer`, `npm`) estão disponíveis.
>
> Agora, continue para a seção **"2. Instalação do Projeto"**.

---

### 2. Instalação do Projeto

Com todos os pré-requisitos prontos, agora vamos configurar o projeto.
Antes de tudo, certifique-se de que você está no diretório onde você criará o projeto. (Minha recomendação pessoal é ser criado uma pasta chamada `Projetos` dentro do seu diretório de usuário ou documentos, mas você pode escolher qualquer lugar que preferir.)

**1. Clone (baixar o projeto)**

Abra o terminal (ou PowerShell no Windows) e navegue até o diretório onde você deseja clonar o projeto. Se estiver usando o WSL, você pode usar o terminal do Linux. Se estiver no Windows nativo, use o PowerShell ou o CMD.

Clone o projeto para a sua máquina local:
```bash
git clone https://github.com/Forte-Tecnologias/fortify.git

# Entre na pasta do projeto
cd fortify
```

**2. Configure o Arquivo de Ambiente**

Copie o arquivo de exemplo `.env.example` para criar seu próprio arquivo de configuração local, o `.env`.
```bash
# No Linux ou Git Bash (WSL/Windows)
cp .env.example .env

# Se estiver usando o Command Prompt (CMD) do Windows
copy .env.example .env

# Pode ser feito manualmente usando o explorer do Windows, clicando com o botão direito no arquivo `.env.example` e selecionando "Copiar", depois "Colar" e renomeando para `.env`.
```

**3. Configure as Extensões do PHP**

Antes de instalar as dependências, execute o script para configurar as extensões necessárias:
```bash
php setup-extensions.php
```

**4. Instale as Dependências (PHP & Node.js)**

Execute os seguintes comandos para instalar todos os pacotes necessários para o backend (Laravel) e o frontend (React).
```bash
# Instala as dependências do PHP com Composer
composer install

# Instala as dependências do JavaScript com NPM
npm install
```

**5. Gere a chave da aplicação**

Após instalar as dependências, gere a chave de criptografia do Laravel:
```bash
php artisan key:generate
```

**6. Executar as migrações do banco de dados**

Antes de rodar a aplicação pela primeira vez, execute as migrações do banco de dados:

```bash
php artisan migrate
```

### 3. Rodando o Projeto

Para rodar a aplicação, você precisará abrir um terminal na pasta do projeto.

**Iniciar o Servidor do Laravel junto com React**
```bash
composer dev
```
### ✅ Sucesso!

Se tudo deu certo, abra seu navegador e acesse **[http://127.0.0.1:8000](http://127.0.0.1:8000)**.

Você deve ver a tela inicial do projeto com a mensagem: **"Parabéns, você conseguiu acessar a página inicial do projeto"**.

### 🤔 Solução de Problemas Comuns

-   **Comando `php`, `composer` ou `npm` não encontrado:** Isso geralmente significa que a ferramenta não foi instalada corretamente ou seu caminho não foi adicionado às variáveis de ambiente do seu sistema. Volte à seção de pré-requisitos e verifique a instalação.