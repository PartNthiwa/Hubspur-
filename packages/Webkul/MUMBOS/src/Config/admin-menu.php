<?php

return [

    // Root: Membership
    [
        'key'   => 'mumbos',
        'name'  => 'Membership',
        'route' => 'admin.mumbos.index',
        'sort'  => 8,
        'icon'  => 'icon-customer-2',
    ],

    // ├── Membership Types
    [
        'key'    => 'mumbos.membership-types',
        'name'   => 'Membership Types',
        'route'  => 'admin.membership-types.index',
        'sort'   => 1,
        'icon'   => '',
        'parent' => 'mumbos',
    ],

    // ├── Shareholders
    [
        'key'    => 'mumbos.shareholders',
        'name'   => 'Shareholders',
        'route'  => 'admin.shareholders.index',
        'sort'   => 2,
        'icon'   => '',
        'parent' => 'mumbos',
    ],

   

    // │   └── Shares
    [
        'key'    => 'mumbos.shareholders.shares',
        'name'   => 'Shares Allocation',
        'route'  => 'admin.shares.index',
        'sort'   => 1,
        'icon'   => '',
        'parent' => 'mumbos.shareholders',
    ],
     // │   ├── Contact Messages
    [
        'key'    => 'mumbos.shareholders.contacts',
        'name'   => 'Contact Messages',
        'route'  => 'admin.shareholders.contact-us',
        'sort'   => 2,
        'icon'   => '',
        'parent' => 'mumbos.shareholders',
    ],

    // ├── Contributions
    [
        'key'    => 'mumbos.contributions',
        'name'   => 'Contributions',
        'route'  => 'admin.contributions.index',
        'sort'   => 3,
        'icon'   => '',
        'parent' => 'mumbos',
    ],



    // │   └── Contribution Phase
    [
        'key'    => 'mumbos.contributions.phases',
        'name'   => 'Contribution Phase',
        'route'  => 'admin.phases.index',
        'sort'   => 1,
        'icon'   => '',
        'parent' => 'mumbos.contributions',
    ],
        // │   ├── Incentives
    [
        'key'    => 'mumbos.contributions.incentives',
        'name'   => 'Incentives',
        'route'  => 'admin.incentives.index',
        'sort'   => 2,
        'icon'   => '',
        'parent' => 'mumbos.contributions',
    ],
     // │   └── Leadrship
    [
        'key'    => 'mumbos.leaders',
        'name'   => 'Leadership',
        'route'  => 'admin.leaders.index',
        'sort'   => 1,
        'icon'   => '',
        'parent' => 'mumbos',
    ],
     // │   └── Teams
    [
        'key'    => 'mumbos.teams',
        'name'   => 'Teams',
        'route'  => 'admin.teams.index',
        'sort'   => 1,
        'icon'   => '',
        'parent' => 'mumbos',
    ],
];
