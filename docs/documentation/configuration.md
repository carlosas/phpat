# Configuration

You can configure PHPat options in your `phpstan.neon` as follows:
```neon
# phpstan.neon
parameters:
    phpat:
        ignore_built_in_classes: false
        show_rule_names: true
```

<br />
This is the complete list of available options:

| Name                      | Description                           | Default |
|---------------------------|---------------------------------------|:-------:|
| `ignore_doc_comments`     | Ignore relations on Doc Comments      | *false* |
| `ignore_built_in_classes` | Ignore relations with PHP+ext classes | *false* |
| `show_rule_names`         | Show rule name to assertion message   | *false* |
