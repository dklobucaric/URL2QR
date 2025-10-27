<?php
require_once __DIR__ . '/phpqrcode.php';

class QrMatrix
{
    /**
     * Generate a 2D matrix (array[y][x]) of 0/1 modules for given text.
     * 1 = dark module
     * 0 = light module
     */
    public static function fromText(string $text): array
    {
        // QRcode::text($text, $outfile = false, $level = 'L', $size = 3, $margin = 4)
        // U tvojoj libi vidjeli smo da QRtools::tcpdfBarcodeArray() zove samo:
        // QRcode::text($code, false, $eccLevel);
        //
        // Dakle zovemo na isti način.
        //
        // Ovo bi trebalo vratiti niz stringova, npr:
        // [
        //   "101001010101...",
        //   "110010010001...",
        //   ...
        // ]
        // Svaki char '1' znači crni modul.
        // Svaki char '0' znači bijeli modul.

        $eccLevel = 'L'; // low ECC, dovoljno za normalan QR
        $lines = QRcode::text($text, false, $eccLevel);

        // Safety: ako ne dobijemo ništa, vratimo prazno
        if (!is_array($lines) || count($lines) === 0) {
            return [];
        }

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
