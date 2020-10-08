@extends('layouts.mail')

@section('h1', $subject)

@section('content')

    <div class="adaptive" style="display: inline-block; width: 100%; text-align: left; vertical-align: top;">
        <table align="left" style="width: 100%;" cellpadding="0" cellspacing="0" border="0" summary="">
            <tr>
                <td class="textCenter  textlogo" align="left">
                    <p>{{ $user_full_name }}, мы получили запрос на восстановление пароля для этого электронного адреса.</p>
                    <p>E-mail: <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                    <p>Пройдите по указанной ссылке: <a href="{{ $restore_url }}">Подтвердить</a></p>
                </td>
            </tr>
            <tr style="width: 100%; height: 30px;"></tr>
        </table>
    </div>

@endsection
