<?php

namespace FlyingApesInc\DeepSearch;

/**
 * Advanced model and relationships search for Laravel
 *
 * @package deepsearch
 * @author Flying Apes Inc.
 */
class DeepSearch
{   
    /**
     * Main process
     *
     * @param string $search
     * @param Illuminate\Database\Eloquent\Builder $builder
     * @param array $searchSchema
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function find($search, $builder, $searchSchema)
    {
        // Remove special characters and split the search
        $cleanSearch = preg_replace('/[^ \w]+/', ' ', $search);
        $cleanSearch = str_replace('  ', ' ', $cleanSearch);
        $splitSearch = explode(' ', $cleanSearch);

        $results = $builder->where(function($query) use ($splitSearch, $searchSchema) {
            // Deep search every word in every field
            self::deepSearch($query, $splitSearch, [$searchSchema]);
        });

        return $results;
    }

    private static function deepSearch($query, $splitSearch, $currentLevel)
    {
        foreach ($currentLevel as $model) {
            foreach ($model['fields'] as $field) {

                if (!isset($model['relationship'])) {
                    foreach ($splitSearch as $word) {
                        if (!$query) {
                            $query->where($field, 'like', '%' . $word . '%');
                        } else {
                            $query->orWhere($field, 'like', '%' . $word . '%');
                        }
                    }
                } else {
                    $query->orWhereHas($model['relationship'], function($relQuery) use ($splitSearch, $field) {
                        foreach ($splitSearch as $word) {
                            $relQuery->where($field, 'like', '%' . $word . '%');
                        }
                    });
                }

            }
            
            if (isset($model['relationships'])) {
                self::deepSearch($query, $splitSearch, $model['relationships']);
            }
        }
        return $query;
    }
}
