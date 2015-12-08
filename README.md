# Book Shelf
CRUD (Create, Read, Update, Delete).

### URL:

#### GET
    - /book (get all book) query param (language, complexity, category) by id in table
    - /book/id (get book by id)
    - /category
    - /category/id
    - /complexity
    - /complexity/id
    - /rating
    - /rating/id
    - /language
    - /language/id
    - /category-books
    - /category-books/id

#### POST (AUTH)
    - /book Query Params
            title
            description
            date_publish
            images[]
            external_links[]
            language_id
            url
            complexity_id

    - /category Query Params
                name

    - /complexity Query Params
                    name

    - /language Query Params
                    name

    - /rating Query Params (WITHOUT AUTH)
                    book_id
                    rating

    - /category-books Query Params
                    book_id
                    category_id

    #### PUT (AUTH)
        - /book Query Params - NEED ALL PARAMETERS
                title
                description
                date_publish
                images[]
                external_links[]
                language_id
                url
                complexity_id

        - /category Query Params
                    name

        - /complexity Query Params
                        name

        - /language Query Params
                        name

#### DELETE (AUTH)
    - /book/id (get book by id)
    - /category/id
    - /complexity/id
    - /rating/id
    - /language/id
    - /category-books/id

## Require
    -   nginx
    -   php-fpm
    -   postgresql
    -   phalcon

## Get Started

1. [Install Phalcon Framework](http://phalconphp.com/en/download/windows)
2. [Install Phalcon Developer Tools](http://phalconphp.com/en/download/tools) - This is a dev tools helpful for phalcon php developers
3. Clone the project for your machine

    `git clone https://github.com/edvaldoribeiro/API-REST-PHALCON-PHP.git`
    
4. Set the access configuration of your database in app/config/config.ini like configExmaple.ini
    [database]
    host     = localhost
    username = test
    password = test
    dbname   = test

5. install composer & install dependency for porject
    [composer](https://getcomposer.org/download/)

6. config the phinx
    Create in root directory phinx.yml like

    paths:
        migrations: %%PHINX_CONFIG_DIR%%/migrations

    environments:
        default_migration_table: phinxlog
        default_database: development
        production:
            adapter: pgsql
            host: localhost
            name: testDb
            user: testUser
            pass: 'test'
            port: 5432
            charset: utf8
    (phinx)[https://phinx.org/]

7. Apply Migration vendor/robmorgan/phinx/bin/phinx migrate -e production in root directory.