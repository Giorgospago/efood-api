<?php

namespace App\Filament\Merchant\Resources\StoreResource\Pages;

use App\Filament\Merchant\Resources\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStore extends EditRecord
{
    protected static string $resource = StoreResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data["latitude"] = $data["location"]["lat"];
        $data["longitude"] = $data["location"]["lng"];

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

}
