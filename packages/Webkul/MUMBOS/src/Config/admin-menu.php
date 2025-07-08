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
        'key'   => 'mumbos.mebership-types',
        'name'  => 'Membership Type',
        'route' => 'admin.membership-types.index',
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
        'sort'  => 13,
    ],
    [
        'key'   => 'mumbos.phases',
        'name'  => 'Contribution Phases',
        'route' => 'admin.phases.index',
        'icon'  => '',
        'sort'  => 14,
    ],

  

     [
        'key'   => 'mumbos.incentives',
        'name'  => 'Incentives',
        'route' => 'admin.incentives.index',
        'icon'  => '',
        'sort'  => 15,
    ],
];
