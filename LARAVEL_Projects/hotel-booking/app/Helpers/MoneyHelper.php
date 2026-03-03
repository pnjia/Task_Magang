<?php

if (!function_exists('format_idr')) {
    function format_idr(float|int $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}