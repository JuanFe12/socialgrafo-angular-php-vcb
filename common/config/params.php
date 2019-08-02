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
            'fk_field' => 'wp_userprofile.fk_user_id'
        ],
        /*
        [
            'table' => 'ext_family_members',
            'field' => 'fk_user_id',
            'fk_field' => 'wp_userprofile.id'
        ],
        /** */
        /*
        [
            'table' => 'ext_internal_trajectory',
            'field' => 'fk_user_id',
            'fk_field' => 'wp_userprofile.id'
        ],
        /** */
        /*
        [
            'table' => 'ext_languages',
            'field' => 'fk_user_id',
            'fk_field' => 'wp_userprofile.id'
        ],
        /** */
        /*
        [
            'table' => 'ext_education_level',
            'field' => 'fk_user_id',
            'fk_field' => 'wp_userprofile.id'
        ],
        /** */
        /*
        [
            'table' => 'ext_trajectory_gerency',
            'field' => 'id',
            'fk_field' => 'ext_internal_trajectory.gerency_id'
        ],
        [
            'table' => 'ext_trajectory_position',
            'field' => 'id',
            'fk_field' => 'ext_internal_trajectory.position_id'
        ],
        /** */
    ]
];
