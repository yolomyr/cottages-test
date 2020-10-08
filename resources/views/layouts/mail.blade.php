<!DOCTYPE html>
<html lang="en">
<head>
	{{--<meta charset="UTF-8">--}}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <style type="text/css">
        table{font-family:Helvetica,Arial,sans-serif;font-size:14px;color:#585858;}

        .h1,.h1 p{font-family:Helvetica,Arial,sans-serif;font-size:26px;letter-spacing:-1px;margin-bottom:16px;margin-top:2px;line-height:30px;}
        .h2,.h2 p{font-family:Helvetica,Arial,sans-serif;font-size:20px;letter-spacing:0;margin-top:2px;line-height:30px;}

        @media only screen and (max-width: 599px) {
            body{-webkit-text-size-adjust:120% !important;-ms-text-size-adjust:120% !important;}
            table{font-size:15px;}
            .subline{float:left;}
            .padd{width:12px !important;}
            .wrap{width:96% !important;}
            .wrap table{width:100% !important;}
            .wrap img{max-width:100% !important;height:auto !important;}
            .wrap .s{width:100% !important;}
            .wrap .m-0{width:0;display:none;}
            .wrap .m-b{margin-bottom:24px !important;}
            .wrap .m-b,.m-b img{display:block;min-width:100% !important;width:100% !important;}
            table.textbutton td{height:auto !important;padding:8px 14px 8px 14px !important;}
            table.textbutton a{font-size:18px !important;line-height:26px !important;}
        }
        @media only screen and (max-width: 479px) {
            .header-block {
                background-image: none !important;
                background: rgb(245, 248, 255) !important;
            }
        }
        @media only screen and (max-width: 320px) {
        }
        @media only screen and (min-device-width: 375px) and (max-device-width: 667px) {
        }
        @media only screen and (min-device-width: 414px) and (max-device-width: 736px) {
        }

    </style>
</head>

<body style="background: #f5f8ff; margin: 0; padding: 0;">
	<table class="bodytbl" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td align="center">
        <!--[if (gte mso 9) | (IE)]>
        	<table align="center" style="width: 604px;" cellpadding="0" cellspacing="0" border="0" ><tr><td>
        <![endif]-->
        		<table width="600" cellpadding="0" cellspacing="0" class="wrap">

        			<!-- 3CELL: BEGIN -->
{{--                    <tr class="header-block" style="background-image: url('http://dev.lookary.ru/img/bgheader.jpg'); background-repeat: no-repeat; background-size: cover; background-position: center; height: 320px; ">--}}
{{--                        <td align="center" valign="top" style="padding: 0; font-size: 0; line-height: 0;">--}}
{{--                            <!--[if (gte mso 9) | (IE)]><table align="center" style="width: 100%;" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top" style="width: 300px;"><![endif]-->--}}
{{--                            <table align="left" style="width: 100%; padding: 20px" cellpadding="0" cellspacing="0" border="0" summary="">--}}
{{--                                <tr style="width: 100%;">--}}
{{--                                    <td class="logo" align="left" style="padding: 50px 0 0; width: 100%; vertical-align: bottom">--}}
{{--                                        <a href="{{ env('APP_URL') }}" target="_blank" style="text-decoration: none;">--}}
{{--                                            <img src="http://dev.lookary.ru/img/mail-logo.png" alt="" style="border: none; width: 270px; height: auto">--}}
{{--                                        </a>--}}
{{--                                    </td>--}}

{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td align="left" style="width: 100%; color: #162546; font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 26px; line-height: 1.2; font-weight: bold; font-style: normal; vertical-align: bottom">--}}
{{--                                        <h2 style="margin: 0 0 20px; color: #162546;">для бизнеса</h2>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        <p class="header-text" style=" margin: 0; font-size: 14px; color: rgba(20, 38, 71, 0.7); line-height: 1.5;">Помогает Вам всегда оставаться на связи со своими <br>партнерами и клиентами.</p>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
      <!-- 3CELL: END -->

      <!-- Row: BEGIN -->
        <tr class="mail-content" style="background: #fff;">
          <td align="center" style="background: #fff; border-bottom-left-radius: 5px;  border-bottom-right-radius: 5px;">
            <!--[if (gte mso 9) | (IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="middle" style="width: 600px;"><![endif]-->
            <div style="display: inline-block; width: 100%; max-width: 580px; text-align: left; vertical-align: middle;">
              <table align="center" style="width: 100%;" cellpadding="0" cellspacing="0" border="0" summary="">
                <tr>
                  <td class="hello" align="center">
                    <h1>@yield('h1')</h1>
                  </td>
                </tr>
              </table>
            </div>
            <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
            <div style="max-width: 580px;">
                @yield('content')
            </div>
          </td>
        </tr>
        <tr class="mail-content" style="background: #fff;">
            <td align="center" style="background: #fff; border-bottom-left-radius: 5px;  border-bottom-right-radius: 5px;">
                <!--[if (gte mso 9) | (IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td valign="middle" style="width: 600px;"><![endif]-->
                <div style="display: inline-block; width: 100%; max-width: 580px; text-align: left; vertical-align: middle;">
                    <table align="center" style="width: 100%;" cellpadding="0" cellspacing="0" border="0" summary="">
                        <tr>
                            <td class="hello fsz14">
{{--                                <a href="{{ env('APP_URL') }}" target="_blank" style="text-decoration: none;"><img src="http://dev.lookary.ru/img/mail-logo2.png" alt="" style="border: none; width: 130px; height: auto; margin-bottom: 10px;"></a>--}}
                                <hr>    
                                @if($adminUser)
                                <p>Администратор</p>
                                <p>ФИО: {{ $adminUser->name }} {{ $adminUser->surname }}</p>
                                <p>Телефон: <a href="tel:{{ $adminUser->phone }}">{{ $adminUser->phone }}</a></p>
                                <p>E-mail: <a href="mailto:{{ $adminUser->email }}">{{ $adminUser->email }}</a></p>
                                @endif
                                <p>Коттеджный поселок Балаково</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->


            </td>
        </tr>
        <!-- Row: END -->

      <tr style="background: #f5f8ff;">
      	<td align="center" valign="top" style="font-size: 0; line-height: 0;border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">

      			<!--[if (gte mso 9)|(IE)]></td><td valign="top" style="width: 300px;"><![endif]-->
{{--      				<div class="adaptive" style="display: inline-block; width: 100%; text-align: center; vertical-align: top; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">--}}
{{--      					<table align="center" style="width: 100%;" cellpadding="0" cellspacing="0" border="0" summary="" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">--}}
{{--      						<tr>--}}
{{--      							<td class="textCenter textlogo" align="center" style=" padding: 10px 10px; color: #333; opacity: .5; font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 12px; line-height: 17px; font-weight: normal; font-style: normal;">--}}
{{--      								Данное письмо сформировано автоматически, отвечать на него не требуется!--}}

{{--      								@if(isset($unsubscribeUrl) && !empty($unsubscribeUrl))--}}
{{--      								<br>Вы получили это письмо, потому что подписаны на рассылку новостей от Lookary.--}}
{{--      								@endif--}}
{{--      							</td>--}}
{{--      						</tr>--}}
{{--      					</table>--}}
{{--      				</div>--}}

{{--      				@if(isset($unsubscribeUrl) && !empty($unsubscribeUrl))--}}
{{--        				<!--[if (gte mso 9)|(IE)]></td><td valign="top" style="width: 300px;"><![endif]-->--}}
{{--    					<div class="adaptive hide" style="display: inline-block; width: 100%; max-width: 100%; text-align: center; vertical-align: top;">--}}
{{--    						<table align="center" style="width: 100%;" cellpadding="0" cellspacing="0" border="0" summary="">--}}
{{--    							<tr>--}}
{{--    								<td align="left" style="padding: 15px 0 30px; color: #000000; font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px; line-height: 14px; font-weight: normal; font-style: normal;">--}}
{{--    									<table align="center" style="background-color: #293642; border-radius: 3px; border: 1px solid #afb3b9; border-color: #afb3b9;" cellpadding="0" cellspacing="0" border="0" summary="">--}}
{{--    										<tr>--}}
{{--    											<td align="center" style="padding: 15px; color: #afb3b9; font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 14px; line-height: 10px; font-weight: normal; font-style: normal;">--}}
{{--    												<a href="{{$unsubscribeUrl}}" target="_blank" style="text-decoration: none; color: #afb3b9;">Отменить подписку</a>--}}
{{--    											</td>--}}
{{--    										</tr>--}}
{{--    									</table>--}}
{{--    								</td>--}}
{{--    							</tr>--}}
{{--    						</table>--}}
{{--    					</div>--}}
{{--    					@endif--}}
      					<!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
      				</td>
      			</tr>
      		</table>
      		<!--[if (gte mso 9) | (IE)]></td></tr></table><![endif]-->
      	</td>
      </tr>
  </table>
</body>

<style type="text/css">
    .mail-content a {
        color:#4876BC;
    }
    .mail-content > td{
        padding: 30px 40px;
    }
    .hello {
      font-size: 26px;
      line-height: 1.1;
      font-weight: bold;
      background: #fff;
      color: #162546;
    }
    p {
        margin: 0 0 10px;
        font-size: 16px;
        font-weight: normal;
        color: rgba(20, 38, 71, 0.7);
        line-height: 1.5;
    }
    .line {
        width: 100%;
        height: 1px;
        background-color: rgba(23, 38, 72, 0.5);
        opacity: .2;
        display: block;
    }
    h3 {
        font-size: 16px;
        font-weight: normal;
        margin: 10px 0 5px;
        color: #162546;
    }
    p.fsz14 {
        font-size: 14px;
    }
</style>
</html>

