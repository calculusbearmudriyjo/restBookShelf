<?php

use Phinx\Migration\AbstractMigration;

class CreateDb extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute('
            CREATE TABLE books (
                id           integer NOT NULL,
                title        varchar(256) NOT NULL,
                description  text NOT NULL,
                date_created date NOT NULL,
                date_publish date NOT NULL,
                images       text[],
                external_links text[],
                url          text NOT NULL,
                complexity_id integer NOT NULL,
                CONSTRAINT   id_books_pk PRIMARY KEY (id)
            )');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DROP TABLE books');
    }
}
