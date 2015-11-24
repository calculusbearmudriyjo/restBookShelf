<?php

use Phinx\Migration\AbstractMigration;

class AddBooksCategoryTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("create sequence category_books_id_seq");

        $this->execute("
            CREATE TABLE category_books (
                id           integer NOT NULL default nextval('category_books_id_seq'),
                book_id      integer NOT NULL references book(id),
                category_id  integer NOT NULL references category(id),
                CONSTRAINT   id_category_books_pk PRIMARY KEY (id)
            )");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DROP TABLE category_books');
        $this->execute('DROP SEQUENCE category_books_id_seq');
    }
}
