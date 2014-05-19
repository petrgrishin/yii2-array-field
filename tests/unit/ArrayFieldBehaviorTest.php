<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

class ArrayFieldBehaviorTest extends PHPUnit_Framework_TestCase {
    public function testSetValue() {
        /** @var \PHPUnit_Framework_MockObject_MockObject|\yii\db\ActiveRecord $model */
        $model = $this
            ->getMockBuilder(\yii\db\ActiveRecord::className())
            ->disableOriginalConstructor()
            ->setMethods(array('__get', 'setAttribute'))
            ->getMock();

        $model
            ->expects($this->any())
            ->method('__get')
            ->will($this->returnCallback(function ($name) {
                return '{}';
            }))->with('attributes');

        $model
            ->expects($this->once())
            ->method('setAttribute')
            ->with('data', '{"a":{"b":true}}');

        $behavior = new \PetrGrishin\ArrayField\ArrayFieldBehavior();
        $behavior->owner = $model;
        $behavior->setFieldNameStorage('data');
        $behavior->loadArray();
        $behavior->setArray(array('a' => array('b' => true)));
        $this->assertEquals(array('a' => array('b' => true)), $behavior->getArray());
    }
}
 