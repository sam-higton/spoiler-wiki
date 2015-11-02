<?php

namespace PropelToSlim;

class PropelToSlim {
    private $pathToSchema;
    /** @var  \Slim\Slim $slimApp */
    private $slimApp;
    private $apiBasePath = "/api/";
    private $modelNameSpace;
    private $schema;
    /** @var ResponseObject\ResponseObjectInterface  $responseObject */
    private $responseObject;

    public function __construct ($app, $pathToSchema) {
        $this->slimApp =  $app;
        $this->pathToSchema = $pathToSchema;
        $this->loadSchema();
        $this->responseObject = new ResponseObject\JsonResponseObject();
    }

    public function loadSchema () {
        $schemaObject = simplexml_load_file($this->pathToSchema);
        $this->schema = $schemaObject;
        $this->modelNameSpace = $this->schema['namespace'];
    }

    public function generateRoutes () {
        foreach($this->schema->table as $table) {
            $tableName = $this->_urlFriendly($table['name']);
            $this->slimApp->post($this->apiBasePath . "add-" . $tableName, $this->_addRecord($table));
            $this->slimApp->get($this->apiBasePath . "fetch-" . $tableName . "s", $this->_fetchRecords($table));
            $this->slimApp->get($this->apiBasePath . "get-" . $tableName . "/:id", $this->_getRecord($table));
            $this->slimApp->get($this->apiBasePath . "get-" . $tableName . "-by/:key/:value", $this->_getRecordBy($table));
        }

    }

    private function _addRecord  ($table) {
        $app = $this->slimApp;
        return function () use ($app, $table) {

            $tableColumns = $this->_getTableColumnMap($table);

            $objectArray = array();
            foreach($tableColumns as $field) {
                if($field['name'] !== "id") {
                    $objectArray[$this->_toCamelCase($field['name'], true)] = $app->request->post($field['name']);
                }
            }
            $propelObject = $this->_getObject($table);
            $propelObject->fromArray($objectArray);

            foreach($table->behavior as $behavior) {
                if($behavior['name'] == "sortable") {
                    $propelObject->setRank($app->request->post('rank'));
                }
            }

            $propelObject->save();
            $objectId = $propelObject->getId();
            $this->responseObject->setStatus("success");
            $this->responseObject->addField("objectId", $objectId);
            $this->responseObject->renderOutput();

        };

    }

    private function _fetchRecords ($table) {
        $app = $this->slimApp;
        return  function () use ($app, $table) {
            $queryObject = $this->_getQueryObject($table);
            $results = $this->_processResults($queryObject->find());
            $this->responseObject->setStatus("success");
            $this->responseObject->addField("resultList", $results);
            $this->responseObject->renderOutput();
        };
    }

    private function _getRecord($table) {
        $app = $this->slimApp;
        return  function ($id) use ($app, $table) {

        };
    }

    private function _getRecordBy($table) {
        $app = $this->slimApp;
        return  function ($key,$value) use ($app, $table) {

        };
    }

    private function _getTableColumnMap ($table) {
        $fieldList = array();
        foreach($table->column as $column) {
            array_push($fieldList, array (
                "name" => (string) $column['name'],
                "phpName" => (string) $column['phpName']
            ));
        }
        return $fieldList;
    }

    private function _getObject ($table) {
        $className = $this->modelNameSpace . "\\" . $table['phpName'];
        $propelObject = new $className();
        return $propelObject;
    }

    private function _getQueryObject ($table) {
        $queryClassName = $this->modelNameSpace . "\\" . $table['phpName'] . "Query";
        $queryObject = new $queryClassName();
        return $queryObject;
    }

    private function _processResults($results) {
        $formattedResults = array();
        foreach($results as $result) {
            array_push($formattedResults, $result->toArray());
        }
        return $formattedResults;
    }

    private function _urlFriendly ($part) {
        return str_replace('_','-',$part);
    }

     private function _toCamelCase($string, $capitalizeFirstCharacter = false) {

        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

}