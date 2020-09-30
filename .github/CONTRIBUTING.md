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
vendor/bin/phpcs src/
vendor/bin/psalm
vendor/bin/phpstan analyse src --level=5
vendor/bin/phpunit tests/unit/
php phpat phpat.yaml
php phpat tests/functional/functional.yaml
```

## The internals

Brief summary of how phpat works internally. The execution consists in several steps:
* **Boot and configuration**: Boots the application reading the configuration file and input args.
* **Reference mapping**: Finds and parses the files that can be analyzed and builds a ReferenceMap.
* **Statement construction**: Finds the tests, reads the rules and creates statements by resolving the selectors using the ReferenceMap.
* **Assertions**: Validates the assertions of each statement and emits the result events.

## Slack channel

Feel free to join the channel [#phpat](https://symfony-devs.slack.com/archives/CQFKA2R0D) on SymfonyDevs' workspace
if you want to discuss something or need some help.

---

## Code of conduct
This project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in this project you agree to abide by its terms.
