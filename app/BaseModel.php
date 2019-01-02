<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public function refresh() {
        // Re-leer relationships
        foreach(array_keys($this['relations']) as $eloquent_rel) {
            $this->load($eloquent_rel);
        }
    }
}

?>