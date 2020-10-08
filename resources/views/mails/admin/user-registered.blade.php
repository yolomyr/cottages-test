@extends('layouts.mail')

@section('h1', $subject)

@section('content')

    <div class="adaptive" style="display: inline-block; width: 100%; text-align: left; vertical-align: top;">
        <table align="left" style="width: 100%;" cellpadding="0" cellspacing="0" border="0" summary="">
            <tr>
                <td class="textCenter  textlogo" align="left">
                    <p>Данные о новом зарегистрированном пользователе:</p>
                    <br>
                    <p>Имя: {{ $user->name }}</p>
                    <p>Фамилия: {{ $user->surname }}</p>
                    <p>День рождения: {{ $user->birthday}}</p>
                    <p>Пол: {{ $user->gender->gender_name }}</p>
                    <p>Телефон: {{ $user->phone }}</p>
                    <p>Email: {{ $user->email }}</p>

                    @if(!empty($user->whatsapp_phone))
                    <p>WhatsApp: {{ $user->whatsapp_phone }}</p>
                    @endif

                    @if(!empty($user->telegram_phone))
                    <p>Telegram: {{ $user->telegram_phone }}</p>
                    @endif

                    @if(!empty($user->viber_phone))
                    <p>Viber: {{ $user->viber_phone }}</p>
                    @endif

                    @if(!empty($user->work_info))
                        <p>Работа: {{ $user->work_info }}</p>
                    @endif

                    @if(!empty($user->hobby_info))
                        <p>Хобби: {{ $user->hobby_info }}</p>
                    @endif

                    @if(!empty($user->family_info))
                        <p>Семья: {{ $user->family_info }}</p>
                    @endif

                    @if(!empty($user->extra_info))
                        <p>Дополнительная информация: {{ $user->extra_info }}</p>
                    @endif

                    @if(!empty($user->user_estates))
                        <p>Имещество пользователя:</p>
                        @foreach($user->user_estates as $estate)
                            <p>Тип - {{ $estate_types[$estate->estate_type_id] }}, номер - {{ $estate->estate_number }}</p>
                        @endforeach
                    @endif

                    <p>Для подтверждения пользователя перейдите по ссылке <a href="{{ $confirmation_url }}">{{ $confirmation_url }}</a></p>
                </td>
            </tr>
            <tr style="width: 100%; height: 30px;"></tr>
        </table>
    </div>

@endsection
