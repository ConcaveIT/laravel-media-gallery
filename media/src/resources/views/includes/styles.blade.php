<style>
    .c-grid-container {display: grid;grid-template-columns: auto auto;padding: 5px;}
    .c-grid-item {padding: 0px 15px;}
    #concave_media_gallery {position: fixed;top: 50%; z-index: 999999 !important;background: #fff;width: 90vw;height: 90vh;left: 50%;transform: translate(-50%,-50%);padding: 25px;box-shadow: 0px 40px 115px 54px #2a2a2a;overflow: hidden;}
    .concave_media_tab {padding: 0; margin: 0;}
    .text-center{text-align: center;}
    .concave_media_tab li,.concave_media_body_left ul li{list-style: none; display: inline-block;border: 1px solid transparent;}
    .concave_media_tab li {margin-right: 15px;padding: 7px;cursor: pointer;}
    .concave_media_tab li:hover {background: #000;color: #fff;}
    .concave_media_header hr,.concave_media_body_left ul {padding: 0;margin: 0;}
    .concave_media_header h3{text-transform: uppercase;}
    .concave_media_body_left{border-right:1px solid rgb(230, 230, 230);overflow-y: scroll;height: 68vh;}
    .concave_media_body_left li img {padding: 4px;cursor: pointer;width: 150px;height: 150px;object-fit: contain;border: 1px solid #efefef;}
    .concave_media_footer {position: fixed;bottom: 0;width: 100%;left: 0;}
    .concave_media_footer .right_button {padding: 20px;background: #efefef;margin: 0 5px 0 0;text-align: right;}
    .concave_media_footer button{background: #2d95ff;border: none;padding: 8px;border-radius: 5px;color: #fff;cursor: pointer;}
    .concave_selected {background: #2d95ff38;position: relative;}
    .concave_selected::after {content: "\2713";position: absolute;right: 20px;top: 10px;font-size: 17px;color: #fff;font-weight: bold;background: #2d95ff;width: 26px;height: 26px;vertical-align: middle;text-align: center;border-radius: 50%;}
    .concave_close {position: absolute;right: 21px;top: 17px;background: #d91515;color: #fff;padding: 3px 10px;border-radius: 7px;cursor: pointer;font-size: 16px;font-weight: bold;font-family: cursive;}
    .read_more{background: #2d95ff;color: #fff !important;padding: 7px 15px;border-radius: 5px;text-decoration: none !important;}
    .selected_images_gallery {margin-top: 15px;}
    .selected_images_gallery span{margin-right: 10px; position: relative;}
    .selected_images_gallery span img{width:110px;}
    .selected_images_gallery span b {position: absolute;right: 0;color: #fff;background: #d71414;padding: 1px 7px;font-family: cursive;cursor: pointer;border-radius: 50%;}
    .c_active_tab {background: #2d95ff;color: #fff;}
    #c_upload_media{display: none;}
    .dropzone {border: 2px dashed #ddd !important;}
    .concave_media_body_left ul li:hover {border: 1px solid #2d95ffb5;}
    .c_btn {border: none;padding: 5px 15px;border-radius: 4px;font-size: 12px;cursor: pointer;}
    .c_primary_btn{background: #2d95ff;color: #fff;}
    .c_delete_btn {background: #f00;color: #fff;}
    #concave_media_gallery code {background: #ededed;padding: 5px 10px;border-radius: 3px;}
    .concave_media_body_right p {margin-bottom: 6px;}
    #concave_media_gallery h5 {color: #2d95ff;}
    .c_disabled{background: #c9c9c9 !important}
    .c_edit_btn {background: #04b72a;color: #fff;}
    .update_meta input, .update_meta textarea {margin-bottom: 10px;width: 320px;border: 1px solid #2d95ff59;font-size: 12px;padding: 8px 10px;}
    .update_meta * {font-size: 12px;}
    .c_message {color:#fff;margin-top: 10px;padding: 5px 10px;border-radius: 4px;}
    .c_b_red{background: #ff0000;}
    .c_b_green{background: #04b72a;}
    .image_size_hint{color: #d71414; font-style: italic}
</style>