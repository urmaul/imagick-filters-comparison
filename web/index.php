<?php
include __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\Slim();
$app->config('debug', true);

$app->get('/', function () use ($app) {
    echo '/:url(/:width)';
});

$app->get('/:url(/:width)', function ($url) use ($app, $width = 200) {
    $imageBlob = HttpClient::from()->get($url);
    
    $imagick = new Imagick();
    $imagick->readImageBlob($imageBlob);
    
    $filters = [
        'scale' => 'scale',
        'POINT' => Imagick::FILTER_POINT,
        'BOX' => Imagick::FILTER_BOX,
        'TRIANGLE' => Imagick::FILTER_TRIANGLE,
        'HERMITE' => Imagick::FILTER_HERMITE,
        'HANNING' => Imagick::FILTER_HANNING,
        'HAMMING' => Imagick::FILTER_HAMMING,
        'BLACKMAN' => Imagick::FILTER_BLACKMAN,
        'GAUSSIAN' => Imagick::FILTER_GAUSSIAN,
        'QUADRATIC' => Imagick::FILTER_QUADRATIC,
        'CUBIC' => Imagick::FILTER_CUBIC,
        'CATROM' => Imagick::FILTER_CATROM,
        'MITCHELL' => Imagick::FILTER_MITCHELL,
        'BESSEL' => Imagick::FILTER_BESSEL,
        'SINC' => Imagick::FILTER_SINC,
        'LANCZOS' => Imagick::FILTER_LANCZOS,
    ];
    
    $n = 20;
    foreach ($filters as $name => $filter) {
        $t1 = microtime(true);
        for ($i = 0; $i<$n; $i++) {
            $im = $imagick->clone();
            if ($filter === 'scale') {
                $im->scaleImage(width, 0);

            } else {
                $im->resizeImage(width, 0, $filter, 1);
            }
        }
        $t2 = microtime(true);
        $spent = round(($t2 - $t1)/$n*1000, 1);
        
        echo '<div style="display: inline-block;">'.$name.' ('.$spent.')<br/><img src="data:image/' . $imagick->getImageFormat() . ';base64,' . base64_encode($im->getImageBlob()) . '"/></div>';
    }
});

$app->run();
