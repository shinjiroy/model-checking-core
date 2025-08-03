# 開発コマンド

## テスト実行
```bash
# 全テスト実行
./vendor/bin/phpunit

# 特定のテストスイート実行
./vendor/bin/phpunit --testsuite Unit
./vendor/bin/phpunit --testsuite Feature

# 特定のテストファイル実行
./vendor/bin/phpunit src/tests/Unit/Model/Kripke/KripkeTest.php
```

## 依存関係管理
```bash
# 依存関係インストール
composer install
```

## Docker環境
```bash
# 開発環境の構築・起動
docker-compose up -d

# コンテナ内でコマンド実行
docker-compose exec server bash
```

## 基本システムコマンド（Linux）
- `ls` - ファイル一覧表示
- `cd` - ディレクトリ移動
- `grep` - ファイル内検索
- `find` - ファイル検索
- `git` - バージョン管理