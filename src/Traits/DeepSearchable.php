<?php

namespace FlyingApesInc\DeepSearch\Traits;

use FlyingApesInc\DeepSearch\DeepSearch;

trait DeepSearchable {

    public function deepSearch($search, array $fields, array $relationFields = [])
    {
        $searchModels = [
            'searchFields' => $fields,
            'relations' => []
        ];

        foreach ($relationFields as $relation => $fields) {
            array_push($searchModels['relations'], [
                'relationship' => $relation,
                'searchFields' => $fields,
            ]);
        }

        return DeepSearch::find($search, self::class, $searchModels);
    }

}
