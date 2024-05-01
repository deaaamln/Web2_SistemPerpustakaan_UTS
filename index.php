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
              alert('Buku berhasil ditambahkan!');
            </script>";
    } else {
      echo "<script>
              alert('Gagal menambahkan buku!');
            </script>";
    }
  }

  if (isset($_POST['borrowBook'])) {
    $bookId = $_POST["bookId"];
    $borrowerName = $_POST["borrowerName"];
    $borrowDate = $_POST["borrowDate"];
    $returnDate = $_POST["returnDate"];

    if ($library->borrowBook($bookId, $borrowerName, $borrowDate, $returnDate)) {
      echo "<script>
              alert('Buku berhasil dipinjam!');
            </script>";
    } else {
      echo "<script>
              alert('Gagal meminjam buku!');
            </script>";
    }
  }

  // Tangani aksi "Hapus" di sini
  if (isset($_POST['deleteBook'])) {
    $bookId = $_POST["bookId"];
    if ($library->deleteBookById($bookId)) {
      echo "<script>
              alert('Buku berhasil dihapus!');
            </script>";
    } else {
      echo "<script>
              alert('Gagal menghapus buku!');
            </script>";
    }
  }

  // Jika tombol "Kembalikan" ditekan
  if (isset($_POST['returnBook'])) {
    $bookId = $_POST["bookId"];
    if ($library->returnBook($bookId)) {
      echo "<script>
              alert('Buku berhasil dikembalikan!');
            </script>";
    } else {
      echo "<script>
              alert('Gagal mengembalikan buku!');
            </script>";
    }
  }
}
$searchQuery = '';
if (isset($_GET['search'])) {
  $searchQuery = $_GET['search'];
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
          <p class="font-bold text-2xl text-blue-500">LibrarySystem Mei</p>
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
          <a class="my-2 font-bold px-3 py-2 rounded-lg bg-blue-500 text-white text-gray-700 transition-colors duration-300 transform md:mx-4 md:my-0"
            href="./index.php">Perpustakaan</a>
          <a class="my-2 text-gray-700 px-3 py-2 transition-colors duration-300 transform  hover:text-white hover:bg-blue-500 hover:rounded-lg md:mx-4 md:my-0"
            href="./peminjaman.php">Peminjaman Buku</a>
        </div>
      </div>
    </div>
  </nav>
  <div class="mt-5 px-4 py-3 flex items-center justify-center w-screen">
    <div class="container">
      <div class="flex items-center justify-between px-4">
        <div class="search">
          <div class="pb-4">
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative mt-1">
              <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                  viewBox="0 0 20 20">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
              </div>
              <form action="index.php" method="get" class="flex items-center gap-2">
                <input type="text" id="table-search" name="search"
                  class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80  focus:ring-blue-500 focus:border-blue-500 "
                  placeholder="Search for items">
                <button
                  class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2 text-center ">Cari</button>
              </form>
            </div>
          </div>
        </div>
        <div class="modal">
          <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
            class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center 0 lue-700 -blue-800"
            type="button">
            Tambah Buku
          </button>
          <!-- Main modal -->
          <div id="crud-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
              <!-- Modal content -->
              <div class="relative bg-white rounded-lg shadow ">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                  <h3 class="text-lg font-semibold text-gray-900 ">
                    Tambah Buku
                  </h3>
                  <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                      viewBox="0 0 14 14">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                  </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5" action="index.php" method="post">
                  <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                      <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Judul</label>
                      <input type="text" name="title" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Judul Buku" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                      <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Penulis</label>
                      <input type="text" name="author" id="price"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                        placeholder="Nama Penulis" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                      <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Tahun Terbit</label>
                      <input type="text" name="tahun" id="price"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                        placeholder="Tahun Terbit" required>
                    </div>
                  </div>
                  <button type="submit" name="addBook"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"></path>
                    </svg>
                    Tambah Buku
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <section class="px-4 mx-auto">
        <table class="divide-y divide-gray-200" style="width: 100%;">
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
                <button id="sort-by-year-btn" class="flex items-center gap-x-2">
                  <span>Tahun Terbit</span>
                  <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z"
                      fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                    <path
                      d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z"
                      fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                    <path
                      d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                      fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                  </svg>
                </button>
              </th>
              <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
                Publisher</th>
              <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 ">
                Status</th>
              <th scope="col" class="px-4 py-3.5 text-sm font-normal text-center rtl:text-right text-gray-500 ">
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200"> <?php
          $library->displayBooksArray();
          ?>
          </tbody>
        </table>
        <div class="flex justify-center mt-5">
          <a href="print.php" target="_blank" rel="noopener noreferrer">
            <button
              class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center 0 lue-700 -blue-800"
              type="button">
              Print Data
            </button>
          </a>
        </div>
      </section>
      <div id="pinjam-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
          <!-- Modal content -->
          <div class="relative bg-white rounded-lg shadow ">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
              <h3 class="text-lg font-semibold text-gray-900 ">
                Peminjaman Buku
              </h3>
              <button type="button"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                data-modal-toggle="pinjam-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                  viewBox="0 0 14 14">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
              </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5" action="index.php" method="post">
              <div class="grid gap-4 mb-4 grid-cols-2">
                <div class="col-span-2">
                  <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Nama</label>
                  <input type="text" name="borrowerName" id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Nama Lengkap" required>
                </div>
                <div class="col-span-2 sm:col-span-1">
                  <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Tanggal Peminjaman</label>
                  <input type="date" name="borrowDate" id="price"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                    placeholder="Nama Penulis" required>
                </div>
                <div class="col-span-2 sm:col-span-1">
                  <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Tanggal Pengembalian</label>
                  <input type="date" name="returnDate" id="price"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                    placeholder="Tahun Terbit" required>
                </div>
                <div class="col-span-2 hidden">
                  <input type="hidden" id="bookIdInput" name="bookId" value="">
                </div>
              </div>
              <button type="submit" name="borrowBook" id="print-data-btn"
                class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
                </svg>
                Meminjam Buku
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    let yearSortClickCount = 0;

    document.getElementById('sort-by-year-btn').addEventListener('click', function () {
      yearSortClickCount++; // Tambahkan jumlah klik setiap kali tombol diklik

      // Jika jumlah klik adalah 2, arahkan kembali ke halaman tanpa parameter 'sortYear'
      if (yearSortClickCount === 2) {
        window.location.href = 'index.php';
      } else {
        // Kirim permintaan HTTP ke halaman yang sama dengan parameter 'sortYear'
        window.location.href = 'index.php?sortYear';
      }
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      var pinjamButtons = document.querySelectorAll("[data-modal-toggle='pinjam-modal']");

      pinjamButtons.forEach(function (button) {
        button.addEventListener("click", function () {
          // Tangkap ID buku dari atribut data
          var bookId = this.getAttribute("data-book-id");

          // Setel nilai input di dalam modal dengan ID buku
          document.getElementById("bookIdInput").value = bookId;
        });
      });
    });
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>