<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationLabel = 'Categorías';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';



    protected static ?string $slug = 'categorias';
    protected static ?string $label = 'Categoríaa';
    protected static ?string $pluralLabel = 'Categorias';

    public static function form(Form $form): Form
    {
        return $form->schema(static::getFormSchema());
    }

    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->label('Nombre de la categoría')
                ->placeholder('Ej. Auriculares'),

            TextInput::make('summary')
                ->required()
                ->label('Resumen')
                ->placeholder('Agregar un resumen de la categoría'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->label('ID'),
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('summary')
                    ->label('Resumen'),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime(),

                TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->date(),


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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
