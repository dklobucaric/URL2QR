<?php
require_once __DIR__ . '/../src/QrMatrix.php';
require_once __DIR__ . '/../src/QrRender.php';

$text  = $_GET['text']  ?? '';
$color = $_GET['color'] ?? '000000';
$size  = isset($_GET['size']) ? intval($_GET['size']) : 300;

if ($size < 100) $size = 100;
if ($size > 2000) $size = 2000;

if (strlen($text) === 0) {
    QrRender::outputPng([], $color, $size);
    exit;
}

$matrix = QrMatrix::fromText($text);

QrRender::outputPng($matrix, $color, $size);
exit;
