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
 *
 */

/**
 * Leave Entitlement form for my entitlements
 */
class MyLeaveEntitlementForm extends LeaveEntitlementSearchForm {
    public function configure() {
        parent::configure();
        
        /* 
         * Replace employee auto complete with an hidden field with logged in emp number
         */
        unset($this['employee']);
        $empNumber = $this->getOption('empNumber');
        $this->setWidget('empNumber', new sfWidgetFormInputHidden(array(), array('value' => $empNumber)));
        $this->setValidator('empNumber', new sfValidatorChoice(array('choices' => array($empNumber), 'required' => false)));    

        $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array(
            'callback' => array($this, 'postValidate')
          ))
        );        
    }

    public function postValidate($validator, $values) {

        /* Format empnumber correctly for use by super class */
        $employee = array('empId' => $values['empNumber']);
        $values['employee'] = $employee;
        unset($values['empNumber']);
        
        return $values;
    }
    
}
