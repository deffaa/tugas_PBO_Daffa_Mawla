<?php
declare(strict_types=1);

echo "<h3>Latihan 1: Modifikasi Constructor Mahasiswa</h3>";
// Merujuk pada modul 3.1[cite: 5] dan soal Latihan Mandiri 1[cite: 4]
class Mahasiswa {
    public function __construct(
        public string $nim,
        public string $nama,
        public int $semester = 1,
        public string $fakultas = "Belum diisi" // Penambahan parameter default[cite: 4]
    ) {}
    
    public function profil(): string {
        return "$this->nim - $this->nama (Semester $this->semester) - Fakultas: $this->fakultas";
    }
}
$mhs1 = new Mahasiswa('231001', 'Alya', 3, 'Sains dan Teknologi'); // Objek 1 dengan parameter lengkap[cite: 4]
$mhs2 = new Mahasiswa('231002', 'Budi', 1); // Objek 2 memicu default "Belum diisi"[cite: 4]
echo $mhs1->profil() . "<br>";
echo $mhs2->profil() . "<br><hr>";


echo "<h3>Latihan 2 & 3: Class Buku dan Destructor</h3>";
// Merujuk pada modul 3.2[cite: 6] dan soal Latihan Mandiri 2 & 3[cite: 4]
class Buku {
    public function __construct(
        public string $judul, 
        public string $penulis, 
        public int $tahun
    ) {
        echo "Object dibuat: $this->judul<br>";
    }
    
    public function tampilInfo(): string {
        return "Buku: $this->judul, Penulis: $this->penulis ($this->tahun)<br>";
    }
    
    // Destructor demonstrasi[cite: 4]
    public function __destruct() {
        echo "<b>Destructor berjalan:</b> Object '$this->judul' dihancurkan dari memori<br>";
    }
}
// Membuat 3 objek sesuai instruksi Latihan 2[cite: 4]
$buku1 = new Buku('Pemrograman PHP', 'Prajoko', 2023); 
$buku2 = new Buku('Struktur Data', 'Alya', 2024); 
$buku3 = new Buku('Basis Data', 'Budi', 2022); 
echo "<br>" . $buku1->tampilInfo();

// Uji coba efek unset ketika ada alias object untuk Latihan 3[cite: 4, 7]
$aliasBuku = $buku1; 
echo "<i>Melakukan unset(\$buku1)...</i><br>";
unset($buku1);
echo "<i>(Destructor 'Pemrograman PHP' belum dipanggil karena \$aliasBuku masih mereferensikan object tersebut)</i><br>";
echo "<i>Melakukan unset(\$aliasBuku)...</i><br>";
unset($aliasBuku); 
echo "<hr>";


echo "<h3>Latihan 4: Atribut Dinamis 'hobi'</h3>";
// Merujuk pada modul 4.3[cite: 7] dan soal Latihan Mandiri 4[cite: 4]
class MahasiswaDinamis {
    private array $atributTambahan = [];
    public function __construct(public string $nim, public string $nama) {}
    
    public function __set(string $key, mixed $value): void {
        $diizinkan = ['hobi', 'asalSekolah', 'agama'];
        if (!in_array($key, $diizinkan, true)) {
            throw new InvalidArgumentException('Atribut tidak diizinkan');
        }
        $this->atributTambahan[$key] = $value;
        echo "(__set) Mengisi atribut: $key = $value<br>";
    }
    public function __get(string $key): mixed {
        echo "(__get) Membaca atribut: $key... ";
        return $this->atributTambahan[$key] ?? null;
    }
    public function __isset(string $key): bool {
        echo "(__isset) Mengecek keberadaan atribut: $key... ";
        return isset($this->atributTambahan[$key]);
    }
    public function __unset(string $key): void {
        echo "(__unset) Menghapus atribut: $key<br>";
        unset($this->atributTambahan[$key]);
    }
}
$mhsDinamis = new MahasiswaDinamis('231003', 'Citra');

// Pengujian 4 magic methods sesuai Latihan 4[cite: 4]
$mhsDinamis->hobi = 'Membaca'; // Uji __set()[cite: 4]
echo $mhsDinamis->hobi . "<br>"; // Uji __get()[cite: 4]
echo (isset($mhsDinamis->hobi) ? "Status: Ada" : "Status: Tidak Ada") . "<br>"; // Uji __isset()[cite: 4]
unset($mhsDinamis->hobi); // Uji __unset()[cite: 4]
echo "<hr>";


echo "<h3>Latihan 5: Method Overloading dengan __call()</h3>";
// Merujuk pada modul 4.4[cite: 8] dan soal Latihan Mandiri 5[cite: 4]
class LaporanMahasiswa {
    public function __construct(private string $nama) {}
    
    public function __call(string $method, array $args): mixed {
        // Hanya mengizinkan method cetakRingkas[cite: 4, 8]
        if ($method === 'cetakRingkas' && count($args) === 0) {
            return 'Mahasiswa: ' . $this->nama . '<br>';
        }
        // Melempar exception jika method lain dipanggil[cite: 4, 8]
        throw new BadMethodCallException('Method tidak tersedia: ' . $method);
    }
}
$laporan = new LaporanMahasiswa('Alya');
echo "<b>Memanggil method yang diizinkan (cetakRingkas):</b> <br>";
echo $laporan->cetakRingkas();

echo "<b>Memanggil method lain yang tidak diizinkan (uji exception):</b> <br>";
try {
    echo $laporan->cetakLengkap(); // Memanggil method yang tidak ada[cite: 4]
} catch (BadMethodCallException $e) {
    // Mencatat exception[cite: 4]
    echo "<span style='color:red;'><b>Exception ditangkap:</b> " . $e->getMessage() . "</span><br>";
}

echo "<br><i>Sisa destructor program akan berjalan otomatis di akhir skrip:</i><br>";
?>