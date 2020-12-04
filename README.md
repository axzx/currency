## Wygenerowanie kluczy jwt

mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout


Kursy walut pobierane są z:
- api.nbp.pl
- api.exchangeratesapi.io

Jeżeli serwis nie działa, dane będą pobrane z kolejnego.
Można dopisać kolejną klasę, do pobierania danych z innego źródła.


## Komendy

`./bin/console app:currency:update`
Zaktualizowanie kursów walut.

`./bin/console app:user:alert`
Wysłanie alertów do użytkowników.


## Instalacja

```
composer inst --no-dev
symfony:serve
```

Trzeba uzupełnić odpowiednio plik `.env.local` i wykonać komendy:
```
./bin/console doctrine:database:create
./bin/console doctrine:schema:update --force
./bin/console doctrine:fixtures:load -n
```

Trzeba zasilić bazę walutami komendą `./bin/console app:currency:update`.

Wchodzimy na dokumentację API:
https://127.0.0.1:8000/api/doc/v1


Żeby móc korzystać z endpointów, trzeba się najpierw zalogować, korzystając z `/api/v1/login_check`. Dane z fixtures:
```
app1
pass123_app1
```

Pobrany token skopiować i wkleić do akcji Authorize (u góry po prawej zielony button).
Token jest ważny przez 1 rok.
