<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Главная страница
 */
class Controller_Index_Cpanellogin extends Controller_Index {

    public function action_login()
    {
        $this->template->styles[] = 'media/css/index/cpanellogin/login.css'; 

        if( isset($_POST['submit_admin']) ){
            
            $validation = Validation::factory( $this->request->post() )
                          ->labels(array( 
                              'username' => __('მომხმარებელი') ,
                              'password' => __('პაროლი')
                           ))
                          ->rule( 'username' , 'not_empty')
                          ->rule( 'username' , 'min_length' , array( ':value' , 3 ) )
                          ->rule( 'username', 'regex', array( ':value' , '/^[A-Za-z]+[A-Za-z0-9-_]+$/') )
                          ->rule( 'password' , 'not_empty')
                          ->rule( 'password' , 'min_length' , array( ':value' , 6 ) );

            if($validation->check()){
                  $data = $validation->as_array();
                  $status = Auth::instance()->login($data['username'], $data['password'], true );
                  if ($status){
                            if(Auth::instance()->logged_in('admin')) {
                              $this->request->redirect( 'cpanel/' . $this->lang_par );  
                            }
                   }else{
                        $errors = array(Kohana::message('auth/user', 'no_user'));
                   }
            }else{
            // Validation failed, collect the errors
            $errors = $validation->errors('validation');
            }
        }
        
        $one_block = View::factory('index/cpanellogin/login')
                     ->bind( 'errors' , $errors )
                     ->bind( 'success' , $success );
               
        $this->template->one_block = array( $one_block );        
    }
    
    public function action_logout(){
        if(Auth::instance()->logout()) {
           $this->request->redirect( $this->lang_par . '/cpanellogin/login');
        }
    }
    
}