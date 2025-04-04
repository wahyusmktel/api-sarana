<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Peminjaman Aset</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .qr {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h2>Surat Keterangan Peminjaman Aset</h2>
    <p><strong>No Dokumen:</strong> {{ $loan->document_number }}</p>
    <hr>
    <h4>Data Aset</h4>
    <p>Nama: {{ $loan->asset->name }}</p>
    <p>Kategori: {{ $loan->asset->category->name }}</p>
    <p>Serial Number: {{ $loan->asset->serial_number }}</p>

    <h4>Detail Peminjaman</h4>
    <p>Peminjam: {{ $loan->borrower->username }}</p>
    <p>Tanggal Pinjam: {{ \Carbon\Carbon::parse($loan->loan_start)->format('d M Y') }}</p>
    <p>Tanggal Kembali: {{ \Carbon\Carbon::parse($loan->loan_end)->format('d M Y') }}</p>
    <p>Status: {{ $loan->status }}</p>

    <div class="qr">
        <p><strong>Tanda Tangan (QR):</strong></p>
        <img src="data:image/png;base64,{{ $qr }}" width="100" height="100">
    </div>

    <p style="margin-top: 40px;">Dicetak pada {{ now()->format('d M Y H:i') }}</p>
</body>

</html>
