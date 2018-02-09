SET lc_messages TO 'en_US.UTF-8';


DROP VIEW IF EXISTS document_job_calc_h1 CASCADE;

CREATE VIEW document_job_calc_h1 AS

    SELECT

        a.document_job_id,

        b.status,
        COUNT(b.document_job_id) AS documents_nr,
        COALESCE(MAX(b.update_date), MAX(b.creation_date)) AS last_change_date

    FROM document_job a
    LEFT JOIN queued_document b ON b.document_job_id = a.document_job_id
    GROUP BY a.document_job_id, b.status;

COMMENT ON VIEW document_job_calc_h1 IS 'calculated fields for document_job, helper 1';


DROP VIEW IF EXISTS document_job_calc_h1b CASCADE;

CREATE VIEW document_job_calc_h1b AS

    SELECT

        document_job_id,
        COUNT(document_job_id) AS documents_nr,
        COALESCE(MAX(update_date), MAX(creation_date)) AS last_change_date,
        MIN( CASE WHEN status = 'PENDING' THEN generation_date ELSE NULL END) AS pending_min_generation_date,
        COALESCE(COUNT( CASE WHEN status = 'PENDING' AND generation_date <= now() THEN 1 ELSE NULL END), 0) AS pending_generation_nr

    FROM queued_document
    GROUP BY document_job_id;

COMMENT ON VIEW document_job_calc_h1b IS 'calculated fields for document_job, helper 1b';


DROP VIEW IF EXISTS document_job_calc_h2 CASCADE;

CREATE VIEW document_job_calc_h2 AS

    SELECT

        a.document_job_id,

        COALESCE(doc_pending.documents_nr,0) AS pending_nr,
        doc_pending.last_change_date AS pending_last_change_date,
        COALESCE(doc_processing.documents_nr,0) AS processing_nr,
        doc_processing.last_change_date AS processing_last_change_date,
        COALESCE(doc_generated.documents_nr,0) AS generated_nr,
        doc_generated.last_change_date AS generated_last_change_date,
        COALESCE(doc_error.documents_nr,0) AS error_nr,
        doc_error.last_change_date AS error_last_change_date

    FROM document_job a
        LEFT JOIN document_job_calc_h1 doc_pending ON doc_pending.document_job_id = a.document_job_id AND doc_pending.status = 'PENDING'
        LEFT JOIN document_job_calc_h1 doc_processing ON doc_processing.document_job_id = a.document_job_id AND doc_processing.status = 'PROCESSING'
        LEFT JOIN document_job_calc_h1 doc_generated ON doc_generated.document_job_id = a.document_job_id AND doc_generated.status = 'GENERATED'
        LEFT JOIN document_job_calc_h1 doc_error ON doc_error.document_job_id = a.document_job_id AND doc_error.status = 'ERROR';

COMMENT ON VIEW document_job_calc_h2 IS 'calculated fields for document_job, helper 2';


DROP VIEW IF EXISTS document_job_calc CASCADE;

CREATE VIEW document_job_calc AS

    SELECT

        h2.*,
        COALESCE(h1b.documents_nr, 0) AS total_nr,
        h1b.pending_min_generation_date,
        h1b.pending_generation_nr,

        CASE
            WHEN COALESCE(h1b.documents_nr, 0) = 0 THEN 100
            ELSE 100 * (h2.generated_nr + h2.error_nr) / h1b.documents_nr
        END AS completion_percentage,

        CASE
            WHEN h2.pending_nr > 0 AND h2.processing_nr + h2.generated_nr + h2.error_nr = 0 THEN 'NOT_STARTED'
            WHEN h2.processing_nr > 0 THEN 'PROCESSING'
            WHEN h2.pending_nr > 0 THEN 'PENDING'
            ELSE 'FINISHED'
        END AS status

    FROM document_job_calc_h2 h2
    LEFT JOIN document_job_calc_h1b h1b ON h1b.document_job_id = h2.document_job_id;

COMMENT ON VIEW document_job_calc IS 'calculated fields for document_job';