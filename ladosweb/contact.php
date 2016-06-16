<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Новости
 */
class Controller_Index_Contact extends Controller_Index {

    public function action_index() {
        $this->template->styles[] = 'media/css/index/contact/v_contact_index.css';
        $this->template->scripts[] = 'media/js/jquery/jquery-1.10.2.min.js'; 
                
        $content = View::factory('index/contact/v_contact_index')
                         ->bind( 'lang_par' , $this->lang_par );

        $this->template->one_block = array($content);
    }

   // პაროლის შეცვლა მომხმარებლის მიერ
   public function action_sendcontact(){
       
     if (!$this->request->is_ajax())
     { 
         die('ufs');exit();
     }

     $data = Arr::extract( $_POST , array('first_last_names','email','phone','notification'));   

     if( empty($data['first_last_names']) || empty($data['email']) || empty($data['phone']) || empty($data['notification']))
     {
       $errors[] =  __('წითელი ვარსკვლავით აღნიშნული ველების შევსება აუცილებელია') ; 
     } 
     
     $userid = NULL;
     if( isset($this->user->id )  ){ $userid = $this->user->id ; }
     
     // მხოლოდ ყოველ 3 წუთში ერთხელ მივცეთ საშუალება შეტყობინების გამოგზავნისა
     $contactiftime = ORM::factory('contact')->where('ip','=',$_SERVER['REMOTE_ADDR'])
             ->and_where('date_created','>',time() - 1*60)->find();
     if($contactiftime->loaded()){
         $errors[] =  __('შეტყობინებების გაგზავნა შესაძლებელია მხოლოდ გარკვეული პერიოდების შემდეგ') ; 
     }
     
     if(!isset($errors)){
            $contact = ORM::factory('contact');
            $data['id_user'] = $userid;
            $data['ip'] =  $_SERVER['REMOTE_ADDR'];
            $data['date_created'] = time();
            $contact->values($data);
        try {
            $contact->save();
        }
        catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('auth');
        }
     }
          
     // თუ შეცდომა არსებობს მაშინ გადავცეთ შესაბამისი შეტყობინება
    if(isset($errors) && !empty($errors)){
        $shecdoma = "";
        
        if(is_array($errors)){
            foreach ($errors as $error) {
                if(is_array($error)){
                    foreach ($error as $er) {
                       $shecdoma .= __($er) . '<br/>';
                    }
                }else{ $shecdoma .= __($error) . '<br/>'; }
            }
        }
        $shecdoma .= "";
                
        $success = 0;
        $resultreturn = $shecdoma;
    }else{
        $success = 1;
        $resultreturn = __('შეტყობინება გაგზავნილია...უახლოეს მომავალში გიპასუხებთ თქვენს მიერ მითითებულ ელ-ფოსტაზე (e-mail) ან ტელეფონზე...გმადლობთ დაინტერესებისათვის')  ; 
   }            

      $result = array(
        'success' => $success ,
        'resultreturn' => $resultreturn ,
      );

      echo json_encode($result);
      exit();
       
   }
   
}