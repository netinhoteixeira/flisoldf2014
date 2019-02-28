<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once 'vendor/autoload.php';

// Cria uma simples configuração "padrão" do Doctrine ORM para as anotações (Annotation)
$estaEmDesenvolvimento = true;
$configuracao = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . '/domain'), $estaEmDesenvolvimento);

$opcoesDaConexao = array(
    'driver' => 'pdo_mysql',
    'host' => '*****',
    'user' => '*****',
    'password' => '******',
    'dbname' => '******'
);

$gerenciadorDeEntidades = EntityManager::create($opcoesDaConexao, $configuracao);

$caminhoFotoOriginal = __DIR__ . '/data/foto/original/';
$caminhoFotoAmostra = __DIR__ . '/data/foto/amostra/';

/**
 * Returns the url query as associative array
 *
 * @param    string    query
 * @return    array    params
 */
function convert_url_query($query) {
    $queryParts = explode('&', $query);

    $params = array();
    foreach ($queryParts as $param) {
        $item = explode('=', $param);
        if (isset($item[1])) {
            $params[$item[0]] = $item[1];
        }
    }

    return $params;
}
