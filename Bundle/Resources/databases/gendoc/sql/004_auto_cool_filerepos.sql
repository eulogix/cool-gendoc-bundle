SET lc_messages TO 'en_US.UTF-8';

SET SCHEMA 'gendoc';
/*
 * This file is part of the Eulogix\Cool package.
 *
 * (c) Eulogix <http://www.eulogix.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

CREATE TABLE IF NOT EXISTS gendoc_files (
    file_id serial not null,

    parent_id integer, -- unused for now

    source_table_id integer,
    source_table TEXT,
    category TEXT,

    file_name TEXT,
    file_size integer,
    upload_date timestamp,
    last_modification_date timestamp,
    uploaded_by_user integer,

    checksum_sha1 TEXT,

    properties JSON,

	CONSTRAINT gendoc_files_pkey PRIMARY KEY (file_id),

	CONSTRAINT gendoc_files_pkey_fk_1 FOREIGN KEY (parent_id)
      REFERENCES gendoc_files (file_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
);

CREATE INDEX files_idx_1 ON gendoc_files (source_table_id);
CREATE INDEX files_idx_2 ON gendoc_files (source_table);
CREATE INDEX files_idx_3 ON gendoc_files (category);
CREATE INDEX files_idx_4 ON gendoc_files (file_name);
CREATE INDEX files_idx_5 ON gendoc_files (file_size);
CREATE INDEX files_idx_6 ON gendoc_files (upload_date);
CREATE INDEX files_idx_7 ON gendoc_files (last_modification_date);
CREATE INDEX files_idx_8 ON gendoc_files (uploaded_by_user);
CREATE INDEX files_idx_9 ON gendoc_files (checksum_sha1);

                
    CREATE FUNCTION queued_document_files_delete() RETURNS trigger
        LANGUAGE plpgsql
        AS $$
    BEGIN
        PERFORM format('DELETE FROM gendoc_files WHERE source_table=%1$L AND source_table_id=%2$L', 'queued_document', OLD.queued_document_id );
        RETURN OLD;
    END;
    $$;

    CREATE TRIGGER queued_document_files_delete_trg
    AFTER DELETE ON queued_document
        FOR EACH ROW
        EXECUTE PROCEDURE queued_document_files_delete();

    