<?php
include 'header.php';
?>
<link rel="stylesheet" href="css/complaint.css">

<div class="complaint-container" style="margin-top: 50px; margin-bottom: 50px;">
    <h1>Submit Your Complaint</h1>
    
    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div class="status-message success">
            Complaint submitted successfully!
        </div>
    <?php endif; ?>

    <form action="submit_complaint.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="category">Complaint Category:</label>
            <select name="category" id="category" required>
                <option value="">-- Select Category --</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Cleanliness">Cleanliness</option>
                <option value="Facilities">Facilities</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" placeholder="Enter the location" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="5" placeholder="Describe your complaint" required></textarea>
        </div>

        <div class="form-group">
            <label for="image">Attach an Image (optional):</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        <button type="submit">Submit Complaint</button>
    </form>
</div>
</body>
</html>
