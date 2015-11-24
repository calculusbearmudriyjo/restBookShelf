<?php

use Phinx\Migration\AbstractMigration;

class CreateDb extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("create sequence book_id_seq");

        $this->execute("
            CREATE TABLE book (
                id           integer NOT NULL default nextval('book_id_seq'),
                title        varchar(256) NOT NULL,
                description  text NOT NULL,
                date_created date NOT NULL,
                date_publish date NOT NULL,
                images       text[],
                external_links text[],
                language_id  integer NOT NULL references language(id),
                url          text NOT NULL,
                complexity_id integer NOT NULL references complexity(id),
                CONSTRAINT   id_books_pk PRIMARY KEY (id)
            )");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DROP TABLE book');
        $this->execute('DROP SEQUENCE book_id_seq');
    }
}
