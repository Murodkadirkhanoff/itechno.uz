<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CharacteristicResource\Pages;
use App\Filament\Resources\CharacteristicResource\RelationManagers;
use App\Models\Characteristic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CharacteristicResource extends Resource
{
    protected static ?string $model = Characteristic::class;

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
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->label('Order'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_uz')->searchable(),
                Tables\Columns\TextColumn::make('name_ru')->searchable(),
                Tables\Columns\TextColumn::make('order'),
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
            RelationManagers\ValuesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCharacteristics::route('/'),
            'create' => Pages\CreateCharacteristic::route('/create'),
            'edit' => Pages\EditCharacteristic::route('/{record}/edit'),
        ];
    }
}
