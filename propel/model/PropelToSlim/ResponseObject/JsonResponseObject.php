<?php

namespace PropelToSlim\ResponseObject;

class JsonResponseObject extends BaseResponseObject implements ResponseObjectInterface {

    public function renderOutput () {
        $this->buildOutput();
        echo json_encode($this->response);
    }

}