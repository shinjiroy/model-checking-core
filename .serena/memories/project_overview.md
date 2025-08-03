# プロジェクト概要

## プロジェクトの目的

モデル検査器の実装。オートマトンとKripke構造を実装し、Computation Tree Logic (CTL)の満足性判定を行う。

## 技術スタック

- **言語**: PHP 8.0.2以上
- **テストフレームワーク**: PHPUnit 9.5.10
- **開発環境**: Docker + Docker Compose
- **自動ロード**: PSR-4

## プロジェクト構造

```tree
src/
├── Model/           # コアモデル（Automaton, Kripke）
├── Value/           # Value Objects（State, Transition, Logic等）
├── Util/           # ユーティリティクラス
└── tests/          # テストコード
```

## 主要コンポーネント

- **Automaton**: 有限オートマトンの実装
- **Kripke**: クリプキ構造の実装とCTL式の満足性判定
- **CTL Logic**: AllUntil, ExistNext, ExistUntil等のCTL演算子
- **Value Objects**: State, Transition, Run, Relation等
