<?php

namespace App\Library;

use \Intervention\Image\Image as InterventionImage;

class InterventionWrapperImage
{
    /**
     * Intervention image instance.
     *
     * @var \Intervention\Image\Image
     */
    private $image;

    function __construct(InterventionImage $image)
    {
        $this->image = $image;
    }

    function getRealPath()
    {
        return $this->image->basePath();
    }

}