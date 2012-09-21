<?php

namespace FastForms;

class OrmDetails
{
    protected $static;
    protected $properties;
    public function __construct($type)
    {
        $this->static = $type;
        $this->properties = \TinyDb\Orm::$instance[$this->get_static_field('table_name')];
    }

    // Here's an example of a case where PHP is really inconsistent/stupid:
    //   We can access static class properties given a string of the class name (or a type)...
    //   ... unless we're trying to do it from the $this context.
    //
    //   These methods are to work around that restriction

    /**
     * Gets the value of a static property from the main ORM class
     * @param  string $key Name of the property to get
     * @return mixed       Value of the property
     */
    protected function get_static_field($key)
    {
        $obj = $this->static;
        return $obj::$$key;
    }

    /**
     * Sets the value of a static property from the main ORM class
     * @param  string $key Name of the property to get
     * @param  mixed       Value of the property
     */
    protected function set_static_field($key, $val)
    {
        $obj = $this->static;
        $obj::$$key = $val;
    }

    /**
     *Checks if the value of a static property from the main ORM class is set
     * @param  string $key Name of the property to check
     * @return boolean     TRUE if it exists and is not null, FALSE otherwise
     */
    protected function isset_static($key)
    {
        $obj = $this->static;
        return isset($obj::$$key);
    }

    /**
     * Gets a list of all populable fields (i.e. all fields excluding auto_increment)
     * @return array List of all fields
     */
    public function get_fields()
    {
        $ret = array();
        foreach ($this->properties['table_layout'] as $field=>$structure)
        {
            if (!$structure['auto_increment']) {
                $ret[] = $field;
            }
        }

        return $ret;
    }

    /**
     * Checks if a field is required
     * @param  string  $key Name of the field to check
     * @return boolean      TRUE if required, FALSE otherwise
     */
    public function is_required($key)
    {
        return !$this->properties['table_layout'][$key]['null'];
    }

    /**
     * Gets the name to display for a given field name
     * @param  string $field_name Name of the field to get the name for
     * @return string             Name
     */
    public function get_display_name($field_name)
    {
        $raw_field_name = '__name_' . $field_name;
        if ($this->isset_static($raw_field_name)) {
            // First, did the user set a name? If so, we'll use name
            return $this->get_static_field($raw_field_name);
        } else {
            // Generate a name:
            $generated_name = preg_replace('/(?<=\\w)(?=[A-Z])/'," $1", $field_name); // camelCase -> camel Case
            $generated_name = str_replace('_', ' ', $generated_name); // under_score -> under score
            $generated_name = ucwords($generated_name);
            return trim($generated_name);
        }
    }

    /**
     * Gets the description for a given field name
     * @param  string $field_name Name of the given field
     * @return [type]             [description]
     */
    public function get_description($field_name)
    {
        $raw_field_name = '__desc_' . $field_name;
        if ($this->isset_static($raw_field_name)) {
            return $this->get_static_field($raw_field_name);
        } else {
            return NULL;
        }
    }
}
