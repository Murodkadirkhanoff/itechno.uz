<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\UserResource\Pages\SortUsers;
use App\Models\Characteristic;
use App\Models\CharacteristicValue;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Rate limiting')
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->schema([

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name_uz')
                                    ->required()
                                    ->label('Name (UZ)'),
                                Forms\Components\TextInput::make('name_ru')
                                    ->required()
                                    ->label('Name (RU)'),
                                Forms\Components\Textarea::make('description_uz')
                                    ->required()
                                    ->label('Description (UZ)'),
                                Forms\Components\Textarea::make('description_ru')
                                    ->required()
                                    ->label('Description (RU)'),

                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name_uz') // assuming 'name_uz' is what you want to display
                                    ->required(),
                                Forms\Components\Select::make('brand_id')
                                    ->relationship('brand', 'name')
                                    ->nullable()
                                    ->label('Brand'),
                                Forms\Components\TextInput::make('artikul')
                                    ->label('Artikul'),


                                Forms\Components\TextInput::make('price')->numeric()->required()->label('Price'),
                                Forms\Components\TextInput::make('discount_price')->numeric()->label('Discount Price'),
                                Forms\Components\TextInput::make('discount_percent')->numeric()->label('Discount Percent'),
                                Forms\Components\TextInput::make('in_stock')
                                    ->integer()
                                    ->required()
                                    ->label('Stock Quantity'),
                            ])


                    ])->columnSpan(2)->columns(2),


                Forms\Components\Grid::make()->schema([
                    Section::make('Фото')
                        ->description('Загрузка фото товара')
                        ->schema([
                            FileUpload::make('images')
                                ->multiple()
                                ->image()
                                ->reorderable()
                                ->appendFiles()
                                ->maxFiles(5)
                                ->disk('public')
                                ->directory('brands')
                                ->maxSize(5120)
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    '1:1',
                                    '4:3',
                                    '16:9',
                                ])
                                ->moveFiles()
                                ->preserveFilenames()
                        ]),

                    Repeater::make('characteristics')
                        ->schema([
                            Select::make('characteristic_id')
                                ->options(Characteristic::all()->pluck('name_uz', 'id')) // Retrieves all characteristics and plucks name and id.
                                ->label('Свойства')
                                ->reactive() // Make this field reactive to update the values dropdown dynamically
                                ->afterStateUpdated(fn(callable $set) => $set('characteristic_value_id', null)), // Reset value when characteristic changes

                            Select::make('characteristic_value_id')
                                ->options(function (callable $get) {
                                    $characteristicId = $get('characteristic_id');
                                    if (!$characteristicId) {
                                        return [];
                                    }
                                    return CharacteristicValue::where('characteristic_id', $characteristicId)
                                        ->pluck('value_uz', 'id'); // Dynamically load values based on the selected characteristic
                                })
                                ->label('Значение')
                                ->reactive() // Ensure this field updates dynamically based on the characteristic_id field
                        ])
                        ->label('Характеристики')
                        ->createItemButtonLabel('Добавить характеристику')
                        ->dehydrated()
                        ->dehydrateStateUsing(fn($state) => $state ?: []),


                ])->columns(1)->columnSpan(1)


            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_uz')->searchable(),
                Tables\Columns\TextColumn::make('name_ru')->searchable(),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('discount_price'),
                Tables\Columns\TextColumn::make('category.name_uz')->label('Category'),
                Tables\Columns\TextColumn::make('brand.name')->label('Brand'),
                ImageColumn::make('images')->circular()->stacked()->limit(3)
                    ->limitedRemainingText()
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
            //RelationManagers\CharacteristicsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'sort' => SortUsers::route('/sort'),

        ];
    }
}
