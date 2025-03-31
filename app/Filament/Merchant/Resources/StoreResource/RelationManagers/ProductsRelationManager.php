<?php

namespace App\Filament\Merchant\Resources\StoreResource\RelationManagers;

use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make("Basic information")
                    ->aside()
                    ->description("Name, category, price, active...")
                    ->schema([
                        Forms\Components\TextInput::make("name")
                            ->required()
                            ->translatable(),
                        Forms\Components\TextArea::make("description")
                            ->translatable(),
                        Forms\Components\TextInput::make("price")
                            ->suffix('€')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\Select::make("product_category_id")
                            ->required()
                            ->preload()
                            ->native(false)
                            ->relationship(
                                name: 'productCategory', 
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->whereRelation("store", "user_id", auth()->id()),
                            ),
                        Forms\Components\Toggle::make("active")
                            ->default(true),
                    ]),
                Forms\Components\Section::make("Photos")
                    ->aside()
                    ->description("Add photos")
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('gallery')
                            ->disk("s3")
                            ->collection('gallery')
                            ->multiple()
                            ->reorderable()
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->reorderable("sort")
            ->defaultGroup('productCategory.name')
            ->groups([
                Group::make('productCategory.name')
                    ->titlePrefixedWithLabel(false)
                    // ->orderQueryUsing(fn (Builder $query, string $direction) => $query
                    //     ->join('product_categories', 'product_categories.id', '=', 'products.id')
                    //     ->orderBy('product_categories.sort')
                    // ),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('mainImage'),
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Product $record) => $record->description),
                Tables\Columns\TextColumn::make("price")
                    ->money("EUR"),
                Tables\Columns\IconColumn::make("active")
                    ->boolean()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
