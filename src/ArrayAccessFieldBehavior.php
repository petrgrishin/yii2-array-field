<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\ArrayField;


use PetrGrishin\ArrayAccess\ArrayAccess;

class ArrayAccessFieldBehavior extends ArrayFieldBehavior {
    /** @var  ArrayAccess */
    private $arrayAccess;

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
        $this->saveArray();
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
}
 