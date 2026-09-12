@csrf
@isset($admin) @method('PUT') @endisset

<div class="max-w-lg space-y-5">
    <div>
        <label for="name" class="block text-sm font-medium text-stone-700">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $admin->name ?? '') }}" required
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $admin->email ?? '') }}" required
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-stone-700">
            Password @isset($admin) <span class="text-stone-400">(leave blank to keep current)</span> @endisset
        </label>
        <input id="password" name="password" type="password"
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-stone-700">Confirm password</label>
        <input id="password_confirmation" name="password_confirmation" type="password"
               class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
    </div>

    <div>
        <span class="block text-sm font-medium text-stone-700">Roles</span>
        @php $selected = old('roles', isset($admin) ? $admin->roles->pluck('name')->all() : []); @endphp
        <div class="mt-2 space-y-2">
            @foreach ($roles as $role)
                <label class="flex items-center gap-2 text-sm text-stone-600">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                           @checked(in_array($role->name, $selected))
                           class="rounded border-stone-300 text-brand-600">
                    {{ $role->name }}
                </label>
            @endforeach
        </div>
        @error('roles') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
        {{ isset($admin) ? 'Save changes' : 'Create admin' }}
    </button>
</div>
