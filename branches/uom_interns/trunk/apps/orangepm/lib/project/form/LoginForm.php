<?php
/**
 * sfForm for login
 *
 * @author orangehrm
 */
class LoginForm extends sfForm {

    /**
	 * Configure LoginForm
	 *
	 */
    public function configure() {

        $this->setWidgets(array(
            'username' => new sfWidgetFormInputText(array(), array('size'=>'20','maxlength' => 15)),
            'password' => new sfWidgetFormInputPassword(array(), array('size'=>'20','maxlength' => 15)),
        ));

        $this->widgetSchema->setNameFormat('login[%s]');

        $this->widgetSchema->setLabel('username', __('Username'));
        $this->widgetSchema->setLabel('password', __('Password'));
        
        $this->setValidators(array(
            'username' => new sfValidatorString(array(), array('required' => __('Enter Username'))),
            'password' => new sfValidatorString(array(), array('required' => __('Enter Password'))),
        ));

    }

}

