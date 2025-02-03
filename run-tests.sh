#!/bin/bash

# Exécuter PHPStan
echo "Running PHPStan..."
php vendor/bin/phpstan analyse src tests

# Exécuter PHP_CodeSniffer
echo "Running PHP_CodeSniffer..."
php vendor/bin/phpcs src tests

# Exécuter PHPUnit
echo "Running PHPUnit tests..."
php bin/phpunit

# Vérifier si tous les tests ont réussi
if [ $? -eq 0 ]; then
    echo "All tests passed successfully!"
    exit 0
else
    echo "Some tests failed!"
    exit 1
fi
