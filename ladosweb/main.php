<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Главная страница dcsd
 */
class Controller_Index_Main extends Controller_Index {

    public function action_index()
    {
//        $this->template->styles[] = 'media/css/main.css'; 
//        $this->template->scripts[] = 'external_plugins/Magnific-Popup-master/jquery.magnific-popup.min.js';
         
        
        $images = ORM::factory('image')->find_all()->as_array();
        
//        foreach ($images as $key => $value) {
//                 ladosweb::print_r( $value , true );
//        }
//        ladosweb::print_r( $images , true );
        
//        $var = 'yyyyyyyyyyyyyyyyyyyyyyy';
        
        $one_block = View::factory('index/main/v_main_index')
                                  ->bind( 'lang_par' ,  $this->lang_par )
                                  ->bind( 'images' ,  $images );
        
        $this->template->one_block = array( $one_block );        
    }
}