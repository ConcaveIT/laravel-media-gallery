<style>
    #concave_media_gallery {position: fixed;top: 50%; z-index: 999999 !important;background: #fff;width: 90vw;height: 90vh;left: 50%;transform: translate(-50%,-50%);padding: 25px;box-shadow: 0px 40px 115px 54px #2a2a2a;overflow: hidden;}
    .concave_media_tab {padding: 0; margin: 0;}
    .text-center{text-align: center;}
    .concave_media_tab li,.concave_media_body_left ul li{list-style: none; display: inline-block;border: 1px solid transparent;}
    .concave_media_tab li {margin-right: 15px;padding: 7px;cursor: pointer;}
    .concave_media_tab li:hover {background: #000;color: #fff;}
    .concave_media_header hr,.concave_media_body_left ul {padding: 0;margin: 0;}
    .concave_media_header h3{text-transform: uppercase;}
    .concave_media_body_left{width: 80%;border-right:1px solid rgb(230, 230, 230);overflow-y: scroll;height: 68vh;}
    .concave_media_body_right{width: 20%}
    .concave_media_body_left li img {padding: 4px;cursor: pointer;width: 150px;height: 150px;object-fit: cover;}
    .concave_media_footer {position: absolute;bottom: 0;width: 100%;left: 0;}
    .concave_media_footer .right_button {padding: 20px;background: #efefef;margin: 0 5px 0 0;text-align: right;}
    .concave_media_footer button{background: #2d95ff;border: none;padding: 8px;border-radius: 5px;color: #fff;cursor: pointer;}
    .concave_selected {background: #2d95ff;position: relative;}
    .concave_selected::after {content: "\2713";position: absolute;right: 20px;top: 10px;font-size: 17px;color: #fff;font-weight: bold;background: #2d95ff;width: 26px;height: 26px;vertical-align: middle;text-align: center;border-radius: 50%;}
    .concave_close {position: absolute;right: 21px;top: 17px;background: #d91515;color: #fff;padding: 3px 10px;border-radius: 7px;cursor: pointer;font-size: 16px;font-weight: bold;font-family: cursive;}
    .read_more{background: #2883b5;color: #fff !important;padding: 7px 15px;border-radius: 5px;text-decoration: none !important;}
    .selected_images_gallery {margin-top: 15px;}
    .selected_images_gallery span{margin-right: 10px; position: relative;}
    .selected_images_gallery span img{width:110px;}
    .selected_images_gallery span b {position: absolute;right: 0;color: #fff;background: #d71414;padding: 1px 7px;font-family: cursive;cursor: pointer;border-radius: 50%;}
    .c_active_tab {background: #2d95ff;color: #fff;}
    #c_upload_media{display: none;}
    .dropzone {border: 2px dashed #ddd !important;}
    .concave_media_body_left ul li:hover {border: 1px solid #2d95ffb5;}
</style>

<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    var base_url = "{{url('/')}}";
    var inputName = "{{$requestedData['inputName']}}";
    var inputType = "{{$requestedData['inputType']}}";
    if(inputType == 'multiple') inputName = inputName+'[]';
    
    jQuery(document).on('click','.concave_media_body_left ul li',function(){
        if(inputType == 'multiple'){
                if (window.event.ctrlKey) {
                    if(jQuery(this).hasClass('concave_selected') == true){
                        jQuery(this).removeClass('concave_selected');
                    }else{
                        jQuery(this).addClass('concave_selected');
                    }
                }else{
                    jQuery('.concave_selected').each(function(key,val){
                        jQuery(this).removeClass('concave_selected');
                    });
                    jQuery(this).addClass('concave_selected');
                }
            }else{
                jQuery('.concave_selected').each(function(key,val){
                    jQuery(this).removeClass('concave_selected');
                });
                jQuery(this).addClass('concave_selected');
            }
    });

    jQuery(document).on('click','.concave_close',function(){
        jQuery('#concave_media_gallery').remove();
    });

    jQuery(document).on('click','.add_selected_media',function(){
        var displayHtml = '<p class="selected_images_gallery">';
        jQuery('.concave_selected').each(function(key,val){
            var fileUrl = jQuery(this).attr('data-file-url');
            displayHtml += '<span><input type="hidden" value="'+fileUrl+'" name="'+inputName+'"><img src="'+base_url+'/'+fileUrl+'"> <b data-file-url='+fileUrl+' class="selected_image_remove">X</b></span>';
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
        jQuery.ajax({
            url: "{{route('concave.gallery.refresh')}}",
                type: "get",
                data: {page:1} ,
                success: function (response) {
                    jQuery('.concave_media_body_left').html(response);
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
            if (file.name == "justinbieber.jpg") {
                done("Naha, you don't.");
            }
            else { done(); }
            }
        };
    });



</script>
