<?php

namespace App\Filament\Resources\ProductFilterTags\Pages;

use App\Filament\Resources\ProductFilterTags\ProductFilterTagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductFilterTag extends CreateRecord
{
    protected static string $resource = ProductFilterTagResource::class;
}
