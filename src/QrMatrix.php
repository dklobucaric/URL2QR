<?php
require_once __DIR__ . '/phpqrcode.php';

class QrMatrix
{

    // Vrati dvodimenzionalni array 0/1
    public static function fromText(string $text): array
    {
        // output buffering jer QRcode::text() ispisuje ASCII
        ob_start();
        // QR_ECLEVEL_L = low error correction, dosta za standardne QR-ove
        QRcode::text($text, false, QR_ECLEVEL_L);
        $raw = ob_get_clean();

        // svaka linija u $raw predstavlja red "piksela", npr "101001010"
        $lines = explode("\n", trim($raw));

        $matrix = [];
        foreach ($lines as $line) {
            $row = [];
            $chars = str_split(trim($line));
            foreach ($chars as $ch) {
                $row[] = ($ch === '1') ? 1 : 0;
            }
            if (count($row) > 0) {
                $matrix[] = $row;
            }
        }

        return $matrix;
    }
}
