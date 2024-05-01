<?php

class Book
{
  public $id;
  public $title;
  public $author;
  public $tahun;
  public $isAvailable;
  public $next;

  public function __construct($id, $title, $author, $tahun)
  {
    $this->id = $id;
    $this->title = $title;
    $this->author = $author;
    $this->tahun = $tahun;
    $this->isAvailable = true;
    $this->next = null;
  }
}
class ReferenceBook extends Book
{
  public $publisherInfo;
  public $ISBN;

  public function __construct($id, $title, $author, $publicationYear, $publisherInfo, $ISBN)
  {
    parent::__construct($id, $title, $author, $publicationYear);
    $this->publisherInfo = $publisherInfo;
    $this->ISBN = $ISBN;
  }

  public function getPublisherInfo()
  {
    return $this->publisherInfo;
  }

  public function getISBN()
  {
    return $this->ISBN;
  }
}

class Library
{
  private $bookCount;
  private $booksArray;
  private $borrowedBooksArray;
  private $borrowedBooksInfo; // Menyimpan informasi peminjaman buku
  private $borrowedBooksCountByBorrower;

  public function __construct()
  {
    $this->bookCount = 0;
    $this->booksArray = [];
    $this->borrowedBooksInfo = [];
    $this->borrowedBooksCountByBorrower = [];
  }
  public function addBookToArray($title, $author, $tahun, $publisherInfo, $ISBN)
  {
    $bookId = ++$this->bookCount; // Gunakan $bookCount yang terus meningkat sebagai ID unik
    $book = [
      'id' => $bookId,
      'title' => $title,
      'author' => $author,
      'tahun' => $tahun,
      'publisherInfo' => $publisherInfo,
      'ISBN' => $ISBN,
      'isAvailable' => true
    ];
    $this->booksArray[$bookId] = $book; // Simpan buku dengan ID yang sesuai
    return $bookId; // Kembalikan ID buku yang baru ditambahkan
  }

  public function sortByYear()
  {
    // Urutkan array buku berdasarkan tahun terbit
    usort($this->booksArray, function ($a, $b) {
      return $a['tahun'] <=> $b['tahun'];
    });
  }

  public function displayBooksArray()
  {

    $sortByYear = isset($_GET['sortYear']);

    // Jika tidak ada parameter 'sortYear', kembalikan data ke kondisi semula
    if (!$sortByYear) {
      // Reset variabel session jika perlu

      // Periksa apakah ada parameter pencarian
      $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

      // Buat variabel untuk menandai apakah ada hasil pencarian
      $hasSearchResults = false;

      // Tampilkan buku seperti semula
      foreach ($this->booksArray as $bookId => $book) {
        // Jika tidak ada kata kunci pencarian atau judul/penulis mengandung kata kunci pencarian
        if (empty($searchQuery) || stripos($book['title'], $searchQuery) !== false || stripos($book['author'], $searchQuery) !== false) {
          echo "<tr>";
          echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $bookId . "</td>";
          echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['ISBN'] . "</td>";
          echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['title'] . "</td>";
          echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['author'] . "</td>";
          echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['tahun'] . "</td>";
          echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['publisherInfo'] . "</td>";
          echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . ($book['isAvailable'] ? "Tersedia" : "Tidak Tersedia") . "</td>";
          echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap text-center'>";
          // Tombol Pinjam
          if ($book['isAvailable']) {
            echo "<button  type='button' data-modal-target='pinjam-modal' data-book-id='$bookId' data-modal-toggle='pinjam-modal' class='text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'>Pinjam</button>";
          } else {
            // Tombol Kembalikan
            echo "<form action='index.php' method='post' style='display: inline;'>";
            echo "<input type='hidden' name='bookId' value='$bookId'>";
            echo "<button type='submit' class='focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-4 py-2 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800' name='returnBook'>Kembalikan</button>";
            echo "</form>";
          }
          // Tombol Hapus
          echo "<form action='index.php' method='post' style='display: inline;'>";
          echo "<input type='hidden' name='bookId' value='$bookId'>";
          echo "<button type='submit' class='focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-4 py-2 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900' name='deleteBook'>Hapus</button>";
          echo "</form>";
          echo "</td>";
          echo "</tr>";

          // Set variabel $hasSearchResults menjadi true
          $hasSearchResults = true;
        }
      }

      // Jika tidak ada hasil pencarian
      if (!$hasSearchResults && !empty($searchQuery)) {
        echo "<tr><td colspan='8'>Tidak ada hasil yang ditemukan untuk pencarian '$searchQuery'.</td></tr>";
      }
      return;
    }

    // Jika ada parameter 'sortYear', lakukan pengurutan berdasarkan tahun
    // dan kemudian tampilkan buku
    $this->sortByYear();

    foreach ($this->booksArray as $bookId => $book) {
      echo "<tr>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $bookId . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['ISBN'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['title'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['author'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['tahun'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['publisherInfo'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . ($book['isAvailable'] ? "Tersedia" : "Tidak Tersedia") . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap text-center'>";
      // Tombol Pinjam
      if ($book['isAvailable']) {
        echo "<form action='index.php' method='post' style='display: inline;'>";
        echo "<input type='hidden' name='bookId' value='$bookId'>";
        echo "<button type='button' data-modal-target='pinjam-modal' data-modal-toggle='pinjam-modal' class='text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'>Pinjam</button>";
        echo "</form>";
      } else {
        // Tombol Kembalikan
        echo "<form action='index.php' method='post' style='display: inline;'>";
        echo "<input type='hidden' name='bookId' value='$bookId'>";
        echo "<button type='submit' class='focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-4 py-2 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800' name='returnBook'>Kembalikan</button>";
        echo "</form>";
      }
      // Tombol Hapus
      echo "<form action='index.php' method='post' style='display: inline;'>";
      echo "<input type='hidden' name='bookId' value='$bookId'>";
      echo "<button type='submit' class='focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-4 py-2 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900' name='deleteBook'>Hapus</button>";
      echo "</form>";
      echo "</td>";
      echo "</tr>";
    }
  }


  public function deleteBookById($bookId)
  {
    if (isset($this->booksArray[$bookId])) {
      unset($this->booksArray[$bookId]);
      return true; // Berhasil menghapus buku
    } else {
      return false; // Gagal menghapus buku (ID tidak valid)
    }
  }
  public function borrowBook($bookId, $borrowerName, $borrowDate, $returnDate)
  {
    // Periksa apakah peminjam sudah meminjam dua buku
    if (isset($this->borrowedBooksCountByBorrower[$borrowerName]) && $this->borrowedBooksCountByBorrower[$borrowerName] >= 2) {
      return false; // Gagal meminjam buku karena peminjam sudah meminjam dua buku
    }

    // Periksa apakah buku tersedia untuk dipinjam
    if (isset($this->booksArray[$bookId]) && $this->booksArray[$bookId]['isAvailable']) {
      // Simpan informasi peminjaman buku
      $this->borrowedBooksInfo[$bookId] = [
        'borrowerName' => $borrowerName,
        'borrowDate' => $borrowDate,
        'returnDate' => $returnDate
      ];

      // Tambah jumlah buku yang dipinjam oleh peminjam
      if (!isset($this->borrowedBooksCountByBorrower[$borrowerName])) {
        $this->borrowedBooksCountByBorrower[$borrowerName] = 1;
      } else {
        $this->borrowedBooksCountByBorrower[$borrowerName]++;
      }

      // Pindahkan buku dari $booksArray ke $borrowedBooksArray
      $this->borrowedBooksArray[$bookId] = $this->booksArray[$bookId];
      unset($this->booksArray[$bookId]); // Hapus dari $booksArray
      return true; // Berhasil meminjam buku
    } else {
      return false; // Gagal meminjam buku karena buku tidak tersedia atau ID tidak valid
    }
  }

  public function denda($tanggalPengembalian)
  {
    // Hitung denda berdasarkan tanggal pengembalian
    // Misalnya, denda per hari adalah Rp 500
    $dendaPerHari = 2000;
    $tanggalKembali = strtotime($tanggalPengembalian);
    $tanggalHariIni = strtotime(date('Y-m-d'));
    $selisihHari = ($tanggalHariIni - $tanggalKembali) / (60 * 60 * 24);

    if ($selisihHari > 0) {
      return $selisihHari * $dendaPerHari;
    } else {
      return 0; // Tidak ada denda
    }
  }


  public function displayBorrowedBooksArray()
  {
    foreach ($this->borrowedBooksArray as $bookId => $book) {
      echo "<tr>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $bookId . "</td>";
  
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['ISBN'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['title'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['author'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['tahun'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $book['publisherInfo'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $this->borrowedBooksInfo[$bookId]['borrowerName'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $this->borrowedBooksInfo[$bookId]['borrowDate'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>" . $this->borrowedBooksInfo[$bookId]['returnDate'] . "</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>Berhasil Di Pinjam</td>";
      echo "<td class='px-4 py-4 text-sm font-medium whitespace-nowrap'>";
      // Tombol Kembalikan
      echo "<form action='index.php' method='post' style='display: inline;'>";
      echo "<input type='hidden' name='bookId' value='$bookId'>";

      // Periksa apakah tanggal pengembalian telah melewati tanggal hari ini
      $returnDate = $this->borrowedBooksInfo[$bookId]['returnDate'];
      $denda = $this->denda($returnDate);

      if ($denda > 0) {
        echo "<button type='button' class='focus:outline-none text-white bg-gray-400 cursor-not-allowed rounded-lg text-xs px-4 py-2 me-2 mb-2'>Denda: $denda</button>";
      } else {
        echo "<button type='submit' class='focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-4 py-2 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800' name='returnBook'>Kembalikan</button>";
      }

      echo "</form>";
      echo "</td>";
      echo "</tr>";
    }
  }

  public function returnBook($bookId)
  {
    if (isset($this->borrowedBooksArray[$bookId])) {
      // Pindahkan buku dari $borrowedBooksArray kembali ke $booksArray
      $this->booksArray[$bookId] = $this->borrowedBooksArray[$bookId];
      // Ubah status buku menjadi tersedia
      $this->booksArray[$bookId]['isAvailable'] = true;
      unset($this->borrowedBooksArray[$bookId]); // Hapus dari $borrowedBooksArray
      return true; // Berhasil mengembalikan buku
    } else {
      return false; // Gagal mengembalikan buku
    }
  }

  public static function printAllData(Library $library)
  {
    // Panggil metode displayBooksArray() pada objek $library
    $library->displayBooksArray();

  }
}

$library = new Library();
Library::printAllData($library);
?>