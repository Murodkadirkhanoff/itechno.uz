<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\CharacteristicValue;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CharacteristicsRelationManager extends RelationManager
{
    protected static string $relationship = 'characteristics';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_uz')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name_uz')
            ->columns([
                Tables\Columns\TextColumn::make('name_uz'),
                Tables\Columns\TextColumn::make('characteristic_value_id')
                    ->formatStateUsing(fn (string $state): string => CharacteristicValue::find($state)->value_uz ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()->preloadRecordSelect()
                    ->form(function (AttachAction $action): array {
                        return [
                            $action->getRecordSelect()
                                ->reactive()  // Make this select field reactive
                                ->afterStateUpdated(fn (callable $set, $state) => $set('characteristic_id', $state))
                                ->label('Characteristic'),

                            Select::make('characteristic_value_id')
                                ->options(function (callable $get) {
                                    $characteristicId = $get('characteristic_id');
                                    if (!$characteristicId) {
                                        return [];
                                    }
                                    return CharacteristicValue::where('characteristic_id', $characteristicId)
                                        ->pluck('value_uz', 'id')->toArray(); // Ensure the result is an array
                                })
                                ->label('Value')
                                ->reactive() // Ensure this field updates dynamically based on the characteristic_id field
                        ];
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
