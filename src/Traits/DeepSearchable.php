<?php

namespace FlyingApesInc\DeepSearch\Traits;

use FlyingApesInc\DeepSearch\DeepSearch;

trait DeepSearchable {

    public function scopeDeepSearch($query, $search, array $fields, array $relationFields = [])
    {
        $searchSchema = [
            'fields' => $fields,
            'relationships' => []
        ];

        foreach ($relationFields as $relation => $fields) {
            $searchSchema['relationships'][] = [
                'relationship' => $relation,
                'fields' => !is_array($fields) ? [$fields] : $fields,
            ];
        }

        return DeepSearch::find($search, $query, $searchSchema);
    }

}
