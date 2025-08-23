<?php

namespace App\Filament\Resources\Registros;

use App\Filament\Resources\Registros\Pages\ManageRegistros;
use App\Models\Registro;
use BackedEnum;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegistroResource extends Resource
{
    protected static ?string $model = Registro::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Registro';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
                TextInput::make('monto')
                ->label('Monto')
                ->numeric()
                ->required(),
                Select::make('concepto_id')
                ->label('Concepto')
                ->relationship('concepto', 'nombre')
                ->required(),
                \Filament\Forms\Components\Toggle::make('estado')
                ->label('Activo')
                ->default(true),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                ->label('Nombre')
                ->searchable(),
                TextColumn::make('monto')
                ->label('Monto')
                ->money('MXN')
                ->searchable(),
                TextColumn::make('concepto.nombre')
                ->label('Concepto')
                ->searchable(),
                TextColumn::make('estado')
                ->label('Estado')
                ->getStateUsing(function ($record) {
                    return $record->estado ? 'Activo' : 'No activo';
                })
                ->colors([
                    'success' => 'Activo',
                    'danger' => 'No activo',
                ])
                ->badge(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Creado')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Actualizado')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRegistros::route('/'),
        ];
    }
}
