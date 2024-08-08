<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ProductImages;
use Illuminate\Validation\ValidationException;

class FileController extends Controller
{   
    static $default = 'default.jpg';
    static $diskName = 'images';

    static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg'],
        'auction' => ['png', 'jpg', 'jpeg'],
    ];

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }
    
    private static function defaultAsset(String $type) {
        return asset($type . '/' . self::$default);
    }
    
    private static function getFileName (String $type, int $id) {
            
        $fileName = null;
        switch($type) {
            case 'profile':
                $fileName = User::find($id)->profile_pic;
                break;
            case 'auction':
                $fileName = ProductImages::firstWhere('auction_id', $id)->image;
                break;
            }
    
        return $fileName;
    }
    
    static function get(String $type, int $userId) {
    
        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }
    
        // Validation: file exists
        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset($type . '/' . $fileName);
        }
    
        // Not found: returns default asset
        return self::defaultAsset($type);
    }

    private static function isValidExtension(String $type, String $extension) {
        $allowedExtensions = self::$systemTypes[$type];

        // Note the toLowerCase() method, it is important to allow .JPG and .jpg extensions as well
        return in_array(strtolower($extension), $allowedExtensions);
    }

    
    function upload(Request $request) {
        // Parameters
        $file = $request->file('file');
        $type = $request->type;
        $id = $request->id;
        $extension = $file->getClientOriginalExtension();
        if (!$file->isValid()) {
            throw ValidationException::withMessages(['image' => 'Image cannot be loaded, try a different one']);
        }
        if (!$this->isValidExtension($type, $extension)) {
            throw ValidationException::withMessages(['image' => 'Unsupported upload extension']);
        }
        // Hashing
        $fileName = $file->hashName(); // generate a random unique id

        switch($request->type) {
            case 'profile':
                $user = User::findOrFail($request->id);
                if ($user) {
                    $user->profile_pic = $fileName;
                    $user->save();
                } else {
                    throw ValidationException::withMessages(['image' => 'Unknown user']);
                }
                break;

            case 'auction':
                $product_image = ProductImages::where('auction_id', $request->id)->firstOrFail();
                if ($product_image) {
                    $product_image->image = $fileName;
                    $product_image->save();
                } else {
                    throw ValidationException::withMessages(['image' => 'Unknown auction']);
                }
                break;


            default:
            throw ValidationException::withMessages(['image' => 'Error: Unsupported upload object']);
        }
        // Save in correct folder and disk
        $request->file->storeAs($type, $fileName, self::$diskName);
        return redirect()->back();
    }

}
