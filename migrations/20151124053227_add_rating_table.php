<?php

use Phinx\Migration\AbstractMigration;

class AddRatingTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("create sequence rating_id_seq");

        $this->execute("
            CREATE TABLE rating (
                id           integer NOT NULL default nextval('rating_id_seq'),
                book_id      integer NOT NULL references book(id),
                rating       smallint NOT NULL,
                CONSTRAINT   id_rating_pk PRIMARY KEY (id)
            )");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DROP TABLE rating');
        $this->execute('DROP SEQUENCE rating_id_seq');
    }
}
