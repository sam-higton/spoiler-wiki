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
        $this->loadObject();
        $this->generateFormFields();
    }

    public function generateFormFields () {
        $columns = $this->tableMap->getColumns();

        foreach($columns as $column) {
            $newField = new FormInput($this->namespace, $column);
            if($newField->hasRelations()) {
                $newField->setTemplate('partials/formInputs/relationalSelect.twig');
            } else {
                $newField->setTemplate($this->templates[$newField->getType()]);
            }
            $this->setField($newField);
        }
    }

    public function getFormFields () {
        return $this->formFields;
    }

    public function populate ($inputArray) {
        //todo: populate form with values from array
        /** @var FormInput $field */
        foreach($this->formFields as $field) {
            if($field->getName() !== "id") {
                $field->setValue($inputArray[$field->getName()]);
            }
        }
    }

    public function getInputs () {
        $inputArray = array ();
        /** @var FormInput $field */
        foreach($this->formFields as $field) {
            $inputArray[ucwords($field->getName())] = $field->getValue();

        }
        return $inputArray;
    }

    public function validate () {
        //todo: check against input validators
    }

    public function save () {
        //todo: save object and return $id
        $this->object->fromArray($this->getInputs());
        $this->object->save();
        return $this->object->getId();
    }

    public function load ($id) {
        //todo: load object from database
    }

    public function getField($key) {
        return $this->formFields[$key];
    }

    public function setField(FormInput $input) {
        $fieldName = $input->getName();
        $this->formFields[$fieldName] = $input;
    }

    public function toArray () {
        $formattedArray = array();
        /** @var FormInput $field */
        foreach($this->formFields as $field) {
            $formattedArray[] = $field->toArray();
        }
        return $formattedArray;
    }

    private function loadObject () {
        $classString = $this->namespace . "\\" . ucwords($this->objectName);
        $this->object = new $classString();
    }

    private function loadTableMap () {
        $mapString = $this->namespace . "\\Map\\" . ucwords($this->objectName) . "TableMap";
        $this->tableMap = $mapString::getTableMap();
        return $this;
    }
}