<!-- resources/views/admin/exercise-muscles/create.blade.php -->


<div class="container">
    <h2 style="margin-bottom: 20px;">🔗 Connect Muscle Groups to Exercise</h2>

    <form method="POST" action="{{ route('admin.exercise-muscles.store') }}"
        style="display: flex; gap: 40px; flex-wrap: wrap;">
        @csrf

        <!-- Exercise Selection -->
        <div style="flex: 1; min-width: 300px; background: #f8f9fa; padding: 20px; border-radius: 10px;">
            <h4 style="margin-bottom: 15px;">💪 Select an Exercise</h4>
            @foreach ($exercises as $exercise)
                <div style="margin-bottom: 10px;">
                    <label style="cursor: pointer;">
                        <input type="radio" name="exercise_id" value="{{ $exercise->id }}" required>
                        <strong>{{ $exercise->name }}</strong>
                    </label>
                </div>
            @endforeach
        </div>

        <!-- Muscle Group Selection -->
        <div style="flex: 2; min-width: 400px; background: #e9ecef; padding: 20px; border-radius: 10px;">
            <h4 style="margin-bottom: 15px;">🧠 Select Muscle Groups (Multiple)</h4>
            @foreach ($muscleGroups as $group)
                <div style="margin-bottom: 12px; border-bottom: 1px solid #ccc; padding-bottom: 8px;">
                    <label style="font-weight: bold;">
                        <input type="checkbox" name="muscle_group_ids[]" value="{{ $group->id }}">
                        {{ $group->muscle_group }}
                        <span style="font-weight: normal; font-size: 0.9em; color: #555;">(Region:
                            {{ $group->region }})</span>
                    </label>

                    <select name="roles[{{ $group->id }}]" style="margin-left: 10px;">
                        <option value="">Select role</option>
                        <option value="primary">Primary</option>
                        <option value="secondary">Secondary</option>
                    </select>
                </div>
            @endforeach
        </div>

        <!-- Submit Button -->
        <div style="width: 100%; margin-top: 20px;">
            <button type="submit"
                style="padding: 10px 20px; font-weight: bold; background-color: #198754; color: white; border: none; border-radius: 6px; cursor: pointer;">
                ✅ Connect
            </button>
        </div>
    </form>
</div>
