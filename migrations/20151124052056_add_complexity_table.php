<?php

use Phinx\Migration\AbstractMigration;

class AddComplexityTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("create sequence complexity_id_seq");

        $this->execute("
            CREATE TABLE complexity (
                id           integer NOT NULL default nextval('complexity_id_seq'),
                text        varchar(256) NOT NULL,
                CONSTRAINT   id_complexity_pk PRIMARY KEY (id)
            )");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DROP TABLE complexity');
        $this->execute('DROP SEQUENCE complexity_id_seq');
    }
}
