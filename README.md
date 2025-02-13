# System Zarządzania Salą Fitness

System do zarządzania salą fitness, umożliwiający zarządzanie zajęciami, karnetami i rezerwacjami.

## Funkcjonalności

System rejestracji i logowania

Rejestracja nowych użytkowników
Logowanie dla klientów i pracowników
Panel administratora


Zarządzanie zajęciami

Kalendarz zajęć z podziałem na dni tygodnia
System zapisów na zajęcia
Różne typy zajęć (GT Training, Les Mills, Pilates, itp.)


System karnetów

Różne typy karnetów (Basic, Standard, Premium)
Historia karnetów
Status aktywności karnetu


Panel użytkownika

Podgląd własnych rezerwacji
Edycja danych osobowych
Zarządzanie karnetem

## Technologie

- PHP
- SQL Server
- HTML5
- CSS3 (Bootstrap)
- JavaScript
- Font Awesome
## Struktura projektu
fitness-system/
├── classes/               # Klasy PHP
│   ├── Database.php
│   └── User.php
├── config/               # Pliki konfiguracyjne
│   └── config.php
├── includes/            # Pliki dołączane
├── pages/              # Strony aplikacji
│   ├── account/        # Panel użytkownika
│   ├── admin/          # Panel administratora
│   ├── classes/        # Zarządzanie zajęciami
│   └── memberships/    # Zarządzanie karnetami
├── public/             # Zasoby publiczne
│   ├── css/
│   ├── js/
│   └── images/
└── templates/          # Szablony
    ├── header.php
    └── footer.php
## Instalacja

1. Sklonuj repozytorium:
```bash
git clone https://github.com/USERNAME/fitness-system.git
```

2. Skonfiguruj serwer (Apache/XAMPP)

3. Skonfiguruj bazę danych w `config/database.php`

4. Zaimportuj strukturę bazy danych z pliku SQL

## Autorzy

- Mikołaj Leski

## Licencja

Ten projekt jest dostępny na licencji MIT.
