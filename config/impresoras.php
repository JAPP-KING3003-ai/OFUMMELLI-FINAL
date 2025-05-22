<?php
// Este archivo debe estar en config/impresoras.php
// Cambia la IP y puerto al de tu impresora de red
// Nombre original: E-Pos printer driver

//  return [
//      'comandera_80mm' => [
//          'type' => 'network',
//          'ip'   => '192.168.1.87', // <-- Cambia esto por la IP de tu impresora
//          'port' => 9100,            // 9100 es el puerto estándar de impresoras térmicas de red
//      ],
//  ];




return [
    'churuata' => [
        'network' => [
            'type' => 'network',
            'ip'   => '192.168.1.87', // Cambia por la IP real de Churuata
            'port' => 9100,
        ],
        'usb' => [
            'type' => 'windows',
            'win_name' => 'churuata', // Debe coincidir con el nombre EXACTO en Windows
        ],
        'width' => 80,
    ],
    'salon_principal' => [
        'network' => [
            'type' => 'network',
            'ip'   => '192.168.1.88', // Cambia por la IP real de Salón Principal
            'port' => 9100,
        ],
        'usb' => [
            'type' => 'windows',
            'win_name' => 'salon_principal',
        ],
        'width' => 80,
    ],
    'POS-58' => [
        'network' => [
            'type' => 'network',
            'ip'   => '192.168.1.89', // Cambia por la IP real de la POS-58
            'port' => 9100,
        ],
        'usb' => [
            'type' => 'windows',
            'win_name' => 'POS-58',
        ],
        'width' => 58,
    ],
];