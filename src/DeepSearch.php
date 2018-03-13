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
	public function __construct()
	{

	}

    /**
     * Main process
     *
     * @param string $search
     * @param Illuminate\Database\Eloquent\Model $model
     * @param array $searchModels
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function find($search, $model, $searchModels)
    {
		// Remove special characters and split the search
		$cleanSearch = preg_replace('/[^ \w]+/', ' ', $search);
		$cleanSearch = str_replace('  ', ' ', $cleanSearch);
  		$splitSearch = explode(' ', $cleanSearch);

		if (count($splitSearch) == 0) {
			return $model::select('*');
		}

		$searchModels = [$searchModels];

		$results = $model::where(function($query) use ($splitSearch, $searchModels) {
			// Deep search every word in every field
			self::deepSearch($query, $splitSearch, $searchModels);
		});

		return $results;
    }

    private static function deepSearch($query, $splitSearch, $currentLevel)
    {
    	foreach ($currentLevel as $searchModel) {
			foreach ($searchModel['searchFields'] as $searchField) {

				if (!isset($searchModel['relationship'])) {
					foreach ($splitSearch as $word) {
						if (!$query) {
							$query->where($searchField, 'like', '%' . $word . '%');
						} else {
							$query->orWhere($searchField, 'like', '%' . $word . '%');
						}
					}
				} else {
					$query->orWhereHas($searchModel['relationship'], function($relQuery) use ($splitSearch, $searchField) {
						foreach ($splitSearch as $word) {
							$relQuery->where($searchField, 'like', '%' . $word . '%');
						}
					});
				}

			}
			
			if (isset($searchModel['relations'])) {
				self::deepSearch($query, $splitSearch, $searchModel['relations']);
			}
		}
		return $query;
    }
}
