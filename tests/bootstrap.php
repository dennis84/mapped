<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Mapped\Tests', __DIR__ . '/../tests');

AnnotationRegistry::registerLoader([$loader, 'loadClass']);
