<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Turmas</title>
    <style>
        /* Estilos Gerais */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        /* Estilos do Header */
        header {
            background-color: #0D5413;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .header-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .header-text {
            flex: 1;
            margin-right: 20px;
        }

        .header-text h1 {
            font-size: 24px;
            margin: 0 0 10px;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 5px 0;
            font-size: 14px;
        }

        .header-details {
            flex: 1.5;
            border-radius: 8px;
        }

        .header-details h2 {
            color: white;
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 2px solid white;
            padding-bottom: 5px;
        }

        .header-details p {
            margin: 5px 0;
            font-size: 14px;
        }

        table {
            /*width: 100%;*/
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 10px;
            font-size: 14px;
        }

        th {
            background-color: #0D5413;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #0D5413;
        }

        h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #0D5413;
        }

        .students-table-section {
            margin-top: 20px;
        }

        .students-table-section h2 {
            font-size: 20px;
            font-weight: bold;
            color: #0D5413;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 3px solid #0D5413;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .students-table-section table {
            border-collapse: collapse;
            margin-top: 10px;
        }

        .students-table-section th{
            border: 1px solid #ddd;
            text-align: left;
            padding: 10px;
            font-size: 12px;
        }

        .first-table th{
            font-size: 18px !important;
        }

        .students-table-section td {
            border: 1px solid #76AA3B;
            text-align: center;
            font-weight: bolder;
            padding: 10px;
            font-size: 14px;
        }

        .students-table-section th {
            background-color: #0D5413;
            color: white;
        }

        .students-table-section tr:nth-child(even) {
            background-color: #C1C1C1;
        }
    </style>

</head>
<body>
<header>
    <div class="header-container">
        <div class="header-text">
            <h1>Relatório de Turmas</h1>
            <p><strong>Escola:</strong> {{$data['school']}}</p>
        </div>
        <div class="header-details">
            <h2>INFORMAÇÕES DA TURMA</h2>
            <p><strong>Turma:</strong> {{$data['class']}}</p>
            <p><strong>Professor Responsável:</strong> {{$data['teacher']}}</p>
            <p><strong>Total de Alunos:</strong> {{$data['studentsQuantity']}}</p>
            <p><strong>Turno:</strong> {{$data['shift']}}</p>
            <p><strong>Nível de ensino:</strong> {{$data['type']}}</p>
        </div>
    </div>
</header>


<section class="students-table-section">
    <h2>ALUNOS QUE ESCREVEM O NOME</h2>
    <table>
        <thead>
        <tr class="first-table">
            <th>NOME DO (A) ALUNO (A)</th>
            <th>COM APOIO</th>
            <th>SEM APOIO</th>
            <th>NÃO ESCREVEM</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data['exams'] as $exam)
            <tr>
                <td>{{$exam->student->name}}</td>
                <td>{{in_array(1, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(2, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(3, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>

<section class="students-table-section">
    <h2>ALUNOS QUE RECONHECEM E ESCREVEM O ALFABETO</h2>
    <table>
        <thead>
        <tr>
            <th>NOME DO (A) ALUNO (A)</th>
            <th>Reconhece todas as letras trabalhadas no bimestre</th>
            <th>Reconhece parcialmente as letras trabalhadas no bimestre</th>
            <th>Escreve todas as letras trabalhadas no bimestre</th>
            <th>Escreve parcialmente as letras trabalhadas no bimestre</th>
            <th>Não reconhece as letras trabalhadas no bimestre</th>
            <th>Não escreve as letras trabalhadas no bimestre</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data['exams'] as $exam)
        <tr>
            <td>{{$exam->student->name}}</td>
            <td>{{in_array(4, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
            <td>{{in_array(5, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
            <td>{{in_array(6, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
            <td>{{in_array(7, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
            <td>{{in_array(8, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
            <td>{{in_array(9, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</section>

<section class="students-table-section">
    <h2>ALUNOS QUE RECONHECEM E ESCREVEM OS ENCONTROS VOCÁLICOS</h2>
    <table>
        <thead>
        <tr>
            <th>NOME DO (A) ALUNO (A)</th>
            <th>Reconhece todos os Encontros Vocálicos trabalhados no bimestre</th>
            <th>Reconhece parcialmente os Encontros Vocálicos trabalhados no bimestre</th>
            <th>Escreve todos os Encontros Vocálicos trabalhados no bimestre</th>
            <th>Escreve parcialmente os Encontros Vocálicos  no bimestre</th>
            <th>Não reconhece os Encontros Vocálicos trabalhados</th>
            <th>Não escreve os Encontros Vocálicos trabalhados no bimestre</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data['exams'] as $exam)
            <tr>
                <td>{{$exam->student->name}}</td>
                <td>{{in_array(10, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(11, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(12, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(13, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(14, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(15, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>

<section class="students-table-section">
    <h2>ALUNOS QUE RECONHECEM E ESCREVEM A FAMÍLIA SILÁBICA</h2>
    <table>
        <thead>
        <tr>
            <th>NOME DO (A) ALUNO (A)</th>
            <th>Reconhece as famílias silábicas trabalhadas no bimestre</th>
            <th>Reconhece parcialmente as famílias silábicas trabalhadas no bimestre</th>
            <th>Escreve todas as famílias silábicas trabalhadas no bimestre</th>
            <th>Escreve parcialmente as famílias silábicas trabalhadas no bimestre</th>
            <th>Não reconhece as famílias silábicas trabalhadas no bimestre</th>
            <th>Não escreve as famílias silábicas trabalhadas no bimestre</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data['exams'] as $exam)
            <tr>
                <td>{{$exam->student->name}}</td>
                <td>{{in_array(16, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(17, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(18, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(19, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(20, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(21, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>

<section class="students-table-section">
    <h2>ALUNOS QUE RECONHECEM E ESCREVEM OS NÚMEROS</h2>
    <table>
        <thead>
        <tr>
            <th>NOME DO (A) ALUNO (A)</th>
            <th>Reconhece os números trabalhadas no bimestre</th>
            <th>Reconhece parcialmente os números trabalhadas no bimestre</th>
            <th>Escreve todos os números trabalhadas no bimestre</th>
            <th>Escreve parcialmente os números trabalhadas no bimestre</th>
            <th>Não reconhece os números trabalhadas no bimestre</th>
            <th>Não escreve os números trabalhadas no bimestre</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data['exams'] as $exam)
            <tr>
                <td>{{$exam->student->name}}</td>
                <td>{{in_array(22, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(23, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(24, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(25, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(26, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
                <td>{{in_array(27, $exam->literacy_parameters_ids) ? 'X' : ''}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>
</body>
</html>

