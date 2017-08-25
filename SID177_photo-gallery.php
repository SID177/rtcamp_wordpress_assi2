<?php
/*
*	Plugin Name: SID177 Photo-Gallery Plugin
*	Author: SID177
*	Description: A simple Photogallery plugin. Add photos, reorder them. Paste the
*	shortcode given to any post to show slideshow to your post.
*	Version: 1.0.0
*/


/*
*	MAIN CLASS, INITIALIZED AT THE END OF THE FILE
*/
class SID177_photogallery{


	// CONSTRUCTOR

    public function __construct(){
    	/*
    	*	ADD FILTERS HERE
    	*/
    	add_filter('manage_'.$this->post_type.'s_columns', array($this,'SID177_imagegallery_shortcode_posttable_head'));

        
        /*
    	*	ADD ACTIONS HERE
    	*/
        add_action('init',array($this,'SID177_create_photogallery_posttype'));
        add_action('add_meta_boxes',array($this,'SID177_add_photogallery_metabox'));
        add_action('save_post',array($this,'SID177_photogallery_savegallery'),5,3);
		add_action('manage_'.$this->post_type.'s_custom_column', array($this,'SID177_imagegallery_shortcode_posttable'), 10, 2 );
		// add_action('the_posts',array($this,'SID177_photogallery_modifyquery'),10);
		
		
		/*
    	*	ADD GLOBALS HERE
    	*/
		global $wpdb;
		$this->wpdb=$wpdb;
		
		
		/*
    	*	PATH TO CSS AND JS FOLDER
    	*/
		$this->css=plugins_url()."/".plugin_basename( __DIR__ )."/assets/css/";
        $this->js=plugins_url()."/".plugin_basename( __DIR__ )."/assets/js/";


		/*
    	*	PATH TO FRONT-END STYLE AND JS
    	*/
		$this->frontend_style=$this->css."frontend-style.css";
		$this->frontend_script=$this->js."frontend-app.js";

		
		/*
    	*	PATH TO BACK-END STYLE AND JS
    	*/
		$this->admin_style=$this->css."admin-style.css";
		$this->admin_script=$this->js."admin-app.js";
    }

    private $post_type="post";
    private $wpdb;
    private $css,$js;
    private $frontend_style,$frontend_script;
    private $admin_style,$admin_script;

    private $posttype_title="SID177 PhotoGallery";
    private $posttype_public=true;
    
    /*
    *	META BOX SUPPORT ARRAY
    */
    private $posttype_supports=array(
    	'title'
    );

    //NAME OF THE POST_TYPE, USED IN post_type FIELD IN POSTS TABLE
    private $posttype_name="SID177photogallery";

    //SHORTCODE NAME FOR THE POST_TYPE
    private $posttype_shortcode="SID177_photogallery_shortcode";
    
    //IMAGE UPLOAD AND REORDERING METABOX DETAILS
    private $posttype_metabox_id="SID177_photogallery_metabox";
    private $posttype_metabox_title="Upload Images";
    private $posttype_metabox_context="normal";
    private $posttype_metabox_priority="high";


    //SHOWING SHORTCODE OF THE POST IN THIS METABOX WITH POST ID
    private $posttype_shortcode_metabox_id="SID177_photogallery_shortcode_metabox";
    private $posttype_shortcode_metabox_title="Shortcode";
    private $posttype_shortcode_metabox_context="side";
    private $posttype_shortcode_metabox_priority="high";

    
    /*
    *	WHAT SHOULD BE DISPLAYED ON THE FRONT-END WHILE APPLYING THIS
    *	SHORTCODE IN THE POST (HTML)
    */
    public function SID177_photogallery_shortcode($attr=[],$content=null){

    	//CONVERTING ATTRIBUTES VALUES TO LOWERCASE
    	//eg. iD="1" TO id="1"
        $attr = array_change_key_case((array)$attr, CASE_LOWER);

        //PARSING THE PASSED VALUES TO $values VARIABLE
        $values = shortcode_atts([
            'id'=>'0',
            'limit'=>'-1'
        ], $attr,'');

        $values['id']=sanitize_text_field($values['id']);
        $values['limit']=sanitize_text_field($values['limit']);

        //GET POSTS HAVING ID TAKEN FROM SHORTCODE AND HAVING POSTTYPE OF THIS POST
        // $result=$this->wpdb->get_results("select * from ".$this->wpdb->posts." where ID=".$values['id']." and post_type='".$this->posttype_name."'");
        $result=$this->wpdb->get_results($this->wpdb->prepare("select * from ".$this->wpdb->posts." where ID=%d and post_type='%s'",$values['id'],$this->posttype_name));
        
        //START BUFFER (EXPLAINED BETTER BELOW)
        ob_start();
        if(!empty($result)){

        	//IF WE FOUND ANY POST!


        	/*
        	*	APPLY FRONT-END STYLES AND CSS HERE
        	*/
        	wp_enqueue_style('style.css',$this->frontend_style);
        	wp_enqueue_script('app.js',$this->frontend_script);

	        //TITLE OF THE POST
	        $title=$result[0]->post_title;
	        global $post;
	        $id=$result[0]->ID.",".$post->ID;

	        //SPLITTING CONTENT WITH LI IN ORDER TO SHOW IMGS ONLY
	        $result=explode('<li',$result[0]->post_content);
	        $i=1;
	        $total=0;
	        foreach ($result as $img) {
	          if(!strpos($img,"img"))
	                continue;
	            $total++;
	        }
	        if($total==0)
	          return "";
	        if($total>$values['limit'] && (int)$values['limit']>0)
	          $total=$values['limit'];
	        ?>
	        <div class="slideshow-container">
	        <?php
	        foreach ($result as $img) {
	            if(!strpos($img,"img"))
	                continue;
	            if($i>$values['limit'] && (int)$values['limit']>0)
	              break;
	            ?>
	            <div class="mySlides fade mySlides_<?php echo $id; ?>" style="background-color: black;">
	            <center>
	              <div class="numbertext"><?php echo esc_html(($i++)." / ".$total); ?></div>
	              	<?php
	              	$img=explode('<img ',$img)[1];
	                echo "<img ".$img;
	                ?>
                	<div class="text"><?php echo esc_html($title); ?></div>
	               </center>
	            </div>
	            <?php
	        }
	      	?>
	            <a class="prev" onclick="plusSlides(-1,'<?php echo $id; ?>')">&#10094;</a>
	            <a class="next" onclick="plusSlides(1,'<?php echo $id; ?>')">&#10095;</a>
	            
	        </div>
	        <br>

	        <div style="text-align:center">
	            <?php
	            $i=1;
	            foreach ($result as $img) {
	                if(!strpos($img,"img"))
	                    continue;
	                if($i>$values['limit'] && (int)$values['limit']>0)
	                	break;
	                ?>
	                <span class="dot dot_<?php echo $id; ?>" onclick="currentSlide(<?php echo esc_html($i++); ?>,'<?php echo $id; ?>')"></span>
	                <?php
	            }
	            ?>
	        </div>
	        <script type="text/javascript">
        	jQuery(document).ready(function(){
			    showSlides(slideIndex,'<?php echo $id; ?>');
			});
        	</script>
	        <?php
        }
        $o=ob_get_contents();
        ob_end_clean();
        return $o;
    }

    public function SID177_create_photogallery_posttype(){
        $args=array(
           	'public'=>$this->posttype_public,
            'label'=>$this->posttype_title,
            'supports'=>$this->posttype_supports
        );
        register_post_type($this->posttype_name,$args);

        $args=array(
        	'public'=>false,
            'label'=>'SID177_status',
            'internal'=>true,
            'private'=>true,
            'show_in_admin_status_list'=>true,
            'show_in_admin_all_list'=>true
        );
        register_post_status('SID177_status',$args);
      	
        add_shortcode($this->posttype_shortcode,array($this,'SID177_photogallery_shortcode'));
    }

    public function SID177_photogallery_shortcode_metabox_html(){
	    ?>
	    <div>
	        <div class="meta-row">
	            <span>[<?php echo esc_html($this->posttype_shortcode); ?> id="<?php echo esc_html($_REQUEST['post']); ?>"]</span>
	        </div>
	    </div>
	    <?php
	}
	
	public function SID177_add_photogallery_metabox(){
	    if($_REQUEST['action']=='edit'){
	    	$post=sanitize_text_field($_REQUEST['post']);
	        $result=$this->SID177_getImageGalleryById($post);
	        if(!empty($result))
	            add_meta_box($this->posttype_shortcode_metabox_id,$this->posttype_shortcode_metabox_title,array($this,'SID177_photogallery_shortcode_metabox_html'),$this->posttype_name,$this->posttype_shortcode_metabox_context,$this->posttype_shortcode_metabox_priority);
	    }
	    add_meta_box($this->posttype_metabox_id,$this->posttype_metabox_title,array($this,'SID177_photogallery_metabox_html'),$this->posttype_name,$this->posttype_metabox_context,$this->posttype_metabox_priority);
	}

	public function SID177_photogallery_metabox_html(){
	    wp_enqueue_media();
	    wp_enqueue_script('jquery-ui-sortable');
	    wp_enqueue_script('admin-js',$this->admin_script);

	    wp_enqueue_style('admin-css',$this->admin_style);
	    ?>
	    <div>
	        <button type="button" id="insert-media-button" onclick="openMedia()" class="button add_media" data-editor="content"><span class="wp-media-buttons-icon"></span> Add Media</button>
	        <div class="meta-row">
	            <ul id="sortable">
	            <?php
	                if(isset($_REQUEST['post'])){
	                    $result=$this->SID177_getImageGalleryById($_REQUEST['post']);
	                    if(!empty($result)){
	                        echo $result[0]->post_content;
	                    }
	                }
	            ?>
	            </ul>
	        </div>
	        <input type="hidden" name="SID177_content" id="SID177_content" form="post"/>
	    </div>
	    <?php
	}

	public function SID177_photogallery_savegallery($post_id,$post,$update){
		if(isset($_REQUEST['SID177_content'])){
	    	$content=$_REQUEST['SID177_content'];
	    	$content=str_replace('\"','',$content);
	    	$this->wpdb->update($this->wpdb->posts,array('post_status'=>'SID177_status'),array('ID'=>$post_id));
	    	$this->wpdb->update($this->wpdb->posts,array('post_content'=>$content),array('ID'=>$post_id));
	    }
	}

	public function SID177_imagegallery_shortcode_posttable_head( $defaults ) {
	    if(isset($_REQUEST['post_type']) && $_REQUEST['post_type']==strtolower($this->posttype_name))
	        $defaults['shortcode'] = 'Shortcode';
	    return $defaults;
	}

	public function SID177_imagegallery_shortcode_posttable( $column_name, $post_id ) {
	    if($column_name=="shortcode" && isset($_REQUEST['post_type']) && $_REQUEST['post_type']==strtolower($this->posttype_name))
	        echo esc_html("[".$this->posttype_shortcode." id=\"$post_id\" limit=\"10\"]");
	}

	public function SID177_getImageGalleryById($id){
	    $result=$this->wpdb->get_results($this->wpdb->prepare("select * from ".$this->wpdb->posts." where ID=%d",$id));
	    return $result;
	}
}
new SID177_photogallery();