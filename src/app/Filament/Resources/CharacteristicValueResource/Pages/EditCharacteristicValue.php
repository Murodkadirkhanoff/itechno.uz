<?php

namespace App\Filament\Resources\CharacteristicValueResource\Pages;

use App\Filament\Resources\CharacteristicValueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCharacteristicValue extends EditRecord
{
    protected static string $resource = CharacteristicValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
