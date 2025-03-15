<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a New Pet</title>
</head>

<body>

    <h1>Add a New Pet</h1>

    @if(session('success'))
        <div style="color: green; font-weight: bold;">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div style="color: red; font-weight: bold;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('pets.store') }}" method="POST">
        @csrf

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br>

        <label for="photoUrls">Photo URL:</label>
        <input type="text" name="photoUrls[]" id="photoUrls" required><br>

        <label for="status">Status (available/sold/pending):</label>
        <select name="status" id="status" required>
            <option value="available">available</option>
            <option value="sold">sold</option>
            <option value="pending">pending</option>
        </select><br>

        <button type="submit">Add pet</button>
    </form>

</body>

</html>