# Traditional Chinese Lint

Traditional Chinese Lint for check words

## Use Actions

Workflow YAML example:

```yaml
jobs:
  lint:
    runs-on: ubuntu-latest
    name: Lint

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Lint
        uses: MilesChou/traditional-chinese-lint@master
        with:
          paths: .
```

# Configuration

Use Config YAML:

```yaml
typical_errors:
    - error: 軟件
      correct: 軟體
```

## References

In this project, we referred to the following resources:

- [ProGit](https://gist.github.com/fntsrlike/cf1e96d60b6f34fab725599b06dfcb2a) - 《Pro Git》第二版中文文件翻譯對照表與規範
- [chinese-copywriting-guidelines](https://github.com/sparanoid/chinese-copywriting-guidelines) - 中文文案排版指北

These are some of the main resources we referenced during the development process. We appreciate the contributions of these resources!
