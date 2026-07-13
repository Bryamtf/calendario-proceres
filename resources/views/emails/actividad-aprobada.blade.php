<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0;padding:0;background:#FAF8F4;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background:#FAF8F4;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0"
                    style="max-width:480px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #E4DFD3;">
                    <tr>
                        <td style="background:#2B3A4A;padding:20px 28px;">
                            <span
                                style="color:#ffffff;font-size:16px;font-weight:600;">{{ \App\Models\ConfiguracionSistema::obtener()->nombre_barrio }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p
                                style="margin:0 0 4px;font-size:11px;color:#5B7A5D;font-weight:700;text-transform:uppercase;letter-spacing:.04em;">
                                Actividad aprobada</p>
                            <h1 style="margin:0 0 16px;font-size:20px;color:#26282B;line-height:1.3;">
                                {{ $actividad->nombre }}</h1>

                            <p style="margin:0 0 22px;font-size:14px;color:#26282B99;line-height:1.5;">
                                El Consejo de Obispado aprobó tu propuesta para el <strong
                                    style="color:#26282B;">{{ $actividad->fecha->format('d/m/Y') }}</strong>. Ya puedes
                                verla reflejada en el calendario del barrio.
                            </p>

                            <a href="{{ route('actividades.show', $actividad) }}"
                                style="display:inline-block;background:#5B7A5D;color:#ffffff;text-decoration:none;font-size:14px;font-weight:600;padding:12px 22px;border-radius:8px;">Ver
                                actividad</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;border-top:1px solid #E4DFD3;background:#FAF8F4;">
                            <p style="margin:0;font-size:11px;color:#26282B66;">Mensaje automático del sistema de
                                planificación trimestral. No respondas a este correo.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
