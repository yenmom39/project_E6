<?php

namespace App\Filament\Resources\LibraryDashboardResource\Pages;

use App\Filament\Resources\LibraryDashboardResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLibraryDashboard extends CreateRecord
{
    protected static string $resource = LibraryDashboardResource::class;

    //function RedirectUrl
    protected function getRedirectUrl(): string
    {
        return $this-> getResource()::getUrl('index');
    }
}
