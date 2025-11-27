<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Setting extends \Backpack\Settings\app\Models\Setting
{
    /**
     * For image uploads.
     * See https://backpackforlaravel.com/docs/4.1/crud-fields#image-1
     *
     * Since value is used between all settings of all types we need be more
     * clever about how we save our data.
     *
     * @param $value
     * @return void
     */
    public function setValueAttribute($value)
    {
        // Handle all none image fields
        $fieldConfiguration = json_decode($this->field);
        if ($fieldConfiguration->type !== 'image') {
            // this is the default behaviour
            $this->attributes['value'] = $value;
        }

        // We can assume we're dealing with an image from this point on
        $disk = 'uploads';
        $prefix = 'uploads/';
        $destination_path = app('tenant')->name."/setting";

        // We're deleting the image
        if (is_null($value) && $this->value != null) {
            Storage::disk($disk)->delete($this->value);
            $this->attributes['value'] = null;
        }

        // Backpack-Settings attempts to save the image as base 64.
        // if a base64 was sent, store it in the db
        if (Str::startsWith($value, 'data:image')) {
            // 0. Make the image
            $image = \Image::make($value)->encode('png', 90);

            // 1. Generate a filename.
            $filename = md5($value . time()) . '.png';

            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path . '/' . $filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            $previousPath = Str::replaceFirst("$prefix/", '', $this->value);
            if( $previousPath){
                \Storage::disk($disk)->delete($previousPath);
            }

            // 4. Save the public path to the database
            $public_destination_path = $prefix . $destination_path;
            $this->attributes['value'] = $public_destination_path . '/' . $filename;
        }
    }
}
