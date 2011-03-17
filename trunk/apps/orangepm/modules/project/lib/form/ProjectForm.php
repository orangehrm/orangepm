<?php

class ProjectForm extends sfForm {

    public function configure() {
        
        $this->setWidgets(array(
            'Name' => new sfWidgetFormInputText(),
            
        ));

        $this->widgetSchema->setNameFormat('project[%s]');


        $this->setValidators(array(
            'Name' => new sfValidatorString(array('required' => true), array('required' => 'The Project Name is required.')),
            
        ));
    }

}
