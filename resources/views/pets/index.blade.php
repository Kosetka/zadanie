<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Pets</title>
</head>

<body>

    <h1>List of Pets</h1>

    @if(session('success'))
        <div style="color: green; font-weight: bold;">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div style="color: red; font-weight: bold;">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('pets.create') }}">Add new</a>
    <form action="{{ route('pets.index') }}" method="GET">
        <label for="status">Select status:</label>
        <select name="status" id="status">
            <option value="available" {{ $status == 'available' ? 'selected' : '' }}>available</option>
            <option value="sold" {{ $status == 'sold' ? 'selected' : '' }}>sold</option>
            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>pending</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Tags</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pets as $pet)
                <tr>
                    <td>{{ $pet['id'] }}</td>
                    <td>{{ $pet['name'] ?? 'No name' }}</td>
                    <td>{{ !empty($pet['category']['name']) ? $pet['category']['name'] : '' }}</td>
                    <td>
                        @if(!empty($pet['tags']))
                            @foreach($pet['tags'] as $tag)
                                {{ $tag['name'] }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $pet['status'] }}</td>
                    <td>
                        <a href="{{ route('pets.edit', $pet['id']) }}">Edit</a>
                        <form action="{{ route('pets.destroy', $pet['id']) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirmDelete()">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        <a href="{{ route('pets.index', ['status' => $status, 'page' => max(1, $page - 1)]) }}">Previous</a>
        <span>Page {{ $page }}</span>
        <a href="{{ route('pets.index', ['status' => $status, 'page' => $page + 1]) }}">Next</a>
    </div>



    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this pet?");
        }
    </script>
</body>

</html>