Add bellow codes to master.blade.php in before body tag ends--


## INSTALLATION

Install the package through [Composer](http://getcomposer.org/).

For Laravel 5.1~:
`composer require concaveit/media`

## CONFIGURATION

```php

@include('concaveit_media::includes/styles')
<script>
      jQuery(document).on('click','.initConcaveMedia',function(){
         var inputName,inputType,imageWidth,imageHeight;
         
         inputName = jQuery(this).attr('data-input-name');
         inputType = jQuery(this).attr('data-input-type');
         imageWidth = jQuery(this).attr('data-image-width');
         imageHeight = jQuery(this).attr('data-image-height');
         imageResize = jQuery(this).attr('data-resize');
         fileLocation = jQuery(this).attr('data-file-location');
         
         jQuery(this).addClass('triggeredButton');
          jQuery.ajax({
            url: "{{route('concave.gallery')}}",
                type: "get",
                data: {inputName:inputName,inputType:inputType,imageWidth:imageWidth,imageHeight:imageHeight,imageResize:imageResize,fileLocation:fileLocation} ,
                success: function (response) {
                    jQuery('body').prepend(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  console.log(textStatus, errorThrown);
                }
          });
      });
      jQuery(document).ready(function() {
          jQuery('.initConcaveMedia').each(function(key, val) {
              var widthText = jQuery(this).attr('data-image-width');
              var heightText = jQuery(this).attr('data-image-height');
              jQuery(this).after('<small style="color:red;font-style:italic;margin-left: 10px;white-space: nowrap;">Image Size (width:' + widthText + ' X height:' + heightText + ')</small>');
          });
      });

      jQuery(document).on('click','.selected_image_remove',function(){
         var removeItem = jQuery(this).attr('data-file-url');
         jQuery(this).closest('span').remove();
         jQuery('input[value="'+removeItem+'"]').remove();
      });
  </script>

```



