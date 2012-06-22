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
            'firstName' => new sfWidgetFormInputText(array(), array('size' => '20', 'maxlength' => 15)),
            'lastName' => new sfWidgetFormInputText(array(), array('size' => '20', 'maxlength' => 15)),
            'email' => new sfWidgetFormInputText(array(), array('size' => '20', 'maxlength' => 30)),
            'userType' => new sfWidgetFormSelect(array('choices' => array(1 => 'System Admin', 2 => 'Project Admin',3 => 'Project Member'))),
            'username' => new sfWidgetFormInputText(array(), array('size' => '20', 'maxlength' => 15)),
            'password' => new sfWidgetFormInputPassword(array(), array('size' => '20', 'maxlength' => 15)),
            'oldPassword' => new sfWidgetFormInputPassword(array(), array('size' => '20', 'maxlength' => 15)),
            'newPassword' => new sfWidgetFormInputPassword(array(), array('size' => '20', 'maxlength' => 15)),
            'confirmPassword' => new sfWidgetFormInputPassword(array(), array('size' => '20', 'maxlength' => 15)),
        ));


        $this->widgetSchema->setNameFormat('user[%s]');
        $this->widgetSchema['firstName']->setAttribute('size', 30);
        $this->widgetSchema['lastName']->setAttribute('size', 30);
        $this->widgetSchema['email']->setAttribute('size', 30);
        $this->widgetSchema->setLabel('firstName', 'First Name');
        $this->widgetSchema->setLabel('lastName', 'Last Name');
        $this->widgetSchema->setLabel('email', 'E-mail');
        $this->widgetSchema->setLabel('userType', 'User Type');
        $this->widgetSchema->setLabel('username', 'Username');
        $this->widgetSchema->setLabel('password', 'Password');
        $this->widgetSchema->setLabel('oldPassword', 'Old Password');
        $this->widgetSchema->setLabel('newPassword', 'New Password');
        $this->widgetSchema->setLabel('confirmPassword', 'Confirm Password');

        if ($this->getOption('userFormType') == 'editUserForm') {

            $this->setDefault('firstName', $this->getOption('firstName'));
            $this->setDefault('lastName', $this->getOption('lastName'));
            $this->setDefault('email', $this->getOption('email'));
            $this->setDefault('password', $this->getOption('password'));
            //$this->setDefault('oldPassword', $this->getOption('password'));
            //$this->widgetSchema['password']->setOption('always_render_empty', false);
            $this->widgetSchema['firstName']->setAttribute('readonly', 'readonly');
            $this->widgetSchema['lastName']->setAttribute('readonly', 'readonly');
            $this->setValidators(array(
            'firstName' => new sfValidatorString(array(), array('required' => __('Enter First Name'))),
            'lastName' => new sfValidatorString(array(), array('required' => __('Enter Last Name'))),
            'email' => new sfValidatorEmail(array(), array('required' => __('Enter email'))),
            'userType' => new sfValidatorString(array('required' => false)),
            'username' => new sfValidatorString(array(), array('required' => __('Enter username'))),
            'oldPassword' => new sfValidatorString(array(), array('required' => __('Enter old password'))),
            'newPassword' => new sfValidatorString(array(), array('required' => __('Enter new password'))),
            'confirmPassword' => new sfValidatorString(array(), array('required' => __('Enter confirm password'))),
        ));
            $this->mergePostValidator( new sfValidatorSchemaCompare('confirmPassword'
            , sfValidatorSchemaCompare::EQUAL, 'newPassword',
            array(), array('invalid' => 'Password does not match! Please retype')));
//            $this->mergePostValidator( new sfValidatorSchemaCompare('oldPassword'
//            , sfValidatorSchemaCompare::EQUAL, 'password',
//            array(), array('invalid' => 'Invalid Nilufer Password')));
        }else{$this->mergePostValidator( new sfValidatorSchemaCompare('oldPassword'
            , sfValidatorSchemaCompare::EQUAL, 'password',
            array(), array('invalid' => 'Invalid Nilufer Password')));
            $this->setValidators(array(
            'firstName' => new sfValidatorString(array(), array('required' => __('Enter First Name'))),
            'lastName' => new sfValidatorString(array(), array('required' => __('Enter Last Name'))),
            'email' => new sfValidatorEmail(array(), array('required' => __('Enter email'))),
            'userType' => new sfValidatorString(array('required' => false)),
            'username' => new sfValidatorString(array(), array('required' => __('Enter username'))),
            'password' => new sfValidatorString(array(), array('required' => __('Enter password'))),
             ));
        }

        
        if ($this->getOption('isSuperAdmin')) {
                $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'postValidation'))));
        }
    }

    public function postValidation($validator, $values) {
        
            $userService = new UserService();
            $usernames = $userService->getAllUsernamesAsArray();
            $errorList = array();
            if (in_array($values['username'], $usernames)) {
                $errorList['username'] = new sfValidatorError($validator, 'Username is already added');
            }
            if (count($errorList) > 0) {

                throw new sfValidatorErrorSchema($validator, $errorList);
            }

            return $values;
        
    }

}