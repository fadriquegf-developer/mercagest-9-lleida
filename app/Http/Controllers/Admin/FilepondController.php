<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use RahulHaque\Filepond\Facades\Filepond;
use App\Http\Controllers\Controller;
use App\Models\ChecklistAnswer;

class FilepondController extends Controller
{
    /**
     * The restore end point is used to restore a temporary file, the load end point is used to restore already uploaded server files.
     * These files might be located in a database or somewhere on the server file system.
     * Either way they might not be directly accessible from the web.
     */
    public function load($id)
    {
        /* TODO: if need Filepond for for more sections create a Media model */

        // search in Checklist Answer
        $answer = ChecklistAnswer::where('id', $id)->firstOrFail();

        $disk = \Storage::disk('local');
        $path = $answer->img;
        // we check for the existing of the file 
        if (!$disk->exists($path)) {
            abort('404'); // we redirect to 404 page if it doesn't exist
        }

        return response()->file($disk->path($path), [
            'Content-Disposition' => 'inline; filename="' . $answer->id . '"',
        ]);
    }

    /** 
     * FilePond uses the restore end point to restore temporary server files.
     * This might be useful in a situation where the user closes the browser window but hadn't finished completing the form.
     * Temporary files can be set with the files property.
     */
    public function restore($id)
    {
        try {
            $fileInfo = Filepond::field($id)->getModel();
        } catch (\Exception $e) {
            abort('404');
        }

        $disk = \Storage::disk('local');
        $path = $fileInfo->filepath;
        // we check for the existing of the file 
        if (!$disk->exists($path)) {
            abort('404'); // we redirect to 404 page if it doesn't exist
        }

        return response()->file($disk->path($path), [
            'Content-Disposition' => 'inline; filename="' . $fileInfo->filename . '"',
        ]);
    }
}
