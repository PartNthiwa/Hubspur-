<?php

return [

    /**
     * Root Menu: Membership
     */
    [
        'key'   => 'mumbos',
        'name'  => 'Membership',
        'route' => 'admin.mumbos.index',
        'sort'  => 8,
        'icon'  => 'icon-customer-2',
    ],

    // Level 1: Membership Types (child of mumbos)
    [
        'key'   => 'mumbos.membership-types',
        'name'  => 'Membership Types',
        'route' => 'admin.membership-types.index',
        'sort'  => 1,
        'icon'  => '',
    ],

    // Level 1: Shareholders (child of mumbos)
    [
        'key'   => 'mumbos.shareholders',
        'name'  => 'Shareholders',
        'route' => 'admin.shareholders.index',
        'sort'  => 2,
        'icon'  => '',
    ],

    // Level 2: Contact Messages (child of shareholders)
    [
        'key'   => 'mumbos.shareholders.contacts',
        'name'  => 'Contact Messages',
        'route' => 'admin.shareholders.contact-us',
        'sort'  => 1,
        'icon'  => '',
    ],

    // Level 1: Contributions (child of mumbos)
    [
        'key'   => 'mumbos.contributions',
        'name'  => 'Contributions',
        'route' => 'admin.contributions.index',
        'sort'  => 3,
        'icon'  => '',
    ],

    // Level 2: Incentives (child of contributions)
    [
        'key'   => 'mumbos.contributions.incentives',
        'name'  => 'Incentives',
        'route' => 'admin.incentives.index',
        'sort'  => 1,
        'icon'  => '',
    ],

    // Level 2: Phases (child of contributions)
    [
        'key'   => 'mumbos.contributions.phases',
        'name'  => 'Contribution Phase',
        'route' => 'admin.phases.index',
        'sort'  => 2,
        'icon'  => '',
    ],
];
