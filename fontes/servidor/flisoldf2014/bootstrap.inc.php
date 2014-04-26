<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once 'vendor/autoload.php';
require 'lib/piwik/PiwikTracker.php';

// Cria uma simples configuração "padrão" do Doctrine ORM para as anotações (Annotation)
$estaEmDesenvolvimento = true;
$configuracao = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . '/domain'), $estaEmDesenvolvimento);

$opcoesDaConexao = array(
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'user' => 'netinhoi_flisol',
    'password' => 'DywnrHvn8Wpdid2saq',
    'dbname' => 'netinhoi_flisoldf2014'
);

$gerenciadorDeEntidades = EntityManager::create($opcoesDaConexao, $configuracao);

$caminhoFotoOriginal = __DIR__ . '/data/foto/original/';
$caminhoFotoAmostra = __DIR__ . '/data/foto/amostra/';

// Análise do Tráfego
$piwikTracker = new PiwikTracker(19, 'http://trafego.francisco.pro');
$piwikTracker->setUserAgent($_SERVER['HTTP_USER_AGENT']);
$idioma = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
$piwikTracker->setBrowserLanguage($idioma[0]);
unset($idioma);
$params = convert_url_query($_SERVER['REQUEST_URI']);
if (array_key_exists('localTime', $params)) {
    $piwikTracker->setLocalTime($params['localTime']);
}
if ((array_key_exists('screenWidth', $params)) && (array_key_exists('screenHeight', $params))) {
    $piwikTracker->setResolution($params['screenWidth'], $params['screenHeight']);
}

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
