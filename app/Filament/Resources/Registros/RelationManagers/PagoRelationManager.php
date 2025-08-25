<?php

namespace App\Filament\Resources\Registros\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Actions\BulkAction;


class PagoRelationManager extends RelationManager
{
    protected static string $relationship = 'Pago';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('monto')
                ->label('Monto')
                ->numeric()
                ->required(),
                Select::make('concepto_id')
                ->label('Concepto')
                ->relationship('concepto', 'nombre')
                ->required(),
                Select::make('mes')
                ->label('Meses pagados')
                ->multiple()
                ->options( \App\Helpers\Mes::list())
                ->searchable()
                ->preload()
                ->columnSpanFull()
                ->default(['enero']),
                \Filament\Schemas\Components\Section::make('Pendiente Pago')
                ->description('Seccion de pagos pendientes')
                ->schema([
                \Filament\Forms\Components\Toggle::make('mes_pendiente')
                ->label('Mes Pendiente')
                ->reactive()
                ->afterStateHydrated(function ($component, $state, $record) {
                    if ($record) {
                        $component->state(!empty($record->pendiente));
                    } else {
                        $component->state(false);
                    }
                }),

                Select::make('pendiente')
                    ->label('Mes Pendiente')
                    ->multiple()
                    ->options(\App\Helpers\Mes::list())
                    ->visible(fn ($get) => $get('mes_pendiente'))
                    ->columnSpanFull()
                    ->searchable()
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if ($record) {
                            $component->state(is_array($record->pendiente) ? $record->pendiente : []);
                        } else {
                            $component->state(['Enero']);
                        }
                    }),
                ])
                ->columnSpanFull()
                ->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('registro.nombre')
                ->label('Nombre')
                ->searchable(),
                TextColumn::make('monto')
                ->label('Monto')
                ->money('MXN')
                ->searchable()
                ->badge(),
                TextColumn::make('concepto.nombre')
                ->label('Concepto')
                ->searchable(),
                TextColumn::make('mes')
                ->label('Meses pagados')
                ->searchable()
                ->badge(),
                TextColumn::make('pendiente')
                ->label('Mes Pendiente')
                ->badge(),
                TextColumn::make('uuid')
                ->label('Uuid')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                ->dateTime('d/m/Y H:i')
                ->label('Creado')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
            CreateAction::make()
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
                EditAction::make(),
                DeleteAction::make(),

                ])->button()
                ->badge()
                ->icon('heroicon-o-ellipsis-vertical')
            ])
            ->toolbarActions([
                BulkAction::make('Exportar a Excel')
                    ->action(function ($records) {
                        $export = new \App\Exports\RegistroExport();
                        return \Maatwebsite\Excel\Facades\Excel::download($export, 'redIca.xlsx');
                    })
                ->color('success'),
            ]);
        
    }
}
