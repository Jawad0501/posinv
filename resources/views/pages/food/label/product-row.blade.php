<tr>
    <td>
        {{ $menu->name }}
        <input name="products[{{ request('length', 0) }}][id]" type="hidden" value="{{ $menu->id }}">
    </td>
    <td>
        <select name="products[{{ request('length', 0) }}][variant]" id="products[][variant]" class="form-control" required>
            <option value="">Select variant</option>
            @foreach ($menu->variants as $variant)
                <option value="{{ $variant->id }}">{{ $variant->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input name="products[{{ request('length', 0) }}][no_of_labels]" type="number" value="1" min="1" class="form-control" required>
    </td>
    <td>
        <input name="products[{{ request('length', 0) }}][packing_date]" type="date" value="{{ date('Y-m-d') }}" class="form-control">
    </td>
    <td>
        <input name="products[{{ request('length', 0) }}][expire_date]" type="date" class="form-control">
    </td>
</tr>
