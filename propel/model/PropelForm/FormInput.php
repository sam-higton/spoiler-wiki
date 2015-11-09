<?php
namespace PropelForm;


class FormInput {
    private $namespace;
    private $name;
    private $type;
    private $value;
    private $template;
    private $column;
    private $relatedOptions = array ();
    private $validators = array ();

    public function __construct ($namespace, $column) {
        $this->column = $column;
        $this->namespace = $namespace;
        $this->name = $column->getName();
        $this->type = $column->getType();
        $this->loadRelatedOptions();
        return $this;
    }

    public function setValue ($value) {
        $this->value = $value;
        return $this;
    }

    public function setTemplate ($template) {
        $this->template = $template;
        return $this;
    }

    public function addValidator (Validator\ValidatorInterface $validator) {
        $this->validators[] = $validator;
        return $this;
    }

    function getName () {
        return $this->name;
    }

    function getType () {
        return $this->type;
    }

    function getValue () {
        return $this->value;
    }

    public function toArray () {
        return array (
            "name" => $this->name,
            "type" => $this->type,
            "template" => $this->template,
            "value" => $this->value,
            "isValid" => $this->isValid(),
            "relatedOptions" => $this->relatedOptions
        );
    }

    public function hasRelations () {
        return (count($this->relatedOptions) > 0);
    }

    public function loadRelatedOptions () {
        if($this->column->isForeignKey()) {
            $relation = $this->column->getRelation();
            $foreignTable = $relation->getForeignTable();
            $foreignTable->getPhpName();
            $queryObjectClassName = $this->namespace . "\\" . $foreignTable->getPhpName() . "Query";
            $relatedObjects = $queryObjectClassName::create()->find();
            $this->relatedOptions = array();
            foreach($relatedObjects as $object) {
                $this->relatedOptions[] = array (
                    "value" => $object->getId(),
                    "label" => $object->getName()
                );
            }
            $fieldArray['relation'] = array (
                "name" => $foreignTable->getName(),
                "options" => $this->relatedOptions
            );
        }
    }

    public function isValid () {
        if(count($this->validators) === 0) {
            return true;
        } else {
            $isValid = true;
            /** @var Validator\ValidatorInterface $validator */
            foreach($this->validators as $validator) {
                $validator->setValue($this->value);
                if(!$validator->isValid()) {
                    $isValid = false;
                }
            }
            return $isValid;
        }
    }

}
