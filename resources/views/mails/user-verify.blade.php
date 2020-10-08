@extends('layouts.mail')

@section('h1', $subject)

@section('content')

    <div class="adaptive" style="display: inline-block; width: 100%; text-align: left; vertical-align: top;">
        <table align="left" style="width: 100%;" cellpadding="0" cellspacing="0" border="0" summary="">
            <tr>
                <td class="textCenter  textlogo" align="left">
                    <p>Благодарим за регистрацию на сайте <a href="{{ env('APP_URL') }}">{{ env('APP_NAME') }}</a>, {{ $user_full_name }}!</p>
                    <p>E-mail: {{ $user->email }}</p>
                    <p>Пароль: {{ $user_password }}</p>
                    <p>Если же, не Вы оставляли этот запрос, то настоятельно рекомендуем проигнорировать данное письмо.</p>
                </td>
            </tr>
        </table>
    </div>

@endsection
