# Contributing

![image-contributing](assets/contributing.png)

<br />

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
vendor/bin/php-cs-fixer fix --config ./ci/php-cs-fixer.php
vendor/bin/phpstan analyse -c ci/phpstan-phpat.neon
vendor/bin/psalm -c ci/psalm.xml
vendor/bin/phpunit tests/unit/
```

## Documentation

The documentation is written in Markdown and is located in the `docs` folder.

The docs page is built with [MkDocs](https://www.mkdocs.org/).
To build the documentation locally, run:
```bash
docker run -p 8000:8000 --rm -v$(pwd):/docs squidfunk/mkdocs-material:9
```
When the container is running, you can access the documentation at `http://localhost:8000`.
When the pull request is merged, the documentation will be automatically deployed to https://phpat.dev.

## Slack channel

Feel free to join the channel [#static-analysis](https://symfony-devs.slack.com/archives/C8SFXTD2M) on SymfonyDevs' workspace
if you want to discuss something or need some help.

---

## Code of conduct
This project is released with a [Contributor Code of Conduct](https://github.com/carlosas/phpat/blob/master/.github/CODE_OF_CONDUCT.md).
By participating in this project you agree to abide by its terms.
