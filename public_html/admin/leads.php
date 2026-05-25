<?php
require 'header.php';

// Handle Delete Lead
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM leads WHERE id = ?")->execute([$id]);
    echo "<div style='color:green; margin-bottom:1rem;'>Lead record deleted!</div>";
}

$leads = $pdo->query("SELECT * FROM leads ORDER BY request_date DESC")->fetchAll();
?>

<div class="flex-between">
    <h2>Download Requests / Leads</h2>
</div>

<div class="card">
    <p>Below are the details of people who have downloaded free notes from your website.</p>
    <table style="margin-top: 1rem;">
        <thead>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Mobile Number</th>
                <th>Requested Class</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($leads as $l): ?>
            <tr>
                <td><?= date('M d, Y h:i A', strtotime($l['request_date'])) ?></td>
                <td><strong><?= htmlspecialchars($l['name']) ?></strong></td>
                <td><a href="https://wa.me/91<?= preg_replace('/[^0-9]/', '', $l['mobile']) ?>" target="_blank"><?= htmlspecialchars($l['mobile']) ?> <i class="fas fa-external-link-alt" style="font-size:12px; color:#eab308;"></i></a></td>
                <td><span style="background:#eab308; color:white; padding:2px 8px; border-radius:4px; font-size:12px;"><?= htmlspecialchars($l['class']) ?></span></td>
                <td>
                    <a href="?delete=<?= $l['id'] ?>" class="btn btn-danger" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;" onclick="return confirm('Delete this lead?');">Remove</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($leads)): ?>
            <tr><td colspan="5" style="text-align:center;">No downloads requested yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</div>
</body>
</html>
