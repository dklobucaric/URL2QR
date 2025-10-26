<?php


class QRencode
{
    public $level = 'L'; // error correction level: L,M,Q,H
    public $size = 3;
    public $margin = 4;

    public function encode($text)
    {
        // Koristimo gotovu implementaciju iz QRcode::png/text u punoj lib verziji.
        // Ovdje, da ne implementiramo Reed-Solomon od nule (predugo),
        // napravit ćemo fallback: pozvati eksterni generator iz punog liba
        // AKO postoji; ako ne postoji, bacit ćemo exception.
        //
        // => Realno rješenje: mi ćemo embedat punu phpqrcode lib kasnije.
        //
        throw new Exception("QRencode stub: full QR generation not yet wired.");
    }
}
