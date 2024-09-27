<?php

return [
    "title" => "Missões",
    "single" => "Missão",
    "group" => "Conteudo",
    "pages" => [
        "groups" => "Gerenciar Grupos de Missões",
        "status" => "Gerenciar Status de Missões"
    ],
    "columns" => [
        "title" => "Título",
        "body" => "Corpo",
        "date" => "Data",
        "time" => "Hora",
        "is_pined" => "Fixado",
        "is_public" => "Público",
        "icon" => "Icone",
        "background" => "Fundo",
        "border" => "Borda",
        "color" => "Cor",
        "font_size" => "Tamanho da Fonte",
        "font" => "Fonte",
        "group" => "Grupo",
        "status" => "Status",
        "user_id" => "Id do Usuario",
        "user_type" => "Tipo do Usuario",
        "model_id" => "Id do Modelo",
        "model_type" => "Tipo do Modelo",
        "created_at" => "Criado em",
        "updated_at" => "Atualizado em"
    ],
    "tabs" => [
        "general" => "Geral",
        "style" => "Estilo"
    ],
    "actions" => [
        "view" => "Ver",
        "edit" => "Editar",
        "delete" => "Excluir",
        "notify" => [
            "label" => "Notificar Usuário",
            "notification" => [
                "title" => "Notificação Enviada",
                "body" => "A Notificação foi enviada."
            ]
        ],
        "share" => [
            "label" => "Compartilhar Nota",
            "notification" => [
                "title" => "O link de compartilhamento foi criado",
                "body" => "O link de compartilhamento foi criado e copiado para a área de transferência"
            ]
        ],
        "user_access" => [
            "label" => "Acceso de Usuário",
            "form" => [
                "model_id" => "Usuários",
                "model_type" => "Tipo de Usuário",
            ],
            "notification" => [
                "title" => "Acceso de Usuário Atualizado",
                "body" => "O acceso de usuário foi atualizado."
            ]
        ],
        "checklist"=> [
            "label" => "Adicionar Lista de Tarefas",
            "form" => [
                "checklist"=> "Lista de Tarefas"
            ],
            "state" => [
                "done" => "Feito",
                "pending" => "Pendente"
            ],
            "notification" => [
                "title" => "Lista de Tarefas Atualizada",
                "body" => "A lista de tarefas foi atualizada.",
                "updated" => [
                    "title" => "Elemento da Lista de Tarefas Atualizado",
                    "body" => "O elemento da lista de tarefas foi atualizado."
                ],
            ]
        ]
    ],
    "notifications" => [
        "edit" => [
            "title" => "Missão Atualizada",
            "body" => "A missão foi atualizada."
        ],
        "delete" => [
            "title" => "Missão Excluída",
            "body" => "A missão foi excluída."
        ]
    ]
];
