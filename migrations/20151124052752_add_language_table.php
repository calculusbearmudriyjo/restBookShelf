<?php

use Phinx\Migration\AbstractMigration;

class AddLanguageTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("create sequence language_id_seq");

        $this->execute("
            CREATE TABLE language (
                id           integer NOT NULL default nextval('language_id_seq'),
                text        varchar(256) NOT NULL,
                CONSTRAINT   id_language_pk PRIMARY KEY (id)
            )");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DROP TABLE language');
        $this->execute('DROP SEQUENCE language_id_seq');
    }
}
