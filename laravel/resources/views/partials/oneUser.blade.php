@php
    use App\Models\Ban;
@endphp

<tr>
    <td>{{ $user->user_id}}</td>
    <td>
        <a href="{{ route('viewProfile', ['id' => $user->user_id]) }}" style="text-decoration:none; color:black;">
            {{ $user->name }}
        </a>
    </td>
    <td>
        <a href="{{ route('viewProfile', ['id' => $user->user_id]) }}" style="text-decoration:none; color:black;">
            {{ $user->username }}
        </a>
    </td>
    <td>
        {{ $user->email}}
    </td>
    <td>
        @if (Ban::firstWhere('user_id', $user->user_id) == null)
            <a class="button" href="{{ route('ban', ['id' => $user->user_id]) }}"> Deactivate </a>
        @else
            <a class="button" href="{{ route('unban', ['id' => $user->user_id]) }}"> Reactivate </a>
        @endif

        <a class="button" href="{{ route('showAdminEdit', ['id' => $user->user_id]) }}"> Edit </a>

        @if (!$user->is_admin)
            <a class="button" href="{{ route('upgrade', ['id' => $user->user_id]) }}"> Promote </a>
        @else
            <a class="button" href="{{ route('downgrade', ['id' => $user->user_id]) }}"> Demote </a>
        @endif
    </td>
</tr>