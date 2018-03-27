SET lc_messages TO 'en_US.UTF-8';

CREATE TABLE IF NOT EXISTS lookups.gendoc_type
                    (value TEXT,
dec_en TEXT,
dec_es TEXT,
dec_pt TEXT,
dec_it TEXT,
sort_order INTEGER,
mandatory_flag BOOLEAN,
filter TEXT[],
schema_filter TEXT[],
original_value TEXT,
notes TEXT,
PRIMARY KEY (value)
                    );

DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN value TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN dec_en TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN dec_es TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN dec_pt TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN dec_it TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN sort_order INTEGER; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN mandatory_flag BOOLEAN; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN filter TEXT[]; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN schema_filter TEXT[]; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN original_value TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_type ADD COLUMN notes TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
CREATE TABLE IF NOT EXISTS lookups.gendoc_category
                    (value TEXT,
dec_en TEXT,
dec_es TEXT,
dec_pt TEXT,
dec_it TEXT,
sort_order INTEGER,
mandatory_flag BOOLEAN,
filter TEXT[],
schema_filter TEXT[],
original_value TEXT,
notes TEXT,
PRIMARY KEY (value)
                    );

DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN value TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN dec_en TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN dec_es TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN dec_pt TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN dec_it TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN sort_order INTEGER; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN mandatory_flag BOOLEAN; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN filter TEXT[]; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN schema_filter TEXT[]; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN original_value TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
DO $$ BEGIN ALTER TABLE lookups.gendoc_category ADD COLUMN notes TEXT; EXCEPTION WHEN OTHERS THEN END; $$;
