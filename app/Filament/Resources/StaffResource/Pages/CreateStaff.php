<?php

namespace App\Filament\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    //function RedirectUrl
    protected function getRedirectUrl(): string
    {
        return $this-> getResource()::getUrl('index');
    }
}