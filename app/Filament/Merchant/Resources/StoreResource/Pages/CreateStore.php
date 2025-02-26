<?php

namespace App\Filament\Merchant\Resources\StoreResource\Pages;

use App\Filament\Merchant\Resources\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data["latitude"] = $data["location"]["lat"];
        $data["longitude"] = $data["location"]["lng"];
        $data['user_id'] = auth()->id();

        return $data;
    }
}
