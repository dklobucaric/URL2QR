<?php
require_once __DIR__ . '/../src/QrMatrix.php';

$text = isset($_GET['text']) ? $_GET['text'] : 'https://dd-lab.hr';
$matrix = QrMatrix::fromText($text);

header('Content-Type: text/plain; charset=utf-8');

echo "Matrix rows: " . count($matrix) . "\n";
echo "Matrix cols: " . (count($matrix) ? count($matrix[0]) : 0) . "\n\n";

foreach ($matrix as $rowIndex => $row) {
    foreach ($row as $cell) {
        echo $cell ? "██" : "  ";
    }
    echo "\n";
    if ($rowIndex > 40) {
        echo "\n... (truncated)\n";
        break;
    }
}
