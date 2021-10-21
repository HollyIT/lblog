<?php

return [
    'groups' => [
        'default' => [
            'disk' => 'public',
            'path' => 'images',
            'formats' => [
                'thumb' => [
                    'mode' => 'scale',
                    'disk' => 'public',
                    'width' => 150,
                    'height' => 200,
                ],
                'front' => [
                    'mode' => 'fit',
                    'upscale' => 'true',
                    'disk' => 'public',
                    'width' => 474,
                    'height' => 192,
                ],
                'page' => [
                    'mode' => 'fit',
                    'upscale' => 'true',
                    'disk' => 'public',
                    'width' => 1600,
                    'height' => 900,
                ],
            ],

        ],
        'avatars' => [
            'disk' => 'public',
            'path' => 'avatars',
            'formats' => [
                'thumb' => [
                    'disk' => 'public',
                    'mode' => 'scale',
                    'width' => 100,
                    'height' => 100,
                ],
            ],

        ],
    ],
];
