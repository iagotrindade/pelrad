<?php

return [
    'empty' => [
        'title' => "Nenhum arquivo ou Pastas encontrados",
    ],
    'folders' => [
        'title' => 'Arquivos',
        'single' => 'Pasta',
        'columns' => [
            'name' => 'Nome',
            'collection' => 'Coleção',
            'description' => 'Descrição',
            'is_public' => 'É Pública',
            'has_user_access' => 'Has User Access',
            'users' => 'Usuários',
            'icon' => 'Icone',
            'color' => 'Cor',
            'is_protected' => 'É Protegida',
            'password' => 'Senha',
            'password_confirmation' => 'Confirmação Senha',
        ],
        'group' => 'Storage',
    ],
    'media' => [
        'title' => 'Arquivo',
        'single' => 'Arquivo',
        'columns' => [
            'image' => 'Imagem',
            'model' => 'Model',
            'collection_name' => 'Nome da Coleção',
            'size' => 'Tamanho',
            'order_column' => 'Ordem',
        ],
        'actions' => [
            'sub_folder'=> [
              'label' => "Criar Sub Pasta"
            ],
            'create' => [
                'label' => 'Adicionar Arquivo',
                'form' => [
                    'file' => 'Arquivo',
                    'title' => 'Título',
                    'description' => 'Descrição',
                ],
            ],
            'delete' => [
                'label' => 'Excluir Pasta',
            ],
            'edit' => [
                'label' => 'Editar Pasta',
            ],
        ],
        'notifications' => [
            'create-media' => 'Arquivo criado com sucesso',
            'delete-folder' => 'Pasta excluída com sucesso',
            'edit-folder' => 'Pasta editada com sucesso',
        ],
        'meta' => [
            'model' => 'Model',
            'file-name' => 'Nome do Arquivo',
            'type' => 'Tipo',
            'size' => 'Tamanho',
            'disk' => 'Disco',
            'url' => 'URL',
            'delete-media' => 'Excluir Arquivo',
        ],
    ],
];
