<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Авторизация
 */
class Controller_Index_Auth extends Controller_Index {

    private $mysecurityskey = "963852security";
    
    public function action_index() {
        $this->action_login();
    }

    public function action_login(){ 

        if(Auth::instance()->logged_in()) {   
//            $this->request->redirect('account');
        }

        if (isset($_POST['submit'])){
            $data = Arr::extract($_POST, array('username', 'password', 'remember'));
            $status = Auth::instance()->login($data['username'], $data['password'], (bool) $data['remember']);

            if ($status){
                if(Auth::instance()->logged_in('login')) {
                    $this->request->redirect('account');
                }
            }else {
                $errors = array(Kohana::message('auth/user', 'no_user'));
            }
        }

        $content = View::factory('index/auth/v_auth_login')
                    ->bind('success', $success)
                    ->bind('errors', $errors)
                    ->bind('data', $data);

        // Выводим в шаблон
        $this->template->title = 'Login';
        $this->template->page_title = 'login';
        $this->template->one_block = array($content);
    }

   
    public function action_register() {
        if (isset($_POST['submit'])){
            $data = Arr::extract($_POST, array('username', 'personal_id', 'password', 'first_name', 'password_confirm', 'email','last_name','year','month','day','gender','country','city','phone','about','skype','facebook'));
            $users = ORM::factory('user');

            
            $birthdate = 0;
            $month = sprintf('%02d', 1);
            $day = sprintf('%02d', 1);
            if(  $_POST['year'] > 1915 && $_POST['year'] < 2001 ){
                if( $_POST['month'] > 0 && $_POST['month'] < 13  ){
                            $month = str_pad($_POST['month'], 2, '0', STR_PAD_LEFT); 
                    }
                if( $_POST['day'] > 0 && $_POST['day'] < 33  ){
                            $day = str_pad($_POST['day'], 2, '0', STR_PAD_LEFT); 
                    }
               $birthdate = $_POST['year'] . '-' .$month . '-' . $day;       
               $_POST['birthdate'] = strtotime( $birthdate );
            }
         
           $_POST['ip_signup'] = $_SERVER['REMOTE_ADDR'];
           $_POST['registration_ip'] = $_SERVER['REMOTE_ADDR'];
           $_POST['registration_date'] = time();
           $_POST['date_created'] = $_POST['registration_date'];
 
          $registration_server = ladosweb::store_glob_server('');
          
           $_POST['registration_server'] = $registration_server;

           
//           $encoded = $this->encode($_POST['password']);
//           echo '$encoded>>>' . $encoded . '<br/>'; 
//           $decoded  = $this->decode($encoded);
//           echo '$decoded>>>' . $decoded . '<br/>'; 
//           
//           die();
           
            try {
                $users->create_user($_POST, array(
                    'username',
                    'personal_id',
                    'first_name',
                    'password',
                    'email',
                    'last_name',
                    'birthdate',
                    'gender',
                    'country',
                    'city',
                    'phone',
                    'about',
                    'skype',
                    'facebook',
                    'ip_signup',
                    'registration_ip',
                    'registration_date',
                    'registration_server',
                    'date_created',
                ));

                $role = ORM::factory('role')->where('name', '=', 'login')->find();
                $users->add('roles', $role);
                // შევინახოთ ეს მომხმარებელი ისტორიაში...
                $this->save_in_w_user_history($users->pk()); 
        // setcookie ებში შევინახოთ ცვლადი შემდგომ წარმატებით დარეგისტრირების შესახებ შეტყობინების გამოსატანად
                setcookie( "success_registration" , 1 , time() + 60 );
   
                $this->action_login();  
                $this->request->redirect('account');
            }
            catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('auth');
            }
        }

        
        $this->template->scripts[] = 'media/js/jquery-1.10.2.min.js';  
        
 //   http://iamceege.github.io/tooltipster/
      //   Tooltips    gips         START  საინფორმაციო , ინსტრუქციებისთვის
      //   http://stackoverflow.com/questions/20433259/tooltipster-plugin-not-firing-jquery-function-when-link-in-in-the-tooltip-is-cli   
         $this->template->styles[] = 'external_plugins/Tooltips/tooltipster-master/tooltipster.css';
         $this->template->scripts[] = 'external_plugins/Tooltips/tooltipster-master/jquery.tooltipster.min.js';
      //   Tooltips    gips          END
        
        $content = View::factory('index/auth/v_auth_register')
                            ->bind('success', $success)
                            ->bind('errors', $errors)
                            ->bind('data', $data);

        // Выводим в шаблон
        $this->template->title = __('რეგისტრაცია');
        $this->template->page_title = __('რეგისტრაცია');
        $this->template->one_block = array($content);
    }
    
    public function action_logout() {
        if(Auth::instance()->logout()) {
            $this->request->redirect();
        }
    }

        
    	/**
	 * რეგისტრაცის დროს w_user_history ამ ცხრილში შევინახოთ საჭირო ინფორმაცია მომხმარებლის შესახებ 
         * ისტორისთვის ვინახავთ... ამ ცხრილს ერთი მომხმარებლისთვის ერთხელ ვიყენებთ
	 */
        private  function save_in_w_user_history($user_id){
            
            $OBJmenuwithoutsub = ORM::factory('wuserhistory');

               $data = Arr::extract($_POST, array(
                    'username',
                    'personal_id',
                    'first_name',
                    'email',
                    'last_name',
                    'birthdate',
                    'gender',
                    'country',
                    'city',
                    'phone',
                    'about',
                    'skype',
                    'facebook',
                    'ip_signup',
                    'registration_ip',
                    'registration_date',
                    'registration_server',
                    'date_created',
                ));
               
               $data['browser'] = ladosweb::getOS();
               $data['operating_system'] = ladosweb::getBrowser();
               $data['user_rand_id'] = ladosweb::encode($_POST['password']);
               $data['user_id'] = $user_id;
               
               $OBJmenuwithoutsub->values($data);
                try
                {
                    $OBJmenuwithoutsub->save();
                }
                catch (ORM_Validation_Exception $e)
                {
                    $errors = $e->errors('validation');
                  //  print_r($errors); die();
                }

        }  

}