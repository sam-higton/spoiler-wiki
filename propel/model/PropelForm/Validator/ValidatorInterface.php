<?php
namespace PropelForm\Validator;

interface ValidatorInterface {
    public function setValue ($value);
    public function isValid ();
}