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
vendor/bin/phpcs src/ --standard=ci/phpcs.xml
vendor/bin/php-cs-fixer fix --config ./ci/php-cs-fixer.php
vendor/bin/phpstan analyse -c ci/phpstan.neon
vendor/bin/psalm -c ci/psalm.xml
vendor/bin/phpunit tests/unit/
```

## Slack channel

Feel free to join the channel [#phpat](https://symfony-devs.slack.com/archives/CQFKA2R0D) on SymfonyDevs' workspace
if you want to discuss something or need some help.

---

## Code of conduct
This project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in this project you agree to abide by its terms.
