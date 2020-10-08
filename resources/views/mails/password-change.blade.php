@extends('layouts.mail')

@section('h1', $subject)

@section('content')

    <div class="adaptive" style="display: inline-block; width: 100%; text-align: left; vertical-align: top;">
        <table align="left" style="width: 100%;" cellpadding="0" cellspacing="0" border="0" summary="">
            <tr>
                <td class="textCenter  textlogo" align="left">
                    <p>Ваш пароль успешно изменен!</p>
                </td>
            </tr>
            <tr style="width: 100%; height: 30px;"></tr>
        </table>
    </div>

@endsection
