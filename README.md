
# Kata
## Installation

```
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
```
## API Doc
### Card generator
Generate random Bingo cards
```
POST -> /api/card_generator
```

Response:
| Param   | Values                                                           |
|---------|------------------------------------------------------------------|
| card_id | (Integer) Id of card created |
| card    | (Array) List with a list by every columns card, every column conteins 5 numbers       |

### Number caller
Call out Bingo numbers
```
POST -> /api/number_caller
```

Response:
| Param   | Values                                                           |
|---------|------------------------------------------------------------------|
| data | (Integer) Number called |

### Validate winner
Check winner cards
```
GET -> /api/validate_winner
```

Response:
| Param   | Values                                                           |
|---------|------------------------------------------------------------------|
| message | (String) Description message |
| data | (Boolean) True if is winner card else return false |
