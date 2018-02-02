/* file generation UUID: 5a746a20400a0 */

--
-- Auditing triggers for queued_document
--

CREATE OR REPLACE FUNCTION queued_document_audf() RETURNS TRIGGER AS
$functionBlock$
    BEGIN
        IF (TG_OP = 'UPDATE') THEN
            NEW.record_version = COALESCE(NEW.record_version,1)+1;
            NEW.update_date = NOW();
            NEW.update_user_id = core.get_logged_user();
        ELSIF (TG_OP = 'INSERT') THEN
            NEW.record_version = 1;
            NEW.creation_date = COALESCE( NEW.creation_date, NOW() );
            NEW.creation_user_id = COALESCE( NEW.creation_user_id, core.get_logged_user() );
        END IF;
        RETURN NEW;
    END;
$functionBlock$
LANGUAGE plpgsql;

CREATE TRIGGER queued_document_audf_trg BEFORE INSERT OR UPDATE ON queued_document
    FOR EACH ROW EXECUTE PROCEDURE queued_document_audf();


