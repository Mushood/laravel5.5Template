<?php

namespace App\Traits;

use App\Models\Image;

trait Media
{
    /**
     * Upload a new image
     *
     * @param Request $request Illuminate\Http\Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function multipleUpload($files, $disk, $folder)
    {
        foreach ($files as $key => $file) {
            if ($file == null ) {
                return response()->json(
                    [
                        'error' => true
                    ]
                );
            }

            $originalName   = $file->getClientOriginalName();
            $filename       =  Carbon::now()->timestamp . '_' . $originalName;

            $manager = new ImageManager();
            $savedFile = $manager->make($file->getRealPath())->resize(1200, 800);
            $savedFile = new InterventionWrapperImage($savedFile);
            Storage::disk($disk)->putFileAs($folder, $savedFile, $filename);

            $uploadedFile = new Image();
            $uploadedFile->name = $filename;
            $uploadedFile->alt = $filename;
            $uploadedFile->save();
        }


        return [
            'id' => $uploadedFile->id,
            'filename' => $filename
        ];
    }
}