<?php 
namespace Concaveit\Media\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller{

    public function gallery(Request $request){

        $requestedData = [
            'inputName' => $request->inputName,
            'inputType' => $request->inputType
        ];

        $images = \DB::table('concave_media')->orderby('id','desc')->paginate(32);
        return view('concaveit_media::gallery',compact('images','requestedData'));
    }

    public function refreshGallery(Request $request){
        $images = \DB::table('concave_media')->orderby('id','desc')->paginate(32);
        return view('concaveit_media::list',compact('images'));
    }

    public function uploadfiles(Request $request){

		$input = $request->all();
		$rules = array(
		    'file' => 'image|max:10000',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails())
		{
			 return response()->json($validation->errors->first(), 400);
		}


        if ($request->hasFile('file')){
            $uploadedImage = $request->file('file');
            $imageName = time() . '.' . $request->file->getClientOriginalExtension();
            $destinationPath = public_path().'/media/';
            $upload_success = $uploadedImage->move($destinationPath, $imageName);
        }

        if( $upload_success ) {
            $data = [
                'title' =>  $imageName,
                'file_url' => 'media/'.$imageName
            ];
            \DB::table('concave_media')->insert($data);
            return response()->json('success', 200);
        } else {
            return response()->json('error', 400);
        }

	}
}