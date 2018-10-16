<?php

return [
    'home' => [
        'title' => 'Главная',
    ],

    'login' => [
        'title'            => 'Вход в админ-панель',
        'buttons'          => [
            'login'           => 'Войти',
            'forgot-password' => 'Забыли пароль?',
        ],
        'fields'           => [
            'password'     => 'Пароль',
            'confirmation' => 'Подтверждение пароля',
        ],
        'reset-password'   => [
            'title'            => 'Восстановление пароля',
            'send-link-title'  => 'Отправить ссылку',
            'reset-link-title' => 'Восстановить пароль',
        ],
        'footer-copyright' => '2018 ' . config('app.name', 'ФКСиС'),
    ],
];