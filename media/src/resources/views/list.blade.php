<ul>
    @forelse ($images as $image)
        <li data-file-url="{{$image->file_url}}"><img src="{{ url('/').'/'.$image->file_url}}"></li>
    @empty
        <li>No Image Available!</li>
    @endforelse
</ul>