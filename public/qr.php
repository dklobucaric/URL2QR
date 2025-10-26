<?php
// public/qr.php
// TODO: Will generate QR as PNG
require_once __DIR__ . '/../src/QrMatrix.php';
require_once __DIR__ . '/../src/QrRender.php';

// 1. Read parameters
$text  = $_GET['text']  ?? '';
$color = $_GET['color'] ?? '000000';
$size  = intval($_GET['size'] ?? 300);

// TODO: validate & sanitize inputs
// TODO: generate QR and output image/png
?>
