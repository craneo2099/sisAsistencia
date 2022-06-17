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

use Orangehrm\Rest\Api\User\Time\TimeConfigAPI;
use Orangehrm\Rest\Http\Request;
use Orangehrm\Rest\Http\Response;

/**
 * @group API
 */
class ApiTimeConfigAPITest extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProvider
     * @param $startDate
     * @param $result
     */
    public function testGetTimeConfigs($startDate, $result)
    {
        $sfEvent = new sfEventDispatcher();
        $sfRequest = new sfWebRequest($sfEvent);
        $request = new Request($sfRequest);

        $timeConfigAPI = new TimeConfigAPI($request);

        $timesheetPeriodService = $this->getMockBuilder('TimesheetPeriodService')->getMock();
        $timesheetPeriodService->expects($this->once())
            ->method('getTimesheetStartDate')
            ->will($this->returnValue($startDate));

        $leaveRequestResponseArray = [
            "startDate" => $result
        ];

        $timeConfigAPI->setTimesheetPeriodService($timesheetPeriodService);
        $response = $timeConfigAPI->getTimeConfigs();

        $success = new Response($leaveRequestResponseArray, []);

        $this->assertEquals($success, $response);
    }

    /**
     * @return Generator
     */
    public function dataProvider()
    {
        yield ['2', 2];
        yield [null, TimesheetPeriodService::DEFAULT_TIMESHEET_START_DATE];
    }
}
