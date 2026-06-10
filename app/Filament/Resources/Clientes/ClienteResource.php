<?php

namespace App\Filament\Resources\Clientes;

use App\Filament\Resources\Clientes\Pages\ManageCliente;
use App\Filament\Resources\Clientes\RelationManagers;
use App\Models\Cliente;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;     
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;


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
                    ->maxLength(255)
                    ->disabled(fn () => Auth::user()?->hasRole('cliente')),
                
                Select::make('colonia')
                    ->options(\App\Helpers\Colonias::colonia())
                    ->searchable()
                    ->label('Colonia')
                    ->required()
                    ->disabled(fn () => Auth::user()?->hasRole('cliente')), 
                
                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->mask('999 999 99 99')
                    ->stripCharacters(' ')
                    ->disabled(fn () => Auth::user()?->hasRole('cliente')), 
                
                TextInput::make('calle')
                    ->label('Calle')
                    ->nullable()
                    ->disabled(fn () => Auth::user()?->hasRole('cliente')), 
                
                \Filament\Forms\Components\Toggle::make('estado')
                    ->label('Activo')
                    ->default(true)
                    ->disabled(fn () => Auth::user()?->hasRole('cliente')), 
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
                
                Action::make('crearUsuario')
                ->label('Correo')
                ->icon('heroicon-o-key')
                ->color('warning')
                
                ->modalWidth('md')
                ->modalHeading('Generar Cuenta de Acceso')
                ->modalDescription('Esta acción creará un usuario automático en el sistema para este cliente, vinculándolo con su historial de pagos y asignándole el rol de acceso limitado.')

                ->visible(fn (Cliente $record) => Auth::user()?->hasRole('super_admin') && is_null($record->user_id))
                
                ->form([
                    TextInput::make('email')
                        ->label('Correo')
                        ->email()
                        ->required()
                        ->rules([
                            function () {
                                return function (string $attribute, $value, \Closure $fail) {
                                    $existe = \App\Models\User::where('email', '=', $value)->exists();
                                    if ($existe) {
                                        $fail('Este correo ya está registrado en el sistema.');
                                    }
                                };
                            },
                        ]),
                    
                    TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->required()
                        ->revealable(),
                ])
                ->action(function (Cliente $record, array $data): void {
                    $usuario = User::create([
                        'name' => $record->nombre,
                        'email' => $data['email'],
                        'password' => Hash::make($data['password']),
                    ]);

                    $usuario->assignRole('cliente');

                    $record->user_id = $usuario->id;
                    $record->save();

                    \Filament\Notifications\Notification::make()
                        ->title('Cuenta creada con éxito')
                        ->body("El usuario para {$record->nombre} ha sido generado.")
                        ->success()
                        ->send();
                }),
                EditAction::make(), 
                DeleteAction::make()->visible(fn () => Auth::user()?->hasRole('super_admin')),
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
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        if ($user?->hasRole('cliente')) {
            return parent::getEloquentQuery()->where('user_id', $user->id);
        }
        return parent::getEloquentQuery();
    }
}