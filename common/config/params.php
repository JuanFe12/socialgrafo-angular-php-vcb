<?php
return [
    'adminEmail' => 'racastellanos@vcb.com.co',
    'supportEmail' => 'racastellanos@vcb.com.co',
    'senderEmail' => 'racastellanos@vcb.com.co',
    'senderName' => 'Socialgrafo Administrador',
    'user.passwordResetTokenExpire' => 3600,
    'base_table' => 'wp_userprofile',
    'related_tables' => [
        [
            'table' => 'wp_users',
            'field' => 'id',
            'fk_field' => 'users_fk_id'
        ],
        [
            'table' => 'wp_users',
            'field' => 'id',
            'fk_field' => 'users_fk_id'
        ],
    ]
];
