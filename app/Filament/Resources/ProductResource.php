<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\CategoryResource;
use Filament\Tables\Actions\ActionGroup;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = 'Menú principal';
    protected static ?string $navigationLabel = 'Productos';
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $slug = 'productos';
    protected static ?string $label = 'Producto';
    protected static ?string $pluralLabel = 'Productos';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del producto')
                    ->schema([


                        Toggle::make('is_active')
                            ->label('¿Esta activo?')
                            ->required()
                            ->default(true),

                        TextInput::make('code')
                            ->label('Código')
                            ->required()
                            ->maxLength(20)
                            ->unique(table: 'products', column: 'code', ignorable: fn($record)=> $record)
                            ->placeholder('Ej. FIC-001')

                            ->rules(
                                ['unique:products,code,']
                            )
                            ->validationMessages([
                                'unique'=>'El código ya existe, por favor ingrese otro.'
                            ]),

                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('summary')
                            ->label('Resumen')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('price')
                            ->label('Precio de venta')
                            ->required()
                            ->numeric()
                            ->minValue(0)

                            ->suffix('Bs'),


                        Select::make('category_id')
                            ->label('Categoría')
                            ->searchable()
                            ->preload()
                            ->relationship('category', 'name')
                            ->placeholder('Seleccionar categoría')
                            ->required()
                            ->createOptionForm(
                                CategoryResource::getFormSchema(),
                            ),

                    ]),

                Section::make('Imagen del producto')
                    ->schema([
                        FileUpload::make('image')
                            ->disk('public')
                            ->label('Imagen')
                            ->visibility('public')
                            ->preserveFilenames()

                            ->acceptedFileTypes(['image/*'])
                            ->required()
                    ]),

                Section::make('Descripción detallada')
                    ->schema([
                        RichEditor::make('description')

                            ->label("Descripcion")
                            ->required()
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable(),

                ImageColumn::make('image')
                    ->label('Imagen')
                    ->size(50),

                TextColumn::make('name')
                    ->searchable()
                    ->label('Nombre'),

                TextColumn::make('summary')
                    ->label('Resumen')
                    ->toggleable(),

                TextColumn::make('is_active')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Activo' : 'Inactivo'),

                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->sortable()
                    ->toggleable()
                    ->dateTime(),

                TextColumn::make('updated_at')
                    ->label('Fecha de actualizacion')
                    ->sortable()
                    ->toggleable()
                    ->dateTime(),


            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
            ])
            ->actions([

                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                ]),


                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
