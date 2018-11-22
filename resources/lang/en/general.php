<?php

return [
    'dashboard' => [
        'section' => [
            'top_dropdown_menu' => [
                'home' => 'Dashboard',
            ],
            'user_view'         => [
                'title' => 'User Profile',
                'tabs'  => [
                    'settings' => 'Settings',
                    'projects' => 'Projects',
                ],
            ],
        ],
    ],

    'account' => [
        'section' => [
            'top_dropdown_menu' => [
                'home' => 'My Account',
            ],
        ],
    ],

    'common' => [
        'section' => [
            'top_dropdown_menu' => [
                'logout' => 'Logout',
            ],
        ],
        'headers' => [
            'action' => 'Action',
        ],
    ],

    'roles' => [
        'client' => 'Client',
        'admin'  => 'Administrator',
    ],
];