<?php

namespace App\Filament\Resources\Clientes;

use App\Filament\Resources\Clientes\Pages\ManageCliente;
use App\Filament\Resources\Clientes\RelationManagers;
use App\Models\Cliente;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserCircle;

    protected static ?string $recordTitleAttribute = 'Cliente';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
                Select::make('colonia')
                ->options(\App\Helpers\Colonias::colonia())
                ->searchable()
                ->label('Colonia')
                ->required(),
                TextInput::make('telefono')
                ->label('Teléfono')
                ->mask('999 999 99 99')
                ->stripCharacters(' '),
                TextInput::make('calle')
                ->label('Calle')
                ->nullable(),
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
                TextColumn::make('colonia')
                ->label('Colonia'),
                TextColumn::make('telefono')
                ->label('Teléfono'),
                TextColumn::make('calle')
                ->label('Calle'),
                TextColumn::make('estado')
                ->label('Estado')
                ->icon(fn ($record) => $record->estado ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                ->getStateUsing(function ($record) {
                    return $record->estado ? 'Activo' : 'Inactivo';
                })
                ->colors([
                    'success' => 'Activo',
                    'danger' => 'Inactivo',
                ])
                ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make(),
                //EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PagoRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCliente::route('/'),
            'edit' => Pages\EditCliente::route("/{record}/edit"),
            //'view' => Pages\ViewCliente::route('/{record}'),
        ];
    }
}
