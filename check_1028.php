<?php
header("Content-Type: application/json");

// Konfigurasi database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "data_radio";

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Koneksi ke database gagal: ' . $conn->connect_error]);
    exit();
}

// Validasi input
if (!isset($_POST['input_1028']) || empty(trim($_POST['input_1028']))) {
    echo json_encode(['success' => false, 'message' => 'Input 10-28 tidak boleh kosong.']);
    exit();
}

// Ambil dan sanitasi data dari permintaan
$input_1028 = strtoupper(htmlspecialchars(trim($_POST['input_1028']))); // Convert input to uppercase

// Query ke database dengan case-insensitive search
$sql = "SELECT nama, `10_28` FROM data_nama WHERE UPPER(`10_28`) LIKE ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Gagal mempersiapkan query: ' . $conn->error]);
    exit();
}

// Bind parameter dan eksekusi
$like_input = "%" . $input_1028 . "%"; // Make the search case-insensitive
$stmt->bind_param("s", $like_input);
$stmt->execute();
$result = $stmt->get_result();

// Periksa hasil query
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'nama' => $row['nama'], '10_28' => $row['10_28']]); // Include the exact 10-28 value
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan.']);
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>
