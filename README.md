# Flashcard Interactive Command

This repository contains a Laravel 11 application with a feature for managing flashcards through an interactive console command.

## Branches

This repo has the following branches:

- **main**: Contains the base Laravel 11 code.
- **clean-up**: Derived from `main`, this branch removes unnecessary files/containers.
- **interactive-command**: Derived from `clean-up`, Contains the code related to the flashcards interactive command.

### Branch Hierarchy:
- main -> clean-up -> interactive-command

## Pull Request
A Pull Request has been created from `interactive-command` to `clean-up` for easy code review.

[PR](https://github.com/tayyabhussainit/flashcard/pull/1)

## Solution Details

The primary feature of this repository is a Laravel console command that manages flashcards. Below are the key files related to this feature:

### Console Command
- `app/Console/Commands/FlashcardInteractive.php`

### Services
- `app/Services/FlashcardService.php`
- `app/Services/FlashcardPracticeService.php`
- `app/Services/MenuService.php`

### Models
- `app/Models/Flashcard.php`
- `app/Models/FlashcardPractice.php`

### Enums
- `app/Enums/MenuItem.php`
- `app/Enums/FlashcardPracticeStatus.php`

### Migrations
- Database migration files are located in `database/migrations`.

### Schema
Two tables are created for the flashcard functionality:

1. `flashcards` [id, question, answer]
2. `flashcard_practices` [id, flashcard_id, user_id, answer, status]

**Note**: Although we currently don't have a `user` entity in the system, the `user_id` is included in the `flashcard_practices` table to allow future user integration. For now, the `user_id` is passed as a command argument.

## Setup Guidelines

### Prerequisites

- Docker
- WSL2 (for Windows users)

### Setup Steps

1. **Clone the repository**:
    ```bash
    git clone git clone git@github.com:tayyabhussainit/flashcard.git
    cd flashcard
    ```

2. **Checkout the `interactive-command` branch**:
    ```bash
    git checkout interactive-command
    ```

3. **Copy the environment file**:
    ```bash
    cp .env.example .env
    ```

4. **Install dependencies**:
    ```bash
    docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
    ```
    Reference: https://laravel.com/docs/11.x/sail#installing-composer-dependencies-for-existing-projects

5. **Start the project using WSL**:
    ```bash
    wsl -d ubuntu
    ```

6. **Go to the project directory (inside WSL)**.

7. **Start the Docker containers**:
    ```bash
    ./vendor/bin/sail up -d
    ```

8. **Generate the application key**:
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

9. **Run migrations**:
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

## Running the Flashcards Command

To run the flashcards interactive command, use the following:

```bash
./vendor/bin/sail artisan flashcard:interactive {user_id}
```

example:
```bash
./vendor/bin/sail artisan flashcard:interactive 1
```

## Running Test Cases

To run the test cases, "testing" database must be created:

```bash
./vendor/bin/sail mysql

```

```bash
mysql> CREATE DATABASE testing;
```

```bash
./vendor/bin/sail artisan test
```

## Code Quality: PHP Code Sniffer

To ensure code quality, PHP Code Sniffer was used to check the code for any violations of coding standards.

### Running PHP Code Sniffer

1. **Access the Docker container**:
    ```bash
    ./vendor/bin/sail bash
    ```

2. **Run PHP Code Sniffer on specific files or directories**:

    - To check the `FlashcardInteractive` command file:
      ```bash
      ./vendor/bin/phpcs -v app/Console/Commands/FlashcardInteractive.php
      ```

    - To check the `Services` directory:
      ```bash
      ./vendor/bin/phpcs -v app/Services/
      ```

    - To check the `Models` directory:
      ```bash
      ./vendor/bin/phpcs -v app/Models/
      ```

    - To check the `Enums` directory:
      ```bash
      ./vendor/bin/phpcs -v app/Enums/
      ```

## Screenshots

Screenshots of the commands, tests and schema exists in screenshots folder at root
