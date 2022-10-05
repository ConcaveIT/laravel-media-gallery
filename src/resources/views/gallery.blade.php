@include('concaveit_media::includes/styles')

@if(isset($_GET['gallery']) && $_GET['gallery'] == 'self')
    <style>
        #concave_media_gallery {
            background: #fff;
            box-shadow: none !important;
            width: 98vw;
        }
        .concave_close,.concave_media_footer{display: none;}
    </style>
@endif

<section id="concave_media_gallery">
    <div class="concave_media_header">
        <h5>ADD MEDIA TO GALLERY</h5>
        <p class="search_media"><input type="text" id="search_media" name="search_media" placeholder="Search Media.." class="form-control"></p>
        <ul class="concave_media_tab">
            <li data-should-show="c_upload_media" class="media_upload_refresh">Upload Media</li>
            <li data-should-show="c_media_gallery" class="c_active_tab media_gallery_refresh">Media Gallery</li>
        </ul>
        <span class="concave_close">X</span>
        <hr>
    </div><br>
    <div class="concave_media_filter"></div>
    <div class="concave_media_body">
        
            <div id="c_upload_media" class="upload_files c_tab_items">
                <form action="{{ route('concave.media.upload')}}" method="post" class="dropzone" id="my-awesome-dropzone">
                    @csrf
                    <input type="hidden" name="imageWidth" value="{{$requestedData['imageWidth']}}">
                    <input type="hidden" name="imageHeight" value="{{$requestedData['imageHeight']}}" >
                    <input type="hidden" name="imageResize" value="{{$requestedData['imageResize']}}" >
                    <input type="hidden" name="fileLocation" value="{{$requestedData['fileLocation']}}" >
                </form>
            </div>
            <div id="c_media_gallery" class="media_gallery c_tab_items">

                <div class="c-grid-container">
                
                    <div class="concave_media_body_left c-grid-item">
                        <ul>
                             @include('concaveit_media::list')
                        </ul>
                       <br>
                        @if($images->lastPage() > 1)
                            <p class="text-center"><a class="read_more" data-last-page="{{$images->lastPage()}}" data-page-number="1" href="javascript:void(0)">Load More</a></p>
                        @endif
                    </div>
                   
                   <div class="concave_media_body_right c-grid-item"></div>

                <div class="concave_media_footer">
                    <p class="right_button"><button class="add_selected_media c_disabled" disabled>Add Selected Media</button></p>
                </div>

            </div>
    </div>
    @include('concaveit_media::includes/scripts')
</section>




