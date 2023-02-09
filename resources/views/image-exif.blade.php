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
                @if($field === 'COMPUTED')
                    @continue
                @endif
                <tr>
                    <td>{{ $field }}</td>
                    <td>{{ $data }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center" class="text-center">COMPUTED</td>
            </tr>
            @foreach ($exif_data['COMPUTED'] as $field => $data)
                <tr>
                    <td>{{ $field }}</td>
                    <td>{{ $data }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
</div>
