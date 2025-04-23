<?php

namespace App\Filament\Resources\LibraryDashboardResource\Pages;

use App\Filament\Resources\LibraryDashboardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLibraryDashboards extends ListRecords
{
    protected static string $resource = LibraryDashboardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
