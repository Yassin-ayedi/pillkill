# 🏥 Medical Web Application

This is a web application for managing **medications** and **appointments**, with user authentication and multiple services.  
Built with **HTML, CSS, JavaScript, PHP, and Python (OCR)**.

---

## 🚀 Application Flow

1. **Landing Page → `index.html`**
   - Entry point of the application.

2. **Authentication:**
   - **Log In** via `login.html` (styled by `enter.css`) → handled by `login.php`.
   - **Sign Up** via `signup.html` (styled by `enter.css`) → handled by `signup.php`.
   - Both interact with the database via `db.php`.

3. **Home Interface → `home.php`**
   - Main dashboard after login.
   - Styled by `home.css`.
   - Navigation to 3 services.

---

## 🏠 Services Overview

### 1️⃣ Service 1: Medication Management

- Interface: `service1.php`
- Styles: `service1.css`
- Scripts: `service1.js`

**Features:**
- Add medications → handled by `add_medication.php`.
- Upload prescription image → processed by `ocr.php` (executes `handwriting_ocr.py` for OCR).
- Delete medications → via `delete_medication.php` (called from `service1.js`).
- Increment dose → via `increment_dose.php` (called from `service1.js`).

---

### 2️⃣ Service 2: Appointment Management

- Interface: `service2.php`
- Styles: `service2.css`
- Scripts: `service2.js`

**Features:**
- Add appointments → handled by `add_appointment.php`.
- Delete appointments → via `delete_appointment.php` (called from `service2.js`).

---

### 3️⃣ Service 3: Static Information Page

- Interface: `service3.php`
- Styles: `service3.css`
- No interactive scripts.

---

## 📁 Project Structure

```plaintext
uploads/
├── (uploaded files)

add_appointment.php
add_medication.php
circle.html
db.php
delete_appointment.php
delete_medication.php
enter.css
handwriting_ocr.py
home.css
home.php
image.png
increment_dose.php
index.html
login.html
login.js
login.php
logout.php
ocr.php
service1.css
service1.js
service1.php
service2.css
service2.js
service2.php
service3.css
service3.php
signup.html
signup.php
site_icon.png
