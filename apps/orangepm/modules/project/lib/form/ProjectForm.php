<?php

class ProjectForm extends sfForm {

    public function configure() {
        
        $this->setWidgets(array(
            __('name') => new sfWidgetFormInputText(),
            
        ));

        $this->widgetSchema->setNameFormat('project[%s]');


        $this->setValidators(array(
            'name' => new sfValidatorString(array('required' => true), array('required' => 'The Project Name is required.')),
            
        ));
    }

}
