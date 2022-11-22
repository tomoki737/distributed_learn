

# 分散学習アプリ
学生時代に国家試験の勉強で解いた日から適切なタイミングで解答ができる分散学習アプリを使っていました。しかし、そのアプリでは解答形式が1つしかなく、効率的に学習することができませんでした。そのため、選択肢や筆記での解答ができる機能を追加し、学習の定着率を上げたいと思ったためこのアプリを作成しました。<br>
<br>
ポートフォリオURL: https://distributed-learn.de/question/search
<br>
<br>  

## トップページ
<img width="368" alt="スクリーンショット 2022-11-03 13 19 22" src="https://user-images.githubusercontent.com/88951052/199647021-6ce36d84-99ef-4015-b9b4-da9274b26c42.png">
<br>

## 特に見ていただきたい点

- インフラ
  - Dockerを使用し、ECS Fargateで本番環境をサーバーレスで運用している点。
  - Terraformを使用し、インフラをコード化している点
  - CircleCIを使い、CICDパイプラインを構築している点
- バックエンド
  - LaravelでAPIを作成し、フロントエンドに JSONデータをリスポンスしている点
- フロントエンド
  - フロントエンドではVueを使用し、完全SPAで構成している点
  - TypeScriptを使用している点
- その他
  - 特に工夫した点は選択肢での回答機能です。回答する際に表示される選択肢は問題の関連度が近いものが表示されるように工夫しました。
<br>

## 使用した技術
* フロントエンド  
  * Javascript
  * Vue (完全SPA、TypeScript)
  * Vuetify（UIフレームワーク）
* バックエンド  
  * PHP 8.1.1
  * Laravel 8.83.5 (API)
  * PHPUnit（テスト）
* インフラ・開発環境  
  * Docker/Docker-compose
  * AWS（ECR,ECS,VPC,Route53,ALB,RDS）
  * Terraform（インフラのコード管理）
  * CircleCI（CI/CD）

<br>

## ER図

<br>

<img width="664" alt="スクリーンショット 2022-10-23 18 09 48" src="https://user-images.githubusercontent.com/88951052/197384015-06621b0f-87f7-44b5-afbb-46e3b240cda1.png">
<br>
terraform GitHub URL https://github.com/tomoki737/distributed_learn_terraform

## AWS構成図

<br>

<img width="811" alt="スクリーンショット 2022-11-03 13 30 16" src="https://user-images.githubusercontent.com/88951052/199647963-7ca55d6b-1d87-4c11-89a3-6d08e9c93b54.png">
<br>

<br>

## 機能一覧
* ログイン前機能
  * 新規会員登録・ログイン機能・ゲストログイン機能
  * 検索機能 (カテゴリ、タグ、学習中の問題による絞り込み機能)
* ログイン後機能
  * ログアウト機能
  * 問題のダウンロード機能
  * 問題一覧
  * 問題作成、編集、削除機能(カテゴリー、Share機能)
  * タグの作成機能
  * 問題を対象外にする機能
  * いいね機能
  * フォロー機能
  * 問題の回答機能
     * わかる、わからないによる回答
     * 選択肢による回答
     * 筆記による回答
     * 回答日からの適切な日に表示
  * ユーザーマイページ表示（作成した問題のダウンロード数、ダウンロードした問題数、いいねされた回数、フォロー、フォロワーの表示）
    * フォロー、フォロワーの一覧表示
