<?php

use Phinx\Migration\AbstractMigration;

class AddCategoryTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("create sequence category_id_seq");

        $this->execute("
            CREATE TABLE category (
                id           integer NOT NULL default nextval('category_id_seq'),
                text        varchar(256) NOT NULL,
                CONSTRAINT   id_category_pk PRIMARY KEY (id)
            )");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DROP TABLE category');
        $this->execute('DROP SEQUENCE category_id_seq');
    }
}
