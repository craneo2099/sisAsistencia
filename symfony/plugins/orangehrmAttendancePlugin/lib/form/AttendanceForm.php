<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
class AttendanceForm extends sfForm {

    public $formWidgets = array(); 
    public $formValidators = array();
    
    public function configure() {
        
        $this->formWidgets['dateFrom'] = new ohrmWidgetDatePicker(array(), array('id' => 'attendance_from','class' => 'date'));
        $this->formWidgets['dateTo'] = new ohrmWidgetDatePicker(array(), array('id' => 'attendance_to','class' => 'date'));
        $this->formWidgets['time'] = new sfWidgetFormInputText(array(), array('class' => 'time'));
        $this->formWidgets['note'] = new sfWidgetFormTextarea(array(), array('class' => 'note'));

        $this->setWidgets($this->formWidgets);
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        
        $this->formValidators['dateFrom'] = new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => false),
            array('invalid' => 'Date format should be ' . $inputDatePattern));
        $this->formValidators['dateTo'] = new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => false),
            array('invalid' => 'Date format should be ' . $inputDatePattern));
        $this->formValidators['time'] = new sfValidatorDateTime(array(), array('required' => __('Enter Time')));
        $this->formValidators['note'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema->setNameFormat('attendance[%s]');

        $this->setValidators($this->formValidators);
    }

}

