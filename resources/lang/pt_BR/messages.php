<?php

return [
    'welcome' => 'Bem-vindo ao :app_name',
    'greeting' => 'Olá, :name!',
    'validation' => [
        'required' => 'O campo :attribute é obrigatório.',
        'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
        'min' => [
            'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'numeric' => 'O campo :attribute deve ser pelo menos :min.',
        ],
        'max' => [
            'string' => 'O campo :attribute não pode ter mais que :max caracteres.',
            'numeric' => 'O campo :attribute não pode ser maior que :max.',
        ],
    ],
    'auth' => [
        'failed' => 'Estas credenciais não correspondem aos nossos registros.',
        'throttle' => 'Muitas tentativas de login. Por favor, tente novamente em :seconds segundos.',
    ],
    'pagination' => [
        'previous' => '&laquo; Anterior',
        'next' => 'Próximo &raquo;',
    ],
    'user' => [
        'created' => 'Usuário criado com sucesso!',
        'updated' => 'Usuário atualizado com sucesso!',
        'deleted' => 'Usuário excluído com sucesso!',
        'not_found' => 'Usuário não encontrado.',
    ],
];
