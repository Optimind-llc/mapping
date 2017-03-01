<?php

namespace App\Http\Controllers\Press\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Storage;
// Models
// use App\Models\Vehicle950A\InspectionResult;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PrintController
 * @package App\Http\Controllers\Press\Client
 */
class PrintController extends Controller
{
    public function saveImgOld(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $request->name;

            $realPath = $file->getRealPath();

            $file_info = $_FILES['file'];

            // Storage::put(
            //     'screenshot/'.$fileName.'.png',
            //     file_get_contents($realPath)
            // );

            // $oldPath = storage_path('app/screenshot').DIRECTORY_SEPARATOR.$fileName.'.png';
            // $newPath = config('path.'.config('app.server_place').'.Press.screenshot').DIRECTORY_SEPARATOR.$fileName.'.png';

            // rename($oldPath, $newPath);
        }

        return \Response::json([
            'fileInfo' => $file_info,
            'file' => $file,
            'fileName' => $fileName,
            'realPath' => $realPath,
            'storagePath' => storage_path()
        ], 200);
    }


    public function saveImg(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $_FILES['file'];
            $fileName = $request->name;

            $tmpPath = $file['tmp_name'];
            $savePath = config('path.'.config('app.server_place').'.Press.screenshot').DIRECTORY_SEPARATOR.$fileName.'.png';

            try {
                if(is_uploaded_file($tmpPath)){

                    // return \Response::json([
                    //     'tmpPath' => $tmpPath,
                    //     'savePath' => $savePath
                    // ], 200);

                    move_uploaded_file($tmpPath, $savePath);
                }

                return \Response::json([
                    'status' => 200,
                    'message' => 'upload files succeeded'
                ], 200);
            }
            catch(Exception $e) {
                return \Response::json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        }
        else {
            return \Response::json([
                'status' => 400,
                'message' => 'No filese uploaded'
            ], 400);
        }
    }
}
