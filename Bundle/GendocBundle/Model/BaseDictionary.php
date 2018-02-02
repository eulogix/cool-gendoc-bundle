<?php

namespace Eulogix\Cool\Bundle\GendocBundle\Model;

class BaseDictionary extends \Eulogix\Cool\Lib\Dictionary\Dictionary {

    /*
    Don't modify this class, use the overridable descendant instead    
    */

    public function getSettings() {
        return array (
  'tables' => 
  array (
    'gendoc.queued_document' => 
    array (
      'attributes' => 
      array (
        'schema' => 'gendoc',
        'rawname' => 'queued_document',
        'editable' => true,
      ),
      'files' => 
      array (
        'category' => 
        array (
          0 => 
          array (
            'name' => 'CUSTOM_TEMPLATE',
            'maxCount' => '1',
          ),
        ),
        'attributes' => 
        array (
        ),
      ),
      'columns' => 
      array (
        'queued_document_id' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'type' => 
        array (
          'attributes' => 
          array (
          ),
          'control' => 
          array (
            'type' => 'select',
          ),
          'lookup' => 
          array (
            'type' => 'table',
            'domainName' => 'GENDOC_TYPE',
          ),
        ),
        'category' => 
        array (
          'attributes' => 
          array (
          ),
          'control' => 
          array (
            'type' => 'select',
          ),
          'lookup' => 
          array (
            'type' => 'table',
            'domainName' => 'GENDOC_CATEGORY',
          ),
        ),
        'description' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'batch' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'cluster' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'template_repository_id' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'master_template' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'output_format' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'output_name' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'data' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'overrideable_flag' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'generation_date' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'attributes' => 
        array (
          'attributes' => 
          array (
          ),
        ),
      ),
    ),
  ),
  'views' => 
  array (
  ),
);
    }
    
    /** returns the schema name **/
    public function getSchemaName() {
        return  'gendoc';
    }
    
    public function getNamespace() {
        return  'Eulogix\Cool\Bundle\GendocBundle\Model';
    }

    public function getProjectDir() {
        return  '@EulogixCoolGendocBundle/Resources/databases/gendoc';
    }
       
}