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
     * @param string  $search
     * @param \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function find($search, $model, $searchModels)
    {
		// Remove special characters and split the search
		$cleanSearch = preg_replace('/[^ \w]+/', ' ', $search);
		$cleanSearch = str_replace('  ', ' ', $cleanSearch);
  		$splitSearch = explode(' ', $cleanSearch);

		if (count($splitSearch) == 0) {
			return  new Collection(['result' => 'Empty Search']);
		}

		$results = $model::where(function($query) use ($splitSearch, $searchModels) {
			// Deep search every word in every field
			$this->deepSearch($query, $splitSearch, $searchModels);
		})->get();

		return $results;
    }

    private function deepSearch($query, $splitSearch, $currentLevel)
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

				if (isset($searchModel['innerRelation'])) {
					$this->deepSearch($query, $splitSearch, $searchModel['innerRelation']);
				} else {
					return $query;
				}

			}
		}
    }
}
