<div class="mx-2 my-1">
    @foreach ($images as $path => $imagePath)
        <div class="w-full font-bold bg-green-400 text-black">
            - {{ $path  }}
        </div>
        <table>
            <thead>
            <tr>
                <td>Filename</td>
                <td>Extension</td>
                <td>MimeType</td>
                <td>Size</td>
            </tr>
            </thead>
            <tbody>
        @foreach($imagePath as $image)
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
