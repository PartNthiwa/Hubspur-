<?php

return [

    /**
     * Membership.
     */
    [
        'key'   => 'mumbos',
        'name'  => 'Membership',
        'route' => 'admin.mumbos.index',
        'sort'  => 8,
        'icon'  => 'icon-customer-2',
    ],

    /**
     * Shares.
     */
    [
        'key'   => 'mumbos.shares',
        'name'  => 'Shares',
        'route' => 'admin.shares.index',
        'sort'  => 10,
        'icon'  => '',
    ],

    /**
     * Shareholders.
     */
    [
        'key'   => 'mumbos.shareholders',
        'name'  => 'Shareholders',
        'route' => 'admin.shareholders.index',
        'icon'  => '',
        'sort'  => 11,
    ],

    /**
     * Contributions.
     */
    [
        'key'   => 'mumbos.contributions',
        'name'  => 'Contributions',
        'route' => 'admin.contributions.index',
        'icon'  => '',
        'sort'  => 12,
    ],

];
