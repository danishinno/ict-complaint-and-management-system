# ICMCS - Setup and Run Guide (macOS)

Your browser cannot run PHP files directly if you double-click them or open them as file paths (e.g., `file:///Users/.../Login.html`). Browsers only understand HTML, CSS, and Javascript. To run PHP code, you need a local web server (like **MAMP** or **XAMPP**) to process the PHP backend.

Here is how to get the application running on your Mac in 5 simple steps.

---

### Step 1: Install MAMP or XAMPP
If you don't have it installed yet, download and install one of the local server environments:
* [Download MAMP for Mac](https://www.mamp.info/) (Recommended for macOS)
* [Download XAMPP for Mac](https://www.apachefriends.org/)

---

### Step 2: Move the Project to the Server Directory
Your server can only process files located inside its official server folder (document root).
1. Open Finder.
2. Move/copy the entire `icmcs` project folder to:
   * **For MAMP:** `/Applications/MAMP/htdocs/`
   * **For XAMPP:** `/Applications/XAMPP/htdocs/`

---

### Step 3: Start the Servers
1. Open the **MAMP** or **XAMPP Control Panel** from your Applications folder.
2. Click **Start Servers** (make sure both **Apache Web Server** and **MySQL Database Server** have green status lights).

---

### Step 4: Open the App in Your Web Browser
Do **NOT** double-click the files. Instead, open your web browser (Safari, Chrome, Firefox) and type the following address:
* **If using MAMP:** `http://localhost:8888/icmcs/Login.html` (or sometimes `http://localhost/icmcs/Login.html`)
* **If using XAMPP:** `http://localhost/icmcs/Login.html`

---

### Step 5: Test the App (Auto-Setup)
1. The first time you load any page, the database connection script will automatically create the database `complaint_management` and the required tables for you.
2. Log in using the pre-seeded default Administrator account:
   * **Admin ID:** `admin`
   * **Password:** `admin123`
3. Or select a role (e.g. **Student**), complete the registration form, and you will be routed back to the login page to sign in!
