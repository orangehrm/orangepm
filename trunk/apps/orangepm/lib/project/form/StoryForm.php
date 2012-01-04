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

   /**
	 * Configure story form
	 *
	 */
    public function configure() {

        $this->setWidgets(array(
            'storyName' => new sfWidgetFormInputText(),
            'dateAdded' => new sfWidgetFormInputText(),
            'estimatedEffort' => new sfWidgetFormInputText(),
            'projectId' => new sfWidgetFormInputHidden(),
            'status' => new sfWidgetFormSelect(array('choices' => array(0 => 'Backlog', 1 => 'Design', 2 => 'Development', 3 => 'Development Completed', 4 => 'Testing',  5 => 'Rework', 6 => 'Accepted'))),
            'statusChangedDate' => new sfWidgetFormInputText(),
            'acceptedDate' => new sfWidgetFormInputText(),
        ));

        $this->setDefault('dateAdded', date('Y-m-d'));
        //$this->setDefault('acceptedDate', date('Y-m-d'));


        $this->widgetSchema->setNameFormat('project[%s]');
        $this->widgetSchema['storyName']->setAttribute('size' , 60);
        $this->widgetSchema->setLabel('storyName', 'Story Name'."<span class='mandatoryStar'>*</span>");
        $this->widgetSchema->setLabel('dateAdded', 'Date Added');
        $this->widgetSchema->setLabel('estimatedEffort', 'Estimated Effort');
        $this->widgetSchema->setLabel('status', 'Status');
        $this->widgetSchema->setLabel('statusChangedDate', 'Status Changed Date');
        $this->widgetSchema->setLabel('acceptedDate', 'Accepted Date');

        $this->setValidators(array(
            'storyName' => new sfValidatorString(array(),array('required' => __('Enter Story Name'))),
            'dateAdded' => new sfValidatorDate(array(),array('required' => __('Enter Date Added'))),
            'estimatedEffort' => new sfValidatorNumber(array('required' =>false)),
            'status' => new sfValidatorString(array('required' =>false)),
            'projectId' => new sfValidatorString(),
            'statusChangedDate' => new sfValidatorDate(array('required' =>false)),
            'acceptedDate' => new sfValidatorDate(array('required' =>false)),
        ));

    }

}

