<section id="concave_media_gallery">
    <div class="concave_media_header">
        <h3>Add Images to Gallery</h3>
        <ul class="concave_media_tab">
            <li data-should-show="c_upload_media" class="media_upload_refresh">Upload Image</li>
            <li data-should-show="c_media_gallery" class="c_active_tab media_gallery_refresh">Media Gallery</li>
        </ul>
        <span class="concave_close">X</span>
        <hr>
    </div><br>
    <div class="concave_media_filter"></div>
    <div class="concave_media_body">
        
            <div id="c_upload_media" class="upload_files c_tab_items">
                <form action="{{ route('concave.media.upload')}}" method="post" class="dropzone" id="my-awesome-dropzone"></form>
            </div>

            <div id="c_media_gallery" class="media_gallery c_tab_items">
                <div class="concave_media_body_left">
                     @include('concaveit_media::list')
                    <br>
                    <p class="text-center"><a class="read_more" href="javascript:void(0)">Load More</a></p>
                </div>
                <div class="concave_media_body_right"></div>
                <div class="concave_media_footer">
                    <p class="right_button"><button class="add_selected_media">Add Selected Media</button></p>
                </div>
            </div>
    </div>
</section>

@include('concaveit_media::scripts')



