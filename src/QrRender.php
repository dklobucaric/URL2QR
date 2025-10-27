<?php

class QrRender
{

    public static function outputPng(array $matrix, string $colorHex, int $sizePx): void
    {
        if (!preg_match('/^[0-9a-fA-F]{6}$/', $colorHex)) {
            $colorHex = '000000';
        }

        $modules = count($matrix);

        if ($modules === 0) {
            self::outputFallback();
            return;
        }

        $pixelPerModule = floor($sizePx / $modules);
        if ($pixelPerModule < 1) $pixelPerModule = 1;

        $imgSize = $modules * $pixelPerModule;

        $img = imagecreatetruecolor($imgSize, $imgSize);

        // background bijelo
        $white = imagecolorallocate($img, 255, 255, 255);

        // boja QR-a iz color pickera
        $r = hexdec(substr($colorHex, 0, 2));
        $g = hexdec(substr($colorHex, 2, 2));
        $b = hexdec(substr($colorHex, 4, 2));
        $dotColor = imagecolorallocate($img, $r, $g, $b);

        // fill bijelom
        imagefill($img, 0, 0, $white);

        // crtanje: 1 znači popuni kvadratić
        for ($y = 0; $y < $modules; $y++) {
            $row = $matrix[$y];
            $cols = count($row);
            for ($x = 0; $x < $cols; $x++) {
                if ($row[$x] === 1) {
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

        header('Content-Type: image/png');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        imagepng($img);
        imagedestroy($img);
    }

    private static function outputFallback(): void
    {
        $img = imagecreatetruecolor(100, 100);
        $bg = imagecolorallocate($img, 255, 200, 200);
        $fg = imagecolorallocate($img, 120, 0, 0);
        imagefill($img, 0, 0, $bg);
        imagestring($img, 4, 10, 40, "ERR", $fg);

        header('Content-Type: image/png');
        imagepng($img);
        imagedestroy($img);
    }
}
