<?php
include './library.php'; // Pastikan library.php dimuat sebelum session_start()

session_start(); // Mulai session

// Buat objek Library jika belum ada dalam session
if (!isset($_SESSION['library'])) {
  $_SESSION['library'] = new Library();
}

$library = $_SESSION['library'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Periksa aksi yang dikirim melalui form
  if (isset($_POST['addBook'])) {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $tahun = $_POST["tahun"];
    $publisherInfo = 'Universitas Teknologi Bandung';
    $ISBN = 'PPSN-1237';
    $bookId = $library->addBookToArray($title, $author, $tahun, $publisherInfo, $ISBN);
    if ($bookId) {
      echo "<script>
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Buku berhasil ditambahkan!',
              });
            </script>";
    } else {
      echo "<script>
              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal menambahkan buku!',
              });
            </script>";
    }
  }


  // Tangani aksi "Hapus" di sini
  if (isset($_POST['deleteBook'])) {
    $bookId = $_POST["bookId"];
    if ($library->deleteBookById($bookId)) {
      echo "<script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Buku berhasil dihapus!',
      });
    </script>";
    } else {
      echo "<script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Buku berhasil dihapus!',
      });
    </script>";
    }
  }

  // Jika tombol "Kembalikan" ditekan
  if (isset($_POST['returnBook'])) {
    $bookId = $_POST["bookId"];
    if ($library->returnBook($bookId)) {
      echo "<script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Buku berhasil Dikembalikan!',
      });
    </script>";
    } else {
      echo "<script>
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Buku gagal dikembalikan!',
              });
            </script>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library System Mei</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-slate-100">
  <nav x-data="{ isOpen: false }" class="relative bg-white shadow">
    <div class="container px-6 py-4 mx-auto md:flex md:justify-between md:items-center">
      <div class="flex items-center justify-between">
        <a href="#">
          <p class="font-bold text-2xl">LibrarySystem Mei</p>
        </a>

        <!-- Mobile menu button -->
        <div class="flex lg:hidden">
          <button x-cloak @click="isOpen = !isOpen" type="button"
            class="text-gray-500  hover:text-gray-600focus:outline-none focus:text-gray-600" aria-label="toggle menu">
            <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
            </svg>

            <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
      <div x-cloak :class="[isOpen ? 'translate-x-0 opacity-100 ' : 'opacity-0 -translate-x-full']"
        class="absolute inset-x-0 z-20 w-full px-6 py-4 transition-all duration-300 ease-in-out bg-white  md:mt-0 md:p-0 md:top-0 md:relative md:bg-transparent md:w-auto md:opacity-100 md:translate-x-0 md:flex md:items-center">
        <div class="flex flex-col md:flex-row md:mx-6">
          <a class="my-2 text-gray-700 px-3 py-2 transition-colors duration-300 transform  hover:text-white hover:bg-blue-500 hover:rounded-lg md:mx-4 md:my-0"
            href="./index.php">Perpustakaan</a>
          <a class="my-2  font-bold px-3 py-2 rounded-lg bg-blue-500 text-white text-gray-700 transition-colors duration-300 transform md:mx-4 md:my-0"
            href="./peminjaman.php">Peminjaman Buku</a>
        </div>
      </div>
    </div>
  </nav>
  <div class="mt-5 px-4 py-3 " style="width: 80%;">
    <section class="px-4 mx-auto flex items-center w-screen justify-center">
      <table class="divide-y divide-gray-200" style="width: 70%;">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
              ID
            </th>
            <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
              ISBN
            </th>

            <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
              Judul
            </th>

            <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
              Penulis
            </th>

            <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
              Tahun Terbit</th>
            <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
              Publisher</th>
            <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
              Nama Peminjam</th>
              <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
              Tanggal Peminjaman</th>
              <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
              Tanggal Pengembalian</th>
              <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
              Status</th>
            <th scope="col" class="px-4 py-3.5 text-sm font-normal text-center rtl:text-right text-gray-500 ">
              Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php $library->displayBorrowedBooksArray(); ?>
        </tbody>
      </table>
    </section>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>