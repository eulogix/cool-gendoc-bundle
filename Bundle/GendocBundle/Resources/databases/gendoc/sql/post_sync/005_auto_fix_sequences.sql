SET lc_messages TO 'en_US.UTF-8';

SET SCHEMA 'gendoc';
SELECT setval('gendoc.queued_document_queued_document_id_seq', COALESCE((SELECT MAX(queued_document_id)+1 FROM gendoc.queued_document), 1), false);
