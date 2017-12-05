<tr>
    <td style="border: 1px solid black; text-align: left; padding-left: 5px; padding-right: 5px; white-space: nowrap;">
        {{ $index }}.
        @if(isset($user))
            {{ $user->is_intro?'Gast van ':'' }}
            {{ $user->user->name }}
        @endif
    </td>
    <td style="border: 1px solid black; text-align: left; padding-left: 5px; padding-right: 5px; width: 100%;">
        @if(isset($user))
            @if($user->allergies != null)Allergie&euml;n: {{ $user->allergies }} @endif
            @if($user->allergies != null && $user->extra_information != null) <br /> @endif
            @if($user->extra_information != null)Extra info: {{ $user->extra_information }} @endif
            @if(($user->cooks || $user->dishwasher) && !$user->isStaff()) (Reserve pers.) @endif
        @endif
    </td>
    <td style="border: 1px solid black; text-align: left; padding-left: 5px; padding-right: 5px;">
        @if(isset($extra))
            {{ $extra }}
        @elseif(isset($user))
            @if(($user->cooks || $user->dishwasher) && $user->isStaff())
                {{ ($user->cooks) ? ($user->dishwasher?'Koker & Afwasser':'Koker'):'Afwasser' }}
            @else
                {{ ($user->price() == $user->paid)?'Betaald':'' }}
            @endif
        @endif
    </td>
</tr>