# 🔔 Reminder System

A simple but powerful reminder system built with Laravel. Create reminders, get notified via email, and manage everything through a clean admin panel.

---

## 💡 What does it do?

Ever forget something important? This app lets you:

- Create reminders with a title, note, and a date/time
- Get an **email notification** automatically when the time comes
- Even if the server was down, **you'll still get your reminder** — it won't be skipped
- Admins can manage all reminders from a beautiful dashboard

---

## 🛠️ Built With

- **Laravel** — PHP framework
- **Filament** — Admin panel
- **Laravel Queues** — Background email jobs
- **Laravel Sanctum** — API authentication
- **MySQL** — Database
- **Pest** — Testing

---

## ⚙️ Installation

### 1. Clone the project

```bash
git clone https://github.com/Yas-shrestha/ReminderApplication.git
cd reminder-system
```

### 2. Install dependencies

```bash
composer install
```

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure your `.env` file

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reminder_system
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=your_email
MAIL_FROM_NAME="Reminder System"
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Create admin user

```bash
php artisan make:filament-user
```

or you can go to url /admin/register for manual registration

### 7. Start the server

```bash
php artisan serve
```

---

## 🔌 API Endpoints

All endpoints require a **Sanctum token** in the header:

```
Authorization: Bearer your_token_here
```

| Method | Endpoint              | Description            |
| ------ | --------------------- | ---------------------- |
| GET    | `/api/reminders`      | Get all your reminders |
| POST   | `/api/reminders`      | Create a new reminder  |
| GET    | `/api/reminders/{id}` | Get a single reminder  |
| PUT    | `/api/reminders/{id}` | Update a reminder      |
| DELETE | `/api/reminders/{id}` | Delete a reminder      |

addition Api route for Login and Register is created and all of these routes and operation are tested via PostMan

### Example Request (Create Reminder)

```json
POST /api/reminders

{
    "title": "Call mom",
    "note": "Don't forget to wish her happy birthday!",
    "remind_at": "2026-05-08 10:00:00"
}
```

---

## 🖥️ Admin Panel

Visit `/admin` to access the admin dashboard.

- Login with your Filament admin credentials
- View all reminders from all users
- Create, edit, and delete reminders
- Filter by sent/pending status

---

## 📧 How the Email Job Works

This is the heart of the system:

1. A **scheduled job** runs every minute in the background
2. It looks for reminders where the time has passed **and** the email hasn't been sent yet
3. It sends the email and marks the reminder as **sent**
4. If the server was down and a reminder was missed — **don't worry!** When the server comes back up, it will still send the email because `isSent` is still `false`

### To run the scheduler locally:

```bash
php artisan schedule:work
```

### To run the queue worker:

```bash
php artisan queue:work
```

---

## 🧪 Running Tests

```bash
php artisan test
```

---

## 👨‍💻 Author

Built by **Yas** as part of a technical evaluation for a Junior Laravel Developer position.

> _"AI was used as a tool to assist development — all logic and decisions were understood and implemented by the developer."_
