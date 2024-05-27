<?php

namespace App\Http\Requests\Backend;

use App\Models\Tablelayout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableLayoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,bmp,jpeg,webp', 'max:1024'],
            // 'categories' => ['nullable'],
            'foods' => ['nullable'],
        ];

    }

    /**
     * Update or store current requested data
     */
    public function saved(Tablelayout $tablelayout = null)
    {
        $input = $this->validated();

        if (! array_key_exists('foods', $input)) {
            $input['foods'] = 0;
        }

        // if ($input['categories'] == null) {
        //     $input['categories'] = 0;
        // }

        if ($input['foods'] == null) {
            $input['foods'] = 0;
        }

        if ($this->isMethod('POST')) {
            $input['available'] = $this->capacity;
        }
        // dd(route('table-menu', [$input['number'], $input['categories'], $input['foods']]));

        $image = $this->isMethod('PUT') ? $tablelayout->image : null;
        $input['image'] = $this->hasFile('image') ? file_upload($this->image, 'table', $image) : $image;

        $this->isMethod('POST') ? Tablelayout::query()->create($input) : $tablelayout->update($input);

        $table_number = $input['number'];

        if (Storage::disk('public')->exists('table-qr/'.$table_number.'.svg')) {
            Storage::disk('public')->delete('table-qr/'.$table_number.'.svg');

            // QrCode::generate(route('table-menu', [$input['number'], $input['foods']]), 'storage/table-qr/'.$table_number.'.svg');

            $table = Tablelayout::query()->where('number', $table_number)->firstOrFail();
            // $table->qr_code = 'table-qr/'.$table_number.'.svg';
            $table->save();

            $message = $this->isMethod('POST') ? 'New table added successfully' : 'Table updated successfully';

            return response()->json(['message' => $message]);
        } else {
            // QrCode::generate(route('table-menu', [encrypt($input['number']), encrypt($input['foods'])]), 'storage/table-qr/'.$table_number.'.svg');

            $table = Tablelayout::query()->where('number', $table_number)->firstOrFail();
            // $table->qr_code = 'table-qr/'.$table_number.'.svg';
            $table->save();

            $message = $this->isMethod('POST') ? 'New table added successfully' : 'Table updated successfully';

            return response()->json(['message' => $message]);
        }
    }
}
