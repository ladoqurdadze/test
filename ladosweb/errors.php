<?php
class Controller_Index_Errors extends Controller_Index {
//    public function __construct($request, $response)
//    {
//        parent::__construct($request, $response);
//    }
// აქედან არის ეს 
//   http://stackoverflow.com/questions/10994221/kohana-404-custom-page    
    public function action_404()
    {  
        $content = View::factory('index/errors/v_errors_404');
        $this->template->block_center = array($content);
    }

    public function action_500()
    {   
        $content = View::factory('index/errors/v_errors_500');
        $this->template->block_center = array($content);
        
    }
}

?>
