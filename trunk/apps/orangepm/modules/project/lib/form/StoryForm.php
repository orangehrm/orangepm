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
            'Story_Name' => new sfWidgetFormInputText(),
            'Date_Added' => new sfWidgetFormInputText(),
            'Estimated_Effort' => new sfWidgetFormInputText(),
            'projectId' => new sfWidgetFormInputHidden(),
        ));

        $this->setDefault('Date_Added', date('Y-m-d'));


        $this->widgetSchema->setNameFormat('project[%s]');

        $this->setValidators(array(
            'Story_Name' => new sfValidatorString(array(),array('required' => 'Enter Story Name')),
            'Date_Added' => new sfValidatorDate(array(),array('required' => 'Enter Date Added')),
            'Estimated_Effort' => new sfValidatorNumber(array(),array('required' => 'Enter Estimated Effort')),
            'projectId' => new sfValidatorString(),
        ));
    }

}

