<?php

namespace App\Traits;

trait ClearableAttributes
{
    public function clearAttributes()
    {
        foreach ($this->getFillable() as $attribute) {
            $this->setAttribute($attribute, null);
        }
    }
}
