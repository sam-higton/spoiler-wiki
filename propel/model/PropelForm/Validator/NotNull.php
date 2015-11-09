<?php
namespace PropelForm\Validator;

class NotNull implements ValidatorInterface {
    private $value;

    public function setValue ($value) {
        $this->value = $value;
        return $this;
    }

    public function isValid () {
        return (!is_null($this->value));
    }

}