<?php

namespace SpoilerWiki;

class PropelApi {
    private $pathToSchema;
    /** @var  \Slim\Slim $slimApp */
    private $slimApp;
    private $apiBasePath = "/api/";
    private $modelNameSpace;
    private $schema;

    public function __construct ($app, $pathToSchema) {
        $this->slimApp =  $app;
        $this->pathToSchema = $pathToSchema;
        $this->loadSchema();
    }

    public function loadSchema () {
        $schemaObject = simplexml_load_file($this->pathToSchema);
        $this->schema = $schemaObject;
        $this->modelNameSpace = $this->schema['namespace'];
    }

    public function generateRoutes () {
        foreach($this->schema->table as $table) {
            $tableName = $table['name'];
            $app = $this->slimApp;
            $this->slimApp->post($this->apiBasePath . "generate-" . $tableName, $this->_addRecord($app, $table));
        }

    }

    private function _addRecord  ($app, $table) {

        return function () use ($app, $table) {

            $fieldList = array();

            foreach($table->column as $column) {
                array_push($fieldList, array (
                    "name" => (string) $column['name'],
                    "phpName" => (string) $column['phpName']
                ));
            }

            $objectArray = array();
            foreach($fieldList as $field) {
                if($field['name'] !== "id") {
                    $objectArray[$this->_toCamelCase($field['name'], true)] = $app->request->post($field['name']);
                }
            }

            $className = $this->modelNameSpace . "\\" . $table['phpName'];
            $propelObject = new $className();
            $propelObject->fromArray($objectArray);
            $propelObject->save();
            $objectId = $propelObject->getId();
            echo "new " . $table['phpName'] . ' id: ' . $objectId;

        };

    }

     private function _toCamelCase($string, $capitalizeFirstCharacter = false) {

        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

}