# コードスタイルと規約

## PHPコードスタイル

- **名前空間**: PSR-4準拠
  - `ModelChecking\` → `src/`
  - `Tests\` → `src/tests/`
- **クラス名**: PascalCase
- **メソッド名**: camelCase
- **定数**: UPPER_SNAKE_CASE
- **プロパティ**: camelCase with protected visibility

## アーキテクチャパターン

- **Value Objects**: 不変オブジェクトとして設計
- **Callable Interface**: 論理式は`__invoke()`メソッドで評価
- **抽象基底クラス**: `CtlProposition`, `Until`等で共通インターフェース
- **State Pattern**: StateクラスでINIT, FINAL, DEFAULT型を管理

## テストパターン

- **Test Case継承**: PHPUnit TestCaseを基底とする
- **Data Provider**: 複雑なテストデータは別クラスで管理
- **Assertion**: 参照同一性と値同等性を適切に使い分け
