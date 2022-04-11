<?php 
namespace Concaveit\Media\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

use Auth;

class MediaController extends Controller{

    public function __construct(){
		$this->middleware(['auth']);
    }


    public function gallery(Request $request){
		
        $requestedData = [
            'inputName' => $request->inputName,
            'inputType' => $request->inputType,
            'imageWidth' => $request->imageWidth,
            'imageHeight' => $request->imageHeight,
            'imageResize' => $request->imageResize,
            'fileLocation' => $request->fileLocation,
        ];

        $images = \DB::table('concave_media')->orderby('id','desc')->paginate(40);

        

        foreach($images as $key => $image){
            if(file_exists(public_path().'/'.$image->file_url)){
                $filePath = public_path().'/'.$image->file_url;
                $filesize = filesize($filePath) ? filesize($filePath)/1000 : 0;
                $image->filesize = number_format((float)$filesize, 0, '.', '');

                $imageDimension = getimagesize($filePath);
                $image->file_dimension =  $imageDimension[0] .'x'. $imageDimension[1];
                $image->file_extension = pathinfo($filePath, PATHINFO_EXTENSION);
            }else{
                $images->forget($key);
            }
           
        }
        return view('concaveit_media::gallery',compact('images','requestedData'));
    }

    public function refreshGallery(Request $request){
        $images = \DB::table('concave_media')->orderby('id','desc')->paginate(40);
        
        foreach($images as $key => $image){
            if(file_exists(public_path().'/'.$image->file_url)){
                $filePath = public_path().'/'.$image->file_url;
                $filesize = filesize($filePath) ? filesize($filePath)/1000 : 0;
                $image->filesize = number_format((float)$filesize, 0, '.', '');

                $imageDimension = getimagesize($filePath);
                $image->file_dimension =  $imageDimension[0] .'x'. $imageDimension[1];
                $image->file_extension = pathinfo($filePath, PATHINFO_EXTENSION);
            }else{
                $images->forget($key);
            }
           
        }

        return view('concaveit_media::list',compact('images'));
    }

    public function uploadfiles(Request $request){


		$input = $request->all();
		$rules = array(
		    'file' => 'image|max:10000',
		);

		$validation = Validator::make($input, $rules);

		if ($validation->fails()){
			return response()->json($validation->errors->first(), 400);
		}

        if ($request->hasFile('file')){
            $uploadedImage = $request->file('file');
            $imageTitle = uniqid();
            $imageName = $imageTitle. '.' . $request->file->getClientOriginalExtension();
            $image_resize = Image::make($uploadedImage->getRealPath());
            
            if($request->imageResize  == 'true' ){
                $image_resize->fit($request->imageWidth, $request->imageHeight);
            }    
            $image_resize->encode($request->file->getClientOriginalExtension(), 65);
            $filePath = '';

            if($request->fileLocation){
                $upload_success = $image_resize->save(public_path('/'.$request->fileLocation.'/' .$imageName));
                $filePath = $request->fileLocation.'/' .$imageName;
            }else{
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
            }

            

        }

        if( $upload_success ) {
            $data = [
                'title' =>  $imageTitle,
                'file_url' => $filePath,
                'uploaded_by' => \Auth::id(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            \DB::table('concave_media')->insert($data);
            return response()->json('success', 200);
        } else {
            return response()->json('error', 400);
        }

	}


    public function delete_file($id){
        $data = \DB::table('concave_media')->where('id',$id)->first();
        $fileUrl = $data->file_url;
        $Originalfile =  public_path().'/'.$fileUrl;
        \DB::table('concave_media')->delete($id);
        unlink($Originalfile);
        return response()->json('success', 200);
    }

    public function update_file(Request $request){
       
        $rules = array(
		    'title' => 'required | unique:concave_media',
            'id'    => 'required'
		);

		$validation = Validator::make($request->all(), $rules);

		if ($validation->fails()){
            $result = ['msg'   => '<p class="c_message c_b_red">Title is required!</p>'];
			return response()->json( $result, 200);
		}


        $id = $request->id;
        $title = $request->title;
        $altText = $request->altText;
        $description = $request->description;
        $data = \DB::table('concave_media')->where('id',$id)->first();
        $fileUrl = $data->file_url;
        $filePath = public_path().'/'.$fileUrl;
        $file_extension = pathinfo($filePath, PATHINFO_EXTENSION);
        //$Originalfile = public_path().'/'.$fileUrl;
        //$Newfile = public_path().'/media/'.$title.'.'.$file_extension;

        $newData = [
            'title' => $title,
            'alt_text' => $altText,
            //'file_url' => 'media/'.$title.'.'.$file_extension,
            'description' => $description
        ];

        \DB::table('concave_media')->where('id',$id)->update($newData);
        //rename($Originalfile,$Newfile);
        $result = [
            'msg'   => '<p class="c_message c_b_green">Updated!</p>',
            'title' => $title,
            'alt_text' => $altText ?? '',
            'description' => $description ?? '',
            //'file_url' => url('/').'/media/'.$title.'.'.$file_extension,
            'file_extension' => $file_extension,
        ];
        return response()->json($result, 200);
    }



    
}