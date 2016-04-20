@section('content')
@if (count($members) > 0)
[
@foreach ($members as $m)
{"name":"{{ $m->name }}","vk":"{{ $m->vk }}","github":"{{ $m->github }}"},
@endforeach
]
@endif
@endsection
