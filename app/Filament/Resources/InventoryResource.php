<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationGroup = 'Menú principal';
    protected static ?string $navigationLabel = 'Inventarios';
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $slug = 'inventario';
    protected static ?string $label = 'Inventario';
    protected static ?string $pluralLabel = 'Inventarios';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Formulario de arqueo')
                    ->columns(2)
                    ->schema([

                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->label('Producto')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('warehouse_id')
                            ->relationship('warehouse', 'name')
                            ->label('Almacen')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('stock')
                            ->required()
                            ->numeric()
                            ->default(0),

                    ])



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
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
                SelectFilter::make('warehouse_id')
                ->label('Almacen')
                ->relationship('warehouse','name')
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}
