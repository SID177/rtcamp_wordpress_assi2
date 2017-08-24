# rtcamp_wordpress_assi2

- This is a wordpress plugin
- Dependency: A javascript library is used: jquery-ui-sortable.
  Make sure that this library can be imported using wp_enqueue_script('jquery-ui-sortable'); function.

## Introduction:

A simple Photo gallery plugin.
The user is able to add photos in a post, and can reorder it.

![Checkbox](https://github.com/SID177/uploaded_images/blob/master/Capture3.PNG?raw=true)

The user has to copy paste a shortcode given to his/her post.

![Checkbox](https://github.com/SID177/uploaded_images/blob/master/Capture4.PNG?raw=true)

The slider will be shown in the post.

![Checkbox](https://github.com/SID177/uploaded_images/blob/master/Capture5.PNG?raw=true)



### Setting limit:

The user can also set limit of the slideshow, that how many images should be shown in the slideshow.
By default all the images will be shown in the slideshow.
To set limit, just add limit attribute in the shortcode shown above.

Example:
  `[SID177_photogallery_shortcode id="123" limit="10"]`
 
If you provide any non-numeric value in the limit attribute or any invalid attribute, it won't work and all the images will be shown.
