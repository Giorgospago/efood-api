<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StoreResource\Pages;
use App\Filament\Merchant\Resources\StoreResource\RelationManagers;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use ArberMustafa\FilamentLocationPickrField\Forms\Components\LocationPickr;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

use Illuminate\Support\Facades\Log;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where("user_id", auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make("Basic information")
                    ->aside()
                    ->description("Name, address, categories, phone number...")
                    ->schema([
                        Forms\Components\TextInput::make("name")
                            ->required()
                            ->translatable(),
                        Forms\Components\TextInput::make("address")
                            ->required()
                            ->translatable(),
                        Forms\Components\Select::make('categories')
                            ->preload()
                            ->multiple()
                            ->relationship(titleAttribute: 'name'),
                        Forms\Components\TextInput::make("phone")
                            ->tel()
                    ]),
                Forms\Components\Section::make("Operating information")
                    ->aside()
                    ->description("Minimum cart value, working hours...")
                    ->schema([
                        Forms\Components\TextInput::make("minimum_cart_value")
                            ->suffix('€')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make("delivery_range")
                            ->suffix('km')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make("working_hours")
                    ]),
                Forms\Components\Section::make("Location")
                    ->aside()
                    ->description("Drag the marker and set the store's location")
                    ->schema([
                        LocationPickr::make('location')
                            ->height(config('filament-locationpickr-field.default_height'))
                            ->defaultZoom(config('filament-locationpickr-field.default_zoom'))
                            ->defaultLocation(config('filament-locationpickr-field.default_location'))
                            ->draggable(),
                    ]),
                Forms\Components\Section::make("Photos")
                    ->aside()
                    ->description("Add a logo and a cover photo")
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->disk("s3")
                            ->collection('logo'),
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->disk("s3")
                            ->collection('cover'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo')
                    ->collection("logo"),
                Tables\Columns\TextColumn::make("name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("address"),
                Tables\Columns\TextColumn::make("categories.name"),
                Tables\Columns\TextColumn::make("minimum_cart_value")
                    ->money("EUR"),
                Tables\Columns\TextColumn::make("delivery_range")->suffix(" km"),
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
            RelationManagers\ProductCategoriesRelationManager::class,
            RelationManagers\ProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
