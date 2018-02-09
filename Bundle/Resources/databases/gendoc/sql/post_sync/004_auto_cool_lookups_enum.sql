SET lc_messages TO 'en_US.UTF-8';


                        ALTER TABLE queued_document DROP CONSTRAINT IF EXISTS queued_document_enum_status;
                        ALTER TABLE queued_document ADD CONSTRAINT queued_document_enum_status CHECK (status IN('PENDING','PROCESSING','GENERATED','ERROR'));
                 