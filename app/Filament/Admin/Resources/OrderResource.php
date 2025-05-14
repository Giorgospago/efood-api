<?php

namespace App\Filament\Admin\Resources;

use App\Enum\OrderStatus;
use App\Enum\PaymentStatus;
use App\Enum\ShippingStatus;
use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Filament\Admin\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = "Orders";

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                "store",
                "user",
                "address",
                "products"
            ]);
    }

    public static function form(Form $form): Form
    {
        $order = $form->getRecord();

        return $form
            ->schema([
                Section::make("information")
                    ->description("Basic information for the order")
                    ->aside()
                    ->schema([
                        TextInput::make("id")
                            ->label("Order ID")
                            ->readOnly(),
                        Select::make("status")
                            ->native(false)
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'out_for_delivery' => 'Out for delivery',
                                'completed' => 'Completed',
                            ])
                            ->columnSpanFull(),
                        Select::make("store_id")
                            ->native(false)
                            ->disabled()
                            ->relationship("store", "name"),
                        Select::make("user_id")
                            ->native(false)
                            ->disabled()
                            ->relationship("user", "name"),
                        Select::make("address_id")
                            ->native(false)
                            ->relationship(
                                "address",
                                "street",
                                function (Builder $query) use ($order) {
                                    return $query->where('user_id', $order->user_id);
                                }
                            )
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                return $record->full_address;
                            }),
                        TextInput::make("note")
                            ->readOnly(),
                    ]),
                Section::make("Payment")
                    ->description("Payment method, payment status, product price, total price and tip")
                    ->aside()
                    ->schema([
                        Section::make("Payment Details")
                            ->collapsible()
                            ->hiddenLabel()
                            ->columns(2)
                            ->schema([
                                Select::make("payment_method")
                                    ->native(false)
                                    ->options([
                                        'card' => 'Card',
                                        'cod' => 'Cash on delivery',
                                    ])
                                    ->columnSpanFull(),
                                Select::make("payment_status")
                                    ->native(false)
                                    ->options([
                                        'pending' => 'Pending',
                                        'completed' => 'Completed',
                                    ])
                                    ->columnSpanFull(),
                                TextInput::make("products_price")
                                    ->suffix("EUR")
                                    ->columnSpan(1)
                                    ->readOnly(),
                                TextInput::make("discount")
                                    ->suffix("EUR")
                                    ->columnSpan(1)
                                    ->readOnly(),
                                TextInput::make("tip")
                                    ->suffix("EUR")
                                    ->columnSpan(1)
                                    ->readOnly(),
                                TextInput::make("total_price")
                                    ->suffix("EUR")
                                    ->columnSpan(1)
                                    ->readOnly(),
                            ])
                    ]),
                Section::make("Shipping")
                    ->description("Shipping method, shipping status, shipping price and delivery time")
                    ->aside()
                    ->schema([
                        Section::make("Shipping Details")
                            ->collapsible()
                            ->hiddenLabel()
                            ->schema([
                                Select::make("shipping_method")
                                    ->native(false)
                                    ->options([
                                        'delivery' => 'Delivery',
                                        'takeaway' => 'Takeaway',
                                    ]),
                                Select::make("shipping_status")
                                    ->native(false)
                                    ->options([
                                        'pending' => 'Pending',
                                        'completed' => 'Completed',
                                    ]),
                                TextInput::make("shipping_price")
                                    ->suffix("EUR")
                                    ->readOnly(),
                                TextInput::make("delivery_time")
                                    ->suffix("Minutes")
                                    ->readOnly(),
                            ])
                    ]),
                Section::make("products")
                    ->aside()
                    ->schema([
                        Repeater::make("products")
                            ->relationship("products")
                            ->columns(2)
                            ->schema([
                                Select::make("product_id")
                                    ->native(false)
                                    ->relationship(
                                        "product",
                                        "name",
                                        function (Builder $query) use ($order) {
                                            return $query->where('store_id', $order->store_id);
                                        }
                                    )
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        $product = Product::find($get("product_id"));
                                        $set("price", $product->price);
                                        $set("quantity", 1);
                                        $set("note", null);
                                        $set("product_name", $product->name);
                                        $set("total_price", $product->price);
                                    })
                                    ->columnSpan(2)
                                    ->allowHtml()
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        return new HtmlString("<div class='flex items-center gap-2'><img style='width: 20px; height: 20px; border-radius: 5px; object-fit: cover;' src='$record->main_image'/><span>$record->name</span></div>");
                                    }),
                                TextInput::make("quantity")
                                    ->required()
                                    ->numeric()
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        $set("total_price", $get("price") * $get("quantity"));
                                    })
                                    ->live()
                                    ->minValue(1)
                                    ->columnSpan(1),
                                TextInput::make("price")
                                    ->required()
                                    ->suffix('EUR')
                                    ->live()
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(1),
                                TextInput::make("note")
                                    ->live()
                                    ->columnSpanFull()
                                    ->maxLength(255),
                                TextInput::make("product_name")
                                    ->live(),
                                TextInput::make("total_price")
                                    ->live(),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("store.name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("address.full_address"),
                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->since(),
                Tables\Columns\TextColumn::make("status")
                    ->badge()
                    ->color(function ($state) {
                        return OrderStatus::from($state)->color();
                    }),
                Tables\Columns\IconColumn::make("payment_method")
                    ->icon(fn (string $state): string => match ($state) {
                        'card' => 'heroicon-o-credit-card',
                        'cod' => 'heroicon-o-banknotes',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'card' => 'info',
                        'cod' => 'success',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make("payment_status")
                    ->badge()
                    ->color(function ($state) {
                        return PaymentStatus::from($state)->color();
                    }),
                Tables\Columns\TextColumn::make("shipping_method"),
                Tables\Columns\TextColumn::make("shipping_status")
                    ->badge()
                    ->color(function ($state) {
                        return ShippingStatus::from($state)->color();
                    }),
                Tables\Columns\TextColumn::make("total_price")->money('EUR'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->searchable()
                    ->native(false)
                    ->options(
                        collect(OrderStatus::cases())
                            ->mapWithKeys(fn ($enum) => [$enum->value => $enum->name])
                            ->toArray()
                    ),
                SelectFilter::make('payment_status')
                    ->searchable()
                    ->native(false)
                    ->options(
                        collect(PaymentStatus::cases())
                            ->mapWithKeys(fn ($enum) => [$enum->value => $enum->name])
                            ->toArray()
                    ),
                Filter::make('price')
                    ->form([
                        Forms\Components\TextInput::make('more_than')
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                $set('less_than', null);
                            })
                            ->default(null)
                            ->live()
                            ->placeholder("More than...")
                            ->minValue(0)
                            ->suffix('€')
                            ->numeric(),
                        Forms\Components\TextInput::make('less_than')
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                $set('more_than', null);
                            })
                            ->default(null)
                            ->live()
                            ->placeholder("Less than...")
                            ->minValue(0)
                            ->suffix('€')
                            ->numeric(),
                    ])
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['more_than']) {
                            return 'More than ' . Number::currency($data['more_than'], 'EUR');
                        }
                        if ($data['less_than']) {
                            return 'Less than ' . Number::currency($data['less_than'], 'EUR');
                        }

                        return null;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['less_than'],
                                fn (Builder $query, $price): Builder => $query->where('total_price', '<', $price),
                            )
                            ->when(
                                $data['more_than'],
                                fn (Builder $query, $price): Builder => $query->where('total_price', '>', $price),
                            );
                    }),
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
