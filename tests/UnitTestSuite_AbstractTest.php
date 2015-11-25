<?php

namespace Craft;

use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * This class allows easier mocking of the Craft\DBConnection class
 * Class UnitTestSuite_AbstractTest.
 */
abstract class UnitTestSuite_AbstractTest extends BaseTest
{
    /**
     * @var ConsoleApp|WebApp
     */
    private $craft;

    /**
     * @return ConsoleApp|WebApp
     */
    protected function getCraft()
    {
        if (!$this->craft) {
            $this->craft = craft();
        }

        return $this->craft;
    }

    /**
     * Returns CDbCriteria mock.
     *
     * @return Mock
     */
    protected function getMockCriteria()
    {
        $mock = $this->getMockBuilder('\CDbCriteria')->getMock();

        return $mock;
    }

    /**
     * @return Mock
     */
    protected function getMockCommandBuilder()
    {
        $mockCriteria = $this->getMockCriteria();
        $mockFindCommand = $this->getMockCDbCommand();

        $mock = $this->getMockBuilder('\CDbCommandBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())->method('createColumnCriteria')->willReturn($mockCriteria);
        $mock->expects($this->any())->method('createFindCommand')->willReturn($mockFindCommand);

        return $mock;
    }

    /**
     * Returns table schema mock.
     *
     * @return Mock
     */
    protected function getMockCDbTableSchema()
    {
        $mock = $this->getMockBuilder('\CDbTableSchema')->getMock();

        return $mock;
    }

    /**
     * @return Mock
     */
    protected function getMockCDbSchema()
    {
        $mockCDbTableSchema = $this->getMockCDbTableSchema();
        $mockCommandBuilder = $this->getMockCommandBuilder();

        $mock = $this->getMockBuilder('\CDbSchema')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())->method('getCommandBuilder')->willReturn($mockCommandBuilder);
        $mock->expects($this->any())->method('getTable')->willReturn($mockCDbTableSchema);

        return $mock;
    }

    /**
     * Returns CDbCommand mock.
     *
     * @return Mock
     */
    protected function getMockCDbCommand()
    {
        $mock = $this->getMockBuilder('\CDbCommand')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())->method('where')->willReturnSelf();
        $mock->expects($this->any())->method('andWhere')->willReturnSelf();
        $mock->expects($this->any())->method('order')->willReturnSelf();
        $mock->expects($this->any())->method('queryRow')->willReturn(false);

        return $mock;
    }

    /**
     * Returns CComponent mock.
     *
     * @return Mock
     */
    protected function getMockCComponent()
    {
        $mockCDbCommand = $this->getMockCDbCommand();

        $mock = $this->getMockBuilder('\CComponent')
            ->setMethods(array('from'))
            ->getMock();

        $mock->expects($this->any())->method('from')->willReturn($mockCDbCommand);

        return $mock;
    }

    /**
     * Returns DbCommand mock.
     *
     * @return Mock
     */
    protected function getMockDbCommand()
    {
        $mockCComponent = $this->getMockCComponent();

        $mock = $this->getMockBuilder('Craft\DbCommand')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->any())->method('select')->willReturn($mockCComponent);

        return $mock;
    }

    /**
     * Returns craft DbService mock.
     *
     * @return Mock
     */
    protected function getMockDbConnection()
    {
        $mockCDbSchema = $this->getMockCDbSchema();
        $mockDbCommand = $this->getMockDbCommand();

        $mock = $this->getMockBuilder('Craft\DbConnection')->getMock();

        $mock->expects($this->any())->method('getSchema')->willReturn($mockCDbSchema);
        $mock->expects($this->any())->method('createCommand')->willReturn($mockDbCommand);

        return $mock;
    }

    /**
     * Mocks Craft db service.
     */
    protected function mockCraftDb()
    {
        $mockDatabase = $this->getMockDbConnection();
        if ($mockDatabase instanceof \IApplicationComponent) {
            $this->getCraft()->setComponent('db', $mockDatabase);
        }
    }

    /**
     * @return Mock|EntryModel
     */
    protected function getMockEntryModel()
    {
        $mock = $this->getMockBuilder(EntryModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}
