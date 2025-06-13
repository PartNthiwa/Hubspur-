<?php

return [
    // Level-1
    [
        'key'   => 'mumbos',
        'name'  => 'MUMBO Memberships',
        'route' => 'admin.mumbos.index',
        'sort'  => 2,
        'icon'  => 'temp-icon',
    ],

    [
        'key' => 'mumbos.shares',
        'name' => 'Shares',
        'route' => 'admin.shares.index',
        'sort' => 3,
        'icon' => 'icon-table',
    ],
    [
        'key'   => 'mumbos.shareholders',
        'name'  => 'Shareholders',
        'route' => 'admin.shareholders.index',
        'icon'  => 'far fa-users',
        'sort'  => 4,
    ],

    [
        'key'   => 'mumbos.contributions',
        'name'  => 'Contributions',
        'route' => 'admin.contributions.index',
        'icon'  => 'far fa-chart-bar',
        'sort'  => 5,
    ],
   
];
