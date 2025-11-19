<form method="POST" action="{{ route('projects.store') }}">
    @csrf

    <label>Naziv projekta:</label>
    <input type="text" name="naziv_projekta" required>

    <label>Opis:</label>
    <textarea name="opis_projekta"></textarea>

    <label>Cijena:</label>
    <input type="number" name="cijena_projekta" step="0.01">

    <label>Obavljeni poslovi:</label>
    <textarea name="obavljeni_poslovi"></textarea>

    <label>Datum početka:</label>
    <input type="date" name="datum_pocetka" required>

    <label>Datum završetka:</label>
    <input type="date" name="datum_zavrsetka">

    <label>Članovi tima:</label>
    <select name="clanovi[]" multiple>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
    <button type="submit">Spremi</button>
</form>
