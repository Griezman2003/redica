<?php

namespace App\Filament\Resources\Registros;

use App\Filament\Resources\Registros\Pages\ManageRegistros;
use App\Filament\Resources\Registros\RelationManagers;
use App\Models\Registro;
use BackedEnum;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'Registro';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make(),
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
            'index' => ManageRegistros::route('/'),
            "edit" => Pages\EditRegistro::route("/{record}/edit"),
            "create" => Pages\createRegistro::route("/create"),
        ];
    }
}
