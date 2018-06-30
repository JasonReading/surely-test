# Test - Jason Reading

- Used symfony 4 with Fos rest bundle
- Wrote tests for the important components
- Provided a very simple angular frontend. Ran out of time on this, so it doesn't handle errors very well or look that great!

## Installation

- composer install
- yarn install
- yarn run dev

- Configure database in .env file
- Run `bin/console doctrine:schema:update --force`
- Run `bin/console server:run`

- Run the test suite with `bin/phpunit`