<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of storyForm
 *
 * @author orangehrm
 */
class storyForm extends sfForm {

    public function configure() {

        $this->setWidgets(array(
            'Story_name' => new sfWidgetFormInputText(),
            'Date_added' => new sfWidgetFormInputText(),
            'Estimated_Effort' => new sfWidgetFormInputText(),
            'projectId' => new sfWidgetFormInputHidden(),
        ));

        $this->setDefault('Date_added', date('Y-m-d'));


        $this->widgetSchema->setNameFormat('project[%s]');

        $this->setValidators(array(
            'Story_name' => new sfValidatorString(),
            'Date_added' => new sfValidatorDate(),
            'Estimated_effort' => new sfValidatorNumber(),
            'projectId' => new sfValidatorString(),
        ));
    }

}

