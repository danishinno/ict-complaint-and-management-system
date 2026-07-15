# 🛠️ Integrated Complaint Management and Centralization System (ICMCS)

Welcome to the **ICMCS** portal. This is a web application designed for students, lecturers, technicians, and administrators to submit, track, and manage ICT-related complaints.

---

## 🚀 How to Run the Project

You can run this project in two ways: in the cloud using **GitHub Codespaces** (no setup required) or locally on your computer using **MAMP/XAMPP**.

### Method A: Run in the Cloud (GitHub Codespaces) — *Recommended*
Since the repository is pre-configured with a development container, you can run the entire PHP and MySQL stack in one click:
1. Go to your GitHub repository: [danishinno/ict-complaint-and-management-system](https://github.com/danishinno/ict-complaint-and-management-system).
2. Click the green **Code** button, select the **Codespaces** tab, and click **Create codespace on main**.
3. Wait for the environment to build.
4. When a popup appears in the bottom-right corner saying **"Active Ports: Port 8080 is forwarded"**, click **Open in Browser** (or visit the link under the **Ports** tab).
5. Append `/Login.html` to the URL to access the login page.
6. The database and tables will initialize automatically on your first load!

---

### Method B: Run Locally (Mac/Windows)
To run PHP and MySQL locally, you must use a local server environment like **MAMP** (recommended for macOS) or **XAMPP**.

#### Step 1: Install MAMP or XAMPP
*   [Download MAMP for Mac](https://www.mamp.info/)
*   [Download XAMPP](https://www.apachefriends.org/)

#### Step 2: Place files in Server Directory
Copy the entire `icmcs` project folder to your local server's document root:
*   **MAMP:** `/Applications/MAMP/htdocs/`
*   **XAMPP (macOS):** `/Applications/XAMPP/htdocs/` or `/Applications/XAMPP/xamppfiles/htdocs/`
*   **XAMPP (Windows):** `C:\xampp\htdocs\`

#### Step 3: Start the Servers
Open your MAMP/XAMPP Control Panel and click **Start Servers**. Make sure both the **Apache Web Server** and **MySQL Database Server** status lights turn green.

#### Step 4: Open in Web Browser
Do **NOT** double-click the HTML/PHP files directly. Instead, open your browser and navigate to:
*   **MAMP default:** `http://localhost:8888/icmcs/Login.html`
*   **XAMPP default:** `http://localhost/icmcs/Login.html`

---

## 🔑 Login Credentials

The system initializes with a default administrator account. You can also sign up for student, lecturer, or technician accounts directly from the login page.

| Role | Username / ID | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` |
| **Others (Student / Lecturer / Tech)** | *Create via Signup button* | *Your choice* |

---

## 📋 Features & User Roles

*   **Students & Lecturers:** 
    *   Register accounts with role-specific details.
    *   Submit complaints with categories, locations, descriptions, and optional image attachments.
    *   Track personal complaint statuses (Unsolved, In Progress, Solved) on their custom dashboard.
*   **Technicians:**
    *   View all submitted complaints.
    *   Update complaint statuses (e.g. mark as "In Progress" or "Solved") in real-time.
*   **Administrators:**
    *   Monitor dashboard statistics (solved/unsolved counts).
    *   Monitor user traffic (registered users categorized by role).
    *   Update complaint statuses.
    *   Generate print-friendly report summaries.

---

## 🗄️ Database Architecture
The application runs on a MySQL database named `complaint_management` consisting of two main tables:
1.  **`users`**: Stores login credentials, roles, and role-specific profile data (in JSON format).
2.  **`complaints`**: Tracks submitted tickets, categories, locations, image paths, and current status.

*Note: The database configuration (`db_connect.php`) checks multiple standard port configurations (e.g., MAMP, XAMPP, and Codespaces) to connect automatically without manual editing.*
