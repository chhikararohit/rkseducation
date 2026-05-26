<?php
require 'header.php';

// Handle Add Note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_note'])) {
    $class_name = $_POST['class_name'];
    
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $allowed = ['pdf', 'doc', 'docx', 'zip'];
        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $filename = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['file']['name']);
            $destination = '../uploads/notes/' . $filename;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
                $file_path = 'uploads/notes/' . $filename;
                $stmt = $pdo->prepare("INSERT INTO notes (class_name, file_path) VALUES (?, ?)");
                $stmt->execute([$class_name, $file_path]);
                echo "<div style='color:green; margin-bottom:1rem;'>Notes file uploaded successfully!</div>";
            }
        } else {
             echo "<div style='color:red; margin-bottom:1rem;'>Invalid file format. Only PDF, DOC, ZIP allowed.</div>";
        }
    }
}

// Handle Delete Note
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("SELECT file_path FROM notes WHERE id = ?");
    $stmt->execute([$id]);
    $note = $stmt->fetch();
    if ($note && strpos($note['file_path'], 'uploads/notes/') === 0) {
        if(file_exists('../' . $note['file_path'])) {
            unlink('../' . $note['file_path']);
        }
    }
    $pdo->prepare("DELETE FROM notes WHERE id = ?")->execute([$id]);
    echo "<div style='color:green; margin-bottom:1rem;'>Notes deleted!</div>";
}

$notes = $pdo->query("SELECT * FROM notes ORDER BY class_name ASC, upload_date DESC")->fetchAll();
?>

<div class="flex-between">
    <h2>Manage Class Notes</h2>
</div>

<div class="grid grid-2 gap-4" style="display:grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Add Form -->
    <div class="card" style="align-self: start;">
        <h3>Upload Notes</h3>
        <form method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
            <div class="form-group">
                <label>Class</label>
                <select name="class_name" required>
                    <option value="">Select Class</option>
                    <option value="8th">8th Class</option>
                    <option value="9th">9th Class</option>
                    <option value="10th">10th Class</option>
                    <option value="11th">11th Class</option>
                    <option value="12th">12th Class</option>
                </select>
            </div>
            <div class="form-group">
                <label>Select File (PDF/DOC/ZIP)</label>
                <input type="file" name="file" accept=".pdf,.doc,.docx,.zip" required>
            </div>
            <button type="submit" name="add_note" class="btn btn-warning w-100" style="width: 100%;">Upload Notes</button>
        </form>
    </div>

    <!-- List -->
    <div class="card">
        <h3>Available Notes Files</h3>
        <table style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>File</th>
                    <th>Date Added</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($notes as $n): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($n['class_name']) ?></strong></td>
                    <td><a href="../<?= htmlspecialchars($n['file_path']) ?>" target="_blank">View File</a></td>
                    <td><?= date('M d, Y', strtotime($n['upload_date'])) ?></td>
                    <td>
                        <a href="?delete=<?= $n['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this file?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($notes)): ?>
                <tr><td colspan="4" style="text-align:center;">No notes formally uploaded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</body>
</html>
