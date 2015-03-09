<?php

class CobColaborativaDataModel extends CobColaborativaBaseModel {
    public function __construct() {
        $columns = array(
            'id' => array(
                'type' => 'integer',
                'length' => 11,
                'serial' => true,
                'primary_key' => true
            ),
            'name' => array(
                'type' => 'string',
                'lenght' => 192,
                'required' => true
            ),
            'type' => array(
                'type' => 'string',
                'length' => 192,
                'required' => true
            ),
            'data' => array(
                'type' => 'text',
                'required' => true
            ),
            'author' => array(
                'type' => 'string',
                'length' => 192,
                'required' => true
            ),
            'link' => array(
                'type' => 'string',
                'length' => 192,
                'required' => true
            ),
            'generate_at' => array(
                'type' => 'datetime'
            )
        );
        parent::__construct('aggregator', $columns);
    }
}

?>
