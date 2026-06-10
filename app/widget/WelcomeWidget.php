<?php

namespace App\widget;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return true;
    }
    protected array|string|int $columnSpan =2;

    protected function getStats(): array
    {
        $user = Auth::user();
        $esAdmin = $user->hasRole('super_admin');

        return [
            // Tarjeta 1: Bienvenida personalizada
            Stat::make('¡Hola, ' . $user->name . '!', 
                $esAdmin ? 'Panel de Administración' : 'Bienvenido a REDICA'
            )
            ->description($esAdmin ? 'Gestiona todo desde aquí' : 'Tu centro de control')
            ->descriptionIcon('heroicon-m-user-circle'),

            // Tarjeta 2: Informativa y útil
            Stat::make(
                $esAdmin ? 'Clientes Totales' : '¿Necesitas ayuda?',
                $esAdmin ? \App\Models\Cliente::count() : 'Soporte Técnico'
            )
            ->description(
                $esAdmin 
                ? 'Clientes registrados en la plataforma' 
                : 'Envíanos un mensaje vía WhatsApp'
            )
            ->descriptionIcon($esAdmin ? 'heroicon-m-users' : 'heroicon-m-chat-bubble-left-right')
            ->color($esAdmin ? 'primary' : 'success')
            ->extraAttributes($esAdmin ? [] : [
                'onclick' => "window.open('https://wa.me/529821257150', '_blank')",
                'style' => 'cursor: pointer;'
            ]),

            // Tarjeta 3: Estado de cuenta / Gestión de Pagos
        //     Stat::make(
        //         $esAdmin ? 'Pagos Pendientes' : 'Tu Próximo Pago',
        //         $esAdmin ? \App\Models\Pago::where('status', 'pendiente')->count() : 'Vencimiento'
        //     )
        //     ->description(
        //         $esAdmin 
        //         ? 'Pagos que requieren validación' 
        //         : 'Consulta tu fecha de corte en el apartado de pagos'
        //     )
        //     ->descriptionIcon($esAdmin ? 'heroicon-m-banknotes' : 'heroicon-m-calendar-days')
        //     ->color($esAdmin ? 'warning' : 'info'),
        ];
    }
}