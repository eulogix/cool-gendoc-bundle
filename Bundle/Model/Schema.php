<?php 
namespace Eulogix\Cool\Gendoc\Bundle\Model;

class Schema extends \Eulogix\Cool\Lib\Database\Schema {

    protected $currentSchema = 'gendoc';

    /**
     * @param \DateTime|null $instant
     * @return DocumentJob[]|mixed|\PropelObjectCollection
     */
    public function getJobsToProcess(\DateTime $instant = null) {
        $instant = $instant ?? new \DateTime();

        $jobIds = $this->fetchArrayWithNumericKeys("
            SELECT job.document_job_id 
        
                FROM gendoc.document_job job
                LEFT JOIN gendoc.document_job_calc calc USING(document_job_id)
                
            WHERE pending_generation_nr > 0
 
                AND 	( schedule_weekdays IS NULL OR extract(dow from :instant::TIMESTAMP) = ANY (string_to_array(schedule_weekdays,',')::int[]) )
                AND     ( schedule_hours IS NULL OR extract(hour from :instant::TIMESTAMP) = ANY (string_to_array(schedule_hours,',')::int[]) )
                AND 	( last_iteration_date IS NULL OR minutes_between_iterations IS NULL OR (:instant::TIMESTAMP - last_iteration_date > (minutes_between_iterations::text || ' minutes')::interval) )

        ", [ ':instant' => $instant->format('c') ]);

        return DocumentJobQuery::create()->findPks($jobIds);
    }
}
