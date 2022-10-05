<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    var base_url = "{{url('/')}}";
    var inputName = "{{$requestedData['inputName']}}";
    var inputType = "{{$requestedData['inputType']}}";
    var fileLocation = "{{$requestedData['fileLocation']}}";
    if(inputType == 'multiple') inputName = inputName+'[]';

    jQuery(document).on('click','.concave_media_body_left ul li',function(){
            if(inputType == 'multiple'){
                if (window.event.ctrlKey) {
                    if(jQuery(this).hasClass('concave_selected') == true){
                        jQuery(this).removeClass('concave_selected');
                    }else{
                        jQuery(this).addClass('concave_selected');
                        jQuery('.add_selected_media').removeClass('c_disabled');
                        jQuery('.add_selected_media').removeAttr('disabled');
                    }
                }else{
                    jQuery('.concave_selected').each(function(key,val){
                        jQuery(this).removeClass('concave_selected');
                    });
                    jQuery(this).addClass('concave_selected');
                    jQuery('.add_selected_media').removeClass('c_disabled');
                    jQuery('.add_selected_media').removeAttr('disabled');
                }
            }else{
                jQuery('.concave_selected').each(function(key,val){
                    jQuery(this).removeClass('concave_selected');
                });
                jQuery(this).addClass('concave_selected');
                jQuery('.add_selected_media').removeClass('c_disabled');
                jQuery('.add_selected_media').removeAttr('disabled');
            }
    });

    //Showing Details
    jQuery(document).on('click','.concave_media_body_left ul li',function(){
        var html = '';
        var itemCount = jQuery('.concave_selected').length;
        if(itemCount > 1){
            
            var totalFilesize = 0;
            var selectedMedia = '';
            jQuery('.concave_selected').each(function(key,val){
                totalFilesize+=  parseInt(jQuery(this).attr('data-filesize'));
                selectedMedia += jQuery(this).attr('data-file-id')+',';
            });

            html = '<p><b>Files:</b> '+itemCount+' Files Selected.</p>'+
                   '<p><b>Total File Size:</b> '+totalFilesize+' KB</p>'+
                   '<p><button data-selected-files="'+selectedMedia+'" class="c_btn c_multiple_delete_btn">Delete All</button></p>';

        }else{
            var fileName = jQuery(this).attr('data-filename');
            var fileAlt = jQuery(this).attr('data-file-alt');
            var fileUrl = jQuery(this).attr('data-file-full-url');
            var fileSize = jQuery(this).attr('data-filesize');
            var fileDescription = jQuery(this).attr('data-file-description');
            var fileUploadTime = jQuery(this).attr('data-fileupload-time');
            var fileId = jQuery(this).attr('data-file-id');
            var fileExtension= jQuery(this).attr('data-file-extension');
            var fileDimension = jQuery(this).attr('data-file-dimension');
            

            html = '<h5>ATTACHMENT DETAILS</h5>'+
                    '<p><b>Title:</b> <span class="attachment_title">'+fileName+'</span>.'+fileExtension+'</p>'+
                    '<p><b>Url:</b> <code class="attachment_url" >'+fileUrl+'</code></p>'+
                    '<p><b>Size:</b> '+fileSize+' KB</p>'+
                    '<p><b>Dimension</b> (width x height): '+fileDimension+'</p>'+
                    '<p><b>Upload Time:</b> '+fileUploadTime+'</p>'+
                    '<p><button data-file-id="'+fileId+'" class="c_btn c_delete_btn">Delete</button></p><hr>'+
                    '<div class="update_meta"><h5>UPDATE META INFORMATION</h5><form id="update_meta">'+
                        '<input type="hidden" name="id" value="'+fileId+'"><br>'+
                        'Title: <br><input type="text" name="title" value="'+fileName+'" placeholder="Title"><br>'+
                        'Alt Text: <br><input type="text" name="altText" value="'+fileAlt+'" placeholder="Alt Text"><br>'+
                        'Description: <br><textarea placeholder="Description" name="description">'+fileDescription+'</textarea><br>'+
                        '<button type="submit" class="c_btn c_edit_btn">Update</button>'+
                    '</form></div>';
        }
        jQuery('.concave_media_body_right').html(html);

    });



    jQuery(document).on('click','.concave_close',function(){
        jQuery('.triggeredButton').removeClass('triggeredButton');
        jQuery('#concave_media_gallery').remove();
    });

    jQuery(document).on('click','.add_selected_media',function(){
       
        if(jQuery('.triggeredButton').attr('data-input-type') == 'single'){
            jQuery('.triggeredButton').next('.selected_images_gallery').remove();
        }
        
        var displayHtml = '<p class="selected_images_gallery">';
        jQuery('.concave_selected').each(function(key,val){
            var fileUrl = jQuery(this).attr('data-file-url');
            var fileId = jQuery(this).attr('data-file-id');
            var fileType = jQuery(this).attr('data-file-type');
            if(fileType == 'image'){
                displayHtml += '<span><input type="hidden" value="'+fileUrl+'" name="'+inputName+'"><img src="'+base_url+'/'+fileUrl+'"> <b data-file-url='+fileUrl+' class="selected_image_remove">X</b></span>';
            }else{
                displayHtml += '<span><input type="hidden" value="'+fileUrl+'" name="'+inputName+'"><img src="'+base_url+'/fileico.png"> <b data-file-url='+fileUrl+' class="selected_image_remove">X</b></span>';
            }
            
        });
        displayHtml += '</p>';

        jQuery('.triggeredButton ').after(displayHtml);
        jQuery('.triggeredButton').removeClass('triggeredButton');
        jQuery('#concave_media_gallery').remove();
    });

    jQuery(document).on('click','.selected_image_remove',function(){
        var removeItem = jQuery(this).attr('data-file-url');
        jQuery(this).closest('span').remove();
        jQuery('input[value="'+removeItem+'"]').remove();
    });

    jQuery(document).on('click','.concave_media_tab li',function(){
        jQuery('.c_tab_items').hide();
        jQuery('.concave_media_tab li').removeClass('c_active_tab');
        jQuery(this).addClass('c_active_tab');
        var selectedItem = jQuery(this).attr('data-should-show');
        jQuery('#'+selectedItem).show();
    });

    jQuery(document).on('click','.media_gallery_refresh',function(){
        jQuery('.read_more').attr('data-page-number',1);
        jQuery('.read_more').show();
        jQuery.ajax({
            url: "{{route('concave.gallery.refresh')}}",
                type: "get",
                data: {page:1} ,
                success: function (response) {
                    jQuery('.concave_media_body_left ul').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
        });
    });

    jQuery(document).on('click','.c_delete_btn',function(){
        jQuery.ajax({
            url: "/concave-media/delete/"+jQuery(this).attr('data-file-id'),
                type: "post",
                data: {} ,
                success: function (response) {
                    if(response == 'success'){
                        jQuery('.concave_selected').remove();
                        jQuery('.concave_media_body_right').html('');
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
        });
    });

    jQuery(document).on('click','.c_multiple_delete_btn',function(){
        jQuery.ajax({
            url: "/concave-media/delete/multiple/"+jQuery(this).attr('data-selected-files'),
                type: "post",
                data: {} ,
                success: function (response) {
                    if(response == 'success'){
                        jQuery('.concave_selected').remove();
                        jQuery('.concave_media_body_right').html('');
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
        });
    });

    

    jQuery(document).on('submit','#update_meta',function(e){
        e.preventDefault();
        jQuery('.c_message').remove();
        jQuery('.c_edit_btn').html('Updating...');
        jQuery('.c_edit_btn').attr('disabled','true');
        
        jQuery.ajax({
            url: "{{route('concave.media.update')}}",
                type: "post",
                data: jQuery(this).serialize() ,
                success: function (response) {
                    if(response){
                        jQuery('.attachment_title').html(response.title);
                        jQuery('.attachment_url').html(response.file_url);
                        jQuery('#update_meta').after(response.msg);
                        jQuery('.concave_selected').attr('data-file-full-url',response.file_url);
                        jQuery('.concave_selected').attr('data-filename',response.title);
                        jQuery('.concave_selected').attr('data-file-extension',response.file_extension);
                        jQuery('.concave_selected').attr('data-file-alt',response.alt_text);
                        jQuery('.concave_selected').attr('data-file-description',response.description);
                        jQuery('.c_edit_btn').html('Update');
                        jQuery('.c_edit_btn').removeAttr('disabled');
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
        });
    });

    

    jQuery(document).on('click','.media_upload_refresh',function(){
        jQuery('#my-awesome-dropzone').dropzone();
        Dropzone.options.myAwesomeDropzone = { 
            paramName: "file",
            maxFilesize: 10,
            accept: function(file, done) {
                 done();
            }
        };
    });

    jQuery(document).on('click','.read_more',function(){
        var page = parseInt(jQuery(this).attr('data-page-number'));
        var lastPage = parseInt(jQuery(this).attr('data-last-page')); 
        if(page <= lastPage ){
            jQuery(this).html('Loading...')
            jQuery.ajax({
                url: "{{route('concave.gallery.refresh')}}",
                    type: "get",
                    data: {page:page+1} ,
                    success: function (response) {
                        jQuery('.concave_media_body_left ul').append(response);
                        jQuery('.read_more').attr('data-page-number',page+1)
                        jQuery('.read_more').html('Load more');
                        if(page+1 == lastPage )  jQuery('.read_more').hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
            });
        }
    }); 
    
    
    jQuery(document).on('keyup','#search_media',function(){
        jQuery('.read_more').attr('data-page-number',1);
        jQuery('.read_more').hide();
        var keyword = jQuery(this).val();
        
        if(keyword == ''){
            jQuery('.read_more').show(); 
        }

        jQuery.ajax({
            url: "/concave-gallery-refresh?search="+keyword,
                type: "get",
                data: {page:1} ,
                success: function (response) {
                    jQuery('.concave_media_body_left ul').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
        });
    });

</script>

