# piiiQcy

Boilerplate for WP theme

![logo](docs/assets/images/logo.png)

piiiQcy（ピィキュー）はWordPressコーディング規約に則ったWordPress用ボイラープレートです。Theme内で完結する簡素な作りでありつつ、モダンな構築を素早く可能にする目的で開発を行っています。

## Setup

### 1. Put the `.env` file in the root

```apache
MYSQL_RANDOM_ROOT_PASSWORD=yes
MYSQL_DATABASE=wordpress
MYSQL_USER=wordpress
MYSQL_PASSWORD=wordpress

WORDPRESS_DB_HOST=db:3306
WORDPRESS_DB_NAME=wordpress
WORDPRESS_DB_USER=wordpress
WORDPRESS_DB_PASSWORD=wordpress
WORDPRESS_DEBUG="true"

VITE_API_URL=192.168.1.110
```

### 2. Install Docker

```bash
make first
```

### 3. Edit `app/WordPress/wp-config.php`

1. Edit the DB connection information based on the `/.env` file.
2. In the development environment, you can develop using Vite's HMR by adding the following constants.

```diff
/ **
   * For developers: WordPress debugging mode.
   *
   * Change this to true to enable the display of notices during development.
   * It is strongly recommended that plugin and theme developers use WP_DEBUG
   * in their development environments.
   *
   * For information on other constants that can be used for debugging,
   * visit the documentation.
   *
   * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
   */
  define( 'WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', '') );
+ define( 'IS_VITE_DEVELOPMENT', true );
```

### 4. Install WordPress

```bash
make wpinstall
```

#### Tips

- How to Change Theme: Visit your admin page, go to `Appearance` > `Themes` and enable the theme named _piiiQcy_.

### 5. Install composer

```bash
composer install
```

### 6. Install wp-coding-standards

```bash
./vendor/bin/phpcs --config-set installed_paths "\
../../cakephp/cakephp-codesniffer,\
../../phpcsstandards/phpcsextra,\
../../phpcsstandards/phpcsutils,\
../../slevomat/coding-standard,\
../../wp-coding-standards/wpcs"
```

### 7. Start Docker

```bash
make up
```

- WordPress URL：<http://localhost:8000>
- WordPress Admin URL：<http://localhost:8000/wp-admin/>
- phpMyAdmin URL：<http://localhost:8080>

### 8. Install Development

```bash
yarn install
```

### 9. Start Development

```bash
yarn dev
```

## Q&A

### How do I get the about page link in the header to work?

For the about page link to work, you must register the about page as a static page.

## About coding standards

- Projects: Please see `.editorconfig`
- JavaScript: Please see `.eslintrc.cjs`
- Style Sheet: Please see `.stylelintrc.cjs`
- PHP: [WordPress Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/)
