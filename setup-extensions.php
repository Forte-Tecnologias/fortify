<?php

// --- Configuração ---
$requiredExtensions = [
    'openssl',
    'pdo_sqlite',
    'curl',
    'fileinfo',
    'mbstring',
];
// --------------------

/**
 * Encontra o caminho para o diretório de configuração do PHP.
 * @return string|false O caminho para o diretório ou false em caso de falha.
 */
function find_php_config_dir() {
    // 1. Tenta obter o diretório a partir do php.ini carregado
    if ($loaded_ini = php_ini_loaded_file()) {
        return dirname($loaded_ini);
    }

    // 2. Se nenhum .ini está carregado, deduz a partir do local do executável
    if (defined('PHP_BINARY') && PHP_BINARY) {
        $php_dir = dirname(PHP_BINARY);
        // Verifica se é um diretório válido e se contém os templates
        if (is_dir($php_dir) && (file_exists($php_dir . '/php.ini-production') || file_exists($php_dir . '/php.ini-development'))) {
            return $php_dir;
        }
    }
    
    // 3. Como último recurso, tenta o comando `php --ini`
    ob_start();
    passthru('php --ini 2>&1', $return_var);
    $output = ob_get_clean();
    if ($return_var === 0 && preg_match('/Configuration File \(php.ini\) Path:\s+(.+)/', $output, $matches)) {
        $config_dir = trim($matches[1]);
        if ($config_dir && is_dir($config_dir)) {
            return $config_dir;
        }
    }

    return false;
}

function configure_php_ini($extensions) {
    $iniDir = find_php_config_dir();
    if (!$iniDir) {
        echo "ERRO CRÍTICO: Não foi possível localizar o diretório de configuração do PHP." . PHP_EOL;
        echo "Verifique sua instalação do PHP ou configure o 'php.ini' manualmente." . PHP_EOL;
        return false;
    }

    $iniFile = $iniDir . DIRECTORY_SEPARATOR . 'php.ini';

    // Se o php.ini não existe, cria a partir de um template
    if (!file_exists($iniFile)) {
        $prodTemplate = $iniDir . DIRECTORY_SEPARATOR . 'php.ini-production';
        $devTemplate = $iniDir . DIRECTORY_SEPARATOR . 'php.ini-development';
        $sourceTemplate = file_exists($prodTemplate) ? $prodTemplate : (file_exists($devTemplate) ? $devTemplate : null);

        if ($sourceTemplate) {
            echo "Arquivo 'php.ini' não encontrado. Criando a partir de '" . basename($sourceTemplate) . "'..." . PHP_EOL;
            if (!copy($sourceTemplate, $iniFile)) {
                echo "ERRO: Falha ao criar o arquivo '$iniFile'. Verifique as permissões de escrita no diretório." . PHP_EOL;
                return false;
            }
        } else {
            echo "ERRO: Nenhum template (php.ini-production/development) encontrado em '$iniDir'." . PHP_EOL;
            return false;
        }
    }

    echo "Lendo o arquivo de configuração: $iniFile" . PHP_EOL;
    $content = file_get_contents($iniFile);
    $originalContent = $content;
    $activated_extensions = [];

    foreach ($extensions as $ext) {
        $pattern = "/^;?\s*extension\s*=\s*{$ext}/im";
        $replacement = "extension={$ext}";

        if (preg_match("/^;\s*extension\s*=\s*{$ext}/im", $content)) {
            $content = preg_replace($pattern, $replacement, $content);
            $activated_extensions[] = $ext;
        } elseif (!preg_match("/^extension\s*=\s*{$ext}/im", $content)) {
            $content .= PHP_EOL . $replacement;
            $activated_extensions[] = $ext;
        }
    }

    if ($content !== $originalContent) {
        if (is_writable($iniFile)) {
            file_put_contents($iniFile, $content);
            echo " -> As seguintes extensões foram ativadas/adicionadas: " . implode(', ', $activated_extensions) . "." . PHP_EOL;
            echo PHP_EOL . "Arquivo 'php.ini' atualizado com sucesso!" . PHP_EOL;
            return true;
        } else {
            echo "Erro: Não foi possível escrever no arquivo '$iniFile'. Verifique as permissões." . PHP_EOL;
            return false;
        }
    } else {
        echo PHP_EOL . "Todas as extensões necessárias já parecem estar ativas." . PHP_EOL;
        return true;
    }
}

echo "--- Script de Configuração do PHP para o Projeto ---" . PHP_EOL;

if (configure_php_ini($requiredExtensions)) {
    echo "--------------------------------------------------------" . PHP_EOL;
    echo "Lembre-se de FECHAR e REABRIR seu terminal para que as alterações tenham efeito." . PHP_EOL;
    exit(0);
} else {
    exit(1);
}