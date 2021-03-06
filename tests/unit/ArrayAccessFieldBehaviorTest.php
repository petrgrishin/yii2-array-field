<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

class ArrayAccessFieldBehaviorTest extends PHPUnit_Framework_TestCase {
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

        $behavior = new \PetrGrishin\ArrayField\ArrayAccessFieldBehavior();
        $behavior->owner = $model;
        $behavior->setFieldNameStorage('data');
        $behavior->loadArray();
        $behavior->setValue('a.b', true);
        $this->assertTrue($behavior->getValue('a.b', false));
    }
}
