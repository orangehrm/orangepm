<?php
/**
 * Form class for add user form
 */
class UserForm extends sfForm {

    /**
	 * Configure Userform
	 *
	 */
    public function configure() {
        $this->setWidgets(array(
            'firstName' => new sfWidgetFormInputText(array(), array('size'=>'20','maxlength' => 15)),
            'lastName' => new sfWidgetFormInputText(array(), array('size'=>'20','maxlength' => 15)),
            'email' => new sfWidgetFormInputText(array(), array('size'=>'20','maxlength' => 30)),
            'userType' => new sfWidgetFormSelect(array('choices' => array(1 => 'System Admin', 2 => 'Project Admin'))),
            'username' => new sfWidgetFormInputText(array(), array('size'=>'20','maxlength' => 15)),
            'password' => new sfWidgetFormInputPassword(array(), array('size'=>'20','maxlength' => 15)),
        ));


        $this->widgetSchema->setNameFormat('user[%s]');
        $this->widgetSchema['firstName']->setAttribute('size' , 30);
        $this->widgetSchema['lastName']->setAttribute('size' , 30);
        $this->widgetSchema['email']->setAttribute('size' , 30);
        $this->widgetSchema->setLabel('firstName', 'First Name');
        $this->widgetSchema->setLabel('lastName', 'Last Name');
        $this->widgetSchema->setLabel('email', 'E-mail');
        $this->widgetSchema->setLabel('userType', 'User Type');
        $this->widgetSchema->setLabel('username', 'Username');
        $this->widgetSchema->setLabel('password', 'Password');

        $this->setValidators(array(
            'firstName' => new sfValidatorString(array(),array('required' => __('Enter First Name'))),
            'lastName' => new sfValidatorString(array(),array('required' => __('Enter Last Name'))),
            'email' => new sfValidatorEmail(array(),array('required' => __('Enter email'))),
            'userType' => new sfValidatorString(array('required' =>false)),
            'username' => new sfValidatorString(array(),array('required' => __('Enter username'))),
            'password' => new sfValidatorString(array(),array('required' => __('Enter password'))),

        ));
        
    }
}