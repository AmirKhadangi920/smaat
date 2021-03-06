<?php namespace App\ModelFilters\Feature;

use App\ModelFilters\MainFilter;
use App\ModelFilters\Query;
use App\ModelFilters\SimpleOrdering;

class FeatureBaseFilter extends MainFilter
{
    use Query, SimpleOrdering;
    
    /**
     * Filter the Features that have a logo or not
     *
     * @param boolean $status
     * @return Builder
     */
    public function hasLogo($status)
    {
        if ( !($this->has_logo ?? false) ) return;
        
        $this->has_field_or_not('logo', $status);
    }
    
    /**
     * Filter the Data that have active or not
     *
     * @param boolean $status
     * @return Builder
     */
    public function isActive($status)
    {
        return $this->where('is_active', $status);
    }

    /**
     * Filter the Features that have specific categories or not
     *
     * @param boolean $status
     * @return Builder
     */
    public function hasCategories($status)
    {
        $this->has_relation_or_not('categories', $status);
    }

    /**
     * Filter the Features that have specific categoriy
     *
     * @param array $ids
     * @return Builder
     */
    public function categories($ids)
    {
        return $this->filter_relation('categories', $ids);
    }


    /**
     * Filter the Features that have specific categoroy
     * or doesn't have any category
     *
     * @param array $ids
     * @return Builder
     */
    public function category($id)
    {
        if ( !$id ) return;

        $this->whereDoesntHave('categories');

        return $this->orWhereHas('categories', function($query) use ($id)
        {
            $query->where('id', $id);
        });
    }
}
