Freeloan Plugin v2.0 for SLiMS by Drajat Hasan (drajathasan20@gmail.com)
Teasted on Akasia

Fitur tambahan v2.0:
1. Berformat PDF dan berbasis FPDF.
2. Multi halaman maks 10 halaman.
2. Fitur autoprint pada pdf viewer.

Fitur tambahan v1.2:
1. add_essay adalah fitur tambahan yang digunakan untuk menambahkan judul skripsi ke dalam data dari setiap anggota.
2. Pembaharuan di plugin member_free_loan_letter.php sudah bisa menampilkan judul skripsi dari setiap anggota.

Lankgah-langkah
1. Salin file submenu.php ke admin/modules/membership
2. Salin file add_essay ke admin/modules/membership
3. Salin file member_free_loan_letter.php ke admin/modules/membership
4. Salin file member_free_loan_letter_pdfgen.php ke admin/modules/membership
5. Salin file printed_settings.inc.php ke admin/admin_template
6. Salin file print_settings.php admin/modules/bibliography
7. Salin folder freeloan ke files/
8. Salin folder fpdf ke lib/
8. Buka phpmyadmin atau aplikasi sejenis masuk ke database yang digunakan oleh slims anda, klik menu import lalu pilih file -> setting.sql -> go .
9. Untuk fitur add_essay, pastikan anda masih tetap di phpmyadmin atau sejenis, setelah itu pilih table member -> klik import lalu pilih file -> add_essay.sql -> go.
10. Plugin Fll(Free Loan Letter) telah terpasang :D

