<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Новости
 */
class Controller_Index_Assets extends Controller_Index
{
//----------------------------------------------------------------------------------------------------------------------
    public function action_avatars()
    {
        $w = $this->request->param('width');
        $h = $this->request->param('height');
        $id = $this->request->param('id');

        $dummy_src = DOCROOT.'img/avatar_dummy_'.$w.'x'.$h.'.png';
        $src = Kohana::config('conf.uploads_path') . '/avatars/' . $id . '_' . $w . 'x' . $h . '.jpg';

        if( ! file_exists($src) )
        {
            $this->request->headers['Content-Type'] = 'image/png';
            $src = $dummy_src;
        }
        else {
            $this->request->headers['Content-Type'] = 'image/jpg';
        }

        $this->request->response = file_get_contents($src);
    }
// -----------------------------------------------
//  game_images ამ ფუნქციას ვაკომენტარებ ან return - ს ჩავუწერ ,რადგან ავტომატურად აპატარავებს სურათებს
// ამის ქვემოთ მაქვს კიდევ ეს ფუნქცია დაკოპირებული და დაკომენტარებული ზუსტად რომელიც მუშაობდა ჩემამდე
// ----------------------------------
    public function action_ladosweb_upload()
    {        
        $obj_main_folder = $this->request->param('obj_main_folder');
        $obj_each_folder = $this->request->param('obj_each_folder');
        $obj_img_name_ext = $this->request->param('obj_img_name_ext');

        
//        echo $obj_main_folder . " <> " . $obj_each_folder . " <> " . $obj_img_name_ext . "<br/>";
         
        
        
//        die("<br/>");
        //$src = DOCROOT.'../../game_images/'.$id.'/'.$w.'.jpg';
/*
		//Let the browser know we're sending an image
		$filetype = explode('.', $image); $filetype = end($filetype);
		$this->request->headers = array('Content-Type: image/'.$type);
*/
        $original_image = ABSPATH . '\ladosweb_upload\\' . $obj_main_folder . '\\' . $obj_each_folder . '\\' . $obj_img_name_ext; 

        
//        echo $original_image . " <br/> ";
//        var_dump( file_exists($original_image) );
        
        if( file_exists($original_image) )
        {
            $this->request->headers['Content-Type'] = 'image/jpg';

//            $cache_folder = DOCROOT.'../../game_images/cache/';
//            $cached_image = $cache_folder.$id.'_'.$w.'.jpg';
//
//            if( ! file_exists($cached_image) )
//            {
//                // If there is no cache file for the image resize the image and save it to the cache
//                Image::factory( $original_image )->resize( $w )->save($cached_image, 81);
//            }
            $response = file_get_contents($original_image);
        }
        // If there is no original image
        else {
            $this->request->status = 404;
            $this->request->headers['HTTP/1.1'] = '404';
            $response =  '';
        }
        
//        echo "<img src='" . 'data: '.'image/jpeg'.';base64,' . base64_encode($response) ."'  />";
//        die();
        
//        $this->request->response = $response;
//        $this->request->response = 'data: '.'image/jpeg'.';base64,' . base64_encode($response);
        echo "<img src='";
        echo 'data: '.'image/jpeg'.';base64,' . base64_encode($response); 
        echo "' />";
    }
//----------------------------------------------------------------------------------------------------------------------
//    public function action_game_images()
//    {
//        $w = $this->request->param('width');
//        $id = $this->request->param('id');
//
//        //$src = DOCROOT.'../../game_images/'.$id.'/'.$w.'.jpg';
///*
//		//Let the browser know we're sending an image
//		$filetype = explode('.', $image); $filetype = end($filetype);
//		$this->request->headers = array('Content-Type: image/'.$type);
//*/
//        $original_image = DOCROOT.'../../game_images/'.$id.'.jpg';
//
//        if( file_exists($original_image) )
//        {
//            $this->request->headers['Content-Type'] = 'image/jpg';
//
//            $cache_folder = DOCROOT.'../../game_images/cache/';
//            $cached_image = $cache_folder.$id.'_'.$w.'.jpg';
//
//            if( ! file_exists($cached_image) )
//            {
//                // If there is no cache file for the image resize the image and save it to the cache
//                Image::factory( $original_image )->resize( $w )->save($cached_image, 81);
//            }
//            $response = file_get_contents($cached_image);
//        }
//        // If there is no original image
//        else {
//            $this->request->status = 404;
//            $this->request->headers['HTTP/1.1'] = '404';
//            $response =  '';
//        }
//
//        $this->request->response = $response;
//    }
//----------------------------------------------------------------------------------------------------------------------
    public function action_banners()
    {
        if( $im = file_get_contents( Kohana::config('conf.uploads_path') . '/banners/'. $this->request->param('file') ) ) {
            header("Content-type: image/jpeg");
            echo $im;
        }
        die;
    }
//----------------------------------------------------------------------------------------------------------------------
    public function action_addssss()
    {

        $id = $this->request->param('id');
        $type = $this->request->param('type');

        if( in_array( $type , array( 'jpg','jepg','png','gif' ) ) ){
            $src =  Kohana::config('conf.uploads_path') . '/adds/' . $id . '.' . $type;
            $this->request->headers['Content-Type'] = 'image/jpg';
        }else{
            $src =  Kohana::config('conf.uploads_path') . '/adds/' . $id . '.' . $type;
            $this->request->headers['application/x-shockwave-flash'] = 'image/jpg';
        }

        $this->request->response = file_get_contents($src);
    }
//----------------------------------------------------------------------------------------------------------------------
}