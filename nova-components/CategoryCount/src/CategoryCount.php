<?php

namespace Hxb\CategoryCount;

use Laravel\Nova\Card;

class CategoryCount extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'CategoryCount';
    }

    public function withName($name)
    {
        $this->name = $name;
        return $this->withMeta(['name' => $this->name]);
    }

    public function withData($data)
    {
        return $this->withMeta(['data' => $data]);
    }
}
