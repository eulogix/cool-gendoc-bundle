/* file generation UUID: 5a7c22ec0a61d */

--
-- Remove Auditing triggers for document_job
--

DROP FUNCTION if EXISTS document_job_audf() CASCADE;




--
-- Remove Auditing triggers for queued_document
--

DROP FUNCTION if EXISTS queued_document_audf() CASCADE;



