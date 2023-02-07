<div class="mx-2 my-1">
    <div class="font-bold underline">
        A80-cli version {{$version}}
    </div>
    <div class="mb-1">
        Mircha Emanuel D'Angelo | https://a80.it
    </div>
    <hr>
    @if(!empty($releases))
        <div class="px-2">
            <div class="mb-1">Releases:</div>
            <table>
                <thead>
                <tr>
                    <th>Release</th>
                    <th>Date</th>
                    <th>Notes</th>
                </tr>
                </thead>
                <tbody>
                @foreach($releases as $release)
                    <tr>
                        <td>{{$release['release']}}</td>
                        <td>{{$release['date']}}</td>
                        <td>
                            <ul>
                                @foreach($release['notes'] as $note)
                                    <li>{{$note}}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <hr>
</div

