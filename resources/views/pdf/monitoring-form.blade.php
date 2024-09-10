<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <style>
        :root {
            font-family: system-ui, sans-serif;
        }

        .subtitle {
            font-size: 1.2rem;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            text-transform: uppercase;
            font-size: 1.2rem;
        }

        td {
            font-size: 1.1rem;
        }

        td, th {
            padding: 4px;
            border-style: solid;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .colored-square {
            background: red;
            height: 30px;
            margin: auto;
        }
    </style>
</head>
<body>
    <h1 class="center">Ficha de acompanhamento</h1>
    <p class="center subtitle">ESCOLA DANIEL TITTON - PRIMEIRO ANO “A“ - PROFESSORA - IZABELA</p>
    <table>
        <thead>
            <tr>
                <th>Aluno(a)</th>
                <th colspan="2">Nível de Escrita</th>
                <th colspan="2">Nível de Leitura</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($class->students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td><div class="colored-square"></div></td>
                <td><div class="colored-square"></div></td>
                <td>NL</td>
                <td>NL</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
