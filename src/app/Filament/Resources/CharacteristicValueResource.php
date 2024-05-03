<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CharacteristicValueResource\Pages;
use App\Filament\Resources\CharacteristicValueResource\RelationManagers;
use App\Models\CharacteristicValue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CharacteristicValueResource extends Resource
{
    protected static ?string $model = CharacteristicValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('characteristic_id')
                    ->relationship('characteristic', 'name_uz')  // Assuming 'name_uz' is what you'd display in the select
                    ->required(),
                Forms\Components\TextInput::make('value_uz')
                    ->required()
                    ->label('Value (UZ)'),
                Forms\Components\TextInput::make('value_ru')
                    ->required()
                    ->label('Value (RU)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('characteristic.name_uz')->label('Characteristic (UZ)'),
                Tables\Columns\TextColumn::make('value_uz')->searchable(),
                Tables\Columns\TextColumn::make('value_ru')->searchable(),
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
            'index' => Pages\ListCharacteristicValues::route('/'),
            'create' => Pages\CreateCharacteristicValue::route('/create'),
            'edit' => Pages\EditCharacteristicValue::route('/{record}/edit'),
        ];
    }
}
