<?php

use Phinx\Migration\AbstractMigration;

class CreateUser extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("create sequence users_id_seq");

        $this->execute("
            CREATE TABLE users (
                id           integer NOT NULL default nextval('users_id_seq'),
                login        varchar(256) NOT NULL,
                password     varchar(128) NOT NULL,
                CONSTRAINT   id_users_pk PRIMARY KEY (id)
            )");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DROP TABLE users');
        $this->execute('DROP SEQUENCE users_id_seq');
    }
}
