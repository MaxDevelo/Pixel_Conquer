<?php

namespace App\Listeners;

use App\Events\SetPixelColor;
use App\Models\Pixel;
use UpdateCarte;

class UpdatePixelColor
{
    public function handle(\App\Events\UpdateCarte $event)
    {
        $pixel = Pixel::where('x', $event->pixel->x)
                      ->where('y', $event->pixel->y)
                      ->first();

        if ($pixel) {
            $pixel->color = $event->pixel->color;
            $pixel->save();
        }
    }
}
