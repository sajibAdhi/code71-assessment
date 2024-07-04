# Coder71-assessment

## Installation

1. Clone the repository
2. Set up the database by importing the `database.sql` file
3. Update the database connection details in the `config.php` file
4. Start the server

## Api endpoint

[POST] /review_api.php

Request body:
```json
{
  "user_id": "1",
  "product_id": "1",
  "review_text": "This is a review text"
}
```

