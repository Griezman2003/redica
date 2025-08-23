<?php

namespace App\Filament\Resources\Conceptos;

use App\Filament\Resources\Conceptos\Pages\ManageConceptos;
use App\Models\Concepto;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConceptoResource extends Resource
{
    protected static ?string $model = Concepto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Concepto';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->columnSpanFull()
                ->maxLength(255),
                \Filament\Forms\Components\MarkdownEditor::make('descripcion')
                ->columnSpanFull()
                ->label('Descripción')
                ->maxLength(255),
                \Filament\Forms\Components\TagsInput::make('atributos')
                    ->label('Etiquetas')
                    ->placeholder('Ejemplo (Pago Internet) y presiona Enter')
                    ->separator(',')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->searchable(),
                \Filament\Tables\Columns\TagsColumn::make('atributos')
                    ->label('Etiquetas'),
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
            'index' => ManageConceptos::route('/'),
        ];
    }
}
