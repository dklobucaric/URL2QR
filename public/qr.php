<?php
require_once __DIR__ . '/../src/QrMatrix.php';
require_once __DIR__ . '/../src/QrRender.php';

// 1. parametri iz URL-a
$text  = $_GET['text']  ?? '';
$color = $_GET['color'] ?? '000000';
$size  = isset($_GET['size']) ? intval($_GET['size']) : 300;

// sigurnosne granice
if ($size < 100) $size = 100;
if ($size > 2000) $size = 2000;

if (strlen($text) === 0) {
    // nema sadržaja -> prikaži fallback PNG (nije error 500, nego vizualni hint)
    QrRender::outputPng([], $color, $size);
    exit;
}

// 2. generiraj matricu
$matrix = QrMatrix::fromText($text);

// 3. renderiraj PNG sa zadanim parametrom boje
QrRender::outputPng($matrix, $color, $size);
exit;
