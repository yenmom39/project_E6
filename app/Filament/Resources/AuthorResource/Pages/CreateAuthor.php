<?php

namespace App\Filament\Resources\AuthorResource\Pages;

use App\Filament\Resources\AuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\RedirectResponse;



class CreateAuthor extends CreateRecord
{
    protected static string $resource = AuthorResource::class;

     // After a new author is created, redirect to the index page
    protected function getRedirectUrl(): string
    {
        return $this-> getResource()::getUrl('index');
    }
}
