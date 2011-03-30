<?php

class ProjectForm extends sfForm {

    public function configure() {
        
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(),
            
        ));

        $this->widgetSchema->setNameFormat('project[%s]');


        $this->setValidators(array(
            'name' => new sfValidatorString(array('required' => true), array('required' => __('The Project Name is required'))),
            
        ));
    }

}