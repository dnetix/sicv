<?php

namespace App\View\Composers;

use App\Models\CompanySetting;
use Illuminate\View\View;

class NavigationComposer
{
    /**
     * Sidebar menu. Each section is either a direct link or a group of
     * children; `match` is the route pattern that marks it as active.
     */
    public function compose(View $view): void
    {
        $sections = [
            [
                'label' => 'Inicio',
                'route' => 'dashboard',
                'match' => 'dashboard',
            ],
            [
                'label' => 'Contratos',
                'match' => 'contracts.*|seals.*',
                'children' => [
                    ['label' => 'Nuevo contrato', 'route' => 'contracts.create'],
                    ['label' => 'Imprimir sellos', 'route' => 'seals.index'],
                ],
            ],
            [
                'label' => 'Clientes',
                'match' => 'clients.*',
                'children' => [
                    ['label' => 'Buscar clientes', 'route' => 'clients.index'],
                    ['label' => 'Nuevo cliente', 'route' => 'clients.create'],
                ],
            ],
            [
                'label' => 'Almacén / POS',
                'match' => 'store.*|sales.*',
                'children' => [
                    ['label' => 'Nueva venta', 'route' => 'sales.create'],
                    ['label' => 'Artículos a la venta', 'route' => 'store.index'],
                    ['label' => 'Nuevo artículo', 'route' => 'store.create'],
                ],
            ],
            [
                'label' => 'Gastos',
                'route' => 'expenses.index',
                'match' => 'expenses.*',
            ],
            [
                'label' => 'Reportes',
                'match' => 'reports.*',
                'children' => [
                    ['label' => 'Contratos vencidos', 'route' => 'reports.expired'],
                    ['label' => 'Contratos en pre-saca', 'route' => 'reports.queued'],
                    ['label' => 'Contratos activos', 'route' => 'reports.active'],
                    ['label' => 'Abonos', 'route' => 'reports.extensions'],
                    ['label' => 'Financiero', 'route' => 'reports.financial'],
                    ['label' => 'Gastos', 'route' => 'reports.expenses'],
                    ['label' => 'Artículos vendidos', 'route' => 'reports.sold'],
                    ['label' => 'Contratos sacados', 'route' => 'reports.pulled'],
                    ['label' => 'Contratos cancelados', 'route' => 'reports.redeemed'],
                    ['label' => 'Estadísticas', 'route' => 'reports.stats'],
                ],
            ],
        ];

        if (auth()->user()?->isAdministrator()) {
            $sections[] = [
                'label' => 'Administración',
                'match' => 'admin.*',
                'children' => [
                    ['label' => 'Datos compraventa', 'route' => 'admin.company.edit'],
                    ['label' => 'Usuarios', 'route' => 'admin.users.index'],
                    ['label' => 'Valores modificados', 'route' => 'admin.overrides.index'],
                ],
            ];
        }

        $view->with('navigation', $sections);
        $view->with('company', CompanySetting::current());
    }
}
