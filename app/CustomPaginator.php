<?php

namespace App;

use Illuminate\Pagination\LengthAwarePaginator;

class CustomPaginator extends LengthAwarePaginator
{
    public function url($page)
    {
        if($page <= 0 || $page > $this->lastPage()){
            return null;
        }

        return $this->path().','.$page;
    }
}
