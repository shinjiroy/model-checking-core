# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Testing
```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test suite  
./vendor/bin/phpunit --testsuite Unit
./vendor/bin/phpunit --testsuite Feature

# Run specific test file
./vendor/bin/phpunit src/tests/Unit/Model/Kripke/KripkeTest.php
```

### Dependencies
```bash
# Install dependencies (requires Composer)
composer install
```

### Docker Environment
```bash
# Build and run development environment
docker-compose up -d

# Execute commands in container
docker-compose exec server bash
```

## Code Architecture

### Core Models
- **Automaton** (`src/Model/Automaton/`): 有限オートマトンの実装
  - 状態集合、初期状態、受理状態、イベント、遷移を管理
- **Kripke** (`src/Model/Kripke/`): クリプキ構造の実装
  - 状態、遷移関係、ラベル付け関数を管理
  - CTL式の充足性判定機能 (`satisfy()` method)
  - 実行パス生成機能 (`getRuns()` method)

### Logic System
- **Proposition** (`src/Value/Logic/Proposition/`): 命題の基底インターフェース
- **CTL** (`src/Value/Logic/Ctl/`): Computation Tree Logicの実装
  - AllUntil, ExistNext, ExistUntil等のCTL演算子
  - 抽象基底クラス `CtlProposition` で統一的なインターフェース

### Value Objects
- **State** (`src/Value/State/`): 状態を表現する値オブジェクト
- **Transition** (`src/Value/Transition/`): 遷移を表現
- **Run** (`src/Value/Run/`): 実行パスを表現
- **Relation** (`src/Value/Relation/`): 遷移関係を表現

### Architecture Notes
- すべての論理式は callable として実装され、`__invoke()` メソッドで評価
- CTL命題は `CtlProposition` を継承し、Kripke構造と状態を引数として判定
- 状態にはINIT, FINAL等の型があり、オートマトンとクリプキ構造で共通利用
- テストでは `TestKripleModel` ユーティリティクラスでモデル構築を支援

## PSR-4 Autoloading
- Namespace: `ModelChecking\` → `src/`
- Test Namespace: `Tests\` → `src/tests/`