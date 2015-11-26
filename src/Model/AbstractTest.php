<?php

namespace NerdsAndCompany\CraftUnitTestSuite\Model;

use PHPUnit_Framework_MockObject_MockObject as Mock;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

use Craft\Craft as Craft;
use Craft\BaseTest;
use Craft\ConsoleApp;
use Craft\WebApp;
use Craft\EntryModel;

/**
 * Class AbstractTest
 * @package NerdsAndCompany\CraftUnitTestSuite\Model
 */
abstract class AbstractTest extends BaseTest
{
    /**
     * @var ConsoleApp|WebApp
     */
    private $craft;

    /**
     * Allows setting mock methods
     * @var array
     */
    protected $methods = array(
        '\CComponent' => array('from')
    );


    /**
     * Allows keeping original methods intact by overriding the set methods
     * @param MockBuilder $mock
     * @param string $name
     */
    private function setMockMethods(MockBuilder $mock, $name)
    {
        if (array_key_exists($name, $this->methods)) {
            $mock->setMethods($this->methods[$name]);
        }
    }

    /**
     * @return ConsoleApp|WebApp
     */
    protected function getCraft()
    {
        if (!$this->craft) {
            $this->craft = Craft::app();
        }

        return $this->craft;
    }

    /**
     * @param string $className
     * @param bool|false $disableConstructor
     * @return Mock
     */
    protected function getObjectMock($className, $disableConstructor = false)
    {
        $mock = $this->getMockBuilder($className);
        if ($disableConstructor) {
            $mock->disableOriginalConstructor();
        }

        $this->setMockMethods($mock, $className);

        return $mock->getMock();
    }

    /**
     * Returns CDbCriteria mock.
     *
     * @return Mock
     */
    protected function getMockCriteria()
    {
        return $this->getObjectMock('\CDbCriteria');
    }

    /**
     * @return Mock
     */
    protected function getMockCommandBuilder()
    {
        $mockCriteria = $this->getMockCriteria();
        $mockFindCommand = $this->getMockCDbCommand();

        $mock = $this->getObjectMock('\CDbCommandBuilder', true);

        $mock->expects($this->any())->method('createCriteria')->willReturn($mockCriteria);
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
        return $this->getObjectMock('\CDbTableSchema');
    }

    /**
     * @return Mock
     */
    protected function getMockCDbSchema()
    {
        $mockCDbTableSchema = $this->getMockCDbTableSchema();
        $mockCommandBuilder = $this->getMockCommandBuilder();

        $mock = $this->getObjectMock('\CDbSchema', true);

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
        $mock = $this->getObjectMock('\CDbCommand', true);

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

        $mock = $this->getObjectMock('\CComponent');

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

        $mock = $this->getObjectMock('Craft\DbCommand', true);

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

        $mock = $this->getObjectMock('Craft\DbConnection');
        $mock->autoConnect = false; // Do not auto connect

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
