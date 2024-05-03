<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\Page;

class SortUsers extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.sort-users';
}
