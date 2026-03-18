<?php
return [
    'server_key'    => env('SB-Mid-server-Mtm2IJEA7vANdKMD3Dyt_PbY'),
    'client_key'    => env('SB-Mid-client-0svOnWVYf5XUY8e9'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'snap_url'      => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',
];