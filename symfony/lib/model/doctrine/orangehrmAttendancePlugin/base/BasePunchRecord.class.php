<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('PunchRecord', 'doctrine');

/**
 * BasePunchRecord
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property int          $id                             Type: integer(8), primary key
 * @property int          $employee_id                    Type: integer(8)
 * @property string       $punch_utc_time                 Type: timestamp(25), Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @property string       $punch_device                   Type: string(255)
 * @property string       $punch_time_offset              Type: string(255)
 * @property string       $punch_user_time                Type: timestamp(25), Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 *  
 * @method int            getId()                         Type: integer(8), primary key
 * @method int            getEmployeeId()                 Type: integer(8)
 * @method string         getPunchUtcTime()               Type: timestamp(25), Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @method string         getPunchDevice()                Type: string(255)
 * @method string         getPunchTimeOffset()            Type: string(255)
 * @method string         getPunchUserTime()              Type: timestamp(25), Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 *  
 * @method PunchRecord    setId(int $val)                 Type: integer(8), primary key
 * @method PunchRecord    setEmployeeId(int $val)         Type: integer(8)
 * @method PunchRecord    setPunchUtcTime(string $val)    Type: timestamp(25), Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @method PunchRecord    setPunchDevice(string $val)     Type: string(255)
 * @method PunchRecord    setPunchTimeOffset(string $val) Type: string(255)
 * @method PunchRecord    setPunchUserTime(string $val)   Type: timestamp(25), Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 *  
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePunchRecord extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_punch_record');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('employee_id as employeeId', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('punch_utc_time as punchUtcTime', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('punch_device as punchDevice', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('punch_time_offset', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('punch_user_time as punchUserTime', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}