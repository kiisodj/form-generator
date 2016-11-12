<?php

namespace Aerian\Form;

use Kris\LaravelFormBuilder\Fields\FormField;
use Kris\LaravelFormBuilder\Form as BaseForm;

class Form extends BaseForm
{
    public function addFields(array $config)
    {
        foreach ($config as $fieldConfig) {
            $this->add($fieldConfig['name'], $fieldConfig['type'], $fieldConfig['options'], $fieldConfig['modify']);
        }

        return $this;
    }
}