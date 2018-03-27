SET lc_messages TO 'en_US.UTF-8';


     ALTER TABLE gendoc.queued_document DROP CONSTRAINT IF EXISTS gendoc_queued_document_type_FK;
     ALTER TABLE gendoc.queued_document ADD CONSTRAINT gendoc_queued_document_type_FK
                            FOREIGN KEY (type)
                            REFERENCES lookups.gendoc_type (value)
                            ON DELETE RESTRICT
                            ON UPDATE CASCADE;
                            
     ALTER TABLE gendoc.queued_document DROP CONSTRAINT IF EXISTS gendoc_queued_document_category_FK;
     ALTER TABLE gendoc.queued_document ADD CONSTRAINT gendoc_queued_document_category_FK
                            FOREIGN KEY (category)
                            REFERENCES lookups.gendoc_category (value)
                            ON DELETE RESTRICT
                            ON UPDATE CASCADE;
                            