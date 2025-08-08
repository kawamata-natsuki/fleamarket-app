@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('title', 'メール認証')

@section('content')
<div class="verify-email-page">
  <h1 class="sr-only">
    メール認証
  </h1>
  <p class="verify-email-page__message">
    登録していただいたメールアドレスに認証メールを送付しました。<br>
    メール認証を完了してください。
  </p>

  <!-- 認証確認 -->
  <form class="verify-email-page__form verify-email-page__form--confirm"
    method="GET"
    action="{{ route('verification.check') }}">
    <button class="verify-email-page__confirm-button" type="submit">
      認証はこちらから
    </button>
  </form>

  <!-- 認証メール再送信 -->
  <form class="verify-email-page__form verify-email-page__form--resend"
    method="POST"
    action="{{ route('verification.send') }}">
    @csrf
    <button class="verify-email-page__resend-button" type="submit">
      認証メールを再送する
    </button>
  </form>
</div>
@endsection