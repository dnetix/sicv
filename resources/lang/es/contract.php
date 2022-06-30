<?php

return [
    'view_title' => 'Contrato :id',

    'new_contract_card_title' => 'Nuevo Contrato',
    'new_contract_card_subtitle' => 'Generar un nuevo contrato a cliente',

    'information' => 'Información',
    'articles' => 'Artículos',
    'article_label' => 'Artículo',
    'article_type' => 'Tipo Artículo',
    'article_weight' => 'Peso',
    'article_amount' => 'Valor',

    'start_date' => 'Fecha inicio',
    'end_date' => 'Fecha finalización',
    'total' => 'Total',
    'extension' => 'Prorroga',
    'type' => 'Tipo Contrato',
    'percentage' => 'Porcentaje',
    'months' => 'Meses',
    'contract_months' => 'Meses contrato',

    \App\Models\Contracts\ContractStates::ACTIVE => 'Activo',
    \App\Models\Contracts\ContractStates::TERMINATED => 'Cancelado',
    \App\Models\Contracts\ContractStates::ENDED => 'Terminado',
    \App\Models\Contracts\ContractStates::ANNULLED => 'Anulado',
    \App\Models\Contracts\ContractStates::LEGALPROBLEM => 'Problema Legal',
    'note_submit' => 'Guardar nota',

    'note_title' => 'Nota contrato',

    'add_article' => 'Agregar artículo',
    'save' => 'Guardar contrato',
    'extension_amount' => 'Prorroga',
    'extension_store' => 'Guardar prorroga',

    'article_description' => 'Descripción del artículo',
];
