<?php

namespace eseperio\translatemanager\traits;

/**
 * Since we are tricking system to bypass a protected method, we use a trait to avoid code duplication.
 */
trait ExtractorTrait
{
    public function getCategoryMessages($category, $language)
    {
        return $this->loadMessages($category, $language);
    }
}
