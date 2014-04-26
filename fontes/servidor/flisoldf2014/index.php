<?php

header('Access-Control-Allow-Origin: *');
require 'bootstrap.inc.php';

/**
 * Cria a aplicação.
 */
$aplicativo = new \Slim\Slim();

$aplicativo->get('/', function () {
    global $piwikTracker;

    header('Content-Type: text/html; charset=utf-8');

    require 'galeria.html';

    $piwikTracker->doTrackPageView('Página principal');
});

$aplicativo->post('/foto/cadastra', function() use ($aplicativo) {
    global $gerenciadorDeEntidades, $caminhoFotoOriginal, $caminhoFotoAmostra, $piwikTracker;

    if (!file_exists($caminhoFotoOriginal)) {
        mkdir($caminhoFotoOriginal, 0755, true);
    }

    if (!file_exists($caminhoFotoAmostra)) {
        mkdir($caminhoFotoAmostra, 0755, true);
    }

    $aplicativo->response->headers->set('Content-Type', 'application/json; charset=utf-8');

    $requisicao = $aplicativo->request();

    // TODO: Validações

    $foto = new Foto();
    $foto->cadastro = new \DateTime('now');
    $foto->nome = $requisicao->post('nome');
    $foto->autor = $requisicao->post('autor');
    $foto->arquivo = md5(uniqid(rand(), true));
    $foto->latitude = $requisicao->post('latitude');
    $foto->longitude = $requisicao->post('longitude');

    $fotoOriginal = $caminhoFotoOriginal . $foto->arquivo . '.jpg';

    $decodificado = base64_decode($requisicao->post('conteudo'));
    file_put_contents($fotoOriginal, $decodificado);

    // cria a imagem de amostra
    $fotoAmostra = PHPImageWorkshop\ImageWorkshop::initFromPath($fotoOriginal);

    // obtém um quadrado (o parâmetro 'MM' diz que é no meio da imagem) o máximo possível
    $fotoAmostra->cropMaximumInPixel(0, 0, 'MM');

    // redimensiona o quadrado da imagem para um tamanho menor
    $fotoAmostra->resizeInPixel(75, 75);

    // salva a amostra da imagem
    $fotoAmostra->save($caminhoFotoAmostra, $foto->arquivo . '.jpg', true, null, 95);

    $gerenciadorDeEntidades->persist($foto);
    $gerenciadorDeEntidades->flush();

    $aplicativo->response->setBody(json_encode($foto->id));

    $piwikTracker->doTrackPageView('Cadastro de Foto');
});

$aplicativo->get('/foto/lista', function() use ($aplicativo) {
    global $gerenciadorDeEntidades, $piwikTracker;

    $aplicativo->response->headers->set('Content-Type', 'application/json; charset=utf-8');

    $repositorioDasFotos = $gerenciadorDeEntidades->getRepository('Foto');
    $fotos = $repositorioDasFotos->findAll();

    $encontrados = array();
    foreach ($fotos as $foto) {
        $foto->cadastro = $foto->cadastro->format('d/m/Y H:i\h');
        $encontrados[] = $foto;
    }

    $aplicativo->response->setBody(json_encode($encontrados));

    $piwikTracker->doTrackPageView('Listagem de Foto');
});

/**
 * Executa a aplicação.
 */
$aplicativo->run();
