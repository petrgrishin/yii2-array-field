<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\ArrayField;


use PetrGrishin\ArrayAccess\ArrayAccess;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class ArrayFieldBehavior extends Behavior {
    protected $fieldNameStorage;
    /** @var  ArrayAccess */
    protected $arrayAccess;

    public function events() {
        return [
            ActiveRecord::EVENT_INIT => 'loadArray'
        ];
    }

    public function getValue($path, $defaultValue = null) {
        return $this->arrayAccess->getValue($path, $defaultValue);
    }

    public function setValue($path, $value) {
        $this->arrayAccess->setValue($path, $value);
        $this->saveArray();
        return $this;
    }

    public function getArray() {
        return $this->arrayAccess->getArray();
    }

    public function setArray(array $data) {
        $this->arrayAccess->setArray($data);
        return $this;
    }

    public function getFieldNameStorage() {
        if (!$this->fieldNameStorage) {
            throw new \Exception('Field name storage is empty');
        }
        return $this->fieldNameStorage;
    }

    public function setFieldNameStorage($fieldNameStorage) {
        $this->fieldNameStorage = $fieldNameStorage;
        return $this;
    }

    public function save() {
        $this->getModel()->save(false, array($this->getFieldNameStorage()));
        return $this;
    }

    /**
     * @return ActiveRecord
     * @throws \Exception
     * TODO:
     */
    protected function getModel() {
        if (!$model = $this->owner) {
            throw new \Exception('Model has not been established');
        }
        if (!$model instanceof ActiveRecord) {
            throw new \Exception(sprintf('Behavior is available only to the class model, the current class `%s`', get_class($model)));
        }
        return $model;
    }

    protected function getData() {
        return $this->getModel()->getAttribute($this->getFieldNameStorage());
    }

    protected function setData($data) {
        $this->getModel()->setAttribute($this->getFieldNameStorage(), $data);
        return $this;
    }

    /**
     * @return $this
     * TODO: protected
     */
    public function loadArray() {
        $data = $this->getData();
        $value = $this->decode($data);
        $this->arrayAccess = ArrayAccess::create($value);
        return $this;
    }

    protected function saveArray() {
        $value = $this->arrayAccess->getArray();
        $data = $this->encode($value);
        $this->setData($data);
        return $this;
    }
    
    protected function encode($value) {
        return json_encode($value);
    }

    protected function decode($data) {
        return json_decode($data, true);
    }
}
