{{ $slot }}:
<table style="border-spacing: 0px 0px; border-collapse: collapse;">
    @for($i = 1; $i <= $consumptions; $i++)
        <tr>
            <td style="border: 1px solid black; white-space: nowrap; padding-left: 5px; padding-right: 5px;">
                Consumptie {{ $i }} ~ &euro;{{ number_format(config('mensa.consumptions.price'), 2) }}</td>
        </tr>
    @endfor
</table>
<br/>