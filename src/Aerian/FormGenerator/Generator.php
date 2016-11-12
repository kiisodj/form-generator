<?php

namespace Aerian\FormGenerator;

use Aerian\Database\Eloquent\Model;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilder as FormBuilder;

//@todo cache
//@todo validate model passed into constructor somehow?
//@todo default values / model form binding
//@todo enums

class Generator
{
    /**
     * Form instance
     * @var Form
     */
    protected $_form;

    /**
     * @var Model
     */
    protected $_model;

    /**
     *
     * Array of columns to exclude from the form
     * @var array
     */
    protected $_excludedColumns = [];

    /**
     * Sets if to autoinclude a submit button
     * @var Boolean
     */
    protected $_autoIncludeSubmit = true;

    /**
     * Generator constructor.
     * @param $model Model
     */
    public function __construct($model)
    {
        $this->_model = $model;
    }

    /**
     * Generates the form from the injected model
     * @return Form
     */
    public function generateForm()
    {
        $form = $this->form();

        $form = $this->_model->getFieldGenerator()->addFields($this->_model, $form, $this->excludedColumns());

        if ($this->autoIncludeSubmit()) {
            $this->addDefaultSubmitButton($form);
        }

        return $form;
    }

    /**
     * Lazily instantiates the default form type, if necessary.
     *
     * @return Form
     */
    public function form()
    {
        if (!$this->_form) {
            $this->_form =  app()->make(FormBuilder::class)->create(Form::class, [
                'method' => 'POST', //@todo make this configurable?
                'url' => '' //route(@todo insert route from model)
            ]);
        }

        return $this->_form;
    }

    /**
     * @param \Aerian\Form\Form $form
     */
    public function setForm(Form $form)
    {
        $this->_form = $form;
    }


    /**
     * Get excluded columns
     * @return array
     */
    public function excludedColumns()
    {
        return $this->_excludedColumns;
    }

    /**
     * array of column names to exclude
     * @param  array $columns
     * @return $this for chaining
     */
    public function setExcludedColumns(array $columns)
    {
        $this->_excludedColumns = $columns;

        return $this;
    }

    /**
     * Get if to include a submit button
     * @return boolean $autoIncludeSubmit
     */
    public function autoIncludeSubmit()
    {
        return $this->_autoIncludeSubmit;
    }

    /**
     * Set if to include a submit button
     * @param  boolean $autoIncludeSubmit
     * @return $this
     */
    public function setAutoIncludeSubmit($autoIncludeSubmit)
    {
        $this->_autoIncludeSubmit = $autoIncludeSubmit;

        return $this;
    }

    /**
     *
     * @param  Form $form
     * @return Form
     *
     */
    public static function addDefaultSubmitButton($form)
    {
        //@todo allow configuration of label?
        return $form->add('submit', 'submit', ['label' => 'Save']);
    }

}
