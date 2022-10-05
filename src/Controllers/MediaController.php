<?php 
namespace Concaveit\Media\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
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


        $admin = User::where('id',\Auth::id())->first();
        if($admin->hasRole('admin') || $admin->hasRole('superadmin')){
            $images = \DB::table('concave_media')->orderby('id','desc')->paginate(40);
        }else{
            $images = \DB::table('concave_media')->orderby('id','desc')->where('uploaded_by',\Auth::id())->paginate(40);
        }
		

        foreach($images as $key => $image){
            if(file_exists(public_path().'/'.$image->file_url) || file_exists(storage_path().'/'.$image->file_url)){

                if($image->type == 'image'){
                    $filePath = public_path().'/'.$image->file_url;
                    $filesize = filesize($filePath) ? filesize($filePath)/1000 : 0;
                    $image->filesize = number_format((float)$filesize, 0, '.', '');
                    $imageDimension = getimagesize($filePath);
                    $image->file_dimension =  $imageDimension[0] .'x'. $imageDimension[1];
                    $image->file_extension = pathinfo($filePath, PATHINFO_EXTENSION);
                }elseif($image->type == 'file'){
                    $filePath = storage_path().'/'.$image->file_url;
                    $filesize = filesize($filePath) ? filesize($filePath)/1000 : 0;
                    $image->filesize = number_format((float)$filesize, 0, '.', '');
                    $image->file_extension = pathinfo($filePath, PATHINFO_EXTENSION);
                    $image->file_dimension =  $image->file_extension.' File';
                    $image->file_url = '/storage/'.$image->file_url;
                }

            }
           
        }

        return view('concaveit_media::gallery',compact('images','requestedData'));
    }

    public function refreshGallery(Request $request){

        $admin = User::where('id',\Auth::id())->first();

        if($admin->hasRole('admin') || $admin->hasRole('superadmin')){
            if($keyword = $request->search){
                $images = \DB::table('concave_media')->orderby('id','desc')->where('title','like',"%$keyword%")->get();
            }else{
                $images = \DB::table('concave_media')->orderby('id','desc')->paginate(40);
            }
            
        }else{
            if($keyword = $request->search){
                $images = \DB::table('concave_media')->orderby('id','desc')->where('uploaded_by',\Auth::id())->where('title','like',"%$keyword%")->get();
            }else{
                $images = \DB::table('concave_media')->orderby('id','desc')->where('uploaded_by',\Auth::id())->paginate(40);
            }
        }
		
        
        foreach($images as $key => $image){
            if(file_exists(public_path().'/'.$image->file_url) || file_exists(public_path().'/storage/'.$image->file_url)){

                if($image->type == 'image'){
                    $filePath = public_path().'/'.$image->file_url;
                    $filesize = filesize($filePath) ? filesize($filePath)/1000 : 0;
                    $image->filesize = number_format((float)$filesize, 0, '.', '');
    
                    $imageDimension = getimagesize($filePath);
                    $image->file_dimension =  $imageDimension[0] .'x'. $imageDimension[1];
                    $image->file_extension = pathinfo($filePath, PATHINFO_EXTENSION);
                }elseif($image->type == 'file'){
                    $filePath = public_path().'/storage/'.$image->file_url;
                    $filesize = filesize($filePath) ? filesize($filePath)/1000 : 0;
                    $image->filesize = number_format((float)$filesize, 0, '.', '');
                    $image->file_extension = pathinfo($filePath, PATHINFO_EXTENSION);
                    $image->file_dimension =  $image->file_extension.' File';
                }

            }else{
                $images->forget($key);
            }
           
        }

        return view('concaveit_media::list',compact('images'));
    }

    public function uploadfiles(Request $request){

		$input = $request->all();
		$rules = array(
		    'file' => 'required',
		);

        $allowedfileExtension=['pdf','docx','doc'];
        $allowedImageExtension=['jpg','jpeg','webp','png','gif'];

        $mediaDir = public_path().'/media';
        $thumbnailDir = public_path().'/media/thumbnail/media';
        if(!is_dir($mediaDir)) mkdir( $mediaDir,0777 );
        if(!is_dir($thumbnailDir)) mkdir( $thumbnailDir,0777 );


		$validation = Validator::make($input, $rules);

		if ($validation->fails()){
			return response()->json('error', 400);
		}

        if ($request->hasFile('file')){


            $uploadedFile = $request->file('file');


            $extension = $uploadedFile->getClientOriginalExtension();

            if(in_array($extension,$allowedImageExtension)){
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $extension;
                $image_resize = Image::make($uploadedFile->getRealPath());
                $image_thumbnail = Image::make($uploadedFile->getRealPath())->fit(230,230);
    
                if($request->imageResize  == 'true' ){
                    $image_resize->fit($request->imageWidth, $request->imageHeight);
                }    
                $image_resize->encode($extension, 65);
                $filePath = '';
    
                if($request->fileLocation){
                    $upload_success = $image_resize->save(public_path('/'.$request->fileLocation.'/' .$imageName));
                    $filePath = $request->fileLocation.'/' .$imageName;
                }else{
                    $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                    $filePath = 'media/'.$imageName;
                }
    
                $image_thumbnail->save(public_path('/media/thumbnail/media/'.$imageName),60);
               
                if( $upload_success ) {
                    $data = [
                        'title' =>  str_replace('.'.$request->file->getClientOriginalExtension(),'',$request->file->getClientOriginalName()),
                        'file_url' => $filePath,
                        'thumbnail_url' => 'media/thumbnail/media/'.$imageName,
                        'uploaded_by' => \Auth::id(),
                        'type' => 'image',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($data);
                    return response()->json('success', 200);
                } else {
                    return response()->json('error', 400);
                }

            }elseif(in_array($extension,$allowedfileExtension)){

                $filepath = $request->file('file')->store('public');

                if($filepath) {
                    $filename = explode('/',$filepath)[1];
                    $data = [
                        'title' =>  str_replace('.'.$request->file->getClientOriginalExtension(),'',$request->file->getClientOriginalName()),
                        'file_url' => $filename,
                        'thumbnail_url' => 'fileico.png',
                        'uploaded_by' => \Auth::id(),
                        'type' => 'file',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($data);
                    return response()->json('success', 200);
                } else {
                    return response()->json('error', 400);
                }
                return response()->json('success', 200);

            }else{
                return response()->json('error', 400);
            }

        }


	}


    public function delete_file($id){
        $data = \DB::table('concave_media')->where('id',$id)->first();
        $fileUrl = $data->file_url;
        $Originalfile =  public_path().'/'.$fileUrl;
        \DB::table('concave_media')->delete($id);
        
        if(file_exists($Originalfile)){
            unlink($Originalfile);
        }
        
        return response()->json('success', 200);
    }

    public function delete_multiple_files($id){
        $ids = explode(',',$id);
        $allData = \DB::table('concave_media')->whereIn('id',$ids)->get();
        
        foreach($allData as $data){
            $fileUrl = $data->file_url;
            $thumbnail_url = $data->thumbnail_url;

            if( $thumbnail_url){
                $OriginalfileThumbnail =  public_path().'/'.$thumbnail_url;
                if(file_exists($OriginalfileThumbnail)){
                    unlink($OriginalfileThumbnail);
                }
            }

            if($fileUrl){
                $Originalfile =  public_path().'/'.$fileUrl;
                \DB::table('concave_media')->delete($data->id);
                if(file_exists($Originalfile)){
                    unlink($Originalfile);
                }
            }

        }

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