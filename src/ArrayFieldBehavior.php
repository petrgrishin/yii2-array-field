<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\ArrayField;


use yii\base\Behavior;
use yii\db\ActiveRecord;

class ArrayFieldBehavior extends Behavior {
    /** @var  string */
    protected $fieldNameStorage;
    /** @var  array */
    private $array;

    public function events() {
        return [
            ActiveRecord::EVENT_INIT => 'loadArray'
        ];
    }

    public function getArray() {
        return $this->array;
    }

    public function setArray(array $data) {
        $this->array = $data;
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
        $this->array = $value ?: array();
        return $this;
    }

    protected function saveArray() {
        $value = $this->getArray();
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
