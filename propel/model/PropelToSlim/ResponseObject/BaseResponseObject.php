<?php

namespace PropelToSlim\ResponseObject;

class BaseResponseObject implements ResponseObjectInterface {

    protected $response;
    protected $errors = array ();
    protected $fields = array ();
    protected $status = "success";

    public function setStatus ($status) {
        $this->status = $status;
    }

    public function addField ($fieldName, $fieldValue) {
        $this->fields[$fieldName] = $fieldValue;
    }

    public function removeField ($fieldName) {
        unset($this->fields[$fieldName]);
    }

    public function clearFields () {
        $this->fields = array ();
    }

    public function addError ($errorName, $errorMessage) {
        $this->errors[$errorName] = $errorMessage;
    }

    public function removeError ($errorName) {
        unset($this->errors[$errorName]);
    }

    public function clearErrors () {
        $this->errors = array ();
    }

    public function fetchErrors () {
        return $this->errors;
    }

    public function buildOutput () {
        $this->response = array ();
        if(count($this->errors) > 0) {
            $this->response['errors'] = $this->errors;
        }

        if(count($this->fields) > 0) {
            $this->response['fields'] = $this->fields;
        }
        $this->response['status'] = $this->status;
        return $this->response;

    }

    public function renderOutput () {
        $this->buildOutput();
        echo serialize($this->response);
    }

}
