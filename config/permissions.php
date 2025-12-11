<?php

return [
    'roles' => [
        'superadmin' => [
            'name' => 'Super Admin',
            'permissions' => [
                'farms.create',
                'farms.edit',
                'farms.delete',
                'users.manage',
                'harvests.view_all',
            ],
        ],
        'manager' => [
            'name' => 'Farm Manager',
            'permissions' => [
                'harvests.create',
                'harvests.edit',
                'harvests.delete',
                'harvests.view',
                'inventory.manage',
            ],
        ],
        'worker' => [
            'name' => 'Farm Worker',
            'permissions' => [
                'harvests.create',
                'harvests.view',
            ],
        ],
    ],
];
