<?php
// public/download.php
// Generate QR PNG and force download with Content-Disposition

require_once __DIR__ . '/../src/QrMatrix.php';
require_once __DIR__ . '/../src/QrRender.php';

// Read params
$text  = (string)($_GET['text']  ?? '');
$color = (string)($_GET['color'] ?? '000000');
$size  = isset($_GET['size']) ? intval($_GET['size']) : 300;
$fname = (string)($_GET['filename'] ?? 'qrcode.png');

// Basic validation / bounds
if ($size < 100) $size = 100;
if ($size > 2000) $size = 2000;

// sanitize filename: allow letters, numbers, dash, underscore, dot
$fname = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $fname);
if (trim($fname) === '') $fname = 'qrcode.png';

// Ensure correct extension
if (strtolower(pathinfo($fname, PATHINFO_EXTENSION)) !== 'png') {
    $fname .= '.png';
}

// require text
if ($text === '') {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: text/plain; charset=utf-8');
    echo "Missing required parameter: text";
    exit;
}

// Generate matrix
$matrix = QrMatrix::fromText($text);
if (!is_array($matrix) || count($matrix) === 0) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: text/plain; charset=utf-8');
    echo "Failed to generate QR matrix";
    exit;
}

// Prepare image in-memory (same logic as QrRender, but buffer the PNG)
if (!preg_match('/^[0-9a-fA-F]{6}$/', $color)) {
    $color = '000000';
}
$modules = count($matrix);
$pixelPerModule = floor($size / $modules);
if ($pixelPerModule < 1) $pixelPerModule = 1;
$imgSize = $modules * $pixelPerModule;

$img = imagecreatetruecolor($imgSize, $imgSize);
$white = imagecolorallocate($img, 255, 255, 255);
$r = hexdec(substr($color, 0, 2));
$g = hexdec(substr($color, 2, 2));
$b = hexdec(substr($color, 4, 2));
$dotColor = imagecolorallocate($img, $r, $g, $b);
imagefill($img, 0, 0, $white);

for ($y = 0; $y < $modules; $y++) {
    for ($x = 0; $x < $modules; $x++) {
        if ($matrix[$y][$x] === 1) {
            imagefilledrectangle(
                $img,
                $x * $pixelPerModule,
                $y * $pixelPerModule,
                ($x + 1) * $pixelPerModule - 1,
                ($y + 1) * $pixelPerModule - 1,
                $dotColor
            );
        }
    }
}

// buffer PNG
ob_start();
imagepng($img);
$pngData = ob_get_clean();
imagedestroy($img);

// Send headers for forced download
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="' . basename($fname) . '"');
header('Content-Length: ' . strlen($pngData));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

echo $pngData;
exit;
