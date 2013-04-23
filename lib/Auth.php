<?php // vim:ts=4:sw=4:et:fdm=marker
/**
 * A basic authentication class. Include inside your API or
 * on a page. You may have multiple Auth instances. Supports
 * 3rd party plugins.
 * 
 * @link http://agiletoolkit.org/doc/auth
*//*
==ATK4===================================================
   This file is part of Agile Toolkit 4 
    http://agiletoolkit.org/
  
   (c) 2008-2011 Romans Malinovskis <atk@agiletech.ie>
   Distributed under Affero General Public License v3
   
   See http://agiletoolkit.org/about/license
 =====================================================ATK4=*/
/*
 * Use:
 *
 * See: /atk4/lib/Auth_Basic.php
 *
 */
class Auth extends Auth_Basic {

    function init(){
        parent::init();
    }
    /** Creates log-in form. Override if you want to use your own form. If you need to change template used by a log-in form, 
     * add template/default/page/login.html */
    function createForm($page){
        $form=$page->add('Form');

        $email=$this->model->hasField($this->login_field);
        $email=$email?$email->caption:'User Name';

        $password=$this->model->hasField($this->password_field);
        $password=$password?$password->caption:'Password';

        $form->addField('Line','username',$email);
        $form->addField('Password','password',$password);
        $form->addSubmit('Login');

        return $form;
    }
}
