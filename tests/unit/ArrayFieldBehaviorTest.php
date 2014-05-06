<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

/**
 * @property \PetrGrishin\ArrayField\ArrayFieldBehavior arrayField
 */
class Test extends \yii\db\ActiveRecord {
    public $data;
    public function behaviors() {
        return [
            'arrayField' => [
                'class' => \PetrGrishin\ArrayField\ArrayFieldBehavior::className(),
                'fieldNameStorage' => 'data',
            ]
        ];
    }
}

class ArrayFieldBehaviorTest extends PHPUnit_Framework_TestCase {
    public function testSimple() {
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Test $model */
        $model = $this
            ->getMockBuilder(\Test::className())
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
            ->with(array());

        $model->getBehavior('arrayField')->setValue('a.b', true);
        $this->assertTrue($model->getBehavior('arrayField')->getValue('a.b', false));
    }
}
