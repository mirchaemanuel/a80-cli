<div class="mx-2 my-1">
    <div class="w-full font-bold bg-green-400 text-black">
        - {{ $imageName  }}
    </div>
    <table>
        <thead>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($exif_data as $field => $data)
            @if(is_array($data))
                <tr>
                    <td colspan="2" align="left" class="font-bold text-left text-red">==== {{ $field }} ====</td>
                </tr>
                @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>{{ is_array($value) ? var_export($value, true) : $value }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" align="left" class="text-left text-red font-bold">---------------------------</td>
                </tr>
            @else
            <tr>
                <td>{{ $field }}</td>
                <td>{{ $data }}</td>
            </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
