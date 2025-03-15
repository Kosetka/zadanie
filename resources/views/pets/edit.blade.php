<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pet</title>
</head>

<body>

    <h1>Edit Pet</h1>

    @if(session('error'))
        <div style="color: red; font-weight: bold;">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div style="color: green; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pets.update', $pet['id']) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $pet['name']) }}" required><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="available" {{ old('status', $pet['status']) == 'available' ? 'selected' : '' }}>available
            </option>
            <option value="sold" {{ old('status', $pet['status']) == 'sold' ? 'selected' : '' }}>sold</option>
            <option value="pending" {{ old('status', $pet['status']) == 'pending' ? 'selected' : '' }}>pending</option>
        </select><br>

        <label for="photoUrls">Photo URL:</label>
        <input type="text" id="photoUrls" name="photoUrls[]"
            value="{{ old('photoUrls', isset($pet['photoUrls'][0]) ? $pet['photoUrls'][0] : '') }}"><br>

        <button type="submit">Update</button>
    </form>

</body>

</html>