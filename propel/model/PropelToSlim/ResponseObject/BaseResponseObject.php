<?php

namespace PropelToSlim\ResponseObject;

class BaseResponseObject implements ResponseObjectInterface {

    private $response;
    private $errors;
    private $status;

    public function setStatus ($status) {

    }

    public function addField ($fieldName, $fieldValue) {

    }

    public function removeField ($fieldName) {

    }

    public function clearFields () {

    }

    public function addError ($errorName, $errorMessage) {

    }

    public function removeError ($errorIndexPos) {

    }

    public function clearErrors () {

    }

    public function fetchErrors () {

    }

    public function buildOutput () {

    }

    public function renderOutput () {

    }

}
