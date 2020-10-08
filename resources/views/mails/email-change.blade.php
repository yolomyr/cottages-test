@extends('layouts.mail')

@section('h1', $subject)

@section('content')

    <div class="adaptive" style="display: inline-block; width: 100%; text-align: left; vertical-align: top;">
        <table align="left" style="width: 100%;" cellpadding="0" cellspacing="0" border="0" summary="">
            <tr>
                <td class="textCenter  textlogo" align="left">
                    <p>{{ $user_full_name }}, для подтверждения изменения вашей почты на {{ $user_new_email }} перейдите по ссылке: <a href="{{ $restore_url }}">Перейти</a></p>
                    <p>Ссылка активна в течении 24 часов с момента отправки запроса.</p>
                </td>
            </tr>
        </table>
    </div>

@endsection
