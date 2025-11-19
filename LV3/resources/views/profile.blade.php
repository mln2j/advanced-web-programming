<h2>Moji projekti (voditelj)</h2>
<ul>
    @foreach($user->projektiVoditelj as $pr)
        <li>{{ $pr->naziv_projekta }}</li>
    @endforeach
</ul>
<h2>Projekti gdje sam član</h2>
<ul>
    @foreach($user->projektiClan as $pr)
        <li>{{ $pr->naziv_projekta }}</li>
    @endforeach
</ul>
