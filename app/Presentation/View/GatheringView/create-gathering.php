<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Gathering</title>
</head>

<body>
    <h2>Create a New Gathering</h2>
    <form action="../controllers/GatheringController.php" method="POST">
        <input type="hidden" name="host_id" value="1"> <!-- Example host ID -->
        <label>Location:</label>
        <input type="text" name="location" required>
        <label>Theme:</label>
        <input type="text" name="theme" required>
        <label>Max Participants:</label>
        <input type="number" name="max_participants" required>
        <label>Description:</label>
        <textarea name="description"></textarea>
        <label>Date & Time:</label>
        <input type="datetime-local" name="date_time" required>
        <button type="submit">Create Gathering</button>
    </form>
</body>

</html>