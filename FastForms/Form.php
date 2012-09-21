<?php

namespace FastForms;

class Form
{
    /**
     * Set this to TRUE to namespace all the auto-generated properties with a hash of the table name. Leaving it at FALSE produces nice HTML, and
     * generates predictable field names which you can work with. There's probably no reason to enable it, but I'm leaving the option out there. If
     * you enable it, you can get the namespace for the fields with get_no_conflict_namespace()
     * @var boolean
     */
    public static $no_conflict = FALSE;

    /**
     * Leaving this at FALSE means that users can't leave a string field blank in a form. Setting this to TRUE means "" is an acceptable input for a
     * non-nullable string type. 9/10 times, if you set a field to NOT NULL in the database, you don't want "", but you can disable this check if
     * you want. If you want to disable it on a per-field basis, disable it for the form, then write __validators in the model.
     * @var boolean
     */
    public $enable_empty_strings = FALSE;

    public $model;
    public $model_details;
    public function __construct($model)
    {
        $this->model = $model;
        $this->model_details = new OrmDetails($model);
    }

    /**
     * Gets the namespace string for form fields
     * @return string namespace string - empty if no_conflict is FALSE
     */
    public function get_no_conflict_namespace()
    {
        if (static::$no_conflict) {
            return '__' . hash('sha256', $this->model) . '_';
        } else {
            return '';
        }
    }

    /**
     * Checks if the post value associated with the field is set
     * @param  string $key Field to check
     * @return boolean     True if the field is set, false otherwise
     */
    public function isset_post($key)
    {
        $isset = isset($_POST[$this->get_post_name($key)]);
        if ($isset && $this->get_post($key) == '' && !$this->enable_empty_strings) {
            return FALSE;
        } else {
            return $isset;
        }
    }

    /**
     * Gets the post value associated with the field
     * @param  string $key Field to get
     * @return mixed       Value
     */
    public function get_post($key)
    {
        return $_POST[$this->get_post_name($key)];
    }

    /**
     * Gets the name of the post key associated with a field
     * @param  string $key Field to get name for
     * @return string      POST key associated with field
     */
    public function get_post_name($key)
    {
        return $this->get_no_conflict_namespace() . $key;
    }

    /**
     * Gets the form data associated with the form
     * @return array Key-value pair of field_name=>value
     */
    public function get_form_data()
    {
        $fields = array();
        foreach ($field in $this->model_details->get_fields()) {
            if ($this->model_details->is_required($field) && !$this->isset_post($field)) {
                throw new \TinyDb\ValidationException("$field is required");
            }

            $fields[$field] = $this->get_post($key);
        }
        return $fields;
    }

    /**
     * Shortcut for update() or create(): updates if the instance is set, otherwise creates
     * @param  \TinyDb\Orm $instance Optional, model to update
     * @return \TinyDb\Orm           Updated/created model
     */
    public function process(\TinyDb\Orm $instance = NULL)
    {
        if ($instance === NULL) {
            return $this->create();
        } else {
            return $this->update();
        }
    }

    /**
     * Creates a new model from the form
     * @return \TinyDb\Orm New model
     */
    public function create()
    {
        $type = $this->model; // Hack, see OrmDetails for more info
        return $type::raw_create($this->get_form_data());
    }

    /**
     * Updates an existing instance of a model from the form
     * @param  \TinyDb\Orm $instance The model instance
     * @return \TinyDb\Orm           Updated model
     */
    public function update(\TinyDb\Orm $instance)
    {
        foreach ($field in $this->model_details->get_fields()) {
            if ($this->model_details->is_required($field) && !$this->isset_post($field)) {
                throw new \TinyDb\ValidationException("$field is required");
            }

            $instance->$field = $this->get_post($key);
        }

        $instance->update();

        return $instance;
    }
}