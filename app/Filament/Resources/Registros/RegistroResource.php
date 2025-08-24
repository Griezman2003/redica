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
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

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
                DateRangePicker::make('mes')
                ->label('Pago respecto a ese mes')
                ->icon('heroicons-backspace')
                ->timezone('UTC'),
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
                TextColumn::make('mes')
                    ->label('Mes de Pago')
                    ->sortable(),
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
                                    "registro" => $record,
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRegistros::route('/'),
            "create" => Pages\createRegistro::route("/create"),
        ];
    }
}
