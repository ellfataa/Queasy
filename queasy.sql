-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2025 at 07:10 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `queasy`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `category_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'other.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `img`) VALUES
(1, 'Matematika', 'math.jpg'),
(2, 'Sains', 'science.jpg'),
(3, 'Bahasa', 'language.jpg'),
(4, 'Economic', 'economic.jpg'),
(5, 'Social', 'sociall.jpg'),
(6, 'Other', 'other.jpg'),
(7, 'Olahraga', 'other.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int NOT NULL,
  `question_id` int NOT NULL,
  `option_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_answer` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `question_id`, `option_text`, `is_answer`) VALUES
(21001, 2101, '10', 1),
(21002, 2101, '11', 0),
(21003, 2101, '9', 0),
(21004, 2101, '12', 0),
(21005, 2102, '15', 1),
(21006, 2102, '20', 0),
(21007, 2102, '8', 0),
(21008, 2102, '10', 0),
(21009, 2103, '90kg', 1),
(21010, 2103, '95kg', 0),
(21011, 2103, '85kg', 0),
(21012, 2103, '100kg', 0),
(21013, 2104, '8', 1),
(21014, 2104, '16', 0),
(21015, 2104, '12', 0),
(21016, 2104, '18', 0),
(21017, 2105, '357', 0),
(21018, 2105, '537', 0),
(21019, 2105, '753', 1),
(21020, 2105, '735', 0),
(22001, 2201, '5 kali', 0),
(22002, 2201, '6 kali', 1),
(22003, 2201, '7 kali', 0),
(22004, 2201, '8 kali', 0),
(22005, 2202, '45%', 0),
(22006, 2202, '52%', 1),
(22007, 2202, '60%', 0),
(22008, 2202, '40%', 0),
(22009, 2203, '4.5 menit', 0),
(22010, 2203, '2 menit', 1),
(22011, 2203, '3 menit', 0),
(22012, 2203, '5 menit', 0),
(22013, 2204, '12.5 m/s', 0),
(22014, 2204, '16.7 m/s', 1),
(22015, 2204, '20 m/s', 0),
(22016, 2204, '15 m/s', 0),
(22017, 2205, '7 orang', 0),
(22018, 2205, '8 orang', 1),
(22019, 2205, '9 orang', 0),
(22020, 2205, '10 orang', 0),
(23001, 2301, '8 gram', 0),
(23002, 2301, '10 gram', 1),
(23003, 2301, '12 gram', 0),
(23004, 2301, '15 gram', 0),
(23005, 2302, '2 mantra', 0),
(23006, 2302, '3 mantra', 1),
(23007, 2302, '4 mantra', 0),
(23008, 2302, '5 mantra', 0),
(23009, 2303, '400 buku', 0),
(23010, 2303, '420 buku', 1),
(23011, 2303, '450 buku', 0),
(23012, 2303, '480 buku', 0),
(23013, 2304, '2/7', 0),
(23014, 2304, '3/7', 0),
(23015, 2304, '4/7', 1),
(23016, 2304, '5/7', 0),
(23017, 2305, '180°', 0),
(23018, 2305, '360°', 1),
(23019, 2305, '450°', 0),
(23020, 2305, '540°', 0),
(24001, 2401, '3 kali', 0),
(24002, 2401, '4 kali', 1),
(24003, 2401, '5 kali', 0),
(24004, 2401, '6 kali', 0),
(24005, 2402, '42 meter', 1),
(24006, 2402, '60 meter', 0),
(24007, 2402, '100 meter', 0),
(24008, 2402, '102 meter', 0),
(24009, 2403, '75 ml', 0),
(24010, 2403, '100 ml', 1),
(24011, 2403, '125 ml', 0),
(24012, 2403, '150 ml', 0),
(24013, 2404, '6 penyihir', 0),
(24014, 2404, '8 penyihir', 0),
(24015, 2404, '9 penyihir', 1),
(24016, 2404, '12 penyihir', 0),
(24017, 2405, '25,92 cm', 0),
(24018, 2405, '31,1 cm', 1),
(24019, 2405, '35 cm', 0),
(24020, 2405, '39 cm', 0),
(24021, 2406, '0,55', 0),
(24022, 2406, '0,65', 0),
(24023, 2406, '0,75', 1),
(24024, 2406, '0,85', 0),
(24025, 2407, '50 cm', 1),
(24026, 2407, '75 cm', 0),
(24027, 2407, '300 cm', 0),
(24028, 2407, '450 cm', 0),
(24029, 2408, '250.000 arcana', 0),
(24030, 2408, '2.500.000 arcana', 1),
(24031, 2408, '25.000.000 arcana', 0),
(24032, 2408, '250.000.000 arcana', 0),
(24033, 2409, '1:1', 0),
(24034, 2409, '2:1', 0),
(24035, 2409, '3:2', 1),
(24036, 2409, '4:1', 0),
(24037, 2410, '37 rune', 1),
(24038, 2410, '42 rune', 0),
(24039, 2410, '48 rune', 0),
(24040, 2410, '54 rune', 0),
(25001, 2501, '5 cm', 0),
(25002, 2501, '7.5 cm', 0),
(25003, 2501, '8 cm', 0),
(25004, 2501, '12.5 cm', 1),
(25005, 2502, '5 cm', 0),
(25006, 2502, '10 cm', 1),
(25007, 2502, '15 cm', 0),
(25008, 2502, '20 cm', 0),
(25009, 2503, '1 jam 30 menit', 0),
(25010, 2503, '2 jam 15 menit', 0),
(25011, 2503, '2 jam 37.5 menit', 1),
(25012, 2503, '3 jam 15 menit', 0),
(25013, 2504, '60 mantra', 0),
(25014, 2504, '70 mantra', 0),
(25015, 2504, '80 mantra', 1),
(25016, 2504, '90 mantra', 0),
(25017, 2505, '20 buku', 0),
(25018, 2505, '24 buku', 1),
(25019, 2505, '25 buku', 0),
(25020, 2505, '28 buku', 0);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `quest_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quiz_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quest_text`, `quiz_id`) VALUES
(2101, 'Raka menemukan pintu terkunci dengan kode 2×3+4. Berapa kode yang harus dimasukkan?', 201),
(2102, 'Raka bertemu 5 naga yang masing-masing memiliki 3 anak. Berapa total naga?', 201),
(2103, 'Jembatan rusak hanya bisa menahan 150kg. Berat Raka 45kg, tasnya 15kg. Berapa beban maksimal yang bisa dibawa?', 201),
(2104, 'Raka punya 24 koin emas. Jika 1/3 diberikan pada peri penolong, berapa yang tersisa?', 201),
(2105, 'Untuk membuka gerbang, Raka perlu menyusun angka 3,5,7 menjadi bilangan terbesar. Angka berapa itu?', 201),
(2201, 'Kamu punya 32 panah. Jika setiap serangan menggunakan 5 panah, berapa kali bisa menyerang sebelum kehabisan?', 202),
(2202, 'Racun naga kehilangan potensi 15% setiap jam. Berapa persen potensi setelah 4 jam?', 202),
(2203, 'Jika 3 pemanah butuh 2 menit untuk mempersiapkan serangan, berapa waktu yang dibutuhkan 7 pemanah?', 202),
(2204, 'Naga bisa menyembur api sejauh 50m dalam 3 detik. Berapa kecepatan api (m/s)?', 202),
(2205, 'Untuk membuat baju anti-api, dibutuhkan 2kg bahan per orang. Jika tersedia 17kg, berapa orang yang bisa dilindungi?', 202),
(2301, 'Untuk ramuan tidur, perbandingan moonstone : stardust adalah 3:2. Jika ada 15 gram moonstone, berapa gram stardust dibutuhkan?', 203),
(2302, 'Membuat lingkaran perlindungan membutuhkan 8 titik energi. Jika setiap mantra memberi 3 titik, berapa mantra minimal yang diperlukan?', 203),
(2303, 'Di perpustakaan, 35% dari 1200 buku adalah buku sihir gelap. Berapa jumlah buku sihir gelap?', 203),
(2304, 'Dalam duel sihir, peluang menang dengan mantra X adalah 3/7. Berapa peluang kalah?', 203),
(2305, 'Jam ajaib berputar 30° setiap 5 menit. Berapa derajat putaran dalam 1 jam?', 203),
(2401, 'Kalender lunar penyihir memiliki siklus 28 hari. Jika ritual harus dilakukan setiap 12 hari, berapa kali ritual dilakukan dalam 2 siklus bulan?', 204),
(2402, 'Dari menara observasi setinggi 60 meter, kamu mengamati bintang dengan sudut elevasi 40°. Jika jarak horizontal ke bintang adalah 50 meter, berapa tinggi bintang dari tanah?', 204),
(2403, 'Ramuan pernafasan air bekerja selama 45 menit per dosis 25 ml. Jika kamu membutuhkan ramuan untuk 3 jam di bawah air, berapa ml ramuan yang harus kamu bawa?', 204),
(2404, 'Portal dimensi berbentuk lingkaran dengan diameter 3 meter. Jika setiap penyihir membutuhkan ruang lingkaran dengan jari-jari 0,5 meter, berapa maksimum penyihir yang dapat melakukan ritual bersama di portal tersebut?', 204),
(2405, 'Tanaman ajaib tumbuh 20% setiap hari. Jika tinggi awal 15 cm, berapakah tingginya setelah 4 hari?', 204),
(2406, 'Dalam peramalan cuaca, probabilitas hujan adalah 0,65 dan probabilitas angin kencang adalah 0,4. Jika probabilitas hujan DAN angin kencang adalah 0,3, berapakah probabilitas hujan ATAU angin kencang?', 204),
(2407, 'Cermin dimensi memantulkan bayangan dengan skala 1:3. Jika tinggi aslimu 150 cm, berapakah tinggi bayanganmu di cermin?', 204),
(2408, 'Kekuatan mantra diukur dalam \"arcana\". 1 megarcana = 1000 kiloarcana, 1 kiloarcana = 1000 arcana. Berapa arcana dalam 2,5 megarcana?', 204),
(2409, 'Ramuan pemulih membutuhkan campuran dengan pH 7,5. Kamu memiliki cairan A dengan pH 6 dan cairan B dengan pH 9. Dengan perbandingan berapa cairan A dan B harus dicampur?', 204),
(2410, 'Rune harus disusun dalam formasi heksagonal. Jika kamu memiliki n lapisan, berapa total rune yang dibutuhkan untuk formasi lengkap dengan n=4?', 204),
(2501, 'Ramuan pertumbuhan meningkatkan ukuran objek sebesar 150% setiap jam. Jika sebuah jamur ajaib awalnya berukuran 2 cm, berapa ukurannya setelah 2 jam?', 205),
(2502, 'Sebuah bola kristal berbentuk lingkaran dengan keliling 62.8 cm. Berapa jari-jari bola kristal tersebut? (Gunakan π = 3.14)', 205),
(2503, 'Jika mantra transformasi berlangsung selama 45 menit per 10 ml ramuan, berapa lama ramuan 35 ml akan bertahan?', 205),
(2504, 'Jika sebuah tongkat sihir menyimpan 1200 unit energi mana dan menggunakan 15 unit per mantra dasar, berapa banyak mantra yang bisa dilakukan sebelum energi habis?', 205),
(2505, 'Sebuah rak buku bisa menampung 30 buku mantra dengan ketebalan masing-masing 4 cm. Jika ketebalan buku diubah menjadi 5 cm, berapa banyak buku yang bisa ditampung?', 205);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int NOT NULL,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int NOT NULL,
  `creator_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `title`, `description`, `category_id`, `creator_id`) VALUES
(201, 'Petualangan Matematika di Negeri Ajaib', 'Setiap jawaban memengaruhi kelanjutan cerita Raka', 1, 22),
(202, 'Matematika Melawan Sang Naga', 'Kalahkan naga dengan kecerdikan matematika', 1, 22),
(203, 'Matematika Sihir di Sekolah Penyihir', 'Kuasi mantra dengan menguasai matematika sihir', 1, 22),
(204, 'Perhitungan Astronomi untuk Penyihir Muda', 'Menguasai rahasia langit dengan perhitungan astronomis', 1, 22),
(205, 'Eksperimen Sains untuk Penjelajah Waktu', 'Di balik pintu laboratorium tersembunyi di Universitas Chronoscience, kamu menemukan catatan peninggalan Profesor Tempora—ilmuwan jenius yang menghilang 73 tahun lalu. Kini, kamu ditantang untuk melanjutkan eksperimennya. Siapkan mikroskop, kalkulator, dan keberanian—karena masa depan ilmu pengetahuan ada di tanganmu.', 2, 22),
(206, 'Misi Penyelamatan di Luar Angkasa Zeta-9', 'Sebuah stasiun luar angkasa mengalami kerusakan parah akibat reaksi kimia tak stabil di laboratorium orbit. Sebagai ilmuwan muda terbaik dari Bumi, kamu dikirim untuk memecahkan masalah ini. Namun, hanya dengan menyelesaikan serangkaian soal sains, kamu bisa menghentikan kehancuran total.', 2, 22),
(207, 'Lab Rahasia di Bawah Gunung Vulcanis', 'Gunung Vulcanis yang telah lama dianggap tidak aktif ternyata menyimpan sebuah lab kuno berisi alat-alat eksperimen ilmiah yang luar biasa. Di dalamnya, kamu menemukan tantangan demi tantangan yang harus dipecahkan dengan logika sains untuk membuka kunci rahasia energi panas bumi.', 2, 22),
(208, 'Virus Alpha: Balapan Melawan Waktu', 'Wabah misterius menyebar cepat di sebuah kota futuristik. Sebuah tim peneliti muda—termasuk kamu—berlomba menemukan penawarnya. Untuk memproduksi vaksin, kamu harus menjawab soal-soal biologi dan kimia secara tepat. Kesalahan sedikit saja bisa fatal.', 2, 22),
(209, 'Matematika Sihir untuk Pemula', 'Pelajari dasar-dasar perhitungan sihir yang diperlukan setiap penyihir pemula', 1, 22),
(301, 'Petualangan Kata-Kata Ajaib', 'Ikuti petualangan sihir dimana setiap kata memiliki kekuatan magis. Salah pilih kata bisa berakibat fatal!', 3, 22),
(302, 'Misteri Puisi Terkunci', 'Bantu detektif muda memecahkan misteri dengan menganalisis petunjuk tersembunyi dalam puisi-puisi kuno', 3, 22),
(303, 'Perjalanan Bahasa Naga', 'Sebagai calon penjinak naga, kamu harus mempelajari bahasa kuno untuk berkomunikasi dengan makhluk legendaris ini', 3, 22),
(304, 'Kutukan Grammar', 'Kamu terjebak dalam dunia dimana kesalahan grammar mengakibatkan kutukan mengerikan. Selamatkan dirimu!', 3, 22),
(305, 'Kerajaan Bangkrut', 'Sebagai menteri keuangan kerajaan yang hampir bangkrut, buat keputusan ekonomi tepat untuk menyelamatkan rakyat', 4, 22),
(306, 'Penjelajah Pasar Modal', 'Kamu mewarisi toko sihir kuno dan harus menginvestasikan uang dengan bijak untuk mengembangkannya', 4, 22),
(307, 'Krisis Desa Ajaib', 'Selamatkan desa sihir dari krisis ekonomi dengan menerapkan prinsip ekonomi yang benar', 4, 22),
(308, 'Entrepreneur Muda', 'Mulai dari nol, bangun bisnis ramuan sihirmu menjadi perusahaan terbesar di dunia magis', 4, 22),
(413, 'Turnamen Quidditch', 'Sebagai kapten tim Quidditch, atur strategi dan latihan untuk memenangkan piala dunia', 7, 22),
(414, 'Olimpiade Kuno', 'Ikuti perjalanan atlet Yunani kuno menuju Olimpiade pertama dengan segala rintangannya', 7, 22),
(415, 'Sekolah Ninja', 'Latih fisik dan mental untuk menjadi ninja terhebat melalui serangkaian ujian olahraga ekstrim', 7, 22),
(416, 'Penyelamatan dengan Parkour', 'Kejar penjahat melalui lorong-lorong kota dengan teknik parkour sambil memecahkan teka-teki', 7, 16),
(417, 'Laboratorium Gila', 'Sebagai asisten profesor gila, lakukan eksperimen sains gila dengan konsekuensi tak terduga', 6, 16),
(418, 'Hacker vs AI', 'Selamatkan sistem komputer dunia dari AI jahat yang ingin mengambil alih kendali', 6, 16),
(419, 'Misi Galeri Seni', 'Curi lukisan berharga dengan memecahkan teka-teki seni dan menghindari sistem keamanan canggih', 6, 16),
(420, 'Epidemi Mematikan', 'Sebagai dokter, selidiki sumber wabah aneh dan temukan penawarnya sebelum terlambat', 6, 16),
(421, 'Dilema Desa Tololongs', 'Sebagai pekerja sosial baru, bantu selesaikan konflik warga Desa Tololongs yang terbelah oleh isu SARA', 5, 16),
(422, 'Anak Jalanan Kota Megah', 'Bantu sekelompok anak jalanan membangun kehidupan baru melalui program pemberdayaan masyarakat', 5, 16),
(423, 'Skandal Panti Wredha', 'Ungkap kebenaran di balik pengelolaan dana Panti Wredha \"Kasih Bunda\" yang mencurigakan', 5, 16),
(424, 'Pemilu Kampung Dukuh', 'Mediasi konflik politik kampung yang hampir pecah menjadi kerusuhan antarwarga', 5, 16);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `score` decimal(5,2) DEFAULT '0.00',
  `completed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `story_segments`
--

CREATE TABLE `story_segments` (
  `id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `question_id` int NOT NULL,
  `story_text` text NOT NULL,
  `show_on_correct` tinyint(1) DEFAULT '1',
  `show_on_wrong` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `story_segments`
--

INSERT INTO `story_segments` (`id`, `quiz_id`, `question_id`, `story_text`, `show_on_correct`, `show_on_wrong`) VALUES
(2101, 201, 2101, '\"Benar!\" seru peri kecil. Pintu terbuka dengan gemuruh. Raka melihat pemandangan indah negeri ajaib di hadapannya. Matahari bersinar cerah dan burung-burung berkicau menyambutnya.....', 1, 0),
(2102, 201, 2101, '\"Salah!\" pintu mengeluarkan suara menggeram. Alarm berbunyi nyaring. Penjaga gerbang datang dan Raka harus menyembunyikan diri selama 1 jam sebelum bisa mencoba lagi.', 0, 1),
(2103, 201, 2102, '\"Tepat sekali!\" kata naga besar. Mereka terkesan dan memberikan Raka peta rahasia. \"Ini akan membawamu ke istana ratu,\" bisik naga terkecil.', 1, 0),
(2104, 201, 2102, '\"Kau tidak menghormati keluarga kami!\" geram naga besar. Mereka menyemburkan api kecil dan membakar tepi jubah Raka. Raka harus mundur dan mencari jalan memutar.', 0, 1),
(2105, 201, 2103, 'Raka menghitung dengan cermat dan memutuskan membawa beban 90kg. Jembatan berderak-derak tetapi tetap aman. Di seberang, Raka menemukan peti harta karun!', 1, 0),
(2106, 201, 2103, 'Raka salah menghitung dan membawa beban terlalu berat. Jembatan runtuh! Untungnya Raka berhasil meraih akar pohon dan selamat, tetapi harus mencari jalan lain yang lebih jauh.', 0, 1),
(2107, 201, 2104, 'Peri tersenyum senang. \"Kau jujur dan baik,\" katanya. Sebagai balasan, peri memberikan ramuan ajaib yang bisa menyembuhkan luka. \"Ini akan membantumu di perjalanan.\"', 1, 0),
(2108, 201, 2104, '\"Kau mencoba menipuku!\" marah peri itu. Ia menyihir separuh koin Raka menghilang. Sekarang Raka hanya punya 12 koin tersisa.', 0, 1),
(2109, 201, 2105, 'Gerbang terbuka dengan gemuruh! Di dalamnya ada ruangan penuh dengan buku-buku ilmu pengetahuan. Raka menemukan catatan penting tentang cara menyelamatkan negeri.', 1, 0),
(2110, 201, 2105, 'Gerbang mengeluarkan asap tebal. Raka batuk-batuk dan mata nya perih. Ketika asap menghilang, Raka menyadari ada tiga pintu baru yang muncul dan harus memilih salah satu.', 0, 1),
(2201, 202, 2201, '\"Perhitunganmu tepat!\" kata pandai besi. Kamu mendapat 6 serangan efektif. Dengan persiapan matang, kamu berhasil melukai sayap naga di serangan ke-5.', 1, 0),
(2202, 202, 2201, '\"Kurang tepat!\" panahmu habis terlalu cepat. Naga yang hanya terluka ringan menjadi lebih ganas dan menghancurkan menara pertahanan.', 0, 1),
(2203, 202, 2202, 'Kamu menunggu dengan sabar. Setelah 4 jam, racun cukup lemah untuk dinetralisir. Dengan risiko minimal, kamu berhasil mengambil sampel untuk membuat penawar.', 1, 0),
(2204, 202, 2202, 'Terlalu cepat! Racun masih terlalu kuat. Upayamu gagal dan beberapa prajurit keracunan. Naga semakin mendekat ke desa.', 0, 1),
(2205, 202, 2203, 'Kamu mengatur pemanah untuk bekerja paralel. Serangan serentak berhasil membuat naga kewalahan! Sisiknya mulai rontok di beberapa titik.', 1, 0),
(2206, 202, 2203, 'Salah mengatur formasi! Pemanah saling menghalangi. Naga memanfaatkan kekacauan ini untuk membakar lumbung desa.', 0, 1),
(2207, 202, 2204, 'Dengan perhitungan tepat, kamu memposisikan pasukan di lereng bukit 55m. Semburan api naga jatuh tepat di depan formasi pasukan!', 1, 0),
(2208, 202, 2204, 'Perkiraan jarakmu meleset! Semburan api mencapai pasukan dan menyebabkan kepanikan. Beberapa perlengkapan terbakar.', 0, 1),
(2209, 202, 2205, 'Bahan cukup untuk 8 baju. Kamu memilih pasukan terbaik dan berhasil menembus pertahanan naga. Satu baju cadangan bahkan bisa digunakan untuk menyelamatkan warga.', 1, 0),
(2210, 202, 2205, 'Kekurangan bahan! Beberapa pasukan terpaksa mundur. Naga melihat celah ini dan memperkuat pertahanan di sisi yang lemah.', 0, 1),
(2301, 203, 2301, 'Ramuanmu berhasil! Cairan berkilau dengan warna ungu sempurna. Guru Alkimia memberimu 5 poin untuk rumahmu.', 1, 0),
(2302, 203, 2301, 'Ramuan meledak! Asap hijau memenuhi ruangan. Gurumu menghela nafas dan menyuruhmu membersihkan semua peralatan.', 0, 1),
(2303, 203, 2302, 'Lingkaran perlindunganmu bersinar terang! Seorang penyihir senior terkesan dan mengajarkanmu mantra rahasia.', 1, 0),
(2304, 203, 2302, 'Lingkaran tidak sempurna! Makhluk gelap bisa menembusnya. Kamu harus berlindung di balik temanmu yang lebih berpengalaman.', 0, 1),
(2305, 203, 2303, 'Kamu menemukan bagian rahasia perpustakaan! Sebuah buku kuno terbuka dan mengajarkanmu mantra langka.', 1, 0),
(2306, 203, 2303, 'Kamu tersesat di bagian terlarang! Penjaga perpustakaan marah dan melarangmu meminjam buku selama seminggu.', 0, 1),
(2307, 203, 2304, 'Kamu memilih strategi tepat! Lawan terpeleset di es yang kamu ciptakan dan kemenangan menjadi milikmu.', 1, 0),
(2308, 203, 2304, 'Salah memilih strategi! Mantramu tidak efektif dan lawan berhasil melumpuhkanmu dengan mudah.', 0, 1),
(2309, 203, 2305, 'Kamu berhasil menyinkronkan jam ajaib! Portal waktu terbuka dan kamu melihat masa depan sekolahmu.', 1, 0),
(2310, 203, 2305, 'Jam ajaib rusak! Waktu sekitar menjadi kacau dan kamu terjebak dalam loop waktu selama beberapa jam.', 0, 1),
(2401, 204, 2401, 'Sempurna! Ritualmu berhasil menyerap energi bulan. Kristal di tengah ruangan bercahaya terang dan Profesor Lunaris memberikan 10 poin untuk asramamu.', 1, 0),
(2402, 204, 2401, 'Oh tidak! Kamu melewatkan waktu ritual yang tepat. Energi bulan memudar dan kristal ritual retak. Kamu harus menunggu siklus berikutnya untuk mencoba lagi.', 0, 1),
(2403, 204, 2402, 'Hebat! Pengamatanmu sangat akurat. Bintang jatuh yang kamu amati berubah menjadi kristal langka yang hanya muncul sekali dalam satu abad.', 1, 0),
(2404, 204, 2402, 'Perkiraanmu meleset! Bintang jatuh terlewatkan dan kamu hanya melihat kilauannya dari kejauhan. Kesempatan langka telah hilang.', 0, 1),
(2405, 204, 2403, 'Ramuanmu bertahan sempurna! Kamu berhasil mengeksplorasi kota bawah air kuno dan menemukan artefak berharga yang akan dipamerkan di museum sekolah.', 1, 0),
(2406, 204, 2403, 'Ramuan habis terlalu cepat! Kamu harus berenang terburu-buru ke permukaan, hampir kehabisan nafas, dan melewatkan penemuan terbesar di dasar danau.', 0, 1),
(2407, 204, 2404, 'Formasi ritualmu sempurna! Portal bercahaya dengan warna pelangi dan semua penyihir berhasil mengirimkan pesan ke dimensi lain.', 1, 0),
(2408, 204, 2404, 'Portal menjadi tidak stabil! Terlalu banyak atau terlalu sedikit penyihir membuat energi tidak seimbang, dan ritual harus dihentikan segera.', 0, 1),
(2409, 204, 2405, 'Tanaman tumbuh sesuai perhitunganmu! Bunga ajaib mekar tepat waktu dan menghasilkan nektar langka yang dapat digunakan untuk ramuan penyembuh terkuat.', 1, 0),
(2410, 204, 2405, 'Perhitunganmu tidak akurat! Tanaman tumbuh terlalu cepat dan layu sebelum bisa dipanen. Profesor Herbologi mengingatkan pentingnya perhitungan yang tepat.', 0, 1),
(2411, 204, 2406, 'Peramalanmu akurat! Kamu berhasil menyiapkan payung dan mantra anti-angin tepat waktu, menjadi satu-satunya siswa yang tiba kering di kelas ramuan.', 1, 0),
(2412, 204, 2406, 'Peramalanmu meleset! Badai tiba tanpa peringatan dan perkamenmu basah kuyup, membuat tugas mingguanmu harus ditulis ulang.', 0, 1),
(2413, 204, 2407, 'Kamu memahami cermin dengan baik! Bayangan kecilmu berhasil masuk ke dunia miniatur dan menemukan gulungan mantra kuno yang tersembunyi.', 1, 0),
(2414, 204, 2407, 'Bayangan tidak sesuai! Cermin retak karena ketidaksesuaian dimensi, dan Profesor Dimensi mengerutkan dahi melihat kecerobohanmu.', 0, 1),
(2415, 204, 2408, 'Konversimu sempurna! Tongkat sihirmu menyesuaikan daya dengan tepat, menciptakan aurora berwarna emas yang menakjubkan seluruh kelas.', 1, 0),
(2416, 204, 2408, 'Kesalahan konversi fatal! Tongkatmu overload dan meledakkan pot bunga di meja guru. Detensimalam ini untukmu.', 0, 1),
(2417, 204, 2409, 'Ramuanmu sempurna! Warnanya berubah menjadi biru turkois cemerlang yang menyembuhkan luka dalam sekejap. Madam Penyembuh meminta resepmu.', 1, 0),
(2418, 204, 2409, 'Campuran tidak seimbang! Ramuan mengeluarkan asap beracun dan seluruh kelas harus dievakuasi. Kamu mendapat tugas tambahan membersihkan laboratorium.', 0, 1),
(2419, 204, 2410, 'Formasi runemu sempurna! Energi mengalir dalam pola harmonis dan membuka portal ke perpustakaan tersembunyi yang penuh dengan pengetahuan kuno.', 1, 0),
(2420, 204, 2410, 'Formasi tidak lengkap! Energi rune menjadi tidak stabil dan semua simbol memudar. Guru Runologi menggelengkan kepala dengan kecewa.', 0, 1),
(2501, 205, 2501, 'Hebat! Ramuanmu bekerja sempurna. Jamur tumbuh tepat sesuai kebutuhan untuk ramuan penyembuhanmu.', 1, 0),
(2502, 205, 2501, 'Oh tidak! Jamur tumbuh terlalu besar dan meledak, mengotori seluruh ruangan dengan spora berwarna ungu.', 0, 1),
(2503, 205, 2502, 'Bola kristalmu bersinar terang! Kamu berhasil menyelaraskan energinya dengan tepat.', 1, 0),
(2504, 205, 2502, 'Bola kristal menjadi keruh. Tampaknya perhitungan yang salah membuatnya tidak berfungsi.', 0, 1),
(2505, 205, 2503, 'Transformasimu sempurna! Kamu berubah menjadi burung hantu dan terbang mengelilingi menara dengan lancar.', 1, 0),
(2506, 205, 2503, 'Ramuan habis di tengah transformasi! Sekarang kamu terjebak dalam bentuk setengah manusia setengah katak.', 0, 1),
(2507, 205, 2504, 'Energi manamu bertahan tepat sampai ritual selesai! Kamu mendapatkan pujian dari guru sihir.', 1, 0),
(2508, 205, 2504, 'Tongkatmu kehabisan energi di tengah ritual penting! Sekarang kamu harus mengisi ulang selama berjam-jam.', 0, 1),
(2509, 205, 2505, 'Pengaturannya sempurna! Rak bukumu terlihat rapi dan semua mantra mudah ditemukan.', 1, 0),
(2510, 205, 2505, 'Buku-bukumu tidak muat! Sekarang beberapa buku penting harus disimpan di lantai dan terkena debu sihir.', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `score` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `role`, `score`) VALUES
(1, 'ZiaKfa', '$2y$10$lclm3HWCTCnJ8paf96MxG.0wdmzOIjXwdBtG24vrNme10Wo4EvvS.', 'zia14148@gmail.com', 'user', 50),
(12, 'Fawwaz', '$2y$10$GLb9xZOggvGsebWsOjgmbu.FKonsK.v7xtoZt7o1b6vRKV85GisBC', 'tes@email.com', 'user', 50),
(13, 'mkhadziq', '$2y$10$0zrtbK2KM4Ssv24k/78anOm8DirkIcUkWTbDDrQU6pUTRdGYFvttG', 'muhammad.khadziq059@mhs.unsoed.ac.id', 'user', 40),
(14, 'khdzq059', '$2y$10$LwT3uYGDrYmcFybIyzLTsuNGJjsixNPla3V4PJwAYMZZszhN67GmW', 'khadziq@gmail.com', 'admin', 250),
(16, 'mikasa', '$2y$10$ebV.upNuwHUieVk2nHIRres/WFV67UZ6uvDmzq3mQpffngrCFVbkG', 'mikasa@gg.com', 'admin', 0),
(18, 'seblak', '$2y$10$rRksAj6YMCr6eBpLpEEGiuYXqaU1HaKka2OIIyZ.7seZHLuAbUBGa', 'seblak@gg.com', 'admin', 50),
(22, 'admin', '$2y$10$o9F0f5EGZ6.2q0T1rsqe2OhW3L6HKrrlv7ZG0ftvf2ui.gHCBcNvq', 'admin@gmail.com', 'admin', 0),
(26, 'kakashi', '$2y$10$B6PQOLZAi2hWf.36k1lH2uFXIAVoMN8cmgL.TMME88dyJQE7zMwtm', 'kakashi@gmail.com', 'user', 260);

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `user_id` int NOT NULL,
  `question_id` int NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `answer` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`user_id`, `question_id`, `is_correct`, `answer`) VALUES
(14, 2101, 1, 21001),
(14, 2102, 1, 21005),
(14, 2103, 1, 21009),
(14, 2104, 1, 21013),
(14, 2105, 1, 21019),
(14, 2201, 1, 22002),
(14, 2202, 0, 22005),
(14, 2203, 0, 22009),
(14, 2204, 1, 22014),
(14, 2205, 1, 22018),
(14, 2301, 1, 23002),
(14, 2302, 1, 23006),
(14, 2303, 1, 23010),
(14, 2304, 1, 23015),
(14, 2305, 0, 23017),
(14, 2401, 0, 24001),
(14, 2402, 1, 24005),
(14, 2403, 0, 24011),
(14, 2404, 0, 24014),
(16, 2101, 0, 21003),
(16, 2102, 0, 21007),
(16, 2103, 0, 21011),
(18, 2101, 0, 21004),
(18, 2102, 0, 21007),
(18, 2103, 0, 21012),
(18, 2501, 1, 25004),
(18, 2502, 1, 25006),
(18, 2503, 1, 25011),
(18, 2504, 1, 25015),
(18, 2505, 1, 25018),
(26, 2101, 1, 21001),
(26, 2102, 1, 21005),
(26, 2103, 1, 21009),
(26, 2104, 0, 21014),
(26, 2105, 1, 21019),
(26, 2201, 1, 22002),
(26, 2202, 0, 22007),
(26, 2203, 0, 22009),
(26, 2204, 1, 22014),
(26, 2205, 1, 22018),
(26, 2301, 1, 23002),
(26, 2302, 1, 23006),
(26, 2303, 1, 23010),
(26, 2304, 1, 23015),
(26, 2305, 1, 23018),
(26, 2401, 1, 24002),
(26, 2402, 1, 24005),
(26, 2403, 1, 24010),
(26, 2404, 1, 24015),
(26, 2405, 1, 24018),
(26, 2406, 1, 24023),
(26, 2407, 0, 24027),
(26, 2408, 1, 24030),
(26, 2409, 1, 24035),
(26, 2410, 1, 24037),
(26, 2501, 1, 25004),
(26, 2502, 1, 25006),
(26, 2503, 1, 25011),
(26, 2504, 1, 25015),
(26, 2505, 1, 25018);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_quiz` (`user_id`,`quiz_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `story_segments`
--
ALTER TABLE `story_segments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`user_id`,`question_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `user_answers_ibfk_3` (`answer`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25023;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2515;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=426;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `story_segments`
--
ALTER TABLE `story_segments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2529;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`);

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `story_segments`
--
ALTER TABLE `story_segments`
  ADD CONSTRAINT `story_segments_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`),
  ADD CONSTRAINT `story_segments_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `FK_user_answers_questions` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `FK_user_answers_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `user_answers_ibfk_3` FOREIGN KEY (`answer`) REFERENCES `options` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
