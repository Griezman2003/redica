<?php

namespace App\Filament\Resources\Clientes\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Toggle;


class PagoRelationManager extends RelationManager
{
    protected static string $relationship = 'Pago';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('monto')
                        ->label('Monto')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => $state ?? 0) 
                        ->default(function () {
                            $primerConcepto = \App\Models\Concepto::query()->first();
                            return $primerConcepto ? $primerConcepto->monto : 0;
                        })
                        ->prefix('$')
                        ->required(),

                Select::make('concepto_id')
                        ->label('Concepto')
                        ->relationship('concepto', 'nombre')
                        ->default(\App\Models\Concepto::query()->first()?->id)
                        ->disabled(! \App\Models\Concepto::exists())
                        ->required()
                        ->live() 
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $concepto = \App\Models\Concepto::query()->find($state);
                                
                                if ($concepto) {
                                    $set('monto', $concepto->monto);
                                }
                            } else {
                                $set('monto', 0);
                            }
                        })
                        ->helperText(
                            ! \App\Models\Concepto::exists()
                                ? '⚠ Debes crear un concepto primero para habilitar este campo'
                                : null
                        ),

                Toggle::make('mes_manual')
                    ->label('Asignar mes de pago manualmente (Regalo / Excepción)')
                    ->live() 
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) {
                            $mesAutonomo = \App\Models\Cliente::obtenerMesPendiente($this->ownerRecord);
                            $set('mes', $mesAutonomo);
                        }
                    })
                    ->columnSpanFull(),

                TextInput::make('mes')
                    ->label('Mes Pendiente / Asignado')
                    ->required()
                    ->default(fn ($get) => $get('mes_manual') 
                        ? null 
                        : \App\Models\Cliente::obtenerMesPendiente($this->ownerRecord)
                    )
                    ->disabled(fn ($get) => ! $get('mes_manual'))
                    ->dehydrated() 
                    ->dehydrateStateUsing(fn ($state) => strtoupper(trim($state)))
                    ->live(onBlur: true)
                    ->formatStateUsing(fn ($state) => strtoupper(trim($state)))
                    ->placeholder(fn ($get) => $get('mes_manual') ? 'Ej. FEBRERO' : '')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('folio')
                ->label('Folio')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('cliente.nombre')
                ->label('Nombre')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('monto')
                ->label('Monto')
                ->money('MXN')
                ->searchable()
                ->badge(),
                TextColumn::make('concepto.nombre')
                ->label('Concepto')
                ->searchable(),
                TextColumn::make('mes')
                ->label('Mes Pagado')
                ->badge(),
                TextColumn::make('uuid')
                ->label('Uuid')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                ->dateTime('d/m/Y')
                ->label('Fecha De Pago')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')
                ->dateTime('d/m/Y H:i')
                ->label('Actualizado')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ]) 
            ->filters([
                //
            ])
            ->headerActions([
            CreateAction::make()->visible(fn () => \Illuminate\Support\Facades\Auth::user()?->hasRole('super_admin'))
            ->createAnother(false)
            ->after(function ($record) {
                $record->generarPdf();
            }),
                //AssociateAction::make(),
            ])
            ->recordActions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\Action::make("Ver")
                        ->label("Ticket")
                        ->icon("heroicon-o-eye")
                        ->color("primary")
                        ->modalHeading("Vista previa del pago")
                        ->modalContent(
                            fn($record) => view("partials.pdf", [
                                "url" => route("pdf", [
                                    "pago" => $record,
                                ]),
                            ]),
                        )
                        ->modalWidth("6xl")
                ->slideOver()
                ->modalSubmitAction(false),
                EditAction::make()->visible(fn () => \Illuminate\Support\Facades\Auth::user()?->hasRole('super_admin')),
                DeleteAction::make()->visible(fn () => \Illuminate\Support\Facades\Auth::user()?->hasRole('super_admin')),
                \Filament\Actions\Action::make("WhatsApp")
                    ->label("Enviar WhatsApp")
                    ->icon("heroicon-o-chat-bubble-left-right")
                    ->color("success") 
                    ->url(function ($record) {
                        $cliente = $record->cliente ?? $this->ownerRecord; 

                        if (! $cliente || ! $cliente->telefono) {
                            return null; 
                        }

                        $telefonoLimpio = preg_replace('/[^0-9]/', '', $cliente->telefono);

                        if (! str_starts_with($telefonoLimpio, '52')) {
                            $telefonoLimpio = '52' . $telefonoLimpio;
                        }

                        $mensaje = rawurlencode("¡Hola *{$cliente->nombre}*! Aquí tienes el enlace de tu ticket de pago por el concepto de *{$record->concepto?->nombre}* correspondiente al mes de *{$record->mes}*: " . route("pdf", ["pago" => $record]));

                        return "https://wa.me/{$telefonoLimpio}?text={$mensaje}";
                    })
                    ->openUrlInNewTab()
                    ->visible(function ($record) {
                        $cliente = $record->cliente ?? $this->ownerRecord;
                        return ! empty($cliente?->telefono);
                    })
                    ->visible(fn () => \Illuminate\Support\Facades\Auth::user()?->hasRole('super_admin')),
                ])->button()
                ->badge()
                ->icon('heroicon-o-cog')
            ])
            ->toolbarActions([
                BulkAction::make('Exportar a Excel')
                    ->action(function ($records) {
                        $export = new \App\Exports\RegistroExport(
                            $records->pluck('id')->toArray()
                        );
                        return \Maatwebsite\Excel\Facades\Excel::download($export, 'redIca.xlsx');
                    })
                ->color('success'),
            ]);
            
    }
}
