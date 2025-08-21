<?php

namespace App\Helper;

use Illuminate\Support\Str;
class MakeSlug
{
    /**
     * Generate a unique slug for a model
     *
     * @param string $name - The name to generate the slug from.
     * @param string $model - The model class name.
     * @param string $column - The column to check uniqueness (default is 'slug').
     * @return string - The unique slug.
     */
    public static function createUniqueSlug($name, $model, $column = 'slug')
    {
        // Generate the initial slug
        $slug = Str::slug($name);

        // Check for duplicates in the specified model and column
        $count = $model::where($column, 'LIKE', "{$slug}%")->count();

        // Increment the slug if duplicates exist
        if ($count) {
            $slug .= '-' . $count;
        }

        return $slug;
    }
}
