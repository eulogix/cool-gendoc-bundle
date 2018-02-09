<?php

namespace Eulogix\Cool\Gendoc\Bundle\Model;

use Eulogix\Cool\Gendoc\Bundle\Model\om\BaseDocumentJob;

class DocumentJob extends BaseDocumentJob
{
    const STATUS_NOT_STARTED    = 'NOT_STARTED';
    const STATUS_PENDING        = 'PENDING';
    const STATUS_PROCESSING     = 'PROCESSING';
    const STATUS_FINISHED       = 'FINISHED';

    // if these keys are available in the job data, will be used in place of the whole data array
    const CONTEXT_START = 'start_snippet_data';
    const CONTEXT_FINISH = 'finish_snippet_data';

    /**
     * @return array
     */
    public function getDataAsArray() {
        return json_decode($this->getData(), true) ?? [];
    }

    /**
     * @param string|array $v
     * @return DocumentJob
     */
    public function setData($v) {
        return parent::setData( is_array($v) ? json_encode($v) : $v );
    }

    /**
     * @param \DateTime|string $toDate
     * @param int $limit
     */
    public function process($toDate = null, $limit = null)
    {
        $this->reload();
        $initialStatus = $this->getStatus();

        $documentIds = $this->getDocumentIds(QueuedDocument::STATUS_PENDING, $toDate, $limit);
        $this->getCoolDatabase()->query(
            "UPDATE queued_document SET status = :status WHERE queued_document_id::text = ANY(string_to_array(:ids::text, ','))",
            [':status' => QueuedDocument::STATUS_PROCESSING, ':ids'=>implode(',',$documentIds)]);

        if($initialStatus == self::STATUS_NOT_STARTED && $startSnippet = $this->getCodeSnippetRelatedByStartCodeSnippetId())
            $startSnippet->evaluate($this->getSnippetContext(self::CONTEXT_START));

        foreach($documentIds as $id) {
            $document = QueuedDocumentQuery::create()->findPk($id);
            $document->process();
        }

        $this->reload();

        if($this->getStatus() == self::STATUS_FINISHED && $finishSnippet = $this->getCodeSnippetRelatedByFinishCodeSnippetId())
            $finishSnippet->evaluate($this->getSnippetContext(self::CONTEXT_FINISH));
    }

    /**
     * @param string $status
     * @param \DateTime|string $toDate
     * @param string $limit
     * @return \int[]
     */
    protected function getDocumentIds($status = null, $toDate = null, $limit = null)
    {
        $schema = $this->getCoolDatabase();
        $sql = "SELECT queued_document_id FROM queued_document WHERE document_job_id = :job_id";
        $parameters = [':job_id' => $this->getPrimaryKey()];

        if($status) {
            $sql.=" AND status = :status";
            $parameters[':status'] = $status;
        }

        if($toDate) {
            $sql.=" AND generation_date <= :toDate";
            $parameters[':toDate'] = $toDate instanceof \DateTime ? $toDate->format('c') : $toDate;
        }

        $sql.=" ORDER BY generation_date ASC, queued_document_id ASC";

        if($limit) {
            $sql.=" LIMIT :limit";
            $parameters[':limit'] = $limit;
        }

        return $schema->fetchArrayWithNumericKeys($sql, $parameters);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getCalculatedField('status');
    }

    /**
     * @param string $context
     * @return array
     */
    protected function getSnippetContext($context)
    {
        $data = $this->getDataAsArray();
        $baseContext = $data[$context] ?? $data;
        return array_merge($baseContext, [
            'job' => $this
        ]);
    }
}
