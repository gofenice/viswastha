<!-- <tr>
    <td>{{ $user->id }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->phone }}</td>
    <td>{{ $user->position }}</td>
    <td>{{ $level }}</td>
    <td>{{ $user->join_amount }}</td>
    <td>{{ $user->total_income }}</td>
</tr> -->
<!-- <tr>
    <td colspan="8">
        <table class="table table-bordered">
            <tbody>
                @if ($user->leftChild)
                @include('mlm.partials.node', ['user' => $user->leftChild, 'level' => $level + 1])
                @endif
                @if ($user->rightChild)
                @include('mlm.partials.node', ['user' => $user->rightChild, 'level' => $level + 1])
                @endif
            </tbody>
        </table>
    </td>
</tr> -->
