{{--
    Shared property form fields.
    Expects: $types (PropertyType[]), $statuses (PropertyStatus[])
    Optional: $property (when editing)
--}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Title --}}
    <div class="lg:col-span-2">
        <x-input-label for="title" value="Title *" />
        <x-text-input id="title" name="title" type="text" class="mt-1 w-full"
                      :value="old('title', $property->title ?? '')" required />
        <x-input-error :messages="$errors->get('title')" class="mt-1" />
    </div>

    {{-- Description --}}
    <div class="lg:col-span-2">
        <x-input-label for="description" value="Description *" />
        <textarea id="description" name="description" rows="5"
                  class="mt-1 w-full rounded-md border-slate-300 bg-white text-slate-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                  required>{{ old('description', $property->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-1" />
    </div>

    {{-- Type --}}
    <div>
        <x-input-label for="type" value="Property Type *" />
        <select id="type" name="type" required
                class="mt-1 w-full rounded-md border-slate-300 bg-white text-slate-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @foreach($types as $type)
                <option value="{{ $type->value }}"
                    {{ old('type', $property->type->value ?? '') === $type->value ? 'selected' : '' }}>
                    {{ $type->label() }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-1" />
    </div>

    {{-- Status --}}
    <div>
        <x-input-label for="status" value="Status *" />
        <select id="status" name="status" required
                class="mt-1 w-full rounded-md border-slate-300 bg-white text-slate-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @foreach($statuses as $status)
                <option value="{{ $status->value }}"
                    {{ old('status', $property->status->value ?? '') === $status->value ? 'selected' : '' }}>
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>

    {{-- Price --}}
    <div>
        <x-input-label for="price" value="Price (USD) *" />
        <x-text-input id="price" name="price" type="number" min="1" step="0.01" class="mt-1 w-full"
                      :value="old('price', $property->price ?? '')" required />
        <x-input-error :messages="$errors->get('price')" class="mt-1" />
    </div>

    {{-- Area --}}
    <div>
        <x-input-label for="area" value="Area (sqft)" />
        <x-text-input id="area" name="area" type="number" min="1" class="mt-1 w-full"
                      :value="old('area', $property->area ?? '')" />
        <x-input-error :messages="$errors->get('area')" class="mt-1" />
    </div>

    {{-- Bedrooms --}}
    <div>
        <x-input-label for="bedrooms" value="Bedrooms" />
        <x-text-input id="bedrooms" name="bedrooms" type="number" min="0" class="mt-1 w-full"
                      :value="old('bedrooms', $property->bedrooms ?? '')" />
        <x-input-error :messages="$errors->get('bedrooms')" class="mt-1" />
    </div>

    {{-- Bathrooms --}}
    <div>
        <x-input-label for="bathrooms" value="Bathrooms" />
        <x-text-input id="bathrooms" name="bathrooms" type="number" min="0" class="mt-1 w-full"
                      :value="old('bathrooms', $property->bathrooms ?? '')" />
        <x-input-error :messages="$errors->get('bathrooms')" class="mt-1" />
    </div>

    {{-- City --}}
    <div>
        <x-input-label for="city" value="City *" />
        <x-text-input id="city" name="city" type="text" class="mt-1 w-full"
                      :value="old('city', $property->city ?? '')" required />
        <x-input-error :messages="$errors->get('city')" class="mt-1" />
    </div>

    {{-- Address --}}
    <div>
        <x-input-label for="address" value="Full Address *" />
        <x-text-input id="address" name="address" type="text" class="mt-1 w-full"
                      :value="old('address', $property->address ?? '')" required />
        <x-input-error :messages="$errors->get('address')" class="mt-1" />
    </div>

    {{-- Featured Image --}}
    <div class="lg:col-span-2">
        <x-input-label for="featured_image" value="Featured Image" />
        <input id="featured_image" name="featured_image" type="file"
               accept="image/jpeg,image/png,image/webp"
               class="mt-1 w-full text-sm text-slate-700
                      file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                      file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700
                      hover:file:bg-indigo-100 cursor-pointer" />
        <p class="mt-1 text-xs text-slate-400">JPG, PNG or WebP · max 5MB · auto-resized to 1200px</p>
        <x-input-error :messages="$errors->get('featured_image')" class="mt-1" />

        @if(isset($property) && $property->featured_image)
            <div class="mt-3">
                <p class="text-xs text-slate-400 mb-1.5">Current image:</p>
                <img src="{{ asset('storage/' . $property->featured_image) }}"
                     class="h-24 w-36 object-cover rounded-lg border border-slate-200" alt="Current" />
            </div>
        @endif
    </div>

</div>
