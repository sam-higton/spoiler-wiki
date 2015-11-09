<?php
namespace PropelForm;

class PropelForm {
    protected $namespace = "";
    protected $objectName = "";
    protected $formFields = array();
    protected $tableMap;
    protected $customTemplates = array();
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
        $this->getTableMap();
        $this->generateFormFields();
    }

    public function generateFormFields () {
        $columns = $this->tableMap->getColumns();

        foreach($columns as $column) {
            $this->formFields = array ();
            if(array_key_exists($column->getName(),$this->customTemplates)) {
                $template = $this->customTemplates[$column->getName()];
            } else {
                $template = $this->templates[$column->getType()];
            }
            $fieldArray = array (
                "type" => $column->getType(),
                "name"=> $column->getName(),
                "template" => $template
            );

            if($column->isForeignKey()) {
                $relation = $column->getRelation();
                $foreignTable = $relation->getForeignTable();
                $foreignTable->getPhpName();
                $queryObjectClassName = $this->namespace . "\\" . $foreignTable->getPhpName() . "Query";
                $relatedObjects = $queryObjectClassName::create()->find();
                $relatedOptions = array();
                foreach($relatedObjects as $object) {
                    $relatedOptions[] = array (
                        "value" => $object->getId(),
                        "label" => $object->getName()
                    );
                }
                $fieldArray['relation'] = array (
                    "name" => $foreignTable->getName(),
                    "options" => $relatedOptions
                );
            }
            $this->formFields[] = $fieldArray;
        }
    }

    public function getFormFields () {
        return $this->formFields;
    }

    public function setTemplate ($fieldName, $templatePath) {
        $this->customTemplates[$fieldName] = $templatePath;
    }

    private function getTableMap () {
        $mapString = $this->namespace . "\\Map\\" . ucwords($this->objectName) . "TableMap";
        $this->tableMap = $mapString::getTableMap();
        return $this;
    }
}