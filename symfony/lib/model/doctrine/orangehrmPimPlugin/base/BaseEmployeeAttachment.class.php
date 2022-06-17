<?php

/**
 * BaseEmployeeAttachment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property int                 $emp_number                    Type: integer(4), primary key
 * @property int                 $attach_id                     Type: integer, primary key
 * @property int                 $size                          Type: integer(4), default "0"
 * @property string              $description                   Type: string(200)
 * @property string              $filename                      Type: string(100)
 * @property object              $attachment                    Type: blob(2147483647)
 * @property string              $file_type                     Type: string(200)
 * @property string              $screen                        Type: string(100)
 * @property int                 $attached_by                   Type: integer(4)
 * @property string              $attached_by_name              Type: string(200)
 * @property string              $attached_time                 Type: timestamp, Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @property Employee            $Employee                      
 *  
 * @method int                   getEmpNumber()                 Type: integer(4), primary key
 * @method int                   getAttachId()                  Type: integer, primary key
 * @method int                   getSize()                      Type: integer(4), default "0"
 * @method string                getDescription()               Type: string(200)
 * @method string                getFilename()                  Type: string(100)
 * @method object                getAttachment()                Type: blob(2147483647)
 * @method string                getFileType()                  Type: string(200)
 * @method string                getScreen()                    Type: string(100)
 * @method int                   getAttachedBy()                Type: integer(4)
 * @method string                getAttachedByName()            Type: string(200)
 * @method string                getAttachedTime()              Type: timestamp, Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @method Employee              getEmployee()                  
 *  
 * @method EmployeeAttachment    setEmpNumber(int $val)         Type: integer(4), primary key
 * @method EmployeeAttachment    setAttachId(int $val)          Type: integer, primary key
 * @method EmployeeAttachment    setSize(int $val)              Type: integer(4), default "0"
 * @method EmployeeAttachment    setDescription(string $val)    Type: string(200)
 * @method EmployeeAttachment    setFilename(string $val)       Type: string(100)
 * @method EmployeeAttachment    setAttachment(object $val)     Type: blob(2147483647)
 * @method EmployeeAttachment    setFileType(string $val)       Type: string(200)
 * @method EmployeeAttachment    setScreen(string $val)         Type: string(100)
 * @method EmployeeAttachment    setAttachedBy(int $val)        Type: integer(4)
 * @method EmployeeAttachment    setAttachedByName(string $val) Type: string(200)
 * @method EmployeeAttachment    setAttachedTime(string $val)   Type: timestamp, Timestamp in ISO-8601 format (YYYY-MM-DD HH:MI:SS)
 * @method EmployeeAttachment    setEmployee(Employee $val)     
 *  
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseEmployeeAttachment extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('hs_hr_emp_attachment');
        $this->hasColumn('emp_number', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('eattach_id as attach_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('eattach_size as size', 'integer', 4, array(
             'type' => 'integer',
             'default' => '0',
             'length' => 4,
             ));
        $this->hasColumn('eattach_desc as description', 'string', 200, array(
             'type' => 'string',
             'length' => 200,
             ));
        $this->hasColumn('eattach_filename as filename', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('eattach_attachment as attachment', 'blob', 2147483647, array(
             'type' => 'blob',
             'length' => 2147483647,
             ));
        $this->hasColumn('eattach_type as file_type', 'string', 200, array(
             'type' => 'string',
             'length' => 200,
             ));
        $this->hasColumn('screen', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('attached_by', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('attached_by_name', 'string', 200, array(
             'type' => 'string',
             'length' => 200,
             ));
        $this->hasColumn('attached_time', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Employee', array(
             'local' => 'emp_number',
             'foreign' => 'emp_number'));
    }
}