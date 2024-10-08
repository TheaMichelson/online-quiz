<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Performance History</title>
</head>
<body>
    <h1>Your Performance History</h1>
    <?php if (!empty($performanceHistory)) : ?>
        <table>
            <tr>
                <th>Quiz Title</th>
                <th>Score</th>
                <th>Date Taken</th>
            </tr>
            <?php foreach ($performanceHistory as $record) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['title']); ?></td>
                    <td><?php echo htmlspecialchars($record['score']); ?></td>
                    <td><?php echo htmlspecialchars($record['date_taken']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No quiz results found.</p>
    <?php endif; ?>
</body>
</html>
