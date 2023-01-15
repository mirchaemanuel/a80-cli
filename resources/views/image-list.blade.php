<div class="mx-2 my-1">
    @foreach ($imagePaths as $path => $images)
        <div class="w-full font-bold bg-green-400 text-black">
            - {{ $path  }}
        </div>
        <table>
            <thead>
            <tr>
                <th>Filename</th>
                <th>Extension</th>
                <th>MimeType</th>
                <th>Size</th>
            </tr>
            </thead>
            <tbody>
        @foreach($images as $image)
            <tr>
                <td>{{ $image['filename'] }}</td>
                <td>{{ $image['extension'] }}</td>
                <td>{{ $image['mimeType'] }}</td>
                <td align="right">{{ bcdiv($image['size'], 1024,2) }} KB</td>
            </tr>
        @endforeach
            </tbody>
        </table>
    @endforeach
</div>
