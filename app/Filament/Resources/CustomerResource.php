<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationGroup = 'CRM';
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $slug = 'clientes';
    protected static ?string $label = 'Clientes';
    protected static ?string $pluralLabel = 'Clientes';

    public static function form(Form $form): Form
    {
        return $form->schema(static::getFormSchema());
    }

    public static function getformSchema(): array
    {
        return [
            Section::make('Informacion del cliente')
                ->columns(2)
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Estado del cliente')
                        ->required()
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label("Nombre")
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label("Correo electrónico")
                        ->email(),
                        // ->unique(table: 'customers', column: 'email', ignorable: fn($record)=>$record)
                        // ->rules(
                        //     ['unique: customers,email']
                        // )
                        // ->validateionMessages([
                        //     'unique' => 'El correo que se encuentra registrado.'
                        // ]),

                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->label("Teléfono")
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('nit')
                        ->maxLength(255)
                        ->label("NIT/Documento de identidad"),

                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->maxLength(255),
                ])
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nit')
                    ->searchable(),
                TextColumn::make('is_active')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Activo' : 'Inactivo'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
