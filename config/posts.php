<?php

use App\Actions\Formatters\FormatRawText;
use App\Actions\Formatters\NL2BRFormatter;

return [
    'formats' => [
        'default' => [
            'label' => 'Default',
            'formatter' => NL2BRFormatter::class,
        ],
        'raw' => [
            'label' => 'Raw',
            'formatter' => FormatRawText::class,
        ],

    ],

    'default_format' => 'default',
];
