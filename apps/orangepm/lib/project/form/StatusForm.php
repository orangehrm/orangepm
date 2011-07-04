<?php
/**
 * Description of Status form
 *
 * @author orangehrm
 */

class StatusForm extends sfForm {

    /**
	 * ConfigureProject form
	 *
	 */
    public function configure() {

        $this->setWidgets(array(
            'searchByStatus' => new sfWidgetFormSelect(array('choices' => array( 0 => 'All', 1 => 'Sheduled', 2 => 'In progress', 3 => 'Closed'), 'default'   => 2)),

        ));

        $this->widgetSchema->setNameFormat('projectSearch[%s]');

        $this->widgetSchema->setLabel('searchByStatus', __('Search by status'));


        $this->setValidators(array(
            'searchByStatus' => new sfValidatorString(array('required' =>false)),

        ));

    }

}
