<?php

return [
    // Level-1
    [
        'key'   => 'mumbos',
        'name'  => 'MUMBOS',
        'route' => 'admin.mumbos.index',
        'sort'  => 2,
        'icon'  => 'temp-icon',
    ],

    // Level-2 container (no route, so clicking just toggles)
    [
        'key'   => 'mumbos.shareholders',
        'name'  => 'Shareholders',
        'route' => 'admin.shareholders.index',
        'icon'  => 'far fa-users',
        'sort'  => 600,
    ],

    // // Level-3 children under “Shareholders”
    // [
    //     'key'   => 'mumbos.shareholders.contact',
    //     'name'  => 'Contact Info',
    //     'route' => 'admin.shareholders.contact',
    //     'icon'  => 'far fa-plus-square',
    //     'sort'  => 601,
    // ],
   
    // Level-2 Contributions sibling
    [
        'key'   => 'mumbos.contributions',
        'name'  => 'Contributions',
        'route' => 'admin.contributions.index',
        'icon'  => 'far fa-chart-bar',
        'sort'  => 700,
    ],
];
