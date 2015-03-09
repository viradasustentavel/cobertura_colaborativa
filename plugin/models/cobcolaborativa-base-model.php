<?php

class CobColaborativaBaseModel {
    public $table_name;
    public $columns;
    private $table_sufix;
    private $table_prefix;

    public function __construct($table_name, $columns) {
        global $wpdb;
        $this->table_sufix = $table_name;
        $this->table_prefix = $wpdb->prefix . 'cultural_';
        $this->table_name = $this->table_prefix . $this->table_sufix;
        $this->columns = $columns;
    }

    public function create_table() {
        global $wpdb;;
        $table_name = $this->table_name;
        $primary_key = '';
        $unique_indexes = array();
        $relations = array();

        $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (";

        foreach ($this->columns as $key => $column) {
            $name = $key;
            $type = $column['type'];
            $length = (array_key_exists('length', $column)) ? $column['length'] : false;
            $serial = (array_key_exists('serial', $column)) ? $column['serial'] : false;
            $required = (array_key_exists('required', $column)) ? $column['required'] : false;
            $unique = (array_key_exists('unique', $column)) ? $column['unique'] : false;
            $is_primary_key = (array_key_exists('primary_key', $column)) ? $column['primary_key'] : false;

            $type = $this->define_type($type, $length);

            if (array_key_exists('relation', $column)) {
                $name = $this->table_prefix . $name;
                $relation = array(
                    'field' => $name,
                    'parent_table' => $this->table_prefix . $column['relation']['parent_table'],
                    'parent_key' => $column['relation']['parent_key']
                );

                array_push($relations, $relation);
            }

            if ($unique) {
                array_push($unique_indexes, $name);
            }

            if ($is_primary_key && $primary_key == '') {
                $primary_key = $name;
            }

            $sql .= $name . " " . $type;

            if ($required) {
                $sql .= " NOT NULL";
            }

            if ($serial) {
                $sql .= " AUTO_INCREMENT";
            }

            $sql .= ",";
        }

        $sql .= "created_at DATETIME, updated_at DATETIME";

        if ($primary_key != '') {
            $sql .= ", PRIMARY KEY (" . $primary_key . ")";
        }

        foreach ($unique_indexes as $index) {
            $sql .= ", CONSTRAINT un_colab_" . $index . " UNIQUE (" . $index . ")";
        }

        foreach ($relations as $relation) {
            $sql .= ", CONSTRAINT fk_" . $relation['field'] . "_" . $this->table_sufix . " FOREIGN KEY (" . $relation['field'] . ") REFERENCES " . $relation['parent_table'] . "(" . $relation['parent_key'] . ")";
        }

        $sql .= ") ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";

        return $wpdb->query($sql);
    }

    private function define_type($type, $lenght = false) {
        $output = '';

        switch ($type) {
            case 'string':
                $length = ($length) ? $length : 255;
                $output = 'VARCHAR(' . $length . ')';
                break;

            case 'integer':
                $length = ($length) ? $length : 11;
                $key = ($length == 1) ? 'TINYINT' : 'INT';
                $output = $key . '(' . $length . ')';
                break;

            case 'text':
                $output = 'TEXT';
                break;

            case 'float':
                $output = 'FLOAT';
                break;

            case 'date':
                $output = 'DATE';
                break;

            case 'datetime':
                $output = 'DATETIME';
                break;

            default:
                $output = 'VARCHAR(255)';
                break;
        }

        return $output;
    }
}

?>
