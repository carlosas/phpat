<p align="center">
    <img width="500px" src="https://raw.githubusercontent.com/carlosas/phpat/master/.github/contributing.png" alt="Contributing">
</p>

# Contributing
There are several ways to help out:
* Create an [issue](https://github.com/carlosas/phpat/issues/) on GitHub if you have found a bug or have an idea for a feature
* Write test cases for open bug issues
* Write patches for open bug/feature issues

## Pull Requests
* Meaningful modifications, for typos or superfluous changes open an issue
* All features should be covered by tests if possible
* All tests and coding standard checks should pass
```bash
composer validate --strict
vendor/bin/phpcs --standard=php_cs.xml src/
vendor/bin/phpunit tests/unit/
php phpat
php phpat tests/functional/functional.yaml
```
---

## Code of conduct
This project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in this project you agree to abide by its terms.
