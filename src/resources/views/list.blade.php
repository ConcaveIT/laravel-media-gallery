@foreach ($images as $image)
    <li 
        data-file-url="{{$image->file_url ?? ''}}"
        data-filename="{{$image->title ?? ''}}"
        data-file-extension="{{$image->file_extension ?? ''}}"
        data-file-alt="{{$image->alt_text ?? ''}}"
        data-file-description="{{$image->description ?? ''}}"
        data-file-full-url="{{url($image->file_url) ?? ''}}"
        data-filesize="{{ $image->filesize ?? '' }}"
        data-file-dimension="{{ $image->file_dimension ?? '' }}"
        data-fileupload-time="{{ date('d M, Y h:ia',strtotime($image->created_at)) ?? '' }}"
        data-file-id="{{$image->id ?? '' }}"
    >
    
        @if($image->file_url)
            @if(file_exists(public_path().'/'.$image->file_url))
                <img src="{{ url('/').'/'.$image->file_url}}">
                <p>{{$image->title}}</p>
            @else
                <img src="{{ url('/').'/no_image.png'}}">
                <p>{{$image->title}}</p>
            @endif
        @endif
    </li>
@endforeach


