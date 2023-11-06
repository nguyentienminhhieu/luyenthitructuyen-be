<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
    
            $fileUrl = url('uploads/' . $fileName); // Tạo URL từ đường dẫn lưu trữ
            return response()->json(['message' => 'File uploaded successfully', 'url' => $fileUrl]);
        } else {
            return response()->json(['error' => 'No file provided'], 400);
        }
    }
}
