<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class ProductSearch
{
    public function apply(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        $keywords = preg_split('/\s+/', trim($search));

        return $query->where(function ($q) use ($keywords) {

            foreach ($keywords as $word) {

                $word = '%' . $word . '%';

                $q->where(function ($sub) use ($word) {

                    $sub->whereRaw($this->like('products.name'), [$word])
                        ->orWhereHas('brand', function ($q) use ($word) {
                            $q->whereRaw($this->like('name'), [$word]);
                        })
                        ->orWhereHas('variants', function ($q) use ($word) {
                            $q->whereRaw($this->like('name'), [$word]);
                        });

                });
            }

        });
    }

    private function like(string $column): string
    {
        return match (config('database.default')) {
            'pgsql' => "$column ILIKE ?",
            default => "$column LIKE ?",
        };
    }
}