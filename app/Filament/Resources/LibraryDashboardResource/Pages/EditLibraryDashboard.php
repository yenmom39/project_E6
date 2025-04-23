<?php

namespace App\Filament\Resources\LibraryDashboardResource\Pages;

use App\Filament\Resources\LibraryDashboardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLibraryDashboard extends EditRecord
{
    protected static string $resource = LibraryDashboardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    //function RedirectUrl
    protected function getRedirectUrl(): string
    {
        return $this-> getResource()::getUrl('index');
    }
}
