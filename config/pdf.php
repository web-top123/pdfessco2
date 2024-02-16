<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default configuration for PDF
    |--------------------------------------------------------------------------
    |
    | App/Services/Pdf
    |
    */

    'cover' => [

        'color' => ['r' => 0, 'g' => 0, 'b' => 0],
        'font-size' => 18,
        'line-height' => 25,
        'margin-top' => 80,
        'title' => [
            'color' => ['r' => 0, 'g' => 0, 'b' => 246],
            'font-size' => 20,
            'font-family' => 'helveticaB',
        ],

    ],

    'divider' => [

        'color' => ['r' => 0, 'g' => 0, 'b' => 246],
        'font-size' => 36,
        'line-height' => 13,
        'margin-top' => 16,

    ],

    'folder' => 'created',

    'font-family' => 'helvetica',

    'footer' => [

        'color' => ['r' => 102, 'g' => 102, 'b' => 102],
        'font-size' => 11,
        'line-height' => 5,
        'border-width' => 0.1,
        'margin-bottom' => 9.5,
        'margin-side' => 10,
        'height' => 15,
    ],

    'header' => [

        'color' => ['r' => 102, 'g' => 102, 'b' => 102],
        'font-size' => 11,
        'line-height' => 5,
        'border-width' => 0.1,
        'margin-top' => 5,
        'margin-side' => 10,
        'height' => 20,

    ],

    'template' => 'templates/PDF-Style-Compressed.pdf'


];
