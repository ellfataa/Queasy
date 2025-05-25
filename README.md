# Queasy 🧠

![Q!](https://github.com/user-attachments/assets/096072ee-faa8-4593-8921-ca5bb129079f)

Queasy adalah platform kuis online yang dirancang untuk memberikan pengalaman belajar yang terstruktur dan progresif. Berbeda dengan platform kuis lainnya, Queasy menerapkan sistem pembelajaran bertahap di mana pengguna harus menyelesaikan minimal 50% dari kuis kategori sebelumnya untuk dapat mengakses kategori selanjutnya.
> Kemudian yang menjadi ciri khas Queasy ini dengan website kuis lainnya yaitu adanya **_alur cerita yang ditambah nyawa untuk setiap kuis_** sehingga apabila nyawa tersebut habis, maka harus mengulang kuis tersebut.

## ✨ Fitur Utama

- **Sistem Pembelajaran Progresif**: Pengguna harus menyelesaikan 50% kuis dari kategori sebelumnya untuk membuka kategori baru
- **Kategori Kuis Beragam**: Berbagai topik pembelajaran yang tersedia
- **Tracking Progress**: Pantau kemajuan belajar secara real-time

## 🚀 Teknologi yang Digunakan

- **Backend**: PHP Native
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5
- **Database**: MySQL
- **Server**: Apache

<img src="https://github.com/user-attachments/assets/06741535-37f9-4cc4-b2fe-ad89001e344c" width="130" height="145">  <img src="https://github.com/user-attachments/assets/426ef86b-ce1d-4707-98be-ca61f068608d" width="150" height="145">  <img src="https://github.com/user-attachments/assets/a4c8687c-eeb6-4670-82ff-0e23177434f9" width="150" height="145">

## 📋 Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Apache Web Server
- Browser modern (Chrome, Firefox, Safari, Edge)

## 🛠️ Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/queasy.git
cd queasy
```

### 2. Setup Database

1. Buat database baru di MySQL:
```sql
CREATE DATABASE queasy;
```

2. Import file database:
```bash
mysql -u username -p queasy_db < database/queasy.sql
```

### 3. Konfigurasi

1. Salin file konfigurasi:
```bash
cp layout/config.example.php layout/config.php
```

2. Edit file `layout/config.php` sesuai dengan pengaturan database Anda:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'queasy');
?>
```


## 📝 License

Project ini dilisensikan under MIT License - lihat file [LICENSE](LICENSE) untuk detail.

---

⭐ **Jangan lupa untuk memberikan star jika project ini membantu Anda!**
