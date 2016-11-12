<?php

namespace Aerian\FormGenerator\FieldGenerator;

use Aerian\Database\Eloquent\Model;
use Kris\LaravelFormBuilder\Form;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\StringType;

class EloquentModel implements FieldGeneratorInterface
{
    protected $_columns;

    public function addFields($model, Form $form, $excludedColumns = [])
    {
        if (!$model instanceof Model) {
            throw new \Exception('Model must be instance of ' . Model::class . ' ' . get_class($model) . ' given');
        }

        foreach ($this->_getColumns($model) as $column) {
            if (in_array($column->getName(), $excludedColumns) || $this->_excludeColumn($column)) {
                continue;
            }

            $name = $this->_getNameForColumn($column);
            $type = $this->_getTypeForColumn($column);

            if ($name && $type) {
                $form->add($name, $type, $this->_getOptionsForColumn($column));
            }
        }

        return $form;
    }

    /**
     * Get columns from the model
     * @return array $columns
     */
    protected function _getColumns(Model $model)
    {
        if ($this->_columns === null) {
            $this->_columns = $model->describe();
        }

        return $this->_columns;
    }

    /**
     * whether or not to exclude this column (for example if it's auto increment etc.)
     * @param Column $column
     * @return bool
     */
    protected function _excludeColumn(Column $column)
    {
        if ($column->getAutoincrement()) {
            return true;
            //@todo skip timestamps
        } elseif ($column->getType() instanceof \Foobar) {
            return true;
        }

        return false;
    }

    /**
     * @param Column $column
     * @return string
     */
    protected function _getNameForColumn(Column $column)
    {
        return $column->getName();
    }

    /**
     * @param Column $column
     * @return string
     */
    protected function _getTypeForColumn(Column $column)
    {
        if ($column->getType() instanceof StringType) {
            return 'text';
        } elseif ($column->getType() instanceof BooleanType) {
            return 'checkbox';
        }
    }

    /**
     * @param Column $column
     * @return array
     */
    protected function _getOptionsForColumn(Column $column)
    {
        $options = [];
        $options['label'] = $this->_getLabelForColumn($column);
        $options['rules'] = $this->_getRulesForColumn($column);

        return $options;
    }

    /**
     * @param Column $column
     * @return string
     */
    protected function _getLabelForColumn(Column $column)
    {
        //@todo column heading map
        //$this->_columns[$this->_model->getHeadingForColumn($column->getName())] = $column;
        return $column->getName();
    }

    /**
     * @param Column $column
     * @return string
     */
    protected function _getRulesForColumn(Column $column)
    {
        $rulesArray = [];

        if ($column->getNotnull()) {
            $rulesArray[] = 'required';
        }

        if ($column->getType() instanceof StringType && $column->getLength() !== null) {
            $rulesArray[] = 'max:' . $column->getLength();
        }

        return implode('|', $rulesArray);
    }
}