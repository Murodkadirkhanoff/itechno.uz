<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_uz')
                    ->required()
                    ->label('Name (UZ)'),
                Forms\Components\TextInput::make('name_ru')
                    ->required()
                    ->label('Name (RU)'),

                Forms\Components\FileUpload::make('icon')
                    ->disk('public')
                    ->directory('categories')
                    ->maxSize(5120)
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                        '4:3',
                        '16:9',
                    ])
                    ->moveFiles()
                    ->preserveFilenames(),

                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name_ru')
                    ->nullable()
                    ->label('Parent Category'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_uz')->searchable(),
                Tables\Columns\TextColumn::make('name_ru')->searchable(),
                Tables\Columns\ImageColumn::make('icon')->disk('public'),
                Tables\Columns\TextColumn::make('parent.name_uz')->label('Parent Category'),
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
