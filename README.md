# TaskForce - The On-Demand Task Marketplace

## About TaskForce

TaskForce is an educational project implemented with the Yii2 framework, courtesy of HTML Academy. It's an online marketplace that connects customers with freelancers for one-off tasks. Think of it as a classified ads platform where individuals can post tasks and freelancers can respond with their offers and quotes.

## Features

- Task postings by customers
- Freelancer responses and offers
- Dynamic task and user management

## Getting Started

To get started with TaskForce, you need to set up the environment:

### Prerequisites

- PHP 7.2 or higher
- MySQL or MariaDB
- Composer

### Installation

1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Run `composer install` to install the dependencies.

### Database Setup

The database dump is located at `/src/taskforce.sql`. To set up your database:

1. Create a new database in your MySQL or MariaDB server.
2. Import the `taskforce.sql` file into your database.
3. Configure your database connection in `/config/db.php` by updating the following details:

   ```php
   return [
       'class' => 'yii\db\Connection',
       'dsn' => 'mysql:host=your_host;dbname=your_db',
       'username' => 'your_username',
       'password' => 'your_password',
       'charset' => 'utf8',
   ];
   ```

Replace `your_host`, `your_db`, `your_username`, and `your_password` with your actual database details.

### Environment Variables

TaskForce requires a `.env` file at the root of your project for API keys and other sensitive data, which is not included in the repository for security reasons. You need to create this `.env` file with the following variables:

```env
YANDEX_JS_API_KEY=your_yandex_js_api_key
YANDEX_SUGGEST_API_KEY=your_yandex_suggest_api_key
VK_CLIENT_ID=your_vk_client_id
VK_CLIENT_SECRET=your_vk_client_secret
```

### Directory Structure

Here's an overview of the main directories in the TaskForce project:

- `assets`: Asset bundles configuration.
- `commands`: Console commands (controllers).
- `config`: Configuration files.
- `controllers`: Web controllers.
- `fixtures`: Fixtures for testing.
- `mail`: Email templates.
- `migrations`: Database migrations.
- `models`: Model classes.
- `runtime`: Runtime files.
- `src`: Custom source code.
- `tests`: Test cases.
- `vagrant`: Vagrant configuration for virtualization.
- `vendor`: Composer dependencies.
- `views`: View files.
- `web`: Webroot directory containing the entry script and web resources.
- `widgets`: Widgets classes.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- HTML Academy for providing the opportunity to work on this wonderful project.
- Yii2 Framework for the robust foundation.
- All contributors who participated in this project.
```

Please remember to replace placeholders such as `your_yandex_js_api_key`, `your_vk_client_id`, and other similar values with the actual keys and credentials. This README does not include sensitive data and assumes you will fill in the actual values before using the file.