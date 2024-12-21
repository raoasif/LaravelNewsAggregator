# Laravel News Aggregatro

## Overview

This project is a Laravel-based application designed to manage articles end points for news aggregator backend.

## Setup Instructions

### Prerequisites

- PHP 8.x
- Required PHP extensions mentioned in Dockerfile 
- Composer
- Docker

### Running with Docker

1. Clone the repository:
    ```bash
    git clone https://github.com/raoasif/LaravelNewsAggregator.git
    cd yourproject
    ```

2. Build and start Docker containers:
    ```bash
    docker-compose up --build
    ```
3. run composer install `http://localhost:8090`.
4. run migrations
5. run seeders
6. database and storeage folder permission (optionsl)
7. Access the application at `http://localhost:8090`.


## API Documentation

run php artisan l5-swagger:generate

The API documentation is available at:
[Swagger API Documentation](http://localhost:8090/news-aggregator/public/api/documentation)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
