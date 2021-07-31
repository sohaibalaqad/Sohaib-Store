<div class="form-group">
    <x-form-input name="name" label="Role Name" :value="$role->name" />
</div>
<div class="form-group">
    @foreach (config('abilities') as $key => $value)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="abilities[]" value="{{ $key }}" 
        @if (in_array($key, $role->abilities ?? [])) checked  @endif >
        <label class="form-check-label">
          {{ $value }}
        </label>
      </div>
    @endforeach
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $button }}</button>
</div>
