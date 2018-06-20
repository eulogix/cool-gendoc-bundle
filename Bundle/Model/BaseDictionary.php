<?php

namespace Eulogix\Cool\Gendoc\Bundle\Model;

class BaseDictionary extends \Eulogix\Cool\Lib\Dictionary\Dictionary {

    /*
    Don't modify this class, use the overridable descendant instead    
    */

    public function getSettings() {
        return array (
  'tables' => 
  array (
    'gendoc.document_job' => 
    array (
      'attributes' => 
      array (
        'schema' => 'gendoc',
        'rawname' => 'document_job',
        'editable' => true,
      ),
      'columns' => 
      array (
        'document_job_id' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'name' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'description' => 
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
        'documents_per_iteration' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'minutes_between_iterations' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'schedule_weekdays' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'schedule_hours' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'last_iteration_date' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'start_code_snippet_id' => 
        array (
          'attributes' => 
          array (
          ),
          'control' => 
          array (
            'type' => 'xhrpicker',
          ),
        ),
        'finish_code_snippet_id' => 
        array (
          'attributes' => 
          array (
          ),
          'control' => 
          array (
            'type' => 'xhrpicker',
          ),
        ),
      ),
    ),
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
          1 => 
          array (
            'name' => 'RENDERED_FILE',
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
        'document_job_id' => 
        array (
          'attributes' => 
          array (
          ),
        ),
        'status' => 
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
            'type' => 'enum',
            'validValues' => 'PENDING,PROCESSING,GENERATED,ERROR',
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
        'error' => 
        array (
          'attributes' => 
          array (
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
        'start_code_snippet_id' => 
        array (
          'attributes' => 
          array (
          ),
          'control' => 
          array (
            'type' => 'xhrpicker',
          ),
        ),
        'finish_code_snippet_id' => 
        array (
          'attributes' => 
          array (
          ),
          'control' => 
          array (
            'type' => 'xhrpicker',
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
        return  'Eulogix\Cool\Gendoc\Bundle\Model';
    }

    public function getProjectDir() {
        return  '@EulogixCoolGendocBundle/Resources/databases/gendoc';
    }
       
}