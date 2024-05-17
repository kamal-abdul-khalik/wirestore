<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\{Group, Hidden, Placeholder, Repeater, Section, Select, Textarea, TextInput, ToggleButtons};
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\{SelectColumn, TextColumn};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Informasi Order')->schema([
                        Select::make('user_id')
                            ->label('Pelanggan')
                            ->searchable()
                            ->preload()
                            ->relationship('user', 'name')
                            ->required(),
                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->searchable()
                            ->options([
                                'dana' => 'Dana',
                                'cod' => 'Bayar Ditempat (COD)',
                            ])
                            ->required(),
                        Group::make()->schema([
                            Select::make('payment_status')
                                ->label('Status Pembayaran')
                                ->options([
                                    'pending' => 'Pending',
                                    'paid' => 'Terbayar',
                                    'failed' => 'Gagal',
                                ])
                                ->searchable()
                                ->default('pending')
                                ->required(),
                            Select::make('currency')
                                ->label('Mata Uang')
                                ->options([
                                    'idr' => 'IDR',
                                    'usd' => 'USD'
                                ])
                                ->searchable()
                                ->default('idr')
                                ->required(),
                            Select::make('shipping_method')
                                ->label('Kurir')
                                ->searchable()
                                ->options([
                                    'jne' => 'JNE',
                                    'jnt' => 'JNT',
                                    'ninja' => 'Ninja Express',
                                    'pos' => 'POS Indonesia',
                                ])
                                ->required(),
                        ])->columns(3)->columnSpanFull(),
                        Textarea::make('notes')
                            ->columnSpanFull(),
                        ToggleButtons::make('status')
                            ->label('Status Pengiriman ')
                            ->inline()
                            ->default('new')
                            ->options([
                                'new' => 'Baru',
                                'processing' => 'Proses',
                                'shiped' => 'Dikirim',
                                'delivered' => 'Terkirim',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shiped' => 'success',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-circle',
                            ])
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
                    Section::make('Keranjang Belanja')
                        ->schema([
                            Repeater::make('orderItems')
                                ->label('Item')
                                ->relationship()
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Nama Produk')
                                        ->relationship('product', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->distinct()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->columnSpan(4)
                                        ->reactive()
                                        ->afterStateUpdated(
                                            fn ($state, Set $set) => $set('unit_amount', Product::find($state)?->price ?? 0)
                                        )
                                        ->afterStateUpdated(
                                            fn ($state, Set $set) => $set('total_amount', Product::find($state)?->price ?? 0)
                                        ),
                                    TextInput::make('quantity')
                                        ->numeric()
                                        ->default(1)
                                        ->minValue(1)
                                        ->required()
                                        ->columnSpan(2)
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set(
                                            'total_amount',
                                            $state * $get('unit_amount')
                                        )),
                                    TextInput::make('unit_amount')
                                        ->numeric()
                                        ->required()
                                        ->disabled()
                                        ->dehydrated()
                                        ->columnSpan(3),
                                    TextInput::make('total_amount')
                                        ->numeric()
                                        ->required()
                                        ->disabled()
                                        ->dehydrated()
                                        ->columnSpan(3),
                                ])->columns(12),
                            Placeholder::make('grand_total_placeholder')
                                ->label('Total Harga')
                                ->content(function (Get $get, Set $set) {
                                    $total = 0;
                                    if (!$repeaters = $get('orderItems')) {
                                        return $total;
                                    }
                                    foreach ($repeaters as $key => $repetaer) {
                                        $total += $get("orderItems.{$key}.total_amount");
                                    }
                                    $set('grand_total', $total);
                                    return (new HtmlString("<div class='text-2xl'>" . Number::currency($total, in: 'IDR', locale: 'id') . "</div>"));
                                }),
                            Hidden::make('grand_total')
                                ->default(0)
                        ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('grand_total')
                    ->money('IDR', locale: 'id')
                    ->searchable(),
                TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->searchable(),
                TextColumn::make('shipping_method')
                    ->label('Kurir')
                    ->searchable(),
                SelectColumn::make('status')
                    ->label('Status Pengiriman')
                    ->options([
                        'new' => 'Baru',
                        'processing' => 'Proses',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Terkirim',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->searchable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(format: 'd M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime(format: 'd M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->closeModalByClickingAway(false),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            AddressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
