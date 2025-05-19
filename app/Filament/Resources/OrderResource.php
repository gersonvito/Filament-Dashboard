<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Inventory;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Forms\Components\TextInput\Actions\HidePasswordAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'CRM';
    protected static ?string $navigationLabel = 'Salidas';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $slug = 'salidas';
    protected static ?string $label = 'Salida';
    protected static ?string $pluralLabel = 'Salidas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Información de facturación")
                ->schema([
                    Forms\Components\Select::make('warehouse_id')
                        ->relationship('warehouse', 'name')
                        ->preload()
                        ->searchable()
                        ->live()
                        ->label("Almacén")
                        ->required(),

                    Forms\Components\Select::make('customer_id')
                        ->label("Cliente")
                        ->preload()
                        ->searchable()
                        ->relationship('customer', 'name')
                        ->createOptionForm(
                                CustomerResource::getformSchema()
                        )
                        ->required(),
                ]),


                Section::make("Carrito de productos")
                    ->schema([
                        Repeater::make("orderProducts")
                        ->relationship()
                        ->columns(3)
                        ->schema([
                            Select::make('product_id')
                                ->label("Producto")
                                ->searchable()
                                ->preload()
                                ->live()
                                ->relationship("product", "name")
                                ->options(
                                    fn(Get $get): array => Product::query()
                                        ->whereHas('inventories', fn($q) => $q
                                            ->where('warehouse_id', $get('../../warehouse_id'))
                                    )
                                    ->pluck("name", 'id')
                                    ->toArray()
                                ),

                            TextInput::make('quantity')
                                ->label('Cantidad')
                                ->default(1)
                                ->numeric()
                                ->minValue(1)
                                ->required()
                                ->reactive()
                                ->rules(function(Get $get){
                                    $productId = $get('product_id');
                                    $warehouseId = $get('../../warehouse_id');

                                    $stock = Inventory::where('product_id', $productId)
                                        ->where('warehouse_id', $warehouseId)
                                        ->value('stock') ?? 0;

                                    return "max:$stock";

                                })

                                ->helperText(function(Get $get){
                                    
                                    $productId = $get('product_id');
                                    $warehouseId = $get('../../warehouse_id');

                                    $stock = Inventory::where('product_id', $productId)
                                        ->where('warehouse_id', $warehouseId)
                                        ->value('stock') ?? 0;

                                    return "Stock disponible $stock";
                                }),


                            Placeholder::make('subTotal')
                                ->label('Sub Total')
                                ->content(function (Get $get){

                                    $productId = $get('product_id');

                                    $subTotal = $get('quantity') * (Product::find($productId)->price ?? 0);
                                    return number_format($subTotal, 2, ".", "");
                                })

                        ])
                        ->afterStateUpdated(function ($set, $state){
                            $total = 0;

                            foreach ($state as $item) {
                                $product = Product::find($item['product_id']);
                                $quantity = $item['quantity'] ?? 0;
                                $total += $quantity * ($product->price ?? 0);
                            }

                            $set('total', $total);
                        })
                        ->mutateRelationshipDataBeforeCreateUsing(function(array $data): array {
                            $product = Product::find($data['product_id']);
                            $data['subTotal'] = $data['quantity'] * $product->price;
                            return $data;
                        }),

                    Hidden::make('total')
                        ->reactive(),

                    Placeholder::make("")
                        ->label("Total a pagar")
                        ->columnSpan('full')
                        ->reactive()
                        ->content(function(Get $get) {
                            $total = $get('total');
                            return number_format($total, 2, ".", "");
                        })
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
