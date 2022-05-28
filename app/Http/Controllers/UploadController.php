<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class UploadController extends Controller
{
    //
    public function uploadTongue(Request $req) {
        $req->validate(array(
            'file' => 'required|image',
        ));
        try{
            $image = $req->file('file');
            $photo_name =strtotime(now()).'.'.$req->file->extension();
            $path='uploads/tongues';
            $image->move($path,$photo_name );

            return response()->json([
                'status' => 200,
                'message' => 'Image Upload successfully',
            ], 200);
        }
        catch (Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 500);
        }

    // var_dump("FSDFDSFSDFDSFSD");die();
    // if ($req->file('file')){

        // // $image = $req->file('file');
        // // $name = time().'.'.$image->getClientOriginalExtension();
        // // // $destinationPath = public_path('uploads/tongues/');
        // // $destinationPath = 'uploads/tongues/';
        // // $image->move($destinationPath, $name);
        // // $this->save();
        // // return back()->with('success','Image Upload successfully');

        //  $res = array(
        //     'status' => 200,
        //     'message' => 'Image Upload successfully!'
        // );
        // return json_encode($res);
        // $image = $_POST['file'];
        // $name = $_POST['filename'];
     
        // $realImage = base64_decode($image);
     
        // file_put_contents('uploads/tongues/'.$name, $realImage);
        

        // $res = array(
        //     'status' => 200,
        //     'message' => 'Image Upload successfully!'
        // );
        // return json_encode($res);
        // // Save Image Data
        // $uploadImage = new UploadImage();
        // $uploadImage->image_name = $name;
        // $uploadImage->image_path = '/upload/images'.$name;
        // $uploadImage->deviceid = $req->deviceid;

        // if($uploadImage->save()) {
        //     $res = array(
        //         'status' => 200,
        //         'message' => 'Image Uploaded Successfully!'
        //     );
        // } else {
        //     $res = array(
        //         'status' => 400,
        //         'message' => 'Database error!'
        //     );
        // }
        // return back()->with('success','Image Upload successfully');

        // return json_encode($res);
    // } else {

    //      $res = array(
    //         'status' => 300,
    //         'message' => 'Image Data invalied!'
    //     );
    //     return json_encode($res);
    // }
  }
}
