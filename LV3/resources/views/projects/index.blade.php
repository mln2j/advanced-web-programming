<h2>Moji projekti:</h2>
<a href="{{ route('projects.create') }}" class="btn btn-primary">Novi projekt</a>
<ul>
    @forelse($projects as $project)
        <li>
            {{ $project->naziv_projekta }} | Voditelj: {{ $project->voditelj->name }}
            <a href="{{ route('projects.edit', $project) }}">Uredi</a>
        </li>
    @empty
        <li>Nema projekata.</li>
    @endforelse
</ul>
