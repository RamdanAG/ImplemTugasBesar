<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExport
{
    // kirim array baris sebagai file CSV yang langsung terunduh
    public static function unduh(string $namaFile, array $baris): StreamedResponse
    {
        return response()->streamDownload(function () use ($baris) {
            $out = fopen('php://output', 'w');

            // penanda UTF-8 supaya Excel membaca huruf dengan benar
            fwrite($out, "\xEF\xBB\xBF");

            foreach ($baris as $b) {
                $b = array_map(fn ($nilai) => self::aman($nilai), $b);
                // argumen terakhir dikosongkan, wajib eksplisit di PHP 8.4 ke atas
                fputcsv($out, $b, ';', '"', '');
            }

            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // teks yang diawali = + - @ bisa dibaca Excel sebagai rumus, jadi diberi tanda petik di depan
    private static function aman($nilai)
    {
        if (is_string($nilai) && $nilai !== '' && in_array($nilai[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'" . $nilai;
        }

        return $nilai;
    }
}