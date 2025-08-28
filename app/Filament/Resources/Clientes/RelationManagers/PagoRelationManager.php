<?php

namespace App\Filament\Resources\Clientes\RelationManagers;

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
                ->required()
                ->default(['enero']),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('folio')
                ->label('Folio')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cliente.nombre')
                ->label('Nombre')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('monto')
                ->label('Monto')
                ->money('MXN')
                ->searchable()
                ->badge(),
                TextColumn::make('concepto.nombre')
                ->label('Concepto')
                ->searchable(),
                TextColumn::make('meses')
                ->label('Meses pagados')
                ->badge(),
                /*
                TextColumn::make('pendiente')
                ->label('Mes Pendiente')
                ->badge()
                ->formatStateUsing(fn ($state) => 
                    str_word_count($state) > 5
                        ? implode(', ', array_slice(explode(', ', $state), 0, 5)) . ' ...'
                        : $state
                ),
                */
                TextColumn::make('uuid')
                ->label('Uuid')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                ->dateTime('d/m/Y')
                ->label('Creado')
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
            CreateAction::make()
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
                EditAction::make(),
                DeleteAction::make(),

                ])->button()
                ->badge()
                ->icon('heroicon-o-ellipsis-vertical')
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
