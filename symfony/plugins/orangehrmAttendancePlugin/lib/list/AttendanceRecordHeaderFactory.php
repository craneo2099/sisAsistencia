<?php

class AttendanceRecordHeaderFactory extends ohrmListConfigurationFactory {

    protected function init() {

        $header1 = new ListHeader();
        $headerDate = new RawLabelCellHeader();
        $headerDate2 = new RawLabelCellHeader();
        $header2 = new RawLabelCellHeader();
        $header3 = new ListHeader();
        $header4 = new RawLabelCellHeader();
        $header5 = new ListHeader();
        $header6 = new ListHeader();

        $header1->populateFromArray(array(
            'name' => 'Employee Name',
            'width' => '15%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => array('getEmployee', 'getFirstAndLastNames')),
        ));
        
        $headerDate->populateFromArray(array(
            'name' => 'Fecha Entrada',
            'width' => '10%',
            'elementType' => 'rawLabel',
            'elementProperty' => array('getter' => 'getDate'),
        ));
        $header2->populateFromArray(array(
            'name' => 'Punch In',
            'width' => '15%',
            'elementType' => 'rawLabel',
            'elementProperty' => array('getter' => 'getPunchInUserTimeAndZone'),
        ));
        
        $header3->populateFromArray(array(
            'name' => 'Punch In Note',
            'width' => '15%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getPunchInNote'),
        ));
        
        $headerDate2->populateFromArray(array(
            'name' => 'Fecha salida',
            'width' => '10%',
            'elementType' => 'rawLabel',
            'elementProperty' => array('getter' => 'getDateOut'),
        ));
        
        $header4->populateFromArray(array(
            'name' => 'Punch Out',
            'width' => '15%',
            'elementType' => 'rawLabel',
            'elementProperty' => array('getter' => 'getPunchOutUserTimeAndZone'),
        ));
        
        $header5->populateFromArray(array(
            'name' => 'Punch Out Note',
            'width' => '15%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getPunchOutNote'),
        ));
        
        $header6->populateFromArray(array(
            'name' => 'Duration(Hours)',
            'width' => '5%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getDuration'),
        ));

        $this->headers = array($header1, $headerDate, $header2, $header3, $headerDate2, $header4, $header5, $header6);
    }

    public function getClassName() {
        return 'AttendanceRecordList';
    }

}