<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Plugin Options
    |--------------------------------------------------------------------------
    */
    'key' => env('GMAP_API', ''),

    'default_location' => [
        'lat' => 40.6449329,
        'lng' => 22.9416259,
    ],

    'default_zoom' => 8,

    'default_draggable' => true,

    'default_clickable' => true,

    'default_height' => '400px',

    'my_location_button' => 'My location',
];
