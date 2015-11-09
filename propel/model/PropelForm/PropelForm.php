<?php
namespace PropelForm;

class PropelForm {
    protected $namespace = "";
    protected $objectName = "";
    protected $object;
    protected $formFields = array();
    protected $tableMap;
    protected $customTemplates = array();
    protected $input = array();
    protected $templates = array (
        "VARCHAR" => "partials/formInputs/textField.twig",
        "LONGVARCHAR" => "partials/formInputs/textArea.twig",
        "INTEGER" => "partials/formInputs/numberField.twig"
    );

    //todo: add form validation
    //todo: add form response handling

    public function __construct ($namespace, $objectName, $templates = false) {
        $this->namespace = $namespace;
        $this->objectName = $objectName;
        if($templates) {
            $this->templates = $templates;
        }
        $this->loadTableMap();
        $this->generateFormFields();
    }

    public function generateFormFields () {
        $columns = $this->tableMap->getColumns();

        foreach($columns as $column) {
            $newField = new FormInput($this->namespace, $column);
            $formFields[$column->getName()] = $newField;
        }
    }

    public function getFormFields () {
        return $this->formFields;
    }

    public function setTemplate ($fieldName, $templatePath) {
        $this->customTemplates[$fieldName] = $templatePath;
    }

    public function prePopulate ($input) {
        //todo: populate form with values from array
    }

    public function validate () {
        //todo: check against input validators
    }

    public function save () {
        //todo: save object and return $id
    }

    public function load ($id) {
        //todo: load object from database
    }

    public function getField($key) {

    }

    public function setField($key, $input) {

    }

    private function loadObject () {

    }

    private function loadTableMap () {
        $mapString = $this->namespace . "\\Map\\" . ucwords($this->objectName) . "TableMap";
        $this->tableMap = $mapString::getTableMap();
        return $this;
    }
}