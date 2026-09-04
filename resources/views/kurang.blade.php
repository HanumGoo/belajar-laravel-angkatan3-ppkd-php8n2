<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('action-kurang') }}" method="post">
        @csrf
        <label for="">Angka 1</label><br>
        <input type="number" name="angka1"><br>
        <label for="">Angka 2</label><br>
        <input type="number" name="angka2"><br>
        <button type="submit">test</button>
    </form>
    <a href="{{ route('counting') }}">tes</a>
    <h3>Hasilnya: {{ isset($hasil) ? $hasil : '0' }}</h3>
</body>

</html>
