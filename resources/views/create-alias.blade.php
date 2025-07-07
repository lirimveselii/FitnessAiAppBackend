<div class="container">
    {{-- <h2>Add Alias for: {{ $exercises->name }}</h2> --}}

    {{-- @if (session('success')) --}}
    <div style="color: green">{{ session('success') }}</div>
    {{-- @endif --}}

    <form method="POST" action="{{ route('alias.store') }}">
        @csrf
        <div>
            <label for="alias">Alias Name:</label>
            <input type="text" name="alias" id="alias" required>
        </div>
        <label for="exercise_id">Select Exercise:</label>
        <select name="exercise_id" id="exercise_id" required>
            <option value="">-- Choose Exercise --</option>
            @foreach ($exercises as $exercise)
                <option value="{{ $exercise->id }}">{{ $exercise->name }}</option>
            @endforeach
        </select>
        <br>
        <button type="submit">Add Alias</button>
    </form>
</div>
